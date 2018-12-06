<?php

namespace LightningReader\Example;

require_once __DIR__."/../../../vendor/autoload.php";

use LightningReader\Parser\Tokenizer;
use LightningReader\Database\MySQLDatabase;
use LightningReader\Validator\Validator;
use LightningReader\Environment\Loader;
use LightningReader\Manipulator\{FileInfo, RequestLogReader};

use TimberLog\Target\ConsoleLogger;


function run()
{
    // Loading dependencies
    $filePath = "logs.log";
    $file = new FileInfo($filePath);

    $context = Loader::load(__DIR__."/../../../.env");
    $tokenizer = new Tokenizer($file, true);
    $connection = new MySQLDatabase($context);
    $connection->connect();
    $validator = new Validator;
    $logger = new ConsoleLogger;

    // Creating main class for file reading and starting it
    $reader = new RequestLogReader(
        $file, $connection, $validator, $tokenizer, $logger);
    $reader->start();
}

run();
