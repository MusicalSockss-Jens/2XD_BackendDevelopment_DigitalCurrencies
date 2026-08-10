<?php
$host = 'localhost';
$dbname = 'virtual_currency';
$username = 'root';
$password = ''; // leeg laten in Laragon

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database verbinding mislukt: " . $e->getMessage());
} 