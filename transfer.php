<?php
session_start();
require_once 'db.php';
require_once 'Transaction.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $_POST['receiver_email'] ?? '']);
        $receiver = $stmt->fetch();

        if (!$receiver) {
            throw new Exception("Deze gebruiker bestaat niet.");
        }

        $transaction = new Transaction();
        $transaction->setSenderId($_SESSION['user_id']);
        $transaction->setReceiverId($receiver['id']);
        $transaction->setAmount($_POST['amount'] ?? 0);
        $transaction->setReason($_POST['reason'] ?? '');

        $transaction->send($pdo);

        $message = "Transfer gelukt!";
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Geld versturen - XD Wallet</title>
</head>
<body>
    <h2>Geld versturen</h2>

    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST">
        <div>
            <label>E-mailadres ontvanger:</label><br>
            <input type="email" name="receiver_email" required>
        </div>
        <br>
        <div>
            <label>Bedrag:</label><br>
            <input type="number" name="amount" step="0.01" min="1" required>
        </div>
        <br>
        <div>
            <label>Reden (optioneel):</label><br>
            <input type="text" name="reason">
        </div>
        <br>
        <button type="submit">Versturen</button>
    </form>
    <p><a href="index.php">Terug naar wallet</a></p>
</body>
</html>