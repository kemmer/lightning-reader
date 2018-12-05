<?php

namespace LightningReader\Database;

use LightningReader\Database\DatabaseInterface;
use LightningReader\Environment\Context;
use PDO, PDOException;


class MySQLDatabase implements DatabaseInterface
{
    private $dsn;
    private $options;
    private $user, $password;

    private $pdo;
    private $connected;

    public function __construct(Context &$context)
    {
        $this->connected = false;

        $this->dsn = "mysql:";
        $this->dsn .= sprintf("host=%s;", $context->getValue("DB_HOST"));
        $this->dsn .= sprintf("dbname=%s;", $context->getValue("DB_DBSE"));
        $this->dsn .= sprintf("charset=%s", $context->getValue("DB_CHAR"));

        $this->options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->user = $context->getValue("DB_USER");
        $this->password = $context->getValue("DB_PASS");
        $this->database = $context->getValue("DB_DBSE");
    }

    public function connect()
    {
        if($this->connected)
            return;

        try {
            $this->pdo = new PDO($this->dsn, $this->user, $this->password, $this->options);
            $this->connected = true;

        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function query(string $query)
    {
        return $this->pdo->query($query);
    }

    public function insert(string $query, array $params = [])
    {
        return $this->pdo->prepare($query)->execute($params);
    }

    public function update(string $query, array $params = [])
    {
        return $this->insert($query, $params);
    }

    public function getPDO()
    {
        return $this->pdo;
    }
}
