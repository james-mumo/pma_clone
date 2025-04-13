<?php
// switch_database.php
include 'db.php';

$input = json_decode(file_get_contents('php://input'), true);
$newDatabase = $input['database'] ?? '';

if (!preg_match('/^[a-zA-Z0-9_]+$/', $newDatabase)) {
    header('Content-Type: application/json');
    die(json_encode(['success' => false, 'message' => 'Invalid database name']));
}

try {
    // Switch to the new database
    $pdo->exec("USE `$newDatabase`");

    // Update your db.php connection if needed (optional)
    file_put_contents(
        'db.php',
        "<?php\n" .
            "\$host = 'localhost';\n" .
            "\$dbname = '$newDatabase';\n" .
            "\$username = 'root';\n" .
            "\$password = '';\n\n" .
            "try {\n" .
            "    \$pdo = new PDO(\"mysql:host=\$host;dbname=\$dbname\", \$username, \$password);\n" .
            "    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);\n" .
            "} catch (PDOException \$e) {\n" .
            "    echo 'Connection failed: ' . \$e->getMessage();\n" .
            "    exit;\n" .
            "}\n"
    );

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
