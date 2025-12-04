<?php
// Simple CLI script to dump 'admin' table rows for debugging.
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'webapp';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    echo "DB connect error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error . PHP_EOL;
    exit(1);
}

$res = $mysqli->query('SELECT id, name, email, phone, password, user_type, created_at, updated_at FROM admin');
if (! $res) {
    echo "Query error: " . $mysqli->error . PHP_EOL;
    exit(1);
}

$rows = [];
while ($r = $res->fetch_assoc()) {
    $rows[] = $r;
}

if (count($rows) === 0) {
    echo "No rows found in admin table." . PHP_EOL;
} else {
    foreach ($rows as $row) {
        echo json_encode($row) . PHP_EOL;
    }
}

$mysqli->close();
