<?php

namespace LightningReader\Test;

require_once __DIR__."/../../../vendor/autoload.php";

use UnityTest\TestCase;
use TimberLog\Target\ConsoleLogger;
use LightningReader\Parser\Tokenizer;
use LightningReader\Database\MySQLDatabase;
use LightningReader\Validator\Validator;
use LightningReader\Environment\{Context, Loader};
use LightningReader\Manipulator\{FileInfo, LineTracker, RequestLogReader};


function main()
{
    $parserTest = new ManipulatorTest(new ConsoleLogger);
    $parserTest->performTesting();
}

class ManipulatorTest extends TestCase
{
    private $filePath;
    private $context;
    private $tokenizer;
    private $connection;
    private $validator;
    private $fileInfo;
    private $logger;

    protected function configure()
    {
        parent::configure();

        $this->filePath = "logs_large.log";
        $this->fileInfo = new FileInfo($this->filePath);

        $this->context = Loader::load(__DIR__."/../../../.env.example");
        $this->tokenizer = new Tokenizer($this->fileInfo, true);
        $this->connection = new MySQLDatabase($this->context);
        $this->connection->connect();
        $this->validator = new Validator;
        $this->logger = new ConsoleLogger;
    }

    public function test_CanCreateRequestLogReader()
    {
        $reader = new RequestLogReader($this->fileInfo, $this->connection, $this->validator, $this->tokenizer, $this->logger);
    }

    public function test_ReadFile()
    {
        $reader = new RequestLogReader($this->fileInfo, $this->connection, $this->validator, $this->tokenizer, $this->logger);
        $reader->start();
    }

    public function test_CanCreateLineTracker()
    {
        $lineTracker = new LineTracker;
    }

    public function test_LineTracker_ShouldStartAtZero()
    {
        $lineTracker = new LineTracker;

        $this->assertEquals($lineTracker->current(), 0);
    }

    public function test_LineTracker_Advance()
    {
        $lineTracker = new LineTracker;
        $lineTracker->advance();
        $lineTracker->advance();
        $lineTracker->advance();

        $this->assertEquals($lineTracker->current(), 3);
    }

    public function test_LineTracker_GetSummary()
    {
        $lineTracker = new LineTracker;
        $expected = [
            'current' => 0,
            'success' => 0,
            'error'   => 0,
        ];

        $this->assertEquals($lineTracker->summary(), $expected);
    }

    public function test_LineTracker_Success()
    {
        $lineTracker = new LineTracker;
        $lineTracker->newSuccess();
        $lineTracker->newSuccess(14);

        $this->assertEquals($lineTracker->summary()['success'], 15);
    }

    public function test_LineTracker_Error()
    {
        $lineTracker = new LineTracker;
        $lineTracker->newError();
        $lineTracker->newError();
        $lineTracker->newError();
        $lineTracker->newError();

        $this->assertEquals($lineTracker->summary()['error'], 4);
    }

    public function test_CanCreateFileInfo()
    {
        $fileInfo = new FileInfo;
    }

    public function test_FileInfo_OpenFile()
    {
        $fileInfo = new FileInfo($this->filePath);
        $this->assertTrue(is_resource($fileInfo->stream()));
    }

    public function test_FileInfo_FilePath()
    {
        $this->assertEquals($this->fileInfo->filePath(), $this->filePath);
    }

    public function test_FileInfo_LineTracker()
    {
        $this->assertTrue($this->fileInfo->lineTracker() instanceof LineTracker);
    }
}

main();
