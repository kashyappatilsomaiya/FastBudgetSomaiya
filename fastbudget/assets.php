<?php
session_start();
header("Content-Type: application/json");

$conn = mysqli_connect("localhost", "root", "", "fastbudget");

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "GET") {

    if (!isset($_SESSION['user_id'])) {
        echo json_encode([]);
        exit();
    }

    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM assets WHERE user_id = $user_id ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode($data);
    exit();
}


if ($method == "POST") {

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["status" => "error", "message" => "Not logged in"]);
        exit();
    }

    $data = json_decode(file_get_contents("php://input"), true);

    $user_id = $_SESSION['user_id'];
    $asset_type = $data['asset_type'];
    $asset_name = $data['asset_name'];
    $amount = $data['amount'];

    $sql = "INSERT INTO assets(user_id, asset_type, asset_name, amount) 
            VALUES('$user_id', '$asset_type', '$asset_name', '$amount')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }

    exit();
}
?>
