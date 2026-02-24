<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/auth.php';

require_login();

// 🔹 JSONx
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "msg" => "Invalid data"]);
    exit;
}

$title    = trim($data['Title']);
$amount   = floatval($data['Amount']);
$category = $data['Category'];
$date     = $data['ExpenseDate'];

$user_id = $_SESSION['user_id'];

// 🔹 Insert into expenses
$exp = $mysqli->prepare(
    "INSERT INTO expenses (user_id, Title, Amount, Category, ExpenseDate)
     VALUES (?, ?, ?, ?, ?)"
);
$exp->bind_param("isdss", $user_id, $title, $amount, $category, $date);
$exp->execute();

// 🔹 Insert into transactions
$txn = $mysqli->prepare(
    "INSERT INTO transactions (user_id, txn_type, title, amount, category, txn_date)
     VALUES (?, 'Expense', ?, ?, ?, ?)"
);
$txn->bind_param("isdss", $user_id, $title, $amount, $category, $date);
$txn->execute();

echo json_encode([
    "status" => "success",
    "data" => [
        "Title" => $title,
        "Amount" => $amount,
        "Category" => $category,
        "ExpenseDate" => $date
    ]
]);
