<?php

namespace LightningReader\Database\Information;

use LightningReader\Database\Information\TableInterface;
use LightningReader\Database\DatabaseInterface;


class FileInfoTable implements TableInterface
{
    public static function fields() : array
    {
        return ['name', 'last_line', 'lines_read', 'lines_failed'];
    }

    public static function openOrRecover(DatabaseInterface $connection, string $filename) : int
    {
        $query = "SELECT id FROM file_info WHERE name = ?";

        $pdo = $connection->getPDO();
        $statement = $pdo->prepare($query);
        $statement->execute([$filename]);

        $info = $statement->fetch();

        /* Found */
        if($info !== false)
            return $info["id"];

        /* Creates a new register for the file in database */
        $query = "INSERT INTO file_info (name, last_line, lines_read, lines_failed) VALUES (?, ?, ?, ?)";
        $statement = $pdo->prepare($query);
        $statement->execute([$filename, 0, 0, 0]);

        return $pdo->lastInsertId();
    }
}
