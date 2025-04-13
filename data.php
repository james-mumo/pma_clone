<?php
// data.php
include 'db.php';

// Get the table name from the URL parameter
$table = $_GET['table'] ?? '';

// Prevent SQL injection
if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
    header('Content-Type: application/json');
    die(json_encode(['error' => 'Invalid table name']));
}

try {
    // Check if table exists first
    $stmt = $pdo->prepare("SELECT 1 FROM information_schema.tables WHERE table_schema = ? AND table_name = ?");
    $stmt->execute([$pdo->query("SELECT DATABASE()")->fetchColumn(), $table]);

    if (!$stmt->fetch()) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Table does not exist']);
        exit;
    }

    // Prepare and execute the SQL query to fetch data
    $stmt = $pdo->prepare("SELECT * FROM `$table` LIMIT 100");
    $stmt->execute();

    // Fetch the data as an associative array
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Set the header for JSON response
    header('Content-Type: application/json');
    echo json_encode($data);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
