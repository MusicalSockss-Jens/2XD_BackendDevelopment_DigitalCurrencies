<?php

class User {
    private $pdo;
    private $email;
    private $password;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
}