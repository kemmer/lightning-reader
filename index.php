<?php

require_once __DIR__."/vendor/autoload.php";

use RoutingMap\{Router, Request};
use LightningReader\Database\Information\RequestTable;
use LightningReader\Environment\Loader;
use LightningReader\Database\MySQLDatabase;


$router = new Router(new Request);

$router->get('/matrix', function() {

    $context = Loader::load(__DIR__."/.env.example");
    $connection = new MySQLDatabase($context);
    $connection->connect();

    return RequestTable::count($connection, [
        // 'statusCode' => 201,
        // 'services' => [
        //     'USER-SERVICE'
        // ],
        // 'fromDateTime' => '2018-08-17 09:26:53',
        // 'toDateTime' => '2018-08-18 10:26:53',
    ]);
});
