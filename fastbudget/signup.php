<?php
require_once __DIR__ . '/inc/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if ($name === "" || $email === "" || $pass === "") {
        header("Location: index.html?error=empty");
        exit();
    }

    // Check duplicate email
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: index.html?error=exists");
        exit();
    }
    $stmt->close();

    $hashed = password_hash($pass, PASSWORD_BCRYPT);

    $stmt = $mysqli->prepare(
        "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
    );
    $stmt->bind_param("sss", $name, $email, $hashed);

    if ($stmt->execute()) {
        header("Location: index.html?signup=success");
        exit();
    }

    header("Location: index.html?error=db");
    exit();
}
?>
