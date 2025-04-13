<?php
header('Content-Type: application/json');

// Add proper authentication/authorization here
// if (!isAdmin()) { die(json_encode(['success' => false, 'message' => 'Unauthorized'])); }

include 'db.php'; // Ensure this defines $pdo

$response = ['success' => false];

try {
    // Use $pdo instead of creating a new PDO instance
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

    if (empty($tables)) {
        $response['message'] = 'Database is already empty';
        $response['success'] = true;
    } else {
        // Disable foreign key checks temporarily
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');

        // Drop each table
        foreach ($tables as $table) {
            $pdo->exec("DROP TABLE IF EXISTS `$table`");
        }

        // Re-enable foreign key checks
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

        $response['success'] = true;
        $response['message'] = 'Deleted ' . count($tables) . ' tables';
        $response['tables'] = $tables;
    }
} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
    $response['error'] = $e->getMessage();
    $response['sqlstate'] = $e->errorInfo[0] ?? null;
}

echo json_encode($response);
