<?php
include "db.php";
session_start();

$user_id = $_SESSION['user_id'] ?? 1;

$data = json_decode(file_get_contents("php://input"), true);

$name  = $data['name'];
$value = $data['value'];
$date  = $data['date'];

/* assets table */
$a = $conn->prepare(
    "INSERT INTO assets (asset_name, value, purchase_date) VALUES (?, ?, ?)"
);
$a->bind_param("sds", $name, $value, $date);
$a->execute();

/* transactions */
$type = "Asset";
$category = "Asset Purchase";

$t = $conn->prepare(
    "INSERT INTO transactions 
    (user_id, txn_type, title, amount, category, txn_date)
    VALUES (?, ?, ?, ?, ?, ?)"
);
$t->bind_param("issdss", $user_id, $type, $name, $value, $category, $date);
$t->execute();

echo json_encode(["status" => "success"]);
