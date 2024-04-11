<?php
session_start();

if (!isset($_SESSION['adminID'])) {
    header("Location: ../admin/admin-login.php");
    exit();
}

if (isset($_POST['section'])) {
    $_SESSION['section'] = $_POST['section'];
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
