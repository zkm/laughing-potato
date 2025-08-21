<?php

namespace Sweetwater\Config;

class Database
{
    private static $instance = null;
    private $connection;
    
    private function __construct()
    {
        $host = $_ENV['DB_HOST'] ?? 'db';
        $user = $_ENV['DB_USER'] ?? 'sweetwater_user';
        $password = $_ENV['DB_PASS'] ?? 'sweetwater_pass';
        $dbname = $_ENV['DB_NAME'] ?? 'sweetwater_db';
        
        $this->connection = mysqli_connect($host, $user, $password, $dbname);
        
        if (!$this->connection) {
            throw new \Exception("Database connection failed: " . mysqli_connect_error());
        }

        // Ensure proper UTF-8 encoding for all connections
        if (!@mysqli_set_charset($this->connection, 'utf8mb4')) {
            // Fallback to SET NAMES if needed
            @mysqli_query($this->connection, "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
        }

        // Relax zero-date restrictions to avoid MySQL 8 strict mode errors when encountering
        // legacy '0000-00-00' style values or comparisons. We only adjust the session mode
        // for this connection and leave other strict checks intact.
        // This prevents errors like: Incorrect DATETIME value: '0000-00-00'
        @mysqli_query(
            $this->connection,
            "SET SESSION sql_mode = REPLACE(REPLACE(@@sql_mode,'NO_ZERO_DATE',''),'NO_ZERO_IN_DATE','')"
        );
    }
    
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    public function getConnection()
    {
        return $this->connection;
    }
    
    public function query(string $sql)
    {
        return mysqli_query($this->connection, $sql);
    }
    
    public function escapeString(string $string): string
    {
        return mysqli_real_escape_string($this->connection, $string);
    }
}
