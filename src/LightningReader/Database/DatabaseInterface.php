<?php

namespace LightningReader\Database;


interface DatabaseInterface
{
    public function connect();
    public function query(string $query);
    public function insert(string $query, array $params = []);
    public function update(string $query, array $params = []);
}
