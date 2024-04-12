<?php
session_start();
include "connection.php";

// Redirect to login page if user is not logged in
if (!isset($_SESSION["user_id"])) {
    header('Location: auth/login.php');
    exit;
}

$user_id = $_SESSION["user_id"];

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handleProfileUpdate($user_id);
}

// Redirect to the profile page
header('Location: ../public/Profile.php');
exit;

// Function to handle profile update
function handleProfileUpdate($user_id)
{
    global $conn;

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $gender = $_POST['gender'];
    $address1 = $_POST['userAddress'];
    $address2 = $_POST['userAddress_2'];
    $city = $_POST['userCity'];
    $state = $_POST['userState'];
    $zip = $_POST['userZip'];
    $_SESSION["profileMessage"] = "";

    if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] === UPLOAD_ERR_OK) {
        $profilePhoto = handleProfilePhotoUpload($user_id);
        if ($profilePhoto) {
            handleProfilePhotoUpdate($user_id, $profilePhoto);
        } else {
            $_SESSION["profileMessage"] .= "Error uploading Profile Photo. Please try again.<br>";
        }
    }

    // Update user profile information
    $sql = "UPDATE users SET FirstName = ?, LastName = ?, Gender = ?, Address1 = ?, Address2 = ?, City = ?, State = ?, Zip = ? WHERE UserID = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssssssi", $firstName, $lastName, $gender, $address1, $address2, $city, $state, $zip, $user_id);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $_SESSION["profileMessage"] .= "Profile information updated successfully.";
        } else {
            $_SESSION["profileMessage"] .= "Error updating Profile information. Please try again.";
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION["profileMessage"] .= "Error preparing the statement. Please try again.";
    }
}

// Function to handle profile photo upload
function handleProfilePhotoUpload($user_id)
{
    $profilePhoto = str_replace([' ', '(', ')'], ['_', '_', '_'], $_FILES['profilePhoto']['name']);
    $profilePhoto = $user_id . '' . time() . '_' . $profilePhoto;
    $temp_name = $_FILES['profilePhoto']['tmp_name'];
    $path = "../assets/images/users/" . $profilePhoto;

    if (move_uploaded_file($temp_name, $path)) {
        return $profilePhoto;
    } else {
        return false;
    }
}

// Function to handle profile photo update
function handleProfilePhotoUpdate($user_id, $profilePhoto)
{
    global $conn;

    $sql = "UPDATE users SET UserImage = ? WHERE UserID = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $profilePhoto, $user_id);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $_SESSION["profileMessage"] = "Profile Photo updated successfully.<br>";
            handleOldProfilePhotoDeletion();
        } else {
            $_SESSION["profileMessage"] = "Error updating Profile Photo. Please try again.<br>";
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION["profileMessage"] = "Error preparing the statement for Profile Photo. Please try again.<br>";
    }
}

// Function to handle deletion of old profile photo
function handleOldProfilePhotoDeletion()
{
    $oldProfilePhoto = $_POST['oldProfilePhoto'];

    if ($oldProfilePhoto != 'profile.png') {
        $oldFilePath = "../assets/images/users/" . $oldProfilePhoto;

        if (file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }
    }
}
