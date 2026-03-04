<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "php_lab_db";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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


function getConnection()
{
    global $pdo;
    return $pdo;
}
