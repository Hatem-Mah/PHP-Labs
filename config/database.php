<?php
// Database connection configuration for XAMPP MySQL
$servername = "localhost";
$username = "root";        // Default XAMPP MySQL username
$password = "";            // Default XAMPP MySQL password (empty)
$dbname = "php_lab_db";

try {
    // Create PDO connection with error handling
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);

    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Set default fetch mode
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage() .
        "<br><br><strong>Make sure:</strong>
        <ul>
            <li>XAMPP is running</li>
            <li>MySQL service is started</li>
            <li>Database 'php_lab_db' exists (run database.sql in phpMyAdmin)</li>
        </ul>");
}

// Helper function to get database connection
function getConnection()
{
    global $pdo;
    return $pdo;
}
