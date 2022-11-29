<?php
namespace Shay3gan\UNIG;

require_once 'jdf.php';

use mysqli;

class Config {
    public static mysqli $db;

    public function __construct()
    {
        date_default_timezone_set('Asia/Tehran');

        $dbConf = [
            'host' => 'localhost',
            'user' => 'root',
            'password' => '',
            'name' => 'unig-bot'
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

    public static function db()
    {
        return self::$db;
    }

    public static function getToken()
    {
        return '123:abc';
    }
}

$adminId = '123';
$webhookSecret = 'SomeRandomLongText';

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
    die('method');

else if ($_GET['secret'] !== $webhookSecret)
    die('secret');


