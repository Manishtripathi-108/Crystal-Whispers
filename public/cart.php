<?php
include "../php/functions.php";

checkGetUserLoginStatus(false, true);

$cat_arr = array();
$summary_arr = array();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dltProduct_ID'])) {
    $dltID = $_POST['dltProduct_ID'];
    dltProduct($dltID, $conn);
}
function dltProduct($dltID, $conn)
{
    $userID = $_SESSION['user_id'];
    if ($dltID != "") {
        $sql = "DELETE FROM cart WHERE userID = '$userID' AND productID = '$dltID'";
        $dltResult = $conn->query($sql);
    }
}
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

    addCssFiles("../", $cssFiles);
    ?>
    <!-- End Styles -->

</head>

<body>
    <!-- header section starts -->
    <?php getHeader("../", "", "Cart") ?>
    <!-- end header section -->

    <!-- Cart -->
    <main id="Cart-page" class="animate__fadeIn">

        <section class="cart-section col-md layout_padding">
            <div class="mx-auto">
                <div class="heading_container heading_center">
                    <h2>
                        Cart
                    </h2>
                </div>
                <div class="row m-0 gap-4 mt-5 justify-content-center">
                    <div class="universal_table col-12 col-lg-8">
                        <table class="table">
                            <thead class="thead-primary">
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>total</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                if (isset($_SESSION["user_id"])) {
                                    $sql = "SELECT * FROM cart WHERE UserID =" . $_SESSION["user_id"];
                                    $cartTable = $conn->query($sql);

                                    if ($cartTable->num_rows > 0) {
                                        while ($row = mysqli_fetch_array($cartTable)) {
                                            $productID = $row["ProductID"];
                                            $userID = $row["UserID"];
                                            $quantity = $row["Quantity"];
                                            $sql = "SELECT 
                                                    p.ProductName, 
                                                    p.ProductPrice,
                                                    p.ProductDiscount,
                                                    c.CategoryName,
                                                    p.ProImg1
                                                FROM products p
                                                JOIN categories c ON p.CategoryID = c.CategoryID
                                                WHERE p.ProductID =" . $productID;

                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                while ($row2 = mysqli_fetch_array($result)) {
                                                    $name = $row2["ProductName"];
                                                    $image = $row2["ProImg1"];
                                                    $price = $row2['ProductPrice'] * ((100 - $row2['ProductDiscount']) / 100);
                                                    $category = $row2["CategoryName"];
                                                    $cat_arr[] = $category;

                                                    echo '
                                                        <tr>
                                                        <td>
                                                            <div cell-name="product" class="product-img" style="background-size: contain; background-image: url(../assets/images/products/' . $image . ');">
                                                            </div>
                                                        </td>
                                                        <td cell-name="product name">
                                                            <div class="product-name">
                                                                <span>' . $name . '</span>
                                                                <span>' . $category . '</span>
                                                            </div>
                                                        </td>
                                                        <td cell-name="price">₹' . number_format($price, 2) . '</td>
                                                        <td cell-name="quantity" class="quantity">
                                                            <div class="input-group">
                                                                ' . $quantity . '
                                                            </div>
                                                        </td>
                                                        <td cell-name="total price">₹' . number_format(($quantity * $price), 2) . '</td>';
                                                    $summary_arr[] = $quantity * $price;
                                                    echo '
                                                        <td>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="dltProduct_ID" value="' . $productID . '">
                                                            <button title="Delete item from cart" type="submit" class="delete-btn">
                                                                <img width="20" src="../assets/icon/delete.svg">
                                                            </button>
                                                            </form>
                                                        </td>
                                                    </tr>';
                                                }
                                            }
                                            $result->close();
                                        }
                                    } else {
                                        echo '
                                    <tr>
                                        <td colspan="6" class="text-center" >
                                            Your Cart Is Empty
                                        </td>
                                    </tr>
                                    ';
                                    }
                                    $cartTable->close();
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="price-table-parent col-12 col-lg-3">
                        <div class="d-flex justify-content-center">
                            <div class="ms-lg-4 mt-4 mt-lg-0" style="width: 320px;">
                                <h2 class="mb-3">Summary</h2>
                                <?php
                                $totalPrice = array_sum($summary_arr);
                                $discount = $totalPrice * 0.1;
                                $shippingCost = $totalPrice * 0.01;
                                $finalPrice = $totalPrice - $discount + $shippingCost;
                                ?>
                                <div class="d-flex justify-content-between">
                                    <p class="mb-2">Total price:</p>
                                    <p class="mb-2">
                                        <?php echo "₹" . number_format($totalPrice, 2); ?>
                                    </p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p class="mb-2">Discount:</p>
                                    <p class="mb-2 text-danger">
                                        <?php echo "- ₹" . number_format($discount, 2); ?>
                                    </p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p class="mb-2">Shipping cost:</p>
                                    <p class="mb-2">
                                        <?php echo "+ ₹" . number_format($shippingCost, 2); ?>
                                    </p>
                                </div>

                                <hr />
                                <div class="d-flex justify-content-between">
                                    <p class="mb-2">Total price:</p>
                                    <p class="mb-2 fw-bold">
                                        <?php echo "₹" . number_format($finalPrice, 2); ?>
                                    </p>
                                </div>
                                <div class="mt-3">
                                    <a href="checkout.php" class="text-center theme-btn m-0 w-100" title="Purchase" type="submit">
                                        Purchase
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Similar Products -->
        <section id="similar-products" class="layout_padding">
            <div class="mx-3">
                <div class="heading_container heading_center pb-5">
                    <h2>
                        Similar Products
                    </h2>
                </div>
                <div class="row m-0 justify-content-center">
                    <?php
                    if (!empty($cat_arr)) {
                        foreach ($cat_arr as $pCategory) {
                            $sql = "SELECT
                                    p.ProductID,
                                    p.ProductName,
                                    p.ProductTargetGender,
                                    p.ProductPrice,
                                    p.ProductDiscount,
                                    p.ProImg1,
                                    p.ProductRating,
                                    c.CategoryName AS category
                                    FROM
                                    products p
                                    JOIN
                                    categories c ON p.CategoryID = c.CategoryID
                                    WHERE 
                                    c.CategoryName='" . $pCategory . "'
                                    ORDER BY
                                    p.ProductUnitsSold DESC
                                    LIMIT 2";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $rating = round($row['ProductRating']);
                                    echo '
                                        <div class="col-sm-6 col-md-4 col-lg-3 p-3">
                                        <div class="product-card">
                                            <div class="p-badge">';
                                    if ($rating > 0) {
                                        for ($i = 1; $i <= $rating; $i++) {
                                            echo '<i class="fa fa-star"></i>';
                                        }
                                    } else {
                                        echo '<i class="fa fa-star-o"></i>';
                                    }

                                    echo '
                                        </div>
                                        <div class="product-tumb d-flex justify-content-center align-items-center">
                                            <img 
                                            src="../assets/images/products/' . $row['ProImg1'] . '" 
                                            alt="Product Image">
                                        </div>
                                        <div class="product-details">
                                            <span class="product-category">' . $row['ProductTargetGender'] . ',' . $row['category'] . '</span>
                                            <h5><a href="Product-details.php?pro=' . $row['ProductID'] . '">' . $row['ProductName'] . '</a></h5>
                                            <div class="product-bottom-details d-flex justify-content-between align-items-center">
                                            <div class="product-price">
                                                <small>₹' . number_format($row['ProductPrice'], 2) . '</small>
                                                ₹' . number_format(($row['ProductPrice'] * ((100 - $row['ProductDiscount']) / 100)), 2) .
                                        '</div>
                                            <div class="product-links">
                                                <form method="post" action="../php/products/add_to_cart.php">
                                                <input type="hidden" name="product_id" value="' . $row['ProductID'] . '">
                                                <button type="submit"><i class="fa fa-shopping-cart"></i></button>
                                                </form>
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>';
                                }
                            }
                        }
                    } else {
                        echo '<p class="d-flex justify-content-center">No similar products found.</p>';
                    }
                    ?>
                </div>
            </div>
        </section>
        <!-- End Similar Products -->

    </main>
    <!--End Cart -->

    <!-- Footer -->
    <?php getFooter(); ?>
    <!-- End footer -->

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