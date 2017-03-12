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
use ReflectionObject;
use SebastianBergmann\Environment\Console;

/**
 * Prints the result of a TextUI TestRunner run.
 *
 * @since Class available since Release 2.0.0
 */
abstract class BaseTestCase extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        $refl = new ReflectionObject($this);
        
        foreach ($refl->getProperties() as $prop) {
            if (!$prop->isStatic() && 0 !== strpos($prop->getDeclaringClass()->getName(), 'PHPUnit_')) {
                $prop->setAccessible(true);
                $prop->setValue($this, null);
            }
        }
    }
}
