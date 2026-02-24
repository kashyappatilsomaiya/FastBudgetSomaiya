<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';

require_admin();

$q = "
SELECT 
    u.name AS user,
    t.txn_type,
    t.title,
    t.amount,
    t.category,
    t.txn_date
FROM transactions t
JOIN users u ON u.id = t.user_id
ORDER BY t.txn_date DESC
";

$res = $mysqli->query($q);

$transactions = [];
$balances = [];

while ($r = $res->fetch_assoc()) {

    $transactions[] = $r;

    if (!isset($balances[$r['user']])) {
        $balances[$r['user']] = 0;
    }

    $balances[$r['user']] += (
        $r['txn_type'] === 'Income'
            ? $r['amount']
            : -$r['amount']
    );
}

header("Content-Type: application/json");
echo json_encode([
    "transactions" => $transactions,
    "balances" => $balances
]);
