<?php
require_once __DIR__ . '/inc/auth.php';
require_login();

echo "User ID: " . $_SESSION['user_id'] . "<br>";
echo "Username: " . $_SESSION['username'];
