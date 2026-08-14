<?php

  class User {
    private $email;
    private $password;

    public function setEmail($email) {
        $email = trim($email);
        if (!str_ends_with($email, '@student.thomasmore.be')) {
            throw new Exception("Het e-mailadres moet eindigen op @student.thomasmore.be");
        }
        $this->email = $email;
    }

    public function setPassword($password) {
        if (strlen($password) < 5) {
            throw new Exception("Het wachtwoord moet minstens 5 tekens lang zijn.");
        }
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }
    public function register($pdo) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $this->email]);

        if ($stmt->fetch()) {
            throw new Exception("Dit e-mailadres is al geregistreerd!");
        }

        $stmt = $pdo->prepare("INSERT INTO users (email, password, balance) VALUES (:email, :password, 10.00)");
        return $stmt->execute([
            'email' => $this->email,
            'password' => $this->password
        ]);
    }
}