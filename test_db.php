<?php
// Open http://localhost/ironforge/test_db.php to check the database link. Delete this file when done.
require __DIR__ . '/config.php';
header('Content-Type: text/plain');
try {
    $pdo = db();
    echo "Connected to database '" . DB_NAME . "'\n";
    foreach (['users'] as $t) {
        try { echo "$t: " . $pdo->query("SELECT COUNT(*) FROM $t")->fetchColumn() . " rows\n"; }
        catch (Throwable $e) { echo "$t: MISSING\n"; }
    }
} catch (Throwable $e) { echo "Connection FAILED: " . $e->getMessage() . "\n"; }
