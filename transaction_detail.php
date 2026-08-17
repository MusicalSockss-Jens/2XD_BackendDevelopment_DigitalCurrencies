<?php
session_start();
require_once 'db.php';
require_once 'helpers.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: history.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT t.id, t.sender_id, t.receiver_id, t.amount, t.reason, t.timestamp,
           s.email AS sender_email, r.email AS receiver_email
    FROM transactions t
    JOIN users s ON t.sender_id = s.id
    JOIN users r ON t.receiver_id = r.id
    WHERE t.id = :id
");
$stmt->execute(['id' => $id]);
$transaction = $stmt->fetch();

if (!$transaction) {
    header('Location: history.php');
    exit;
}

if ($transaction['sender_id'] != $_SESSION['user_id'] && $transaction['receiver_id'] != $_SESSION['user_id']) {
    header('Location: history.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Transactiedetail - XD Wallet</title>
</head>
<body>
    <h2>Transactiedetail</h2>

    <p>Van: <?= htmlspecialchars(emailToName($transaction['sender_email'])) ?></p>
    <p>Naar: <?= htmlspecialchars(emailToName($transaction['receiver_email'])) ?></p>
    <p>Bedrag: <?= htmlspecialchars($transaction['amount']) ?> XD</p>
    <p>Reden: <?= $transaction['reason'] ? htmlspecialchars($transaction['reason']) : 'Geen reden opgegeven' ?></p>
    <p>Datum: <?= htmlspecialchars($transaction['timestamp']) ?></p>

    <p><a href="history.php">Terug naar geschiedenis</a></p>
</body>
</html>