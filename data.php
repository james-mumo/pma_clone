<?php
// data.php
include 'db.php';  // Ensure this file contains the $pdo connection.

// Get the table name from the URL parameter
$table = $_GET['table'] ?? '';

// Prevent SQL injection
if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
    die("Invalid table name.");
}

// Prepare and execute the SQL query to fetch data
$stmt = $pdo->prepare("SELECT * FROM `$table` LIMIT 100");
$stmt->execute();

// Fetch the data as an associative array
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set the header for JSON response
header('Content-Type: application/json');
echo json_encode($data);
