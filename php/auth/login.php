<?php
include "../functions.php";

// Redirect to Profile page if user is logged in
if (checkGetUserLoginStatus()) {
    header('Location: ../../Profile.php');
    exit;
}

$loginMessage = isset($_SESSION["loginMessage"]) ? $_SESSION["loginMessage"] : "";
$Message = isset($_SESSION["Message"]) ? $_SESSION["Message"] : "";
$signUpName = isset($_SESSION["signUpName"]) ? $_SESSION["signUpName"] : "";
$Email = isset($_SESSION["Email"]) ? $_SESSION["Email"] : "";

unset($_SESSION["signUpName"]);
unset($_SESSION["Email"]);
unset($_SESSION["loginMessage"]);
unset($_SESSION["Message"]);
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

    <!-- Get Styles -->
    <?php
    $cssFiles = array(
        'bootstrap.css',
        'style.css',
        'responsive.css',
        'animation.css'
    );

    addCssFiles("../../", $cssFiles);
    ?>
    <!-- End Styles -->

</head>

<body>

    <!-- header section starts -->
    <?php getHeader(null, true) ?>
    <!-- end header section -->

    <main class="login-page-container d-flex align-items-center justify-content-center animate__fadeIn">
        <div id="login-form" class="<?php echo ($Message == "") ? "" : "d-none"; ?>">
            <form action="../process_login.php" method="post">
                <h3 class="pb-4"
                    style="color:var(--theme-color); font-weight: bold; font-family:  Playfair Display, serif;">
                    Login
                </h3>
                <?php
                if ($loginMessage != "") {
                    echo '<div id="login_alert" class="alert ' . ($loginMessage == "Registration successful, Login to continue" ? "alert-success" : "alert-danger") . ' alert-dismissible fade show" role="alert">
                ' . $loginMessage . '
                </div>';
                }
                ?>
                <div class="form-floating mb-3">
                    <input required name="loginEmail" type="email" class="form-control" id="loginEmail" value="<?php echo $Email; ?>" placeholder="Email address">
                    <label for="loginEmail">Email address</label>
                </div>
                <div class="form-floating mb-3">
                    <input required type="password" class="form-control" name="loginPassword" id="loginPassword" placeholder="Password">
                    <label for="loginPassword">Password</label>
                </div>
                <button title="Login" type="submit" class="theme-btn">Login</button>
            </form>
            <div class="signup-section mt-3">
                Not a member yet?
                <a id="toggle-signup" style="color: var(--theme-color); cursor: pointer;"> Sign Up</a>.
            </div>
        </div>

        <div id="signup-form" class="<?php echo ($Message == "") ? "d-none" : ""; ?>">
            <form action="../process_login.php" method="post">
                <h3 class="pb-4" style="color:var(--theme-color); font-weight: bold; font-family:  Playfair Display, serif;">
                    Signup
                </h3>

                <?php
                if ($Message != "") {
                    echo '<div id="signup_alert" class="alert alert-danger alert-dismissible fade show" role="alert">
                ' . $Message . '
                </div>';
                }
                ?>

                <div class="form-floating mb-3">
                    <input required name="signUpName" type="text" pattern="[a-zA-Z ]+" title="Only alphabets and spaces are allowed." class="form-control" id="signUpName" value="<?php echo $signUpName; ?>" placeholder="Full Name">
                    <label for="signUpName">Full Name</label>
                </div>
                <div class="form-floating mb-3">
                    <input required name="signUpEmail" type="email" class="form-control" value="<?php echo $Email; ?>" id="signUpEmail" placeholder="Email address">
                    <label for="signUpEmail">Email address</label>
                </div>
                <div class="form-floating mb-3">
                    <input required name="signUpPassword" type="password" class="form-control" id="signUpPassword" placeholder="Password">
                    <label for="signUpPassword">Password</label>
                    <small id="passwordHelpBlock" class="form-text text-muted">
                        Your password must be 8-20 characters long, contain letters and numbers, and must not contain
                        spaces, or emoji.
                    </small>
                </div>
                <div class="form-floating mb-3">
                    <input required name="confirmSignUpPassword" type="password" class="form-control" id="confirmSignupPassword" placeholder="Confirm Password">
                    <label for="confirmSignupPassword">Confirm Password</label>
                </div>
                <button title="Signup" type="submit" class="theme-btn">Signup</button>
            </form>
            <div class="signup-section mt-3">
                Already a member?
                <a id="toggle-login" style="color: var(--theme-color); cursor: pointer;"> Login</a>.
            </div>
        </div>
    </main>


    <!-- Footer -->
    <footer class="text-center text-lg-start bg-light text-white animate__fadeInUp">
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

    addJsFiles("../../", $jsFiles);
    ?>
    <!-- End Scripts -->
</body>

</html>