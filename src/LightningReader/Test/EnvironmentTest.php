<?php

namespace LightningReader\Test;

require_once __DIR__."/../../../vendor/autoload.php";

use UnityTest\TestCase;
use TimberLog\Target\ConsoleLogger;
use LightningReader\Environment\{Loader, Context};


function main()
{
    $test = new EnvironmentTest(new ConsoleLogger);
    $test->performTesting();
}

class EnvironmentTest extends TestCase
{
    private $context;

    protected function configure()
    {
        parent::configure();

        $this->context = new Context([
            "DB_HOST" => "127.0.0.1",
            "DB_PORT" => "3306"
        ]);
    }

    public function test_CanCreateContext()
    {
        $context = new Context([]);
    }

    public function test_ContextHasKey_DB_PORT()
    {
        $context = new Context([
            "DB_HOST" => "127.0.0.1",
            "DB_PORT" => "3306"
        ]);

        $this->assertTrue($context->isSet("DB_PORT"));
    }

    public function test_ContextHasKey_DB_PORT_FailEmpty()
    {
        $context = new Context([
            "DB_HOST" => "127.0.0.1",
            "DB_PORT" => ""
        ]);

        $this->assertFalse($context->isSet("DB_PORT"));
    }

    public function test_ContextGetValue_DB_HOST()
    {
        $context = new Context([
            "DB_HOST" => "127.0.0.1",
            "DB_PORT" => "3306"
        ]);

        $this->assertEquals($context->getValue("DB_HOST"), "127.0.0.1");
    }

    public function test_ContextGetValue_DB_HOST_FailEmpty_Null()
    {
        $context = new Context([
            "DB_HOST" => "",
            "DB_PORT" => "3306"
        ]);

        $this->assertEquals($context->getValue("DB_HOST"), null);
    }

    public function test_ContextIsEmpty()
    {
        $context = new Context();

        $this->assertTrue($context->isEmpty());
    }

    public function test_CanLoad_DefaultPath()
    {
        $context = Loader::load();
    }

    public function test_CanLoad_InvalidPath()
    {
        $context = Loader::load("/var/log/sdsds.txt");

        $this->assertFalse($context);
    }

    public function test_CanLoad_SpecificPath()
    {
        $context = Loader::load(__DIR__."/../../../.env");
        $this->assertTrue(($context !== false));
    }

    public function test_CanLoad_SpecificPath_HasValues()
    {
        $context = Loader::load(__DIR__."/../../../.env.example");
        $this->assertEquals($context->getValue("ENV_VAR4"), "dump");
    }
}

main();
