<?php
session_start();
header("Content-Type: application/json");

if(!isset($_SESSION['user_id'])){
    echo json_encode(["status"=>"error"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$conn = mysqli_connect("localhost","root","","fastbudget");

$data = json_decode(file_get_contents("php://input"), true);

$stmt = mysqli_prepare($conn,
"INSERT INTO debts (user_id, debt_type, debt_name, amount, interest_rate, due_date)
 VALUES (?, ?, ?, ?, ?, ?)");

mysqli_stmt_bind_param($stmt,"issdds",
$user_id,
$data['debt_type'],
$data['debt_name'],
$data['amount'],
$data['interest_rate'],
$data['due_date']
);

mysqli_stmt_execute($stmt);

echo json_encode([
"status"=>"success",
"data"=>$data
]);
