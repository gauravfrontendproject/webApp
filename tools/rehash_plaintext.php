<?php
// Re-hash plaintext passwords in `admin` table.
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'webapp';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    echo "DB connect error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error . PHP_EOL;
    exit(1);
}

$res = $mysqli->query('SELECT id, email, password FROM admin');
if (! $res) {
    echo "Query error: " . $mysqli->error . PHP_EOL;
    exit(1);
}

$updated = 0;
while ($r = $res->fetch_assoc()) {
    $id = $r['id'];
    $email = $r['email'];
    $pw = $r['password'];
    if ($pw === null || $pw === '') continue;
    // Consider bcrypt ($2*) and argon ($argon)
    if (strpos($pw, '$2') === 0 || strpos($pw, '$argon') === 0) {
        echo "Skipping id={$id} ({$email}) already hashed\n";
        continue;
    }
    // Re-hash plaintext password
    $new = password_hash($pw, PASSWORD_DEFAULT);
    $stmt = $mysqli->prepare('UPDATE admin SET password = ? WHERE id = ?');
    if (! $stmt) {
        echo "Prepare failed: " . $mysqli->error . PHP_EOL;
        continue;
    }
    $stmt->bind_param('si', $new, $id);
    if ($stmt->execute()) {
        echo "Re-hashed id={$id} ({$email})\n";
        $updated++;
    } else {
        echo "Failed to update id={$id} ({$email}): " . $stmt->error . "\n";
    }
    $stmt->close();
}

echo "Done. Updated {$updated} rows." . PHP_EOL;
$mysqli->close();
