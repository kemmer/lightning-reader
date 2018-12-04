<?php

namespace LightningReader\Test;

require_once __DIR__."/../../../vendor/autoload.php";

use UnityTest\TestCase;
use TimberLog\Target\ConsoleLogger;
use LightningReader\Database\MySQLDatabase;
use LightningReader\Database\Operation\MultipleInsert;
use LightningReader\Environment\Loader;


function main()
{
    $parserTest = new DatabaseTest(new ConsoleLogger);
    $parserTest->performTesting();
}

class DatabaseTest extends TestCase
{
    private $context;
    private $connection;

    protected function configure()
    {
        parent::configure();

        $this->context = Loader::load(__DIR__."/../../../.env.example");
        $this->connection = new MySQLDatabase($this->context);
        $this->connection->connect();
    }

    public function test_CanCreateMySQLDatabase()
    {
        $connection = new MySQLDatabase($this->context);
    }

    public function test_Connect()
    {
        $connection = new MySQLDatabase($this->context);
        $connection->connect();
    }

    public function test_CanCreateMultipleInsert_FileInfo()
    {
        $multipleInsert = new MultipleInsert($this->connection);
        $columns = ['name', 'last_line', 'lines_read', 'lines_failed'];
        $lines = [['myfile.txt', '1', '0', '0'], ['myfile2.txt', '1', '0', '0']];
        $multipleInsert->insert("file_info", $columns, $lines);
    }
}

main();
