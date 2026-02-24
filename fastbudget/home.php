<?php
session_start();

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // optional
header("Access-Control-Allow-Headers: Content-Type");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "NOT_LOGGED_IN"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$conn = mysqli_connect("localhost", "root", "", "fastbudget");

// Fetch assets
$sql = "SELECT id, asset_type, asset_name, amount, created_at 
        FROM assets WHERE user_id = $user_id ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

$assets = [];
while ($row = mysqli_fetch_assoc($result)) {
    $assets[] = $row;
}

echo json_encode([
    "username" => $username,
    "assets" => $assets
]);
