<?php
include 'admin/db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$message = trim($_POST['message'] ?? '');
if ($message === '') {
    echo json_encode(["success" => false, "error" => "Empty message"]);
    exit;
}

// If sender is admin, send to user; else send to admin
$stmt = $conn->prepare("SELECT role FROM user_info WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$roleRes = $stmt->get_result()->fetch_assoc();
$role = $roleRes['role'] ?? 'user';

if ($role === 'admin') {
    $receiver_id = intval($_POST['receiver_id'] ?? 0);
    if ($receiver_id <= 0) {
        echo json_encode(["success" => false, "error" => "No user selected"]);
        exit;
    }
} else {
    $receiver_id = 3; // admin id
}

$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $user_id, $receiver_id, $message);
$stmt->execute();

echo json_encode(["success" => true]);

?>
