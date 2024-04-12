<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {

    $logoutType = $_POST['logout'];

    switch ($logoutType) {
        case 'admin_logOut':
            unset($_SESSION['AdminID']);
            header('Location:../../admin/admin-login.php');
            exit;

        case 'user_logOut':
            unset($_SESSION['user_id']);
            header('Location:login.php');
            exit;

        default:
            unset($_SESSION['user_id']);
            unset($_SESSION['AdminID']);
            header('Location:.././index.php');
            exit;
    }
}

header('Location:../index.php');
exit;
