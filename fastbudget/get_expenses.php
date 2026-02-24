<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/auth.php';

require_login();

$user_id = $_SESSION['user_id'];

$stmt = $mysqli->prepare(
    "SELECT id, Title, Amount, Category, ExpenseDate
     FROM expenses
     WHERE user_id = ?
     ORDER BY ExpenseDate DESC"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$res = $stmt->get_result();
$expenses = [];

while ($row = $res->fetch_assoc()) {
    $expenses[] = $row;
}

header("Content-Type: application/json");
echo json_encode($expenses);
