<?php
include "../php/functions.php";

if (isset ($_SESSION['AdminID'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset ($_POST['admin_name'], $_POST['admin_pass'])) {
        $adminName = $_POST['admin_name'];
        $adminPassword = $_POST['admin_pass'];

        try {
            $sql = "SELECT AdminID, AdminPassword FROM admins WHERE AdminName = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error: " . $conn->error);
            }
            $stmt->bind_param("s", $adminName);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $storedPassword = $row['AdminPassword'];

                // Verify password
                if (password_verify($adminPassword, $storedPassword)) {
                    $_SESSION['AdminID'] = $row['AdminID'];
                    unset($_SESSION["adminLoginMessage"]);
                    $stmt->close();
                    header('Location: index.php');
                    exit;
                } else {
                    $_SESSION["adminLoginMessage"] = "Incorrect password. Please try again.";
                }
            } else {
                $_SESSION["adminLoginMessage"] = "No admin found with the provided username.";
            }

            $stmt->close();
            header('Location: admin-login.php');
            exit;
        } catch (Exception $e) {
            $_SESSION["adminLoginMessage"] = $e->getMessage();
            header('Location: admin-login.php');
            exit;
        }
    }
}

$adminLoginMessage = isset ($_SESSION["adminLoginMessage"]) ? $_SESSION["adminLoginMessage"] : "";
unset($_SESSION["adminLoginMessage"]);
?>

<!DOCTYPE html>
<html>

<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Site Metas -->
    <link rel="icon" href="icon/favicon.png" type="image/gif" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Crystal Whispers</title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="css/responsive.css" rel="stylesheet" />
    <!-- animation css -->
    <link rel="stylesheet" href="css/animation.css">

</head>

<body>

    <!-- header section starts -->
    <?php getHeader(null, true) ?>
    <!-- end header section -->

    <main id="adminlogin" class="login-page-container d-flex align-items-center justify-content-center animate__fadeIn">
        <div>
            <form action="" method="post" class="admin-login-form">
                <h3 class="pb-4"
                    style="color:var(--theme-color); font-weight: bold; text-transform: uppercase; margin: 0; font-family:  Playfair Display, serif;">
                    Admin Login
                </h3>
                <?php if ($adminLoginMessage != "") {
                    echo '
                <div id="signup_alert" class="alert alert-danger alert-dismissible fade show"
                    role="alert">
                    ' . $adminLoginMessage . '
                </div>';
                }
                ?>
                <div class="form-floating mb-3">
                    <input name="admin_name" type="text" class="form-control" id="admin-name"
                        placeholder="Email address">
                    <label for="admin-name">User Name</label>
                </div>
                <div class="form-floating mb-3">
                    <input name="admin_pass" type="password" class="form-control" id="Password" placeholder="Password">
                    <label for="Password">Password</label>
                </div>
                <button title="login" type="submit" class="theme-btn">Login</button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center text-lg-start bg-light text-white">
        <!-- Copyright -->
        <div class="text-center p-4 border-top">
            &copy; 2023 <span class="shop-name">Crystal Whispers.</span>
            All rights reserved.
        </div>
        <!-- Copyright -->
    </footer>
    <!-- Footer -->

    <!-- Get Scripts -->
    <?php
    $jsFiles = array(
        'jquery-3.4.1.min.js',
        'bootstrap.js',
        'bootstrap.bundle.js',
        'custom.js'
    );

    addJsFiles("../", $jsFiles);
    ?>
    <!-- End Scripts -->
</body>

</html>