<?php
require_once('db_connect.php');
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $res = $conn->query("UPDATE orders SET status = 1 WHERE id = $id");
    if ($res) {
        echo 'success';
    } else {
        echo 'error';
    }
}
$conn->close();