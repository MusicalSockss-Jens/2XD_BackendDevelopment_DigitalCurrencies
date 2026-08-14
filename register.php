<?php
require_once 'db.php';
require_once 'User.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $user = new User();
        $user->setEmail($_POST['email'] ?? '');
        $user->setPassword($_POST['password'] ?? '');

        if ($user->register($pdo)) {
            $message = "Account succesvol aangemaakt! Je hebt 10.00 tokens gekregen.";
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Registreren - XD Wallet</title>
</head>
<body>
    <h2>Registreren</h2>

    <?php if ($message): ?>
        <p style="color: blue;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST">
        <div>
            <label>E-mailadres (@student.thomasmore.be):</label><br>
            <input type="email" name="email" required placeholder="naam@student.thomasmore.be">
        </div>
        <br>
        <div>
            <label>Wachtwoord (min. 5 tekens):</label><br>
            <input type="password" name="password" required>
        </div>
        <br>
        <button type="submit">Account aanmaken</button>
    </form>
    <p>Al een account? <a href="login.php">Inloggen</a></p>
</body>
</html>