<?php
require_once __DIR__ . '/inc/auth.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === "" || $password === "") {
        header("Location: index.html?login=empty");
        exit();
    }

    $stmt = $mysqli->prepare(
        "SELECT id, name, password, role FROM users WHERE email = ?"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {

        $stmt->bind_result($uid, $uname, $hashedPass, $role);
        $stmt->fetch();

        if (password_verify($password, $hashedPass)) {

            //  SESSION 
            login_user($uid, $uname, $role);

            // ROLE BASED REDIRECT
            if ($role === 'admin') {
                header("Location: admin/dashboard.html  ");
            } else {
                header("Location: home.html");
            }
            exit();
        }
    }

    header("Location: index.html?login=invalid");
    exit();
}
?>
