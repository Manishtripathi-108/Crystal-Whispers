<?php
include "../php/functions.php";
$userID = checkGetUserLoginStatus(true, true);

$summary_arr = array();

$checkCart = "SELECT * FROM cart WHERE userID = " . $userID;
$cartResult = $conn->query($checkCart);

if ($cartResult->num_rows == 0) {
    header('Location: cart.php');
    exit();
}

$_SESSION["confirm_order"] = True;
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

    <!-- header section -->
    <?php getHeader("../", "") ?>
    <!-- end header section -->

    <main class="animate__fadeIn">
        <section class="py-5">
            <div class="heading_container heading_center">
                <h2>
                    Checkout
                </h2>
            </div>
            <div class="m-4">
                <div class="row">
                    <div class="col-lg-4 d-flex justify-content-center">
                        <div class="ms-lg-4 mt-4 mt-lg-0" style="max-width: 320px;">
                            <h2 class="mb-3">Items in cart</h2>

                            <?php
                            $sql = "SELECT * FROM cart WHERE userID =" . $userID;
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                $quantity = $row['quantity'];
                                $product_id = $row['ProductID'];
                                $sql = "SELECT
                                        p.product_name, 
                                        p.price,
                                        p.discount,
                                        i.img_1
                                    FROM products p
                                    JOIN product_img i ON p.product_id = i.img_to_pro
                                    WHERE p.product_id =" . $product_id;

                                $result2 = mysqli_query($conn, $sql);
                                $row2 = mysqli_fetch_assoc($result2);
                                $priceAfterDiscount = $row2['price'] * ((100 - $row2['discount']) / 100);
                                echo '
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="me-3 position-relative">
                                            <span class="position-absolute top-0 start-100 translate-middle badge border border-light theme-bg-color">
                                                ' . $quantity . '
                                            </span>
                                            <img src="images/products/' . $row2['img_1'] . '" style="height: 96px; width: 96x;" class="img-sm rounded border" />
                                        </div>
                                        <div>
                                            <a href="#" class="nav-link">
                                                ' . $row2['product_name'] . '
                                            </a>
                                            <div class="price text-muted">Total: ₹' . number_format($quantity * $priceAfterDiscount, 2) . '</div>
                                ' . $summary_arr[] = $quantity * $priceAfterDiscount . '
                                            </div>
                                    </div>';
                            }
                            ?>

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
                                    ₹
                                    <?php echo number_format($totalPrice, 2); ?>
                                </p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="mb-2">Discount:</p>
                                <p class="mb-2 text-danger">
                                    - ₹
                                    <?php echo number_format($discount, 2); ?>
                                </p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="mb-2">Shipping cost:</p>
                                <p class="mb-2">
                                    + ₹
                                    <?php echo number_format($shippingCost, 2); ?>
                                </p>
                            </div>
                            <hr />
                            <div class="d-flex justify-content-between">
                                <p class="mb-2">Total price:</p>
                                <p class="mb-2 fw-bold">
                                    ₹
                                    <?php echo number_format($finalPrice, 2); ?>
                                </p>
                            </div>
                            <hr />
                        </div>
                    </div>

                    <div class="col-lg-8 mb-4">
                        <div class="card shadow-0 border">
                            <div class="p-4">
                                <form action="confirm-order.php" method="post">
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <div class="form-floating">
                                                <input required name="f_name" type="text" class="form-control"
                                                    id="first_name" placeholder="First Name">
                                                <label for="first_name">First Name</label>
                                            </div>
                                        </div>

                                        <div class="col-6 mb-3">
                                            <div class="form-floating">
                                                <input required type="text" name="l_name" id="last_name"
                                                    class="form-control" placeholder="Last Name">
                                                <label for="last_name">Last Name</label>
                                            </div>
                                        </div>

                                        <div class="col-6 mb-3">
                                            <div class="form-floating">
                                                <input required name="p_number" type="tel" class="form-control"
                                                    id="phone_number" placeholder="Phone Number" maxlength="10"
                                                    pattern="\d{10}">
                                                <label for="phone_number">Phone Number</label>
                                            </div>
                                        </div>

                                        <div class="col-6 mb-3">
                                            <div class="form-floating">
                                                <input required name="email" type="email" class="form-control"
                                                    id="email" placeholder="Email address">
                                                <label for="email">Email address</label>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4" />

                                    <h5 class="card-title mb-3">Shipping info</h5>

                                    <div class="row">
                                        <div class="col-sm-7 mb-3">
                                            <div class="form-floating">
                                                <input required name="address" type="text" class="form-control"
                                                    id="address"
                                                    placeholder="Flat, House no., Building, Company, Apartment">
                                                <label for="address">Flat, House no., Building, Company</label>
                                            </div>
                                        </div>

                                        <div class="col-sm-5 mb-3">
                                            <div class="form-floating">
                                                <input name="address2" type="text" class="form-control" id="address2"
                                                    placeholder="Area, Street, Sector, Village">
                                                <label for="address2">Area, Street, Sector, Village</label>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 mb-3">
                                            <div class="form-floating">
                                                <div class="form-floating">
                                                    <input required name="city" type="text" class="form-control"
                                                        id="city" placeholder="City">
                                                    <label for="city">City</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-3 col-6 mb-3">
                                            <div class="form-floating">
                                                <input required name="zip" type="text" id="zip" class="form-control"
                                                    placeholder="Zip Code" />
                                                <label for="zip">Zip Code</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-6 mb-3">
                                            <div class="form-floating">
                                                <div class="form-floating">
                                                    <select required name="state" class="form-select" id="state"
                                                        placeholder="State">
                                                        <option value="">Select State</option>
                                                        <option value="Andaman and Nicobar Islands">Andaman and Nicobar
                                                            Islands
                                                        </option>
                                                        <option value="Andhra Pradesh">Andhra Pradesh</option>
                                                        <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                                        <option value="Assam">Assam</option>
                                                        <option value="Bihar">Bihar</option>
                                                        <option value="Chandigarh">Chandigarh</option>
                                                        <option value="Chhattisgarh">Chhattisgarh</option>
                                                        <option value="Dadra and Nagar Haveli and Daman and Diu">Dadra
                                                            and Nagar
                                                            Haveli and Daman and Diu</option>
                                                        <option value="Delhi">Delhi</option>
                                                        <option value="Goa">Goa</option>
                                                        <option value="Gujarat">Gujarat</option>
                                                        <option value="Haryana">Haryana</option>
                                                        <option value="Himachal Pradesh">Himachal Pradesh</option>
                                                        <option value="Jharkhand">Jharkhand</option>
                                                        <option value="Karnataka">Karnataka</option>
                                                        <option value="Kerala">Kerala</option>
                                                        <option value="Lakshadweep">Lakshadweep</option>
                                                        <option value="Madhya Pradesh">Madhya Pradesh</option>
                                                        <option value="Maharashtra">Maharashtra</option>
                                                        <option value="Manipur">Manipur</option>
                                                        <option value="Meghalaya">Meghalaya</option>
                                                        <option value="Mizoram">Mizoram</option>
                                                        <option value="Nagaland">Nagaland</option>
                                                        <option value="Odisha">Odisha</option>
                                                        <option value="Puducherry">Puducherry</option>
                                                        <option value="Punjab">Punjab</option>
                                                        <option value="Rajasthan">Rajasthan</option>
                                                        <option value="Sikkim">Sikkim</option>
                                                        <option value="Tamil Nadu">Tamil Nadu</option>
                                                        <option value="Telangana">Telangana</option>
                                                        <option value="Tripura">Tripura</option>
                                                        <option value="Uttar Pradesh">Uttar Pradesh</option>
                                                        <option value="Uttarakhand">Uttarakhand</option>
                                                        <option value="West Bengal">West Bengal</option>
                                                    </select>
                                                    <label for="state">State</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="form-check mb-3">
                                        <input required class="form-check-input" type="checkbox" value="save_address"
                                            id="flexCheckDefault1" />
                                        <label class="form-check-label" for="flexCheckDefault1">Save this
                                            address</label>
                                    </div> -->

                                        <div class="mb-3">
                                            <div class="form-floating">
                                                <textarea name="msg_seller" class="form-control" id="message" rows="2"
                                                    placeholder="Message to seller" style="height: 150px;"></textarea>
                                                <label for="message">Message to seller</label>
                                            </div>
                                        </div>

                                        <hr class="my-4" />

                                        <h5 class="card-title mb-3">Payment Method</h5>
                                        <div class="row mb-3">
                                            <div class="col-lg-4 mb-3">
                                                <div class="form-check h-100 border rounded-3">
                                                    <div class="p-3">
                                                        <input required class="form-check-input" type="radio"
                                                            name="pay_method" value="Razorpay" id="razorpay" checked />
                                                        <label class="form-check-label" for="razorpay">
                                                            RazorPay <br />
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 mb-3">
                                                <div class="form-check h-100 border rounded-3">
                                                    <div class="p-3">
                                                        <input required class="form-check-input" type="radio"
                                                            name="pay_method" value="COD" id="cod" />
                                                        <label class="form-check-label" for="cod">
                                                            Cash on Delivery <br />
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="float-end">
                                            <a href="cart.php" class="op-btn">Cancel</a>
                                            <input type="hidden" name="price" value="<?php echo $finalPrice; ?>">
                                            <button type="submit" class="simple-btn">Place Order</button>
                                        </div>
                                </form>
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