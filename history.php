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
    <title>Transactiegeschiedenis - XD Wallet</title>
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