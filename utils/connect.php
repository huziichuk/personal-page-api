<?php

$charset = 'utf8mb4';

$dsn = "mysql:host=" . HOST . ";port=" . PORT . ";dbname=" . DB . ";charset=$charset";

try {
    $pdo = new PDO($dsn, USER, PASSWORD,[
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}