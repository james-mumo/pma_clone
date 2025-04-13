<?php
// Include your db.php file to access the $pdo object
require 'db.php';

// Set content type for JSON response
header('Content-Type: application/json');

// Read the raw POST data (JSON)
$input = json_decode(file_get_contents('php://input'), true);

$table = $input['table'] ?? null;
$key = $input['key'] ?? null;
$value = $input['value'] ?? null;
$row_id = $input['row_id'] ?? null;

if (!$table || !$key || !$row_id) {
    // If any required parameters are missing, send an error message
    echo json_encode(['success' => false, 'message' => 'Missing parameters.']);
    exit;
}

try {

    // Disable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS=0");

    // Use prepared statements to prevent SQL injection
    $stmt = $pdo->prepare("UPDATE `$table` SET `$key` = :value WHERE id = :id");
    $stmt->execute([
        ':value' => $value,
        ':id' => $row_id
    ]);


    // Re-enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS=1");

    // Return a success message
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    // Ensure foreign key checks are re-enabled even if error occurs
    $pdo->exec("SET FOREIGN_KEY_CHECKS=1");


    // If an error occurs, return the error message in JSON format
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
