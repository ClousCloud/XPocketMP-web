<?php

namespace App\Config;

use PDO;

class Database
{
    private static $instance = null;

    public static function getConnection()
    {
        if (self::$instance === null) {
            $dsn = 'mysql:host=127.0.0.1;dbname=xpocketm_developer;charset=utf8';
            $username = 'xpocketm_developer';
            $password = 'xpocketm_developer';

            try {
                self::$instance = new PDO($dsn, $username, $password);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                die('Database connection failed: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}