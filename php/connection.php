<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "crystal_whispers_db";

$conn = mysqli_connect($host, $username, $password, $dbname);

// error_reporting(0);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>