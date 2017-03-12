<?php
namespace Mio\TestUtils\Infrastructure\ElasticSearch;

use Mio\TestUtils\Infrastructure\ElasticSearch\DataSet\DataSet;
use \Elasticsearch\ClientBuilder;

trait ElasticSearchTrait
{
    protected static $search_service=NULL;
    protected static $dataSet=NULL;

    public static function setUpSearchWithDocumentFixture($documentFixture){
        self::getElasticSearchDataSet()
            ->setFixture($documentFixture)
            ->setSettings(self::getSettings())
            ->setMappings(self::getMappings())
            ->deleteIndices()
            ->buildIndices();
    }


    public static function tearDownSearch()
    {
        self::getElasticSearchDataSet()->deleteIndices();
    }

    protected function searchService(){
        return self::client();
    }
    /**
     * etrieve a Elastic Search connection client.
     * @return \Elasticsearch\Client|null
     */
    protected static function client(){
        if (empty(self::$search_service)) {

            $elastic_search_host = $GLOBALS['elasticsearch_host'];


            $clientBuilder = ClientBuilder::create();
            $clientBuilder->setHosts([$elastic_search_host]);
            self::$search_service = $clientBuilder->build();
        }

        return self::$search_service;
    }

    /**
     * Retrieve a Elastic Search connection client, only use with codeception
     * @return \Elasticsearch\Client|null
     */
//    protected static function client(){
//        if (empty(self::$search_service)) {
//
//            $config = Configuration::config();
//
//            if(!isset($config['settings']) || !isset($config['settings']['elasticsearch']) || !isset($config['settings']['elasticsearch']['host'])){
//                $message = <<<YML
//Missing config elasticsearch hosts at file codeception.yml:
//settings:
//    elasticsearch:
//        host: elasticsearch-example:9200
//YML;
//                throw new InvalidArgumentException($message);
//            }
//
//            $elastic_search_host = $config['settings']['elasticsearch']['host'];
//
//
//            $clientBuilder = ClientBuilder::create();
//            $clientBuilder->setHosts([$elastic_search_host]);
//            self::$search_service = $clientBuilder->build();
//        }
//
//        return self::$search_service;
//    }

    /**
     * Retrieve a dataset object.
     *
     * @return DataSet
     */
    protected static function getElasticSearchDataSet(){
        if (empty(self::$dataSet)) {
            self::$dataSet = new DataSet(self::client());
        }
        return self::$dataSet;
    }

//    protected  static abstract function getFixture();
    protected  static abstract function getMappings();
    protected  static abstract function getSettings();
}