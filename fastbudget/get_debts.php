<?php
session_start();
header("Content-Type: application/json");

if(!isset($_SESSION['user_id'])){
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user_id'];
$conn = mysqli_connect("localhost","root","","fastbudget");

$res = mysqli_query($conn,
"SELECT debt_type, debt_name, amount, interest_rate, due_date
 FROM debts WHERE user_id=$user_id
 ORDER BY due_date");

$rows=[];
while($r=mysqli_fetch_assoc($res)){
    $rows[]=$r;
}

echo json_encode($rows);
