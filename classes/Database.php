<?php

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private string $host = 'localhost';
    private string $dbname = 'php_lab_db';
    private string $user = 'root';
    private string $pass = '';

    private function __construct()
    {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->user,
                $this->pass
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage() .
                "<br><br><strong>Make sure XAMPP is running and database 'php_lab_db' exists.</strong>");
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
