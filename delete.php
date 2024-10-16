<?php
// delete.php

require 'db.php'; // Include the database connection

// Get the user ID from the URL
$id = $_GET['id'] ?? null;

// Check if ID is provided
if (!$id) {
    echo "Invalid User ID.";
    exit;
}

try {
    // Check if user exists
    $checkStmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $checkStmt->execute([$id]);
    $user = $checkStmt->fetch();

    if (!$user) {
        echo "User not found.";
        exit;
    }

    // Delete the user
    $deleteStmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $deleteStmt->execute([$id]);

    // Redirect back to the main page with a success message
    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    echo "Error deleting user: " . $e->getMessage();
    exit;
}
?>
