<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit;
}

$stmt = $pdo->prepare("SELECT balance FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    http_response_code(404);
    exit;
}

header('Content-Type: application/json');
echo json_encode(['balance' => $user['balance']]);