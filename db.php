<?php
// db.php

$host = '127.0.0.1'; // Database host
$db   = 'user_registration'; // Database name
$user = 'root'; // Database username
$pass = ''; // Database password
$charset = 'utf8mb4'; // Character set

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Enable exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Disable emulation of prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options); // Create PDO instance
} catch (PDOException $e) {
    // Handle connection errors
    echo 'Database Connection Failed: ' . $e->getMessage();
    exit;
}
?>
