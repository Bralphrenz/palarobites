<?php
include 'admin/db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "No session"]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Check role
$stmt = $conn->prepare("SELECT role FROM user_info WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$roleRes = $stmt->get_result()->fetch_assoc();
$role = $roleRes['role'] ?? 'user';

if ($role === 'admin') {
    $partner_id = intval($_GET['user_id'] ?? 0);
    if ($partner_id <= 0) {
        echo json_encode(["success" => false, "error" => "No user selected"]);
        exit;
    }
} else {
    $partner_id = 3; // admin id
}

$q = $conn->prepare("SELECT * FROM messages 
                     WHERE (sender_id=? AND receiver_id=?) OR (sender_id=? AND receiver_id=?) 
                     ORDER BY created_at ASC");
$q->bind_param("iiii", $user_id, $partner_id, $partner_id, $user_id);
$q->execute();
$res = $q->get_result();

$messages = [];
while ($row = $res->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode([
    "success" => true,
    "messages" => $messages,
    "debug" => [
        "user_id" => $user_id,
        "partner_id" => $partner_id,
        "role" => $role,
        "count" => count($messages)
    ]
]);
