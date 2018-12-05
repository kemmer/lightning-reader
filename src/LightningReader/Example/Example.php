<?php

namespace LightningReader\Example;

require_once __DIR__."/../../../vendor/autoload.php";

use LightningReader\Parser\Tokenizer;
use LightningReader\Database\MySQLDatabase;
use LightningReader\Validator\Validator;
use LightningReader\Environment\Loader;
use LightningReader\Manipulator\RequestLogReader;


function run()
{
    // Loading dependencies
    $filename = "logs.log";
    $stream = fopen($filename, "r");

    $context = Loader::load(__DIR__."/../../../.env");
    $tokenizer = new Tokenizer($stream, true);
    $connection = new MySQLDatabase($context);
    $connection->connect();
    $validator = new Validator;

    // Creating main class for file reading and starting it
    $reader = new RequestLogReader(
        $filename, $stream, $connection, $validator, $tokenizer);
    $reader->start();
}

run();
