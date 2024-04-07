<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin/admin_login.php");
    exit();
}

if (isset($_POST['section'])) {
    $_SESSION['section'] = $_POST['section'];
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
