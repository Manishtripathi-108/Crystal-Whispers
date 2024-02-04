<?php
session_start();
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Subscribe_news_ltr'])) {
    $Subscribe_news_ltr = $_POST['Subscribe_news_ltr'];
    $sql = "INSERT INTO newsletter (Subscribe_news_ltr) VALUES ('$Subscribe_news_ltr')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $_SESSION['scrollPoint'] = $_POST['scrollPoint'];
        $_SESSION['sbsAlert'] = "Thank you for subscribing to our newsletter!";
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        $_SESSION['sbsAlert'] = "Error! Please try again";
        $_SESSION['scrollPoint'] = $_POST['scrollPoint'];
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
}
?>