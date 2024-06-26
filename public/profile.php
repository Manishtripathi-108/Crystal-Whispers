<?php
include "../php/functions.php";

$user = checkGetUserLoginStatus(true);

$sql = "SELECT 
    firstName,
    LastName,
    gender,
    Address1,
    Address2,
    City,
    State,
    Zip,
    UserImage
    FROM users WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $firstName = $row['firstName'];
    $lastName = $row['LastName'];
    $gender = $row['gender'];
    $userAddress = $row['Address1'];
    $userAddress_2 = $row['Address2'];
    $userCity = $row['City'];
    $userState = $row['State'];
    $userZip = $row['Zip'];
    $user_profilePhoto = $row['UserImage'];
} else {
    echo '
    <body>
        <h1 class="d-flex justify-content-center align-items-center">No user found, Login Again.</h1>
    </body>
    ';
    sleep(5);
    header('Location:../php/auth/login.php');
    exit();
}
$stmt->close();


// error msg
$profileMessage = isset($_SESSION["profileMessage"]) ? $_SESSION["profileMessage"] : "";
unset($_SESSION["profileMessage"]);

// active page
$activePage = isset($_POST['page']) ? $_POST['page'] : "Profile";

function isActive($page, $activePage)
{
    return ($page === $activePage) ? "active" : "";
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

    <title>Profile | Crystal Whispers</title>

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
    <?php getHeader("../", "", "Profile") ?>
    <!-- end header section -->

    <!-- user-profile -->
    <main id="user-profile-page" class="use-side-nav animate__fadeIn d-flex align-items-center">
        <div class="row m-0 gap-3 w-100">

            <!-- sideNav -->
            <section class="sideNav col-md-auto layout_padding">
                <div class="profile p-2">
                    <img src="../assets/images/users/<?php echo $user_profilePhoto; ?>" class="mx-auto d-block mb-2" alt="Profile Image">
                    <div class="user-name text-center">
                        <?php echo $firstName; ?>
                    </div>
                </div>

                <div class="sideNav-url margin-auto">
                    <div class="url margin-auto">
                        <form action="" method="post">
                            <input type="hidden" name="page" value="Profile">
                            <button class="<?php echo isActive('Profile', $activePage); ?>" title="View your profile">
                                Profile
                            </button>
                        </form>
                        <hr align="center" style="border-color: var(--theme-color);">
                    </div>
                    <div class="url margin-auto">
                        <form action="" method="post">
                            <input type="hidden" name="page" value="Orders">
                            <button class="<?php echo isActive('Orders', $activePage); ?>" title="View your orders" type="submit">
                                Orders
                            </button>
                        </form>
                        <hr align="center" style="border-color: var(--theme-color);">
                    </div>
                    <div class="url margin-auto">
                        <form action="" method="post">
                            <input type="hidden" name="page" value="Contact Us">
                            <button class="<?php echo isActive('Contact Us', $activePage); ?>" title="Get in touch with us" type="submit">
                                Contact Us
                            </button>
                        </form>
                        <hr align="center" style="border-color: var(--theme-color);">
                    </div>
                    <div class="url margin-auto">
                        <button class="<?php echo isActive('Logout', $activePage); ?>" title="Logout" data-toggle="modal" data-target="#logoutModal">
                            Logout
                        </button>
                    </div>
                </div>
            </section>
            <!-- End sideNav -->

            <!-- Profile -->
            <section class="profile-section col-md animate__fadeInUp layout_padding 
                <?php echo isActive('Profile', $activePage) == "active" ? "" : "d-none"; ?> me-3">
                <div class="heading_container heading_center">
                    <h2>
                        Profile
                    </h2>
                </div>

                <div class="container p-4 mt-3">
                    <form action="../php/process_profile.php" method="post" class="row g-3" enctype="multipart/form-data">
                        <!--Avatar-->
                        <div>
                            <div class="d-flex justify-content-center mb-4">
                                <img id="selectedAvatar" src="../assets/images/users/<?php echo $user_profilePhoto; ?>" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;" />
                            </div>

                            <div class="d-flex justify-content-center">
                                <div class="simple-btn">
                                    <input type="hidden" name="oldProfilePhoto" value="<?php echo $user_profilePhoto; ?>">
                                    <label class="form-label m-1" for="profilePhoto">Change Profile Photo</label>
                                    <input type="file" class="form-control d-none" id="profilePhoto" name="profilePhoto" accept="image/*" onchange="displaySelectedImage(event, 'selectedAvatar')" />
                                </div>
                            </div>
                        </div>
                        <?php
                        if ($profileMessage != "") {
                            echo '<div id="signup_alert" class="alert' . ($profileMessage == "Error updating Profile information. Please try again." ? " alert-danger" : " alert-success") . '  alert-dismissible fade show" role="alert">
                            ' . $profileMessage . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                        }
                        ?>
                        <div class="col-md-4 form-floating mb-3">
                            <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo $firstName; ?>" placeholder="First Name">
                            <label for="firstName" class="form-label">First Name:</label>
                        </div>

                        <div class="col-md-4 form-floating mb-3">
                            <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo $lastName; ?>" placeholder="Last Name">
                            <label for="lastName" class="form-label">Last Name:</label>
                        </div>
                        <div class="col-md-4 form-floating mb-3">
                            <select name="gender" class="form-select">
                                <option>Gender</option>
                                <option value="Male" <?php echo ($gender == "Male") ? "selected" : "" ?>>Male</option>
                                <option value="Female" <?php echo ($gender == "Female") ? "selected" : "" ?>>Female
                                </option>
                            </select>
                            <label for="show">Gender:</label>
                        </div>
                        <div class="col-12 form-floating mb-3">
                            <input type="text" class="form-control" id="userAddress" name="userAddress" value="<?php echo $userAddress; ?>" placeholder="Address:">
                            <label for="userAddress" class="form-label">Address:</label>
                        </div>
                        <div class="col-12 form-floating mb-3">
                            <input type="text" class="form-control" id="userAddress_2" name="userAddress_2" value="<?php echo $userAddress_2; ?>" placeholder="Apartment, studio, or floor">
                            <label for="userAddress_2" class="form-label">Apartment, studio, or floor:</label>
                        </div>
                        <div class="col-md-6 form-floating mb-3">
                            <input type="text" class="form-control" id="userCity" name="userCity" value="<?php echo $userCity; ?>" placeholder="City">
                            <label for="userCity" class="form-label">City:</label>
                        </div>
                        <div class="col-md-4 form-floating mb-3">
                            <input type="text" class="form-control" id="userState" name="userState" value="<?php echo $userState; ?>" placeholder="State">
                            <label for="userState" class="form-label">State:</label>
                        </div>
                        <div class="col-md-2 form-floating mb-3">
                            <input type="text" class="form-control" id="userZip" name="userZip" value="<?php echo $userZip; ?>" placeholder="Zip">
                            <label for="userZip" class="form-label">Zip:</label>
                        </div>
                        <div class="col-12 d-flex justify-content-center">
                            <button type="submit" style="width: 200px; margin: 10px auto 0px;" class="theme-btn">Update</button>
                        </div>
                    </form>
                </div>
            </section>
            <!-- End profile -->

            <!-- Orders -->
            <section class="orders-section col-md animate__fadeInUp layout_padding overflow-auto vh-100 me-3 px-3 px-md-auto
                <?php echo isActive('Orders', $activePage) == "active" ? "" : "d-none"; ?>">
                <div class="heading_container heading_center">
                    <h2>Your Orders</h2>
                </div>
                <div class="universal_table mt-3">
                    <table class="table cell-name-active">
                        <thead class="thead-primary">
                            <tr>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $orderDetails = fetchOrderDetails();
                            if ($orderDetails) :
                                $sNo = 0;
                                foreach ($orderDetails as $order) :
                                    $sNo++;
                            ?>
                                    <tr>
                                        <td cell-name="Order ID">
                                            <?= $sNo ?>
                                        </td>
                                        <td cell-name="Product">
                                            <div class="product-img" style="background-image: url(../assets/images/products/<?= $order['ProductImage'] ?>);"></div>
                                        </td>
                                        <td cell-name="Product Name">
                                            <div class="product-name">
                                                <span>
                                                    <?= $order['ProductName'] ?>
                                                </span>
                                                <span>
                                                    <?= $order['CategoryName'] . ', ' . $order['ProductTargetGender'] ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td cell-name="Price">₹<?= $order['UnitPrice'] ?>
                                        </td>
                                        <td cell-name="Quantity">
                                            <?= $order['Quantity'] ?>
                                        </td>
                                        <td cell-name="Total">₹<?= $order['TotalPrice'] ?>
                                        </td>
                                        <td cell-name="Status" class="order-status">
                                            <img src="../assets/icon/<?= $order['OrderStatus'] ?>.svg" alt="<?= $order['OrderStatus'] ?>">
                                            <div class="status-tooltip">
                                                <?= $order['OrderStatus'] ?>
                                            </div>
                                        </td>
                                    </tr>
                            <?php
                                endforeach;
                            else :
                                echo '<tr><td colspan="7" class="text-center">No Orders Found</td></tr>';
                            endif;
                            ?>

                        </tbody>
                    </table>
                </div>
            </section>
            <!-- End orders -->

            <!-- Contact Us -->
            <section class="contact_us col-md animate__fadeInUp layout_padding overflow-auto vh-100 me-3
            <?php echo isActive('Contact Us', $activePage) == "active" ? "" : "d-none"; ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-md-10 offset-md-1">
                            <div class="contact_inner">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="contact_form_inner">
                                            <div class="contact_field">
                                                <h3 style="color: var(--theme-color);">Contact Us</h3>
                                                <p>
                                                    Feel Free to contact us any time. We will get back to you as soon as
                                                    we can!
                                                </p>
                                                <form action="../php/contactUS_action.php" method="post">
                                                    <input type="hidden" name="user_id" value="<?= $user ?>">
                                                    <input name="name" type="text" class="form-control mb-3 form-group" placeholder="Name" required />
                                                    <input name="email" type="text" class="form-control mb-3 form-group" placeholder="Email" required />
                                                    <textarea name="message" class="form-control mb-3 form-group" placeholder="Message" required></textarea>
                                                    <button type="submit" class="theme-btn">Send</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="contact_info_sec">
                                    <h4>Contact Info</h4>
                                    <div class="d-flex info_single align-items-center">
                                        <img src="../assets/icon/phone.png">
                                        <span style="margin-left: 5px;">+91 62806-00090</span>
                                    </div>
                                    <div class="d-flex info_single align-items-center">
                                        <img src="../assets/icon/email.png">
                                        <span style="margin-left: 5px;">crystals@gmail.com</span>
                                    </div>
                                    <div class="d-flex info_single align-items-center">
                                        <img src="../assets/icon/address.png">
                                        <span style="margin-left: 5px;">Sadar Bazar, Barnala, <br>
                                            Punjab-148101.
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="map_sec">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <div class="map_inner">
                                    <h4 style="color: var(--theme-color);">Find Us on Google Map</h4>
                                    <p>Visit our store in Sadar Bazar, Barnala, Punjab-148101. Use the interactive map
                                        below to find the best route to our store.</p>
                                    <div class="map_bind">
                                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3442.2026810456155!2d75.54581047554548!3d30.37360207475956!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3910f2179e8e2fc5%3A0xc9859b4c430f6163!2sSadar%20Bazar%20Rd%2C%20Barnala%2C%20Punjab%20148101!5e0!3m2!1sen!2sin!4v1704290123428!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                                        </iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Contact Us -->

        </div>
    </main>
    <!--End user-profile -->

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Logout Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to logout?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="../php/auth/logout.php" method="post">
                        <input type="hidden" name="logout" value="user_logOut">
                        <button class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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