<?php

namespace LightningReader\Test;

require_once __DIR__."/../../../vendor/autoload.php";

use UnityTest\TestCase;
use TimberLog\Target\ConsoleLogger;
use LightningReader\Parser\Tokenizer;
use LightningReader\Database\MySQLDatabase;
use LightningReader\Validator\Validator;
use LightningReader\Environment\{Context, Loader};
use LightningReader\Manipulator\RequestLogReader;


function main()
{
    $parserTest = new ManipulatorTest(new ConsoleLogger);
    $parserTest->performTesting();
}

class ManipulatorTest extends TestCase
{
    private $stream;
    private $context;
    private $tokenizer;
    private $connection;
    private $validator;

    protected function configure()
    {
        parent::configure();

        $this->stream = fopen("logs.log", "r");

        $this->context = Loader::load(__DIR__."/../../../.env.example");
        $this->tokenizer = new Tokenizer($this->stream);
        $this->connection = new MySQLDatabase($this->context);
        $this->connection->connect();
        $this->validator = new Validator;
    }

    public function test_CanCreateRequestLogReader()
    {
        $reader = new RequestLogReader($this->stream, $this->connection, $this->validator, $this->tokenizer);
    }

    public function test_ReadFile()
    {
        $reader = new RequestLogReader($this->stream, $this->connection, $this->validator, $this->tokenizer);
        $reader->start();
    }
}

main();
