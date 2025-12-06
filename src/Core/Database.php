<?php
namespace App\Core;

class Database
{
    private static ?\PDO $instance = null;

    public static function getInstance(): \PDO
    {
        if (self::$instance === null) {
            $dsn = $_ENV['DB_DSN'] ?? ($_SERVER['DB_DSN'] ?? 'mysql:host=localhost;dbname=equip_catalog;charset=utf8mb4');
            $user = $_ENV['DB_USER'] ?? ($_SERVER['DB_USER'] ?? 'root');
            $pass = $_ENV['DB_PASS'] ?? ($_SERVER['DB_PASS'] ?? '');

            $options = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
            ];

            self::$instance = new \PDO($dsn, $user, $pass, $options);
        }
        return self::$instance;
    }

    private function __construct() {}
    private function __clone() {}
    public function __wakeup() {}
}