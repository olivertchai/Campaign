<?php

namespace Core\Database;

use Core\Constants\Constants;
use PDO;

class Database
{
    public static function getDatabaseConn(): PDO
    {
        $user = $_ENV['DB_USERNAME'];
        $pwd  = $_ENV['DB_PASSWORD'];
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $db   = $_ENV['DB_DATABASE'];

        $pdo = new PDO('pgsql:host=' . $host . ';port=' . $port . ';dbname=' . $db, $user, $pwd);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    public static function getConn(): PDO
    {
        $user = $_ENV['DB_USERNAME'];
        $pwd  = $_ENV['DB_PASSWORD'];
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];

        $pdo = new PDO('pgsql:host=' . $host . ';port=' . $port . ';dbname=postgres', $user, $pwd);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    public static function create(): void
    {
        $dbName = $_ENV['DB_DATABASE'];
        $statement = self::getConn()->prepare('SELECT 1 FROM pg_database WHERE datname = :database');
        $statement->execute(['database' => $dbName]);

        if ($statement->fetchColumn() === false) {
            self::getConn()->exec("CREATE DATABASE {$dbName};");
        }
    }

    public static function drop(): void
    {
        $sql = 'DROP DATABASE IF EXISTS ' . $_ENV['DB_DATABASE'] . ';';
        self::getConn()->exec($sql);
    }

    public static function migrate(): void
    {
        $sql = file_get_contents(Constants::databasePath()->join('schema.sql'));
        self::getDatabaseConn()->exec($sql);
    }

    public static function exec(string $sql): void
    {
        self::getDatabaseConn()->exec($sql);
    }    

}
