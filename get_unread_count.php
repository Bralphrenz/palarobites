<?php
    include 'admin/db_connect.php';
    session_start();

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["success" => false, "error" => "No session"]);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $admin_id = 3; // Admin user ID

    // Count unread messages from admin
    $count_stmt = $conn->prepare("SELECT COUNT(*) as unread_count FROM messages WHERE receiver_id = ? AND sender_id = ? AND is_read = 0");
    $count_stmt->bind_param("ii", $user_id, $admin_id);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result()->fetch_assoc();
    $unread_count = $count_result['unread_count'] ?? 0;

    echo json_encode([
        "success" => true,
        "unread_count" => $unread_count
    ]);
?>