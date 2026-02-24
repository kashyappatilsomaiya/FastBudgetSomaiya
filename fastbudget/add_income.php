<?php
session_start();
header("Content-Type: application/json");

if(!isset($_SESSION['user_id'])){
    echo json_encode(["status"=>"error","message"=>"NOT_LOGGED_IN"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$conn = mysqli_connect("localhost","root","","fastbudget");
if(!$conn){
    echo json_encode(["status"=>"error","message"=>"DB error"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$title = $data['Title'];
$amount = $data['Amount'];
$category = $data['Category'];
$date = $data['IncomeDate'];

# Insert into income
$stmt = mysqli_prepare($conn,
"INSERT INTO income (user_id,title,amount,category,income_date)
 VALUES (?,?,?,?,?)");

mysqli_stmt_bind_param($stmt,"isdss",
$user_id,$title,$amount,$category,$date);

if(mysqli_stmt_execute($stmt)){

    # ALSO insert into transactions
    mysqli_query($conn,
    "INSERT INTO transactions
    (user_id,txn_type,title,category,amount,txn_date)
    VALUES
    ($user_id,'Income','$title','$category',$amount,'$date')");

    echo json_encode(["status"=>"success"]);
}
else{
    echo json_encode(["status"=>"error"]);
}

mysqli_close($conn);
?>
