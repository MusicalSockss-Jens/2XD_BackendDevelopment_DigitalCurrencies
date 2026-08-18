<?php
session_start();
require_once 'db.php';
require_once 'helpers.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT t.id, t.sender_id, t.receiver_id, t.amount, t.timestamp,
           s.email AS sender_email, r.email AS receiver_email
    FROM transactions t
    JOIN users s ON t.sender_id = s.id
    JOIN users r ON t.receiver_id = r.id
    WHERE t.sender_id = :user_id OR t.receiver_id = :user_id2
    ORDER BY t.timestamp DESC
");
$stmt->execute([
    'user_id' => $_SESSION['user_id'],
    'user_id2' => $_SESSION['user_id']
]);
$transactions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Transactiegeschiedenis - XD Wallet</title>
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
      ul {
        list-style: none;
        padding: 0;
        margin: 0 0 1.5rem 0;
      }
      li {
        background-color: #1e293b;
        border: 1px solid #334155;
        padding: 1.25rem;
        margin-bottom: 1rem;
        border-radius: 8px;
        transition: border-color 0.3s, background-color 0.3s;
      }
      li:hover {
        border-color: #38bdf8;
        background-color: #334155;
      }
      a {
        color: #f8fafc;
        text-decoration: none;
      }
      a:hover {
        color: #38bdf8;
      }
      p a {
        padding: 0.75rem 1.5rem;
        background-color: #1e293b;
        border: 1px solid #334155;
        border-radius: 8px;
        display: inline-block;
        transition: background-color 0.3s, border-color 0.3s;
      }
      p a:hover {
        background-color: #334155;
        border-color: #38bdf8;
      }
    </style>
</head>
<body>
    <h2>Transactiegeschiedenis</h2>

    <ul>
    <?php foreach ($transactions as $t): ?>
        <?php if ($t['sender_id'] == $_SESSION['user_id']): ?>
            <li>
                <a href="transaction_detail.php?id=<?= $t['id'] ?>">
                    Je hebt <?= htmlspecialchars($t['amount']) ?> tokens gestuurd naar <?= htmlspecialchars(emailToName($t['receiver_email'])) ?> op <?= htmlspecialchars($t['timestamp']) ?>
                </a>
            </li>
        <?php else: ?>
            <li>
                <a href="transaction_detail.php?id=<?= $t['id'] ?>">
                    <?= htmlspecialchars(emailToName($t['sender_email'])) ?> heeft je <?= htmlspecialchars($t['amount']) ?> tokens gestuurd op <?= htmlspecialchars($t['timestamp']) ?>
                </a>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
    </ul>

    <p><a href="index.php">Terug naar wallet</a></p>
</body>
</html>