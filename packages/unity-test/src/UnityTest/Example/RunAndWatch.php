<?php

namespace UnityTest\Example;

require __DIR__."/../../../vendor/autoload.php";
use UnityTest\TestCase;
use UnityTest\Assert\AssertException;
use UnityTest\Assert\AssertCode;

use TimberLog\Target\ConsoleLogger;


class TestingExample extends TestCase
{
    protected function configure()
    {
    }

    protected function test_Example()
    {
        // Testing ErrorException
        strpos();
    }

    protected function test_Example2()
    {
    }

    protected function test_Example3()
    {
        // Testing AssertException (1)
        throw new AssertException(AssertCode::TRUE);
    }

    protected function test_Example4()
    {
    }

    protected function test_Example5()
    {
    }

    protected function test_Example6()
    {
        // Testing AssertException (2)
        $this->assertTrue(false);
    }

    protected function test_Example7()
    {
        // Testing AssertException (3)
        $this->assertEquals(1, 2);
    }

    protected function test_Example8()
    {
        $this->assertTrue(true);
    }

    protected function test_Example9()
    {
        $this->assertEquals(6, 6);
    }
}

$choosen = new ConsoleLogger;
$te = new TestingExample($choosen);
$te->performTesting();
