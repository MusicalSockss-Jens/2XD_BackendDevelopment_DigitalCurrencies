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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Transactiedetail - XD Wallet</title>
    <style>
      body {
        background-color: #0f172a;
        color: #f8fafc;
        font-family: system-ui, -apple-system, sans-serif;
        margin: 0;
        padding: 2rem;
      }
      h2 {
        color: #38bdf8;
        font-size: 1.5rem;
      }
      p {
        margin: 1rem 0;
      }
      a {
        color: #f8fafc;
        text-decoration: none;
        padding: 0.75rem 1.5rem;
        background-color: #1e293b;
        border: 1px solid #334155;
        border-radius: 8px;
        display: inline-block;
        transition: background-color 0.3s, border-color 0.3s;
      }
      a:hover {
        background-color: #334155;
        border-color: #38bdf8;
      }
    </style>
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