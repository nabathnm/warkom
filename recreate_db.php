<?php

$host = '127.0.0.1';
$port = 3306;
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected successfully to MySQL server.\n";

    // Drop database if exists
    $pdo->exec("DROP DATABASE IF EXISTS warkom_db");
    echo "Database dropped (if existed).\n";

    // Create database
    $pdo->exec("CREATE DATABASE warkom_db");
    echo "Database 'warkom_db' recreated successfully.\n";

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
