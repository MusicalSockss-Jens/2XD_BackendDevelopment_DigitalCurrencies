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
}