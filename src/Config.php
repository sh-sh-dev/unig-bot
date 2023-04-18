<?php
namespace Shay3gan\UNIG;

use mysqli;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

class Config {
    public static mysqli $db;

    public function __construct()
    {
//        self::checkSecurity();

        date_default_timezone_set($_ENV['TIMEZONE']);

        $dbConf = [
            'host' => $_ENV['DB_HOST'],
            'user' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS'],
            'name' => $_ENV['DB_NAME'],
        ];

        self::$db = new mysqli(
            $dbConf['host'],
            $dbConf['user'],
            $dbConf['password'],
            $dbConf['name']
        ) or die('Failed to connect to the db');

        self::$db->query("SET NAMES 'utf8mb4'");
        self::$db->query("SET CHARACTER SET 'utf8mb4'");
        self::$db->query("SET character_set_connection = 'utf8mb4'");
    }

    public static function db(): mysqli
    {
        return self::$db;
    }

    public static function getToken(): string
    {
        return $_ENV['TOKEN'];
    }

    public static function checkSecurity(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
            die('method');

        else if ($_GET['secret'] !== $_ENV['WEBHOOK_SECRET'])
            die('secret');
    }
}
