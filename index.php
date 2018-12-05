<?php

require_once __DIR__."/vendor/autoload.php";

use RoutingMap\{Router, Request};
use LightningReader\Database\Information\RequestTable;
use LightningReader\Environment\Loader;
use LightningReader\Database\MySQLDatabase;


$request = new Request;
$router = new Router($request);

$callback = function($request) {

    $context = Loader::load(__DIR__."/.env.example");
    $connection = new MySQLDatabase($context);
    $connection->connect();

    return RequestTable::count($connection, $request->data());
};

$router->get('/matrix', $callback);
$router->post('/matrix', $callback);
