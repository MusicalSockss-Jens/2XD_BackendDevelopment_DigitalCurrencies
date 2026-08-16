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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registreren - XD Wallet</title>
    <style>
      body {
        background-color: #0f172a;
        color: #f8fafc;
        font-family: system-ui, -apple-system, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
      }
      .card {
        background-color: #1e293b;
        border: 1px solid #334155;
        padding: 2rem;
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
        margin-bottom: 0.5rem;
      }
      input {
        width: 100%;
        padding: 0.75rem;
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
        padding: 0.75rem;
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
        margin-bottom: 1rem;
        padding: 0.75rem;
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
        margin-top: 1rem;
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
</head>
<body>
    <div class="card">
        <h1>Registreren</h1>

        <?php if ($message): ?>
            <p class="message <?= strpos($message, 'succesvol') !== false ? 'success' : 'error' ?>"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>E-mailadres (@student.thomasmore.be):</label>
            <input type="email" name="email" required placeholder="naam@student.thomasmore.be">

            <label>Wachtwoord (min. 5 tekens):</label>
            <input type="password" name="password" required>

            <button type="submit">Account aanmaken</button>
        </form>
        <p class="link-text">Al een account? <a href="login.php">Inloggen</a></p>
    </div>
</body>
</html>