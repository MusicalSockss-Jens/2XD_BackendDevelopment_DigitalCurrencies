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
    <style>
      body {
        background-color: #0f172a;
        color: #f8fafc;
        font-family: system-ui, -apple-system, sans-serif;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
      }
      .card {
        background-color: #1e293b;
        border: 1px solid #334155;
        padding: 10px;
        border-radius: 12px;
        width: 320px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
      }
      h1 {
        font-size: 1.25rem;
        color: #38bdf8;
        margin-top: 0;
      }
      label {
        display: block;
        color: #94a3b8;
        margin-bottom: 10px;
      }
      input {
        width: 100%;
        padding: 10px;
        margin-bottom: 1rem;
        background-color: #0f172a;
        border: 1px solid #334155;
        border-radius: 6px;
        color: #f8fafc;
        box-sizing: border-box;
      }
      input:focus {
        outline: none;
        border-color: #38bdf8;
      }
      button {
        width: 100%;
        padding: 10px;
        background-color: #38bdf8;
        color: #0f172a;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s;
      }
      button:hover {
        background-color: #0ea5e9;
      }
      .message {
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 6px;
      }
      .success {
        background-color: #164e63;
        color: #86efac;
      }
      .error {
        background-color: #7f1d1d;
        color: #fca5a5;
      }
      .link-text {
        color: #94a3b8;
        margin-top: 10px;
        text-align: center;
      }
      a {
        color: #38bdf8;
        text-decoration: none;
      }
      a:hover {
        text-decoration: underline;
      }
    </style>
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