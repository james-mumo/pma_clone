<?php
// tables.php
include 'db.php'; // Ensure db.php has the $pdo connection

// Perform the query using PDO
$stmt = $pdo->query("SHOW TABLES");
$tables = [];

while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
    // Since "SHOW TABLES" returns a single column, just access the first element
    $tables[] = $row[0];  // $row[0] contains the table name
}

header('Content-Type: application/json');
echo json_encode($tables);
