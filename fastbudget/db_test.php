<?php
require_once __DIR__ . '/inc/config.php';

$result = $mysqli->query("SELECT COUNT(*) AS cnt FROM users");
if ($result) {
    $row = $result->fetch_assoc();
    echo "DB OK — users count: " . (int)$row['cnt'];
} else {
    echo "DB ERROR: " . $mysqli->error;
}
