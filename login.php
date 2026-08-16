<?php
session_start();
require_once 'db.php';
require_once 'User.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $user = new User();
        $user->setEmail($_POST['email'] ?? '');
        $user->setPassword($_POST['password'] ?? '');

        $result = $user->login($pdo);

        $_SESSION['user_id'] = $result['id'];
        $_SESSION['email'] = $result['email'];

        header('Location: index.php');
        exit;
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Inloggen - XD Wallet</title>
</head>
<body>
    <h2>Inloggen</h2>

    <?php if ($message): ?>
        <p style="color: red;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST">
        <div>
            <label>E-mailadres:</label><br>
            <input type="email" name="email" required>
        </div>
        <br>
        <div>
            <label>Wachtwoord:</label><br>
            <input type="password" name="password" required>
        </div>
        <br>
        <button type="submit">Inloggen</button>
    </form>
    <p>Nog geen account? <a href="register.php">Registreren</a></p>
</body>
</html>