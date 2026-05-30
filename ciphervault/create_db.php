<?php
$host = '127.0.0.1';
$port = '3306';
$user = 'root';
$pass = '';
$db = 'ciphervault';

try {
    $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db`;");
    echo "Database created successfully\n";
} catch (PDOException $e) {
    echo "Error creating database: " . $e->getMessage() . "\n";
}
