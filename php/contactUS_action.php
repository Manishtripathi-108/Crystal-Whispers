<?php
include "connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    if (!empty($name) && !empty($email) && !empty($message)) {

        $sql = "INSERT INTO contactUs (name, email, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $message);
        $stmt->execute();
        $stmt->close();
    }
}
header("Location: ../Profile.php");
    exit;
?>