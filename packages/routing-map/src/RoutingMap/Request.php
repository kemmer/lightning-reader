<?php

namespace RoutingMap;


class Request
{
    private $data;
    private $information;

    public function __construct()
    {
        $this->load();
    }

    private function load()
    {
        // Request parameters, both GET and POST
        $this->data = [];
        foreach($_REQUEST as $name => $value) {
            $this->data[$name] = $value;
        }

        // Setting everything into information
        $this->information = [
            "protocol" => $_SERVER["SERVER_PROTOCOL"],
            "method"   => $_SERVER["REQUEST_METHOD"],
            "uri"      => $_SERVER["REQUEST_URI"]
        ];
    }

    public function __get(string $name)
    {
        if(array_key_exists($name, $this->information))
            return $this->information[$name];
        return "";
    }

    public function data(string $key = "")
    {
        if(empty($key))
            return $this->data;
        if(array_key_exists($key, $this->data))
            return $this->data[$key];
        return "";
    }
}
