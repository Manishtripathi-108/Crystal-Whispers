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
            header('Location: ../php/auth/login.php');
            exit();
        }
        return false;
    }
}

//header
function getHeader($dir = null, $fileDir = null, $title = null, $notGetLogin = null)
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

                    <a class="navbar-brand" href="' . ($title == 'Home' ? '' : '../') . 'index.php">
                        <img src="' . $dir . 'assets/icon/favicon.png" alt="Crystal Whispers">
                        <span>Crystal Whispers</span>
                    </a>
                    
                    <div class="quote_btn-container navbar-toggler">
                        <a href="' . $fileDir . 'cart.php" class="position-relative">
                            <span style="left: 90%;" class="position-absolute top-0 translate-middle badge border border-light rounded-circle theme-bg-color">
                            ' . $cartQuantity . '
                            </span>
                            <img src="' . $dir . 'assets/icon/shopping-cart.svg" alt="Cart">
                        </a>
                    </div>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <div class="d-flex ms-auto flex-column flex-lg-row align-items-center">
                            <ul class="navbar-nav">
                                <li class="nav-item ' . ($title == 'Home' ? 'active' : '') . '">
                                <a class="nav-link" href="' . ($title == 'Home' ? '' : '../') . 'index.php">Home</a>
                                </li>
                                <li class="nav-item ' . ($title == 'About' ? 'active' : '') . '">
                                    <a class="nav-link" href="' . $fileDir . 'about.php">About</a>
                                </li>
                                <li class="nav-item ' . ($title == 'Jewellery' ? 'active' : '') . '">
                                    <a class="nav-link" href="' . $fileDir . 'jewellery.php">Jewellery</a>
                                </li>
                                <li class="nav-item ' . ($title == 'Profile' ? 'active' : '') . '">
                                    <a class="nav-link op-btn" id="profile-button" href="' . $fileDir . 'Profile.php">
                                    <img src="' . $dir . 'assets/icon/Profile.svg" alt="Profile">
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="quote_btn-container web-cart">
                            <a href="' . $fileDir . 'cart.php" class="position-relative">
                                <span style="left: 70%;"
                                    class="position-absolute top-0 translate-middle badge border border-light rounded-circle theme-bg-color">
                                    ' . $cartQuantity . '
                                    </span>
                                <img src="' . $dir . 'assets/icon/shopping-cart.svg" alt="Cart">
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

                    <a class="navbar-brand" href="' . ($title == 'Home' ? '' : '../') . 'index.php">
                    <img src="' . $dir . 'assets/icon/favicon.png" alt="Crystal Whispers">
                    <span>Crystal Whispers</span>
                    </a>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <div class="d-flex ms-auto flex-column flex-lg-row align-items-center">
                            <ul class="navbar-nav">
                            <li class="nav-item ' . ($title == 'Home' ? 'active' : '') . '">
                                <a class="nav-link" href="' . ($title == 'Home' ? '' : '../') . 'index.php">Home</a>
                            </li>
                            <li class="nav-item ' . ($title == 'About' ? 'active' : '') . '">
                                <a class="nav-link" href="' . $fileDir . 'about.php">About</a>
                            </li>
                            <li class="nav-item ' . ($title == 'Jewellery' ? 'active' : '') . '">
                                <a class="nav-link" href="' . $fileDir . 'jewellery.php">Jewellery</a>
                            </li>
                            ' . (is_null($notGetLogin) ? '<li class="nav-item">
                                    <a class="nav-link simple-btn" id="login-button" href="../php/auth/login.php">Login</a>
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

    $sql = "SELECT SUM(Quantity) AS totalQuantity FROM cart WHERE userID =" . $_SESSION['user_id'];
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $cartQuantity = $row['totalQuantity'];
    }

    return $cartQuantity = $cartQuantity == null ? 0 : $cartQuantity;
}

//footer
function getFooter($dir = "../")
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
            <form action="' . $dir . 'php/subscribe-news.php" method="post">
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
                <img src="' . $dir . 'assets/icon/facebook.svg" alt="Facebook">
                </a>
                <a href="" class="mr-4 text-reset">
                <img src="' . $dir . 'assets/icon/twitter.svg" alt="Twitter">
                </a>
                <a href="" class="mr-4 text-reset">
                <img src="' . $dir . 'assets/icon/instagram.svg" alt="Instagram">
                </a>
                <a href="https://wa.me/916280600090" class="mr-4 text-reset">
                <img src="' . $dir . 'assets/icon/whatsapp.svg" alt="whatsapp">
                </a>
            </div>
        </section>

        <!-- Section: footer-links  -->
        <section class="footer-links">
            <div class="container text-md-start mt-5">
                <div class="row mt-3 justify-content-around">
                    <div class="col-md-3 text-center col-lg-3 mb-4">
                        <h6 class="text-uppercase fw-bold mb-4">
                            <img width="50" src="' . $dir . 'assets/icon/favicon.png" alt="Icon">
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
                            <img src="' . $dir . 'assets/icon/address.png" alt="address"> <span>Sadar Bazar, Barnala, <br>
                                Punjab-148101.</span>
                        </p>
                        <p class="col-sm-12 d-flex align-items-center justify-content-center justify-content-sm-start gap-2">
                            <a href="mailto:crystalwhisper@gmail.com">
                                <img src="' . $dir . 'assets/icon/email.png" alt="email">
                                <span>crystals@gmail.com</span>
                            </a>
                        </p>
                        <p class="col-sm-12 d-flex align-items-center justify-content-center justify-content-sm-start gap-2">
                            <a href="tel:+916280600090">
                                <img src="' . $dir . 'assets/icon/phone.png" alt="phone">
                                <span>+91 62806-00090</span>
                            </a>
                        </p>
                        <p class="col-sm-12 d-flex align-items-center justify-content-center justify-content-sm-start gap-2">
                            <a href="https://wa.me/916280600090" target="_blank">
                                <img src="' . $dir . 'assets/icon/whatsapp.png" alt="whatsapp">
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

// move items from cart to order_items (confirm order)
function moveItemsFromCartToOrder($conn, $userID, $order_id)
{
    $sql = "SELECT ProductID, Quantity FROM cart WHERE UserID = $userID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $productID = $row['ProductID'];
            $quantity = $row['Quantity'];
            $fetch_price = "SELECT ProductPrice, ProductDiscount FROM Products WHERE ProductID = $productID";
            $result_price = $conn->query($fetch_price);
            $row_price = $result_price->fetch_assoc();
            $unitPrice = $row_price['ProductPrice'] * ((100 - $row_price['ProductDiscount']) / 100);
            $totalPrice = $unitPrice * $quantity;

            $insertOrderItems = "INSERT INTO orderItems (OrderID, ProductID, Quantity, UnitPrice, TotalPrice) VALUES (?, ?, ?, ?, ?)";
            $insertOrderItemsStmt = $conn->prepare($insertOrderItems);
            $insertOrderItemsStmt->bind_param("iiidi", $order_id, $productID, $quantity, $unitPrice, $totalPrice);
            $inOrderItems = $insertOrderItemsStmt->execute();

            if ($inOrderItems) {
                $deleteCartItems = "DELETE FROM cart WHERE UserID = $userID";
                $conn->query($deleteCartItems);
            }
        }
    }
}

// update units_sold and stock in products table (confirm order)
function updateProductStockDetails($conn, $userID)
{
    $sql = "SELECT ProductID, Quantity FROM cart WHERE UserID = $userID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $productID = $row['ProductID'];
            $quantity = $row['Quantity'];

            $sql_product_details = "SELECT ProductUnitsSold, ProductStock FROM Products WHERE ProductID = $productID";
            $result_product_details = $conn->query($sql_product_details);

            if ($result_product_details->num_rows > 0) {
                $row_product_details = $result_product_details->fetch_assoc();
                $units_sold = $row_product_details['ProductUnitsSold'];
                $stock = $row_product_details['ProductStock'];

                $units_sold += $quantity;
                $stock -= $quantity;

                $updateProductDetails = "UPDATE Products SET ProductUnitsSold = $units_sold, ProductStock = $stock WHERE product_id = $productID";
                $conn->query($updateProductDetails);
            }
        }
    }
}

// insert order details into the orders table (confirm order)
function insertOrderDetails($conn, $userID, $name, $email, $phone, $payMethod, $shipAddress, $msgSeller, $totalAmount)
{
    $insertOrderStmt = $conn->prepare("INSERT INTO orders (UserID, RCVName, RCVEmail, RCVPhone, PayMethod, ShipAddress, MsgSeller, TotalAmount)
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $insertOrderStmt->bind_param("issssssd", $userID, $name, $email, $phone, $payMethod, $shipAddress, $msgSeller, $totalAmount);
    $inOrders = $insertOrderStmt->execute();

    return $inOrders;
}

// fetch products
function fetchProducts($category = "All", $gender = "All", $material = "All", $occasion = "All", $orderBy = 0, $limit = 0)
{
    global $conn;

    $sql = "SELECT
            p.ProductID,
            p.ProductName,
            p.ProductTargetGender,
            p.ProductPrice,
            p.ProductDiscount,
            p.ProductRating,
            p.ProductStock,
            p.ProductUnitsSold,
            p.ProductMaterial,
            p.ProductWeight,
            p.ProductColor,
            p.ProImg1,
            o.OccasionName,
            c.CategoryName
        FROM
            Products p
        JOIN
            Categories c ON p.CategoryID = c.CategoryID
        JOIN
            Occasions o ON p.OccasionID = o.OccasionID
        WHERE 1";

    if ($category !== 'All') {
        $sql .= " AND p.CategoryID = '$category'";
    }

    if ($gender !== 'All') {
        $sql .= " AND p.ProductTargetGender = '$gender'";
    }

    if ($material !== 'All') {
        $sql .= " AND p.ProductMaterial = '$material'";
    }

    if ($occasion !== 'All') {
        $sql .= " AND p.OccasionID = '$occasion'";
    }

    $sql .= " ORDER BY ";

    switch ($orderBy) {
        case '1':
            $sql .= "p.ProductCreatedAt DESC";
            break;
        case '2':
            $sql .= "p.ProductUnitsSold DESC";
            break;
        case '3':
            $sql .= "p.ProductDiscount DESC";
            break;
        case '4':
            $sql .= "p.ProductRating DESC";
            break;
        case '5':
            $sql .= "p.ProductWeight DESC";
            break;
        case '6':
            $sql .= "p.ProductStock DESC";
            break;
        case '7':
            $sql .= "p.ProductID DESC";
            break;
        default:
            $sql .= "p.ProductCreatedAt DESC";
    }

    if ($limit > 0) {
        $sql .= " LIMIT " . $limit;
    }
    $result = $conn->query($sql);
    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $productDetails = array(
                    'id' => $row['ProductID'],
                    'name' => $row['ProductName'],
                    'price' => $row['ProductPrice'],
                    'image' => $row['ProImg1'],
                    'category' => $row['CategoryName'],
                    'stock' => $row['ProductStock'],
                    'material' => $row['ProductMaterial'],
                    'weight' => $row['ProductWeight'],
                    'color' => $row['ProductColor'],
                    'sold' => $row['ProductUnitsSold'],
                    'rating' => $row['ProductRating'],
                    'discount' => $row['ProductDiscount'],
                    'Gender' => $row['ProductTargetGender']
                );
                $productData[] = $productDetails;
            }
            return $productData;
        }
    }
}

// fetch worker details
function fetchWorkerDetails()
{
    global $conn;

    $sql = "SELECT WorkerName, WorkerPosition, WorkerImage, WorkerDescription
            FROM workers
            WHERE WorkerDescription IS NOT NULL AND WorkerDescription != 'No Description Available'
            LIMIT 18;";
    $result = $conn->query($sql);
    $workersData = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $workerInfo = array(
                'name' => $row['WorkerName'],
                'position' => $row['WorkerPosition'],
                'image' => $row['WorkerImage'],
                'description' => $row['WorkerDescription']
            );
            $workersData[] = $workerInfo;
        }
        return $workersData;
    }
}

// fetch shop reviews
function fetchShopReviews()
{
    global $conn;

    $sql = "SELECT 
                firstName,
                LastName,
                ShopReview,
                UserImage
            FROM Users
            WHERE ShopReview IS NOT NULL AND ShopReview != ''
            LIMIT 6";
    $Test_Result = $conn->query($sql);

    if ($Test_Result->num_rows > 0) {

        while ($row = $Test_Result->fetch_assoc()) {
            $shopReview = array(
                'UserName' => $row['firstName'] . ' ' . $row['LastName'],
                'review' => $row['ShopReview'],
                'UserImage' => $row['UserImage']
            );
            $shopReviewData[] = $shopReview;
        }

        return $shopReviewData;
    }
}

//fetch order details for user
function fetchOrderDetails()
{
    global $conn;

    $sql = "SELECT o.*, 
                    oi.ProductID, 
                    oi.Quantity, 
                    oi.UnitPrice, 
                    oi.TotalPrice,
                    p.ProductName, 
                    p.ProductTargetGender, 
                    c.CategoryName, 
                    p.ProImg1 
            FROM orders o 
            JOIN orderItems oi ON o.OrderID = oi.OrderID 
            JOIN Products p ON oi.ProductID = p.ProductID 
            JOIN Categories c ON p.CategoryID = c.CategoryID 
            WHERE o.UserID = ? 
            ORDER BY o.OrderCreatedAt DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $orderData = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $orderDetails = array(
                'orderID' => $row['OrderID'],
                'RCVName' => $row['RCVName'],
                'RCVEmail' => $row['RCVEmail'],
                'RCVPhone' => $row['RCVPhone'],
                'PayMethod' => $row['PayMethod'],
                'ShipAddress' => $row['ShipAddress'],
                'MsgSeller' => $row['MsgSeller'],
                'TotalPrice' => $row['TotalPrice'],
                'OrderStatus' => $row['OrderStatus'],
                'OrderCreatedAt' => $row['OrderCreatedAt'],
                'ProductID' => $row['ProductID'],
                'Quantity' => $row['Quantity'],
                'ProductName' => $row['ProductName'],
                'ProductTargetGender' => $row['ProductTargetGender'],
                'CategoryName' => $row['CategoryName'],
                'UnitPrice' => $row['UnitPrice'],
                'ProductImage' => $row['ProImg1']
            );
            $orderData[] = $orderDetails;
        }
    }

    return $orderData;
}

// add css files
function addCssFiles($toDir, $cssFiles)
{
    foreach ($cssFiles as $cssFile) {
        echo '<link rel="stylesheet" type="text/css" href="' . $toDir . 'assets/css/' . $cssFile . '" />' . PHP_EOL;
    }
}

// add js files
function addJsFiles($toDir, $jsFiles)
{
    $i = 1;
    foreach ($jsFiles as $jsFile) {
        if ($i == 1) {
            echo '    <script src="https://kit.fontawesome.com/34176f497f.js" crossorigin="anonymous"></script>' . PHP_EOL;
            $i++;
        }
        echo '<script src="' . $toDir . 'assets/js/' . $jsFile . '"></script>' . PHP_EOL;
    }
}
