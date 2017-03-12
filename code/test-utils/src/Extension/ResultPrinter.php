<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mio\TestUtils\Extension;

use Exception;
use PHP_Timer;
use PHPUnit_Extensions_PhptTestCase;
use PHPUnit_Framework_AssertionFailedError;
use PHPUnit_Framework_Exception;
use PHPUnit_Framework_Test;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_TestFailure;
use PHPUnit_Framework_TestListener;
use PHPUnit_Framework_TestResult;
use PHPUnit_Framework_TestSuite;
use PHPUnit_Framework_Warning;
use PHPUnit_TextUI_ResultPrinter;
use PHPUnit_Util_InvalidArgumentHelper;
use PHPUnit_Util_Printer;
use PHPUnit_Util_Test;
use SebastianBergmann\Environment\Console;

/**
 * Prints the result of a TextUI TestRunner run.
 *
 * @since Class available since Release 2.0.0
 */
class ResultPrinter extends PHPUnit_TextUI_ResultPrinter implements PHPUnit_Framework_TestListener
{

    private $previous_time=0;
    protected function getTestAsString($testName){
        $text = $testName;
        $text = preg_replace('/([A-Z]+)([A-Z][a-z])/', '\\1 \\2', $text);
        $text = preg_replace('/([a-z\d])([A-Z])/', '\\1 \\2', $text);
        $text = preg_replace('/^test /', '', $text);
        $text = ucfirst(strtolower($text));
        return str_replace(['::', 'with data set'], [':', '|'], $text);
    }

    protected function regularTestMessage(PHPUnit_Framework_Test $test,$symbol){
        $short_class_name = explode('\\', get_class($test));
        $time='';

        if($test instanceof  PHPUnit_Framework_TestCase && !empty($test->getTestResultObject()) ){
            $time = ($test->getTestResultObject()->time()-$this->previous_time);
            $time = '('.round($time,4).'s)';
            $this->previous_time = $test->getTestResultObject()->time();
        }

        printf("\n".$symbol." \e[1m\e[1;35m %s\e[0m: %s \e[1m\e[0;32m%s\e[0m", array_pop($short_class_name), $this->getTestAsString($test->getName()), $time);
    }

    /**
     * An error occurred.
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     * @param float                  $time
     */
    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->regularTestMessage($test, $symbol="\e[1m\e[0;31mE\e[0m");
        $this->lastTestFailed = true;
    }

    /**
     * A failure occurred.
     *
     * @param PHPUnit_Framework_Test                 $test
     * @param PHPUnit_Framework_AssertionFailedError $e
     * @param float                                  $time
     */
    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
        $this->regularTestMessage($test, $symbol="\e[1m\e[0;31m✖\e[0m");
        $this->lastTestFailed = true;
    }

    /**
     * A warning occurred.
     *
     * @param PHPUnit_Framework_Test    $test
     * @param PHPUnit_Framework_Warning $e
     * @param float                     $time
     *
     * @since Method available since Release 5.1.0
     */
    public function addWarning(PHPUnit_Framework_Test $test, PHPUnit_Framework_Warning $e, $time)
    {
        $this->regularTestMessage($test, $symbol="\e[1m\e[1;33mW\e[0m");
        $this->lastTestFailed = true;
    }

    /**
     * Incomplete test.
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     * @param float                  $time
     */
    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->regularTestMessage($test, $symbol="\e[1m\e[1;33mI\e[0m");
        $this->lastTestFailed = true;
    }

    /**
     * Risky test.
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     * @param float                  $time
     *
     * @since Method available since Release 4.0.0
     */
    public function addRiskyTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->regularTestMessage($test, $symbol="\e[1m\e[1;33mR\e[0m");
        $this->lastTestFailed = true;
    }

    /**
     * Skipped test.
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception              $e
     * @param float                  $time
     *
     * @since Method available since Release 3.0.0
     */
    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->regularTestMessage($test, $symbol="\e[1m\e[0;36mS\e[0m");
        $this->lastTestFailed = true;
    }

    /**
     * A testsuite started.
     *
     * @param PHPUnit_Framework_TestSuite $suite
     *
     * @since Method available since Release 2.2.0
     */
    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        $suite_name = $suite->getName();
        if (!empty($suite_name) && false === strpos($suite_name, '\\')) {
            $total_testcase = $suite->count();
            printf("\n\e[1m\e[1;37m%s Tests (%d)\e[0m --------------------------------------------------------------------   \n",
                   ucfirst($suite_name), $total_testcase);
        }
    }

    /**
     * A testsuite ended.
     *
     * @param PHPUnit_Framework_TestSuite $suite
     *
     * @since Method available since Release 2.2.0
     */
    public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        $suite_name = $suite->getName();
        if (!empty($suite_name) && false === strpos($suite_name, '\\')) {
            printf(
                "\n--------------------------------------------------------------------- \n" );
        }
    }

    /**
     * A test ended.
     *
     * @param PHPUnit_Framework_Test $test
     * @param float                  $time
     */
    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
        if (!$this->lastTestFailed) {
            $this->regularTestMessage($test, $symbol="\e[1m\e[0;32m✔\e[0m");
        }

        if ($test instanceof PHPUnit_Framework_TestCase) {
            $this->numAssertions += $test->getNumAssertions();
        } elseif ($test instanceof PHPUnit_Extensions_PhptTestCase) {
            $this->numAssertions++;
        }

        $this->lastTestFailed = false;

        if ($test instanceof PHPUnit_Framework_TestCase) {
            if (!$test->hasExpectationOnOutput()) {
                $this->write($test->getActualOutput());
            }
        }
    }

}
