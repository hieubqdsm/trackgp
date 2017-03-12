<?php
namespace Mio\TestUtils\Infrastructure\ElasticSearch\DataSet;

use Elasticsearch\Client;

class DataSet {
    /**
     * Max retries / waiting time for indexing
     */
    const MAX_RETRY = 30;
    /**
     * Fixture data.
     *
     * [index name] => [type name] => [][data]
     *
     * @var array
     */
    protected $fixture = array();
    /**
     * Mappings
     *
     * [index_name] => [type name] => [mappings]
     *
     * @var array
     */
    protected $mappings = array();
    /**
     * Settings
     *
     * [index_name] => [settings]
     *
     * @var array
     */

    public $settings = array();
    /**
     * Connection object.
     *
     * @var Client
     */
    protected $connection;

    /**
     * Constructor.
     *
     * @param Client
     */
    public function __construct(Client $connection) {
        $this->connection = $connection;
    }

    /**
     * Sets up the fixture data.
     *
     * see $this->fixture
     *
     * @param array $data
     * @return DataSet
     */
    public function setFixture(array $data) {
        $this->fixture = $data;
        return $this;
    }

    /**
     * Sets up the fixture mappings
     *
     * @param array $mappings
     * @return DataSet
     */
    public function setMappings(array $mappings) {
        $this->mappings = $mappings;
        return $this;
    }

    /**
     * Sets up the fixture settings
     *
     * @param array $settings
     * @return DataSet
     */
    public function setSettings(array $settings) {
        $this->settings = $settings;
        return $this;
    }

    /**
     * Delete all indices specified in the fixture keys.
     *
     * @return DataSet
     */
    public function deleteIndices() {
        foreach (array_keys($this->fixture) as $index) {
            if ($this->connection->indices()->exists(compact('index'))) {
                $this->connection->indices()->delete(compact('index'));
            }
        }
        return $this;
    }

    /**
     * Creates all types with data from the fixture.
     *
     * @return DataSet
     */
    public function buildIndices() {
        $verify = [];
        $documents = [];


        foreach ($this->fixture as $index => $types) {
            if (!$this->connection->indices()->exists(compact('index'))) {
                $body =  ['settings' => ['number_of_shards' => 1, 'number_of_replicas' => 1]];
                $this->connection->indices()->create(
                    compact('index', 'body'));
            }
            if (!empty($this->settings[$index])) {
                $this->defineSettings($index);
            }
            if (!empty($this->mappings[$index])) {
                $this->defineMappings($index);
            }
            $documents[$index] = $this->getDocumentCount($index);
            foreach ($types as $type => $data) {
                if (empty($data)) {
                    continue;
                }
                $params = [
                    'index' => $index,
                    'type'  => $type,
                    'body'  => []
                ];
                foreach ($data as $key => $entry) {
                    $params['body'][] = [
                        'index' => [
                            '_id' => (!empty($entry['id'])) ? $entry['id'] : $key
                        ]
                    ];
                    $params['body'][] = $entry;
                }

                $response = $this->connection->bulk($params);
                if(!empty($response['errors'])){
                    throw new \UnexpectedValueException(var_export($response,true));
                }
                if (!empty($response['items'])) {
                    if (!isset($verify[$index])) {
                        $verify[$index] = 0;
                    }
                    $verify[$index] += count($response['items']);
                }
            }
        }


        //ensure that data has been indexed before you can use it
        if (!empty($verify)) {
            foreach ($verify as $index => $count) {
                if (empty($count)) {
                    continue;
                }
                $retries = 1;
                do {
                    if ($retries == static::MAX_RETRY) {
                        throw new \RuntimeException("Indexing time out for Elastic Search Fixture");
                    }
                    $response = $this->connection->indices()->stats(compact('index'));
                    if(!empty($response['errors'])){
                        throw new \UnexpectedValueException(var_export($response,true));
                    }
                    $retries++;
                    usleep(100000);
                } while ($response['indices'][$index]['total']['docs']['count'] != $documents[$index]);
            }
        }
        return $this;
    }

    /**
     * Add settings to the index
     *
     * @param string $index
     * @return void
     */
    public function defineSettings($index) {
        if (empty($this->settings[$index])) {
            return;
        }
        $params = ['index' => $index];
        unset($this->settings[$index]['number_of_shards']);
        unset($this->settings[$index]['number_of_replicas']);
        $this->connection->cluster()->health($params + ['wait_for_status' => 'yellow']);
        $this->connection->indices()->close($params);
        $this->connection->indices()->putSettings($params + ['body' => ['settings' => $this->settings[$index]]]);
        $this->connection->indices()->open($params);
    }

    /**
     * Add mappings to the index
     *
     * @param string $index
     * @return void
     */
    protected function defineMappings($index) {
        foreach ($this->mappings[$index] as $type => $mappings) {
            $params = [
                'index' => $index,
                'type'  => $type
            ];
            if ($this->connection->indices()->existsType($params)) {
                $this->connection->indices()->deleteMapping($params);
            }
            if (empty($mappings)) {
                continue;
            }
            $params['body'][$type] = (array)$mappings;
            $this->connection->indices()->putMapping($params);
        }
    }

    /**
     * Get the document count for an index
     *
     * @param string $index
     * @return integer
     */
    protected function getDocumentCount($index) {
        if (empty($this->fixture[$index])) {
            return 0;
        }
        $documents = 0;
        foreach ($this->fixture[$index] as $type => $records) {
            $documents += count($records);
            if (empty($this->mappings[$index][$type])) {
                continue;
            }
            foreach ($records as $record) {
                $documents += $this->getDocumentNestedCount($this->mappings[$index][$type], $record);
            }
        }
        return $documents;
    }

    /**
     * Get the document count for an index
     *
     * @param array $mappings
     * @param array $records
     * @return integer
     */
    protected function getDocumentNestedCount(array $mappings, array $records) {
        if (empty($records)) {
            return 0;
        }
        $documents = 0;
        foreach ($mappings['properties'] as $key => $properties) {
            if (empty($properties['type']) || $properties['type'] !== 'nested') {
                continue;
            }
            $documents += count($records[$key]);
            if (!empty($properties['properties'])) {
                foreach ($records[$key] as $record) {
                    if (!is_array($record)) {
                        continue;
                    }
                    $documents += $this->getDocumentNestedCount($properties, $record);
                }
            }
        }
        return $documents;
    }
}