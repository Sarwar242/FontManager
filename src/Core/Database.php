<?php
namespace FontManager\Core;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            try {
                $config = require __DIR__ . '/../../config/database.php';
                
                $dsn = sprintf(
                    "mysql:host=%s;dbname=%s;charset=utf8mb4", 
                    $config['host'], 
                    $config['database']
                );

                self::$instance = new PDO($dsn, $config['username'], $config['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                // Log error
                throw new DatabaseConnectionException(
                    "Database connection failed: " . $e->getMessage()
                );
            }
        }
        return self::$instance;
    }

    private function __construct() {}
    private function __clone() {}
}

// Custom Exception
class DatabaseConnectionException extends \Exception {}