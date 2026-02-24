<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/auth.php';

require_login();
header('Content-Type: application/json');

$user_id = (int)$_SESSION['user_id'];

/* fetch transactions */
$txns = [];
$res = $mysqli->query("
    SELECT txn_type, title, amount, category, txn_date
    FROM transactions
    WHERE user_id = $user_id
    ORDER BY txn_date DESC
");

while ($row = $res->fetch_assoc()) {
    $txns[] = $row;
}

/* totals */
$totals = $mysqli->query("
    SELECT
        SUM(CASE WHEN txn_type='Income' THEN amount ELSE 0 END)  AS total_income,
        SUM(CASE WHEN txn_type='Expense' THEN amount ELSE 0 END) AS total_expense
    FROM transactions
    WHERE user_id = $user_id
")->fetch_assoc();

$balance = ($totals['total_income'] ?? 0) - ($totals['total_expense'] ?? 0);

echo json_encode([
    "transactions"   => $txns,
    "total_income"   => $totals['total_income'] ?? 0,
    "total_expense"  => $totals['total_expense'] ?? 0,
    "balance"        => $balance
]);
