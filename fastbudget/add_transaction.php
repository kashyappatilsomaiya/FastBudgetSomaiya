<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/auth.php';

require_login();
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    echo json_encode(["status" => "error", "msg" => "Invalid JSON"]);
    exit;
}

$user_id  = $_SESSION['user_id'];
$type     = $data['Type']; // Income | Expense
$title    = trim($data['Title']);
$amount   = floatval($data['Amount']);
$category = $data['Category'];
$date     = $data['Date'];

$stmt = $mysqli->prepare(
    "INSERT INTO transactions (user_id, txn_type, title, amount, category, txn_date)
     VALUES (?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param("issdss",
    $user_id, $type, $title, $amount, $category, $date
);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "data" => [
            "Type" => $type,
            "Title" => $title,
            "Amount" => $amount,
            "Category" => $category,
            "Date" => $date
        ]
    ]);
} else {
    echo json_encode(["status" => "error"]);
}
