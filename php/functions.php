<?php
session_start();
include "connection.php";

// check user login
function checkGetUserLoginStatus($returnID = false, $redirect = false)
{
    if (isset($_SESSION['user_id'])) {
        if ($returnID) {
            return $_SESSION['user_id'];
        } else {
            return true;
        }
    } else {
        if ($redirect) {
            header('Location: login.php');
            exit();
        }
        return false;
    }
}

//header
function getHeader($title = null, $notGetLogin = null)
{
    if (isset($_SESSION['user_id']) && is_null($notGetLogin)) {
        $cartQuantity = getCartQuantity();

        echo '
        <header class="header_section ' . ($title != 'Home' ? 'innerpage_header' : '') . ' animate__slideDown">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg custom_nav-container">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <a class="navbar-brand" href="index.php">
                        <img src="icon/favicon.png" alt="Crystal Whispers">
                        <span>Crystal Whispers</span>
                    </a>
                    
                    <div class="quote_btn-container navbar-toggler">
                        <a href="cart.php" class="position-relative">
                            <span style="left: 90%;"
                            class="position-absolute top-0 translate-middle badge border border-light rounded-circle theme-bg-color">
                            ' . $cartQuantity . '
                            </span>
                            <img src="icon/shopping-cart.svg" alt="Cart">
                        </a>
                    </div>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <div class="d-flex ms-auto flex-column flex-lg-row align-items-center">
                            <ul class="navbar-nav">
                                <li class="nav-item ' . ($title == 'Home' ? 'active' : '') . '">
                                <a class="nav-link" href="index.php">Home</a>
                                </li>
                                <li class="nav-item ' . ($title == 'About' ? 'active' : '') . '">
                                    <a class="nav-link" href="about.php">About</a>
                                </li>
                                <li class="nav-item ' . ($title == 'Jewellery' ? 'active' : '') . '">
                                    <a class="nav-link" href="jewellery.php">Jewellery</a>
                                </li>
                                <li class="nav-item ' . ($title == 'Profile' ? 'active' : '') . '">
                                    <a class="nav-link op-btn" id="profile-button" href="Profile.php">
                                    <img src="icon/Profile.svg" alt="Profile">
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="quote_btn-container web-cart">
                            <a href="cart.php" class="position-relative">
                                <span style="left: 70%;"
                                    class="position-absolute top-0 translate-middle badge border border-light rounded-circle theme-bg-color">
                                    ' . $cartQuantity . '
                                    </span>
                                <img src="icon/shopping-cart.svg" alt="Cart">
                            </a>
                    </div>
                    </div>
                </nav>
            </div>
    </header>
    ';
    } else {
        echo '
        <header class="header_section ' . ($title != 'Home' ? 'innerpage_header' : '') . ' animate__slideDown">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg custom_nav-container">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>

                    <a class="navbar-brand" href="index.php">
                    <img src="icon/favicon.png" alt="Crystal Whispers">
                    <span>Crystal Whispers</span>
                    </a>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <div class="d-flex ms-auto flex-column flex-lg-row align-items-center">
                            <ul class="navbar-nav">
                            <li class="nav-item ' . ($title == 'Home' ? 'active' : '') . '">
                                <a class="nav-link" href="index.php">Home</a>
                            </li>
                            <li class="nav-item ' . ($title == 'About' ? 'active' : '') . '">
                                <a class="nav-link" href="about.php">About</a>
                            </li>
                            <li class="nav-item ' . ($title == 'Jewellery' ? 'active' : '') . '">
                                <a class="nav-link" href="jewellery.php">Jewellery</a>
                            </li>
                            ' . (is_null($notGetLogin) ? '<li class="nav-item">
                                    <a class="nav-link simple-btn" id="login-button" href="login.php">Login</a>
                                </li>' : '') . '
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </header>
        ';
    }
}

//getting cart quantity
function getCartQuantity()
{
    global $conn;

    $sql = "SELECT SUM(quantity) AS totalQuantity FROM cart WHERE userID =" . $_SESSION['user_id'];
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $cartQuantity = $row['totalQuantity'];
    }

    return $cartQuantity = $cartQuantity == null ? 0 : $cartQuantity;
}

//footer
function getFooter()
{
    if (isset($_SESSION['sbsAlert'])) {
        echo '<div id="signup_alert" class="alert' . ($_SESSION['sbsAlert'] == "Error! Please try again" ? " alert-danger" : " alert-success") . '
        alert-dismissible fade show" role="alert">
                ' . $_SESSION['sbsAlert'] . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';

        unset($_SESSION['sbsAlert']);
    }
    echo '
    <footer class="text-lg-start bg-light text-white fadeInUp">
        <section class="pt-3">
            <form action="php/subscribe-news.php" method="post">
                <input type="hidden" id="scrollPoint" name="scrollPoint" value="">
                <div class="row d-flex justify-content-center align-items-baseline mx-0 ">
                    <div class="col-auto">
                        <p class="pt-2">
                            <strong>Sign up for our newsletter</strong>
                        </p>
                    </div>
                    <div class="col-md-5 col-12">
                        <!-- Email input -->
                        <div class="form-floating mb-4 text-dark">
                            <input type="email" id="Subscribe-news-ltr" name="Subscribe_news_ltr" class="form-control"
                                placeholder="Enter Your Email" />
                            <label for="Subscribe-news-ltr">Enter Your Email</label>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="submit" id="subscribe-btn" class="simple-btn mb-4" onclick="setScrollPosition()">
                            Subscribe
                        </button>
                    </div>
                </div>
            </form>

            <script>
                function setScrollPosition() {
                    var scrollPosition = window.scrollY;
                    document.getElementById("scrollPoint").value = scrollPosition;
                }
                var scrollPosition = ' . (isset($_SESSION['scrollPoint']) ? $_SESSION['scrollPoint'] : 0) . ';
                window.scrollTo(0, scrollPosition);
            </script>
        </section>

        <!-- Section: Social media -->
        <section class="d-flex justify-content-center justify-content-lg-around p-3 border-bottom">
            <div class="me-5 d-none d-lg-block">
                <span>Get connected with us on social networks:</span>
            </div>
            <div class="d-flex justify-content-around align-items-center gap-4">
                <a href="" class="mr-4 text-reset">
                <img src="icon/facebook.svg" alt="Facebook">
                </a>
                <a href="" class="mr-4 text-reset">
                <img src="icon/twitter.svg" alt="Twitter">
                </a>
                <a href="" class="mr-4 text-reset">
                <img src="icon/instagram.svg" alt="Instagram">
                </a>
                <a href="https://wa.me/916280600090" class="mr-4 text-reset">
                <img src="icon/whatsapp.svg" alt="whatsapp">
                </a>
            </div>
        </section>

        <!-- Section: footer-links  -->
        <section class="footer-links">
            <div class="container text-md-start mt-5">
                <div class="row mt-3 justify-content-around">
                    <div class="col-md-3 text-center col-lg-3 mb-4">
                        <h6 class="text-uppercase fw-bold mb-4">
                            <img width="50" src="icon/favicon.png" alt="Icon">
                            <span class="shop-name">Crystal Whispers</span>
                        </h6>
                        <p>
                            Elevate your style with our exquisite selection of handcrafted jewelry.
                        </p>
                    </div>
                    <div class="col-2 col-lg-2 mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold mb-4 theme-color">
                            Menu
                        </h6>
                        <p>
                            <a href="index.php">Home</a>
                        </p>
                        <p>
                            <a href="about.php">About</a>
                        </p>
                        <p>
                            <a href="jewellery.php">Jewellery</a>
                        </p>
                        <p>
                            <a href="cart.php">Blog</a>
                        </p>
                    </div>
                    <div class="col-2 col-lg-2 mb-4">
                        <h6 class="text-uppercase fw-bold mb-4 theme-color">
                            Useful links
                        </h6>
                        <p>
                            <a href="Profile.php">Profile</a>
                        </p>
                        <p>
                            <a href="Profile.php">Settings</a>
                        </p>
                        <p>
                            <a href="Profile.php">Orders</a>
                        </p>
                        <p>
                            <a href="Profile.php">Contact Us</a>
                        </p>
                    </div>
                    <div class="col-12 col-sm-5 mb-md-0 mb-4 row">
                        <h6 class="col-12 text-center text-sm-start col fw-bold mb-4 theme-color">Contact</h6>
                        <p class="col-sm-12 d-flex align-items-center justify-content-center justify-content-sm-start gap-2">
                            <img src="icon/address.png" alt="address"> <span>Sadar Bazar, Barnala, <br>
                                Punjab-148101.</span>
                        </p>
                        <p class="col-sm-12 d-flex align-items-center justify-content-center justify-content-sm-start gap-2">
                            <a href="mailto:crystalwhisper@gmail.com">
                                <img src="icon/email.png" alt="email">
                                <span>crystals@gmail.com</span>
                            </a>
                        </p>
                        <p class="col-sm-12 d-flex align-items-center justify-content-center justify-content-sm-start gap-2">
                            <a href="tel:+916280600090">
                                <img src="icon/phone.png" alt="phone">
                                <span>+91 62806-00090</span>
                            </a>
                        </p>
                        <p class="col-sm-12 d-flex align-items-center justify-content-center justify-content-sm-start gap-2">
                            <a href="https://wa.me/916280600090" target="_blank">
                                <img src="icon/whatsapp.png" alt="whatsapp">
                                <span>+91 62806-00090</span>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Copyright -->
        <div class="text-center p-4 border-top">
            &copy; 2023 <span class="shop-name">Crystal Whispers.</span>
            All rights reserved.
        </div>
        <!-- Copyright -->
    </footer>
    ';

    if (isset($_SESSION['scrollPoint'])) {
        unset($_SESSION['scrollPoint']);
    }
}

//get scripts
function getScripts()
{
    echo '
    <!-- font awesome -->
    <script src="https://kit.fontawesome.com/34176f497f.js" crossorigin="anonymous"></script>
    <!-- jQery -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <!-- bootstrap js -->
    <script src="js/bootstrap.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
    <!-- custom js -->
    <script src="js/custom.js"></script>
    ';
}

// move items from cart to order_items (confirm order)
function moveItemsFromCartToOrder($conn, $userID, $order_id)
{
    $sql = "SELECT productID, quantity FROM cart WHERE userID = $userID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $productID = $row['productID'];
            $quantity = $row['quantity'];

            $insertOrderItems = "INSERT INTO order_items (orderID, productID, quantity) VALUES (?, ?, ?)";
            $insertOrderItemsStmt = $conn->prepare($insertOrderItems);
            $insertOrderItemsStmt->bind_param("iii", $order_id, $productID, $quantity);
            $inOrderItems = $insertOrderItemsStmt->execute();

            if ($inOrderItems) {
                $deleteCartItems = "DELETE FROM cart WHERE userID = $userID";
                $conn->query($deleteCartItems);
            }
        }
    }
}

// update units_sold and stock in products table (confirm order)
function updateProductDetails($conn, $userID)
{
    $sql = "SELECT productID, quantity FROM cart WHERE userID = $userID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $productID = $row['productID'];
            $quantity = $row['quantity'];

            $sql_product_details = "SELECT units_sold, stock_quantity FROM products WHERE product_id = $productID";
            $result_product_details = $conn->query($sql_product_details);

            if ($result_product_details->num_rows > 0) {
                $row_product_details = $result_product_details->fetch_assoc();
                $units_sold = $row_product_details['units_sold'];
                $stock = $row_product_details['stock_quantity'];

                $units_sold += $quantity;
                $stock -= $quantity;

                $updateProductDetails = "UPDATE products SET units_sold = $units_sold, stock_quantity = $stock WHERE product_id = $productID";
                $conn->query($updateProductDetails);
            }
        }
    }
}

// insert order details into the orders table (confirm order)
function insertOrderDetails($conn, $userID, $name, $email, $phone, $paymentMethod, $shippingAddress, $msgSeller, $totalAmount)
{
    $insertOrderStmt = $conn->prepare("INSERT INTO orders (user_id, receiver_name, email, phone, payment_method, shipping_address, msg_seller, total_amount)
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $insertOrderStmt->bind_param("issssssd", $userID, $name, $email, $phone, $paymentMethod, $shippingAddress, $msgSeller, $totalAmount);
    $inOrders = $insertOrderStmt->execute();

    return $inOrders;
}

// fetch worker details to about page
function fetchWorkerDetails()
{
    global $conn;

    $sql = "SELECT name, position, image, description
            FROM workers
            WHERE description IS NOT NULL AND description != 'No Description Available'
            LIMIT 18;";
    $result = $conn->query($sql);
    $workersData = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $workerInfo = array(
                'name' => $row['name'],
                'position' => $row['position'],
                'image' => $row['image'],
                'description' => $row['description']
            );
            $workersData[] = $workerInfo;
        }
    }
    return $workersData;
}
?>