<?php

namespace App;

use PDO;
use PDOException;

class Database
{
    public static function connect()
    {
        try {
            $pdo = new PDO('mysql:host=127.0.0.1;dbname=php_lab3;charset=utf8mb4', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (PDOException $e) {
            exit('Ошибка подключения к базе данных: ' . $e->getMessage());
        }
    }
}
