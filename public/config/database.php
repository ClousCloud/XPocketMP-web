<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private static $pdo;

    public static function getPDO()
    {
        if (!self::$pdo) {
            try {
                self::$pdo = new PDO('mysql:host=localhost;dbname=xpocketm_developer', 'xpocketm_developer', 'xpocketm_developer');
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}