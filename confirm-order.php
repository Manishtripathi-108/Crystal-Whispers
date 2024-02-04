<?php
include "php/functions.php";
$userID = checkGetUserLoginStatus(true, true);

$con_order = isset($_SESSION["confirm_order"]) ? "" : "";
unset($_SESSION["confirm_order"]);

$summary_arr = array(0);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['f_name']) && isset($_POST['l_name']) && isset($_POST['p_number']) && isset($_POST['email']) && isset($_POST['address']) && isset($_POST['address']) && isset($_POST['city']) && isset($_POST['zip']) && isset($_POST['msg_seller']) && isset($_POST['pay_method']) && isset($_POST['price'])) {
    $f_name = $_POST["f_name"];
    $l_name = $_POST["l_name"];
    $p_number = $_POST["p_number"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $address2 = $_POST["address2"];
    $city = $_POST["city"];
    $zip = $_POST["zip"];
    $state = $_POST["state"];
    $msg_seller = $_POST["msg_seller"];
    $pay_method = $_POST["pay_method"];
    $price = $_POST["price"];

    $name = $f_name . ' ' . $l_name;
    $shipping_address = $address . ', ' . $address2 . ', ' . $zip . ', ' . $city;

    $inOrders = insertOrderDetails($conn, $userID, $name, $email, $p_number, $pay_method, $shipping_address, $msg_seller, $price);

    if ($inOrders) {
        $order_id = $conn->insert_id;

        // Update units_sold and stock in products table
        updateProductDetails($conn, $userID);

        // Move items from cart to order_items
        moveItemsFromCartToOrder($conn, $userID, $order_id);
    } else {
        echo "Error: Unable to process the order.";
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
    <?php getHeader() ?>
    <!-- end header section -->

    <main id="about-page" class="animate__fadeIn">
        <section class="py-5">
            <div class="heading_container heading_center">
                <h2>
                    Order Placed
                </h2>
            </div>
            <div class="m-4">
                <div class="p-2 m-2 p-md-4 m-md-4">
                    <div class="d-flex justify-content-between align-items-center py-3">
                        <h2 class="h5 mb-0"><a href="#" class="text-muted"></a> Order #
                            <?php echo $order_id ?>
                        </h2>
                    </div>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <h2>Payment Method</h2>
                                            <p>
                                                <?php echo $pay_method ?>
                                                <br>
                                                Total: ₹
                                                <?php echo $price ?>
                                                <span class="badge bg-success rounded-pill">
                                                    PAID
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-lg-6">
                                            <h2>Billing address</h2>
                                            <address>
                                                <strong style="color:var(--theme-color);">
                                                    <?php echo $f_name . " " . $l_name ?>
                                                </strong>
                                                <br>
                                                <?php echo $address . " " . $address2 ?>
                                                <br>
                                                <?php echo $city ?>
                                                <br>
                                                <?php echo $zip ?>
                                                <br>
                                                <?php echo $state ?>
                                                <br>+91
                                                <?php echo $p_number ?>
                                            </address>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card mb-4">
                                <!-- Shipping information -->
                                <div class="card-body">
                                    <h2>Shipping Address</h2>
                                    <address>
                                        <strong style="color:var(--theme-color);">
                                            <?php echo $f_name . " " . $l_name ?>
                                        </strong>
                                        <br>
                                        <?php echo $address . " " . $address2 ?>
                                        <br>
                                        <?php echo $city ?>
                                        <br>
                                        <?php echo $zip ?>
                                        <br>
                                        <?php echo $state ?>
                                        <br>+91
                                        <?php echo $p_number ?>
                                    </address>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 card mb-4">
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tbody>
                                        <?php
                                        $sql_order_items = "SELECT productID, quantity FROM order_items WHERE orderID = $order_id";
                                        $result_order_items = $conn->query($sql_order_items);

                                        if ($result_order_items->num_rows > 0) {
                                            while ($row_order_items = $result_order_items->fetch_assoc()) {
                                                $productID = $row_order_items['productID'];
                                                $quantity = $row_order_items['quantity'];

                                                $sql_product_details = "SELECT 
                                        p.product_name,
                                        p.price,
                                        p.discount,
                                        i.img_1
                                        FROM products p
                                        JOIN product_img i ON p.product_id = i.img_to_pro
                                        WHERE p.product_id = $productID";
                                                $result_product_details = $conn->query($sql_product_details);

                                                if ($result_product_details->num_rows > 0) {
                                                    $row_product_details = $result_product_details->fetch_assoc();
                                                    $productName = $row_product_details['product_name'];
                                                    $img1 = $row_product_details['img_1'];
                                                    $price = $row_product_details['price'] * ((100 - $row_product_details['discount']) / 100);

                                                    echo '
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex mb-2">
                                                                        <div class="flex-shrink-0">
                                                                            <img src="images/products/' . $img1 . '" alt="" width="35" class="img-fluid">
                                                                        </div>
                                                                        <div class="flex-lg-grow-1 ms-3">
                                                                            <p>
                                                                                <a href="#" class="text-reset">
                                                                                    ' . $productName . '
                                                                                </a>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>' . $quantity . '</td>
                                                                <td class="text-end">₹' . number_format($quantity * $price, 2) . '</td>';
                                                    $summary_arr[] = $quantity * $price;
                                                    echo '</tr>';
                                                }
                                            }
                                        }
                                        ?>
                                    </tbody>

                                    <?php
                                    $totalPrice = array_sum($summary_arr);
                                    $discount = $totalPrice * 0.1;
                                    $shippingCost = $totalPrice * 0.01;
                                    $finalPrice = $totalPrice - $discount + $shippingCost;
                                    ?>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2">Subtotal</td>
                                            <td class="text-end">₹
                                                <?php echo number_format($totalPrice, 2); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2">Discount</td>
                                            <td class="text-danger text-end">
                                                - ₹
                                                <?php echo number_format($discount, 2); ?>
                                            </td>
                                        </tr>
                                        <tr style="border-bottom: 1px solid black;">
                                            <td colspan="2">Shipping</td>
                                            <td class="text-end">
                                                + ₹
                                                <?php echo number_format($shippingCost, 2); ?>
                                            </td>
                                        </tr>
                                        <tr class="fw-bold theme-color">
                                            <td colspan="2">TOTAL</td>
                                            <td class="text-end">
                                                ₹
                                                <?php echo number_format($finalPrice, 2); ?>
                                            </td>
                                        </tr>
                                        <tr class="fw-bold theme-color">
                                            <td colspan="3">
                                                <div class="float-end mt-5">
                                                    <a href="index.php" class="simple-btn">Return Home</a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <?php getFooter(); ?>
    <!-- End footer -->

    <!-- Get Scripts -->
    <?php getScripts(); ?>
    <!-- End Scripts -->
</body>

</html>