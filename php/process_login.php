<?php
session_start();
include "connection.php";

// Redirect to Profile page if user is logged in
if (isset($_SESSION["user_id"])) {
    header('Location: ../Profile.php');
    exit;
}

// Check if it's a signup form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signUpName'], $_POST['signUpEmail'], $_POST['signUpPassword'], $_POST['confirmSignUpPassword'])) {
    $signUpName = ucwords($_POST['signUpName']);
    $signUpEmail = strtolower($_POST['signUpEmail']);
    $signUpPassword = $_POST['signUpPassword'];
    $confirmSignUpPassword = $_POST['confirmSignUpPassword'];
    $_SESSION["signUpName"] = $signUpName;
    $_SESSION["Email"] = $signUpEmail;


    // Validate passwords
    if ($signUpPassword != $confirmSignUpPassword) {
        $_SESSION["Message"] = "Passwords do not match";
    } elseif (($result = validatePassword($signUpPassword)) !== true) {
        $_SESSION["Message"] = $result;
    } elseif (validateName($signUpName)) {
        $_SESSION["Message"] = "User already exists, Login instead";
    } elseif (userExists($signUpEmail)) {
        $_SESSION["Message"] = "User already exists, Login instead";
    } else {
        //register the user
        if (handleUserRegistration($signUpName, $signUpEmail, $signUpPassword)) {
            $_SESSION["loginMessage"] = "Registration successful, Login to continue";
        } else {
            $_SESSION["Message"] = "Something went wrong, Try again";
        }
    }

    header('Location:auth/login.php');
    exit;
}

// Check if it's a login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loginEmail'], $_POST['loginPassword'])) {
    $loginEmail = strtolower($_POST['loginEmail']);
    $loginPassword = $_POST['loginPassword'];

    // Attempt to login the user
    $userID = handleUserLogin($loginEmail, $loginPassword);

    if ($userID !== false) {
        $_SESSION['user_id'] = $userID;
        unset($_SESSION["loginMessage"]);
        header('Location: ../Profile.php');
        exit;
    } else {
        $_SESSION["loginMessage"] = "Incorrect email or password. Please try again.";
        header('Location:auth/login.php');
        exit;
    }
}

//function to validate Name
function validateName($name)
{
    if (!preg_match('/^[a-zA-Z ]+$/', $name)) {
        return "Name must only contain alphabets and spaces.";
    }
}

// Function to validate password
function validatePassword($password)
{
    // Check if the password length is between 8 and 20 characters
    if (strlen($password) < 8 || strlen($password) > 20) {
        return "Password must be between 8 and 20 characters long.";
    }

    // Check if the password contains letters and numbers
    if (!preg_match('/[a-zA-Z]/', $password) || !preg_match('/\d/', $password)) {
        return "Password must contain both letters and numbers.";
    }

    // Check if the password contains only allowed characters
    if (!preg_match('/^[a-zA-Z0-9]+/', $password)) {
        return "Password must not contain spaces, special characters, or emoji.";
    }

    return true;
}

// Function to check if a user already exists
function userExists($email)
{
    global $conn;

    $hashedEmail = hash('sha256', $email);
    $sql = "SELECT UserID, UserPassword FROM users WHERE UserEmail = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $hashedEmail);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $numRows = $result->num_rows;
    $stmt->close();
    return $numRows > 0;
}

// Function to handle user registration
function handleUserRegistration($name, $email, $password)
{
    global $conn;

    $hashedEmail = hash('sha256', $email);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (UserName, UserEmail, UserPassword) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $name, $hashedEmail, $hashedPassword);

    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        return true;
    } else {
        return false;
    }
}

// Function to handle user login
function handleUserLogin($email, $password)
{
    global $conn;

    $hashedEmail = hash('sha256', $email);
    $sql = "SELECT UserID, UserPassword FROM users WHERE UserEmail = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $hashedEmail);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        if ($row = mysqli_fetch_assoc($result)) {
            $storedPassword = $row['UserPassword'];

            if (password_verify($password, $storedPassword)) {
                return $row['UserID'];
            }
        }
    }
    return false;
}
?>