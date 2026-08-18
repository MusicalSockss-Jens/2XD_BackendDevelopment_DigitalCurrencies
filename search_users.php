<?php
session_start();
require_once 'db.php';
require_once 'helpers.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit;
}

$query = $_GET['q'] ?? '';

if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("SELECT email FROM users WHERE email LIKE :query AND id != :user_id LIMIT 5");
$stmt->execute([
    'query' => '%' . $query . '%',
    'user_id' => $_SESSION['user_id']
]);
$users = $stmt->fetchAll();

$results = array_map(function($u) {
    return [
        'email' => $u['email'],
        'name' => emailToName($u['email'])
    ];
}, $users);

echo json_encode($results);