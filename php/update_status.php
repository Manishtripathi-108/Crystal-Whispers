<?php
session_start();
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['orderId'];
    $newStatus = $_POST['newStatus'];

    $updateQuery = "UPDATE orders SET OrderStatus = ? WHERE OrderID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('si', $newStatus, $orderId);

    $stmt->execute();
    $stmt->close();

    $_SESSION['section'] = "Orders";
    header("Location: " . $_SERVER['HTTP_REFERER']);

    exit();
}
