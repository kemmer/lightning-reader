<?php

namespace LightningReader\Database\Information;

use LightningReader\Database\Information\Table;
use LightningReader\Database\DatabaseInterface;


class RequestErrorTable implements Table
{
    public static function fields() : array
    {
        return ['file_info_id', 'line', 'except', 'content'];
    }

    public static function newError(DatabaseInterface $connection, int $file_info_id, $line, string $except, string $content)
    {
        $query = "INSERT INTO request_errors (file_info_id, `line`, except, content) VALUES (?,?,?,?)";

        $pdo = $connection->getPDO();
        $statement = $pdo->prepare($query);
        $statement->execute([$file_info_id, $line, $except, $content]);
    }
}
