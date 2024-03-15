<?php
include "php/functions.php";

if (!isset($_SESSION['AdminID'])) {
    header("Location: admin-login.php");
    exit();
} else {
    $admin = $_SESSION['AdminID'];
}

$productMessage = isset($_SESSION['productMessage']) ? $_SESSION['productMessage'] : "";
unset($_SESSION['productMessage']);

$workerMessage = isset($_SESSION['workerMessage']) ? $_SESSION['workerMessage'] : "";
unset($_SESSION['workerMessage']);

// active page
$activePage = isset($_SESSION['section']) ? $_SESSION['section'] : "Products";
unset($_SESSION['section']);

function isActive($page, $activePage)
{
    return ($page === $activePage) ? "active" : "";
}

//function to fetch admin image and name
function fetchAdminDetails()
{
    global $conn;
    $admin = $_SESSION['AdminID'];
    $sql = "SELECT 
            AdminImage, 
            AdminName
            FROM admins WHERE AdminID = '$admin'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row;
    }
    $result->close();
    return null;
}

// Function to Fetch Orders
function fetchOrders()
{
    global $conn;
    $orders = [];

    $sql = "SELECT * FROM orders ORDER BY order_id DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $order = [
                'order_id' => $row['order_id'],
                'status' => $row['status'],
                'receiver_name' => $row['receiver_name'],
                'phone' => $row['phone'],
                'email' => $row['email'],
                'total_amount' => $row['total_amount'],
                'payment_method' => $row['payment_method'],
                'payment_status' => $row['payment_status'],
                'shipping_address' => $row['shipping_address'],
                'order_date' => $row['order_date'],
                'msg_seller' => $row['msg_seller'],
                'items' => fetchOrderItems($row['order_id']),
            ];

            $orders[] = $order;
        }
    }

    $result->close();

    return $orders;
}

// Function to Fetch Order Items
function fetchOrderItems($orderId)
{
    global $conn;
    $items = [];

    $itemsQuery = "SELECT oi.quantity as items_count, p.product_name as item_name, p.product_id
                                FROM order_items oi
                                JOIN products p ON oi.productID = p.product_id
                                WHERE oi.orderID = $orderId";

    $itemsResult = $conn->query($itemsQuery);

    if ($itemsResult->num_rows > 0) {
        while ($itemRow = mysqli_fetch_assoc($itemsResult)) {
            $items[] = [
                'items_count' => $itemRow['items_count'],
                'item_name' => $itemRow['item_name'],
                'product_id' => $itemRow['product_id'],
            ];
        }
    }

    return $items;
}

function fetchContacts()
{
    global $conn;
    $contacts = [];

    $sql = "SELECT * FROM contactUs ORDER BY ID DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $contact = [
                'ID' => $row['ID'],
                'name' => $row['name'],
                'email' => $row['email'],
                'Message' => $row['message'],
            ];

            $contacts[] = $contact;
        }
    }

    $result->close();

    return $contacts;
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
    <?php getHeader(null, true); ?>
    <!-- end header section -->

    <!-- Admin -->
    <main id="admin-page" class="use-side-nav">
        <div class="row m-0 gap-3">

            <section class="sideNav col-md-auto layout_padding">
                <div class="profile p-2">
                    <?php $adminDetails = fetchAdminDetails(); ?>
                    <img src="images/admin/<?= $adminDetails['AdminImage'] ?>" class="mx-auto d-block mb-2"
                        alt="Profile Image">
                    <div class="user-name text-center">
                        <?= $adminDetails['AdminName'] ?>
                    </div>
                </div>

                <div class="sideNav-url margin-auto">
                    <div class="url margin-auto">
                        <form action="php/change_section.php" method="post">
                            <input type="hidden" name="section" value="Products">
                            <button class="<?php echo isActive('Products', $activePage); ?>" title="View Products">
                                Products
                            </button>
                        </form>
                        <hr align="center" style="border-color: var(--theme-color);">
                    </div>
                    <div class="url margin-auto">
                        <form action="php/change_section.php" method="post">
                            <input type="hidden" name="section" value="Orders">
                            <button class="<?php echo isActive('Orders', $activePage); ?>" title="View Orders">
                                Orders
                            </button>
                        </form>
                        <hr align="center" style="border-color: var(--theme-color);">
                    </div>
                    <div class="url margin-auto">
                        <form action="php/change_section.php" method="post">
                            <input type="hidden" name="section" value="Workers">
                            <button class="<?php echo isActive('Workers', $activePage); ?>" title="View Workers">
                                Workers
                            </button>
                        </form>
                        <hr align="center" style="border-color: var(--theme-color);">
                    </div>
                    <div class="url margin-auto">
                        <form action="php/change_section.php" method="post">
                            <input type="hidden" name="section" value="Enquiries">
                            <button class="<?php echo isActive('Enquiries', $activePage); ?>" title="View Enquiries">
                                Enquiries
                            </button>
                        </form>
                        <hr align="center" style="border-color: var(--theme-color);">
                    </div>
                    <div class="url margin-auto">
                        <button data-toggle="modal" data-target="#logoutModal">
                            Logout
                        </button>
                    </div>
                </div>
            </section>

            <section class="Products-section col-md animate__fadeIn layout_padding me-3 table-responsive px-3 px-md-auto
                <?php echo isActive('Products', $activePage) == "active" ? "" : "d-none"; ?>">
                <div class="accordion" id="productsAccordion">

                    <!-- add new product -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseProductOne" aria-expanded="true"
                                aria-controls="collapseProductOne">
                                Add New Product
                            </button>
                        </h2>
                        <div id="collapseProductOne" class="accordion-collapse collapse show"
                            data-bs-parent="#productsAccordion">
                            <div class="accordion-body">
                                <div id="add_product" class="p-4 mt-3 upping_shadow">
                                    <form action="php/process_product.php" method="post" class="row g-3"
                                        enctype="multipart/form-data">

                                        <div class="heading_container text-start">
                                            <h4>
                                                Product Images :
                                            </h4>
                                        </div>
                                        <div id="add_product_img" class="row mb-2 justify-content-center">
                                            <div class="imgBox col-sm-5 col-md-4 p-4">
                                                <div class="d-flex justify-content-center mb-4">
                                                    <img id="selectedImage1" src="images/add-image.png" />
                                                </div>
                                                <div class=" d-flex justify-content-center">
                                                    <div class="simple-btn">
                                                        <label class="form-label m-1" for="productImage1">Add Image 1
                                                            (Required)</label>
                                                        <input type="file" class="form-control d-none"
                                                            id="productImage1" name="productImage1" accept="image/*"
                                                            onchange="displaySelectedImage(event, 'selectedImage1')"
                                                            required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="imgBox col-sm-5 mt-3 mt-md-auto col-md-4 p-4">
                                                <div class="d-flex justify-content-center mb-4">
                                                    <img id="selectedImage2" src="images/add-image.png" />
                                                </div>
                                                <div class=" d-flex justify-content-center">
                                                    <div class="simple-btn">
                                                        <label class="form-label m-1" for="productImage2">Add Image
                                                            2</label>
                                                        <input type="file" class="form-control d-none"
                                                            id="productImage2" name="productImage2" accept="image/*"
                                                            onchange="displaySelectedImage(event, 'selectedImage2')" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="imgBox col-sm-5 mt-3 mt-md-auto col-md-4 p-4">
                                                <div class="d-flex justify-content-center mb-4">
                                                    <img id="selectedImage3" src="images/add-image.png" />
                                                </div>
                                                <div class=" d-flex justify-content-center">
                                                    <div class="simple-btn">
                                                        <label class="form-label m-1" for="productImage3">Add Image
                                                            3</label>
                                                        <input type="file" class="form-control d-none"
                                                            id="productImage3" name="productImage3" accept="image/*"
                                                            onchange="displaySelectedImage(event, 'selectedImage3')" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                        if ($productMessage != "") {
                                            echo '<div id="signup_alert" class="alert' . ($productMessage == "Error updating Profile information. Please try again." ? " alert-danger" : " alert-success") . '  alert-dismissible fade show" role="alert">
                                            ' . $productMessage . '
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>';
                                        }
                                        ?>
                                        <div class="heading_container text-start">
                                            <h4>
                                                Product Details :
                                            </h4>
                                        </div>
                                        <div class="col-md-4 form-floating mb-3">
                                            <input type="text" class="form-control" id="product_name"
                                                name="product_name" placeholder="Product Name" required>
                                            <label for="product_name" class="form-label">Product Name</label>
                                        </div>

                                        <div class="col-md-4 form-floating mb-3">
                                            <select name="category" class="form-select" required>
                                                <option value="">Select Category</option>
                                                <?php
                                                $sql = "SELECT * FROM categories";
                                                $cat_result = $conn->query($sql);

                                                if ($cat_result->num_rows > 0) {
                                                    while ($row = $cat_result->fetch_assoc()) {
                                                        echo '<option value="' . $row['ID'] . '">' . $row['category_name'] . '</option>';
                                                    }
                                                }

                                                $cat_result->close();
                                                ?>
                                            </select>
                                            <label for="show">Category</label>
                                        </div>

                                        <div class="col-md-4 form-floating mb-3">
                                            <input type="number" class="form-control" id="price" name="price"
                                                placeholder="Price" required>
                                            <label for="price" class="form-label">Price</label>
                                        </div>
                                        <div class="col-md-4 form-floating mb-3">
                                            <input type="tel" class="form-control" id="discount" name="discount"
                                                maxlength="2" pattern="\d{1,2}" placeholder="Discount" required>
                                            <label for="discount" class="form-label">Discount (1-99)</label>
                                        </div>
                                        <div class="col-md-4 form-floating mb-3">
                                            <input type="number" step="any" class="form-control" id="weight"
                                                name="weight" placeholder="Weight (in g)" required>
                                            <label for="weight" class="form-label">Weight (in g)</label>
                                        </div>
                                        <div class="col-md-4 form-floating mb-3">
                                            <input type="text" class="form-control" id="color" name="color"
                                                placeholder="Color" required pattern="^[a-zA-Z]+$">
                                            <label for="color" class="form-label">Color</label>
                                        </div>
                                        <div class="col-md-4 form-floating mb-3">
                                            <select name="gender" class="form-select" required>
                                                <option value="">Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                            <label for="show">Gender:</label>
                                        </div>
                                        <div class="col-md-4 form-floating mb-3">
                                            <select name="material" class="form-select" required>
                                                <option value="">Select Material</option>
                                                <option value="Gold">Gold</option>
                                                <option value="Silver">Silver</option>
                                                <option value="Platinum">Platinum</option>
                                            </select>
                                            <label for="show">Material</label>
                                        </div>
                                        <div class="col-md-4 form-floating mb-3">
                                            <select name="occasion" class="form-select" required>
                                                <option value="">Occasion</option>
                                                <?php
                                                $sql = "SELECT * FROM occasions";
                                                $occ_result = $conn->query($sql);
                                                if ($occ_result->num_rows > 0) {
                                                    while ($row = $occ_result->fetch_assoc()) {
                                                        echo '<option value="' . $row['ID'] . '">' . $row['value'] . '</option>';
                                                    }
                                                }
                                                $occ_result->close();
                                                ?>
                                            </select>
                                            <label for="show">Occasion</label>
                                        </div>

                                        <div class="col-md-4 form-floating mb-3">
                                            <input type="number" class="form-control" id="stock_quantity"
                                                name="stock_quantity" placeholder="Stock Quantity" required>
                                            <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                        </div>

                                        <div class="col-12 d-flex justify-content-center">
                                            <input type="hidden" name="do" id="addProduct" value="addProduct">
                                            <button type="submit" style="width: 200px; margin: 10px auto 0px;"
                                                class="theme-btn">
                                                Add Product
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- products table -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseProductTwo" aria-expanded="false"
                                aria-controls="collapseProductTwo">
                                Products Table
                            </button>
                        </h2>
                        <div id="collapseProductTwo" class="accordion-collapse collapse"
                            data-bs-parent="#productsAccordion">
                            <div class="accordion-body">
                                <!-- Filter -->
                                <div id="filter-bar" class="animate__slideDown mt-5">
                                    <div class="container-fluid">
                                        <nav class="navbar navbar-expand-lg custom_nav-container">
                                            <button class="navbar-toggler" type="button" data-toggle="collapse"
                                                data-target="#filter" aria-controls="filter" aria-expanded="false"
                                                aria-label="Toggle filter">
                                                <span class="navbar-toggler-icon"></span>
                                            </button>

                                            <div class="navbar-brand">
                                                <h2>
                                                    Filter
                                                </h2>
                                            </div>

                                            <form action="" method="get">
                                                <div class="collapse navbar-collapse" id="filter">
                                                    <div
                                                        class="d-flex gap-2 ms-auto flex-column flex-lg-row align-items-center flex-wrap">
                                                        <!-- Category -->
                                                        <div class="form-floating">
                                                            <select name="category" class="form-select"
                                                                style="width: 150px;" id="category">
                                                                <option>All</option>
                                                                <?php
                                                                $sql = "SELECT * FROM categories";
                                                                $cat_result = $conn->query($sql);

                                                                if ($cat_result->num_rows > 0) {
                                                                    while ($row = $cat_result->fetch_assoc()) {
                                                                        if (isset($_GET['category']) && $row['ID'] == $_GET['category']) {
                                                                            echo '<option value="' . $row['ID'] . '"selected>' . $row['category_name'] . '</option>';
                                                                        } else {
                                                                            echo '<option value="' . $row['ID'] . '">' . $row['category_name'] . '</option>';
                                                                        }
                                                                    }
                                                                }

                                                                $cat_result->close();
                                                                ?>
                                                            </select>
                                                            <label for="category">Category</label>
                                                        </div>

                                                        <!-- Gender -->
                                                        <div class="form-floating">
                                                            <select name="gender" class="form-select"
                                                                style="width: 150px;" id="Gender">
                                                                <option>All</option>
                                                                <option value="Male" <?php echo (isset($_GET['gender']) && $_GET['gender'] == 'Male') ? 'selected' : ''; ?>>
                                                                    Male</option>
                                                                <option value="Female" <?php echo (isset($_GET['gender']) && $_GET['gender'] == 'Female') ? 'selected' : ''; ?>>
                                                                    Female</option>
                                                            </select>
                                                            <label for="Gender">Gender</label>
                                                        </div>

                                                        <!-- Material -->
                                                        <div class="form-floating">
                                                            <select name="material" class="form-select"
                                                                style="width: 150px;" id="Material">
                                                                <option>All</option>
                                                                <option value="Gold" <?php echo (isset($_GET['material']) && $_GET['material'] == 'Gold') ? 'selected' : ''; ?>>
                                                                    Gold</option>
                                                                <option value="Silver" <?php echo (isset($_GET['material']) && $_GET['material'] == 'Silver') ? 'selected' : ''; ?>>
                                                                    Silver</option>
                                                                <option value="Platinum" <?php echo (isset($_GET['material']) && $_GET['material'] == 'Platinum') ? 'selected' : ''; ?>>Platinum
                                                                </option>
                                                            </select>
                                                            <label for="Material">Material</label>
                                                        </div>

                                                        <!-- Occasion -->
                                                        <div class="form-floating">
                                                            <select name="occasion" class="form-select"
                                                                style="width: 150px;" id="occasion">
                                                                <option>All</option>
                                                                <?php
                                                                $sql = "SELECT * FROM occasions";
                                                                $occ_result = $conn->query($sql);

                                                                if ($occ_result->num_rows > 0) {
                                                                    while ($row = $occ_result->fetch_assoc()) {
                                                                        if (isset($_GET['occasion']) && $row['ID'] == $_GET['occasion']) {
                                                                            echo '<option value="' . $row['ID'] . '" selected>' . $row['value'] . '</option>';
                                                                        } else {
                                                                            echo '<option value="' . $row['ID'] . '">' . $row['value'] . '</option>';
                                                                        }
                                                                    }
                                                                }

                                                                $occ_result->close();
                                                                ?>
                                                            </select>
                                                            <label for="occasion">Occasion</label>
                                                        </div>

                                                        <!-- display -->
                                                        <div class="form-floating">
                                                            <select name="show" class="form-select"
                                                                style="width: 150px;" id="show">
                                                                <option>All Products</option>
                                                                <option value="1" <?php echo (isset($_GET['show']) && $_GET['show'] == '1') ? 'selected' : ''; ?>>
                                                                    Latest Products</option>
                                                                <option value="2" <?php echo (isset($_GET['show']) && $_GET['show'] == '2') ? 'selected' : ''; ?>>
                                                                    Bestsellers</option>
                                                                <option value="3" <?php echo (isset($_GET['show']) && $_GET['show'] == '2') ? 'selected' : ''; ?>>
                                                                    Product ID</option>
                                                                <option value="4" <?php echo (isset($_GET['show']) && $_GET['show'] == '2') ? 'selected' : ''; ?>>
                                                                    Stock Quantity</option>
                                                                <option value="5" <?php echo (isset($_GET['show']) && $_GET['show'] == '2') ? 'selected' : ''; ?>>
                                                                    Weight</option>
                                                                <option value="6" <?php echo (isset($_GET['show']) && $_GET['show'] == '2') ? 'selected' : ''; ?>>
                                                                    Discount</option>
                                                            </select>
                                                            <label for="show">Order By</label>
                                                        </div>

                                                        <input name="display-filter" type="hidden" value="yes">

                                                        <div class="form-floating">
                                                            <button class="simple-btn" type="submit" title="filter">
                                                                Filter
                                                            </button>
                                                            <a href="admin.php" class="simple-btn">Reset</a>
                                                        </div>

                                                    </div>
                                                </div>
                                            </form>
                                        </nav>
                                    </div>
                                </div>

                                <!-- products table -->
                                <div class="heading_container heading_center mt-3 mb-3">
                                    <h2>
                                        Products
                                    </h2>
                                </div>
                                <div class="universal_table">
                                    <table class="table cell-name-active">
                                        <thead class="thead-primary">
                                            <tr>
                                                <th>S No.</th>
                                                <th>Image</th>
                                                <th>Product</th>
                                                <th>Category</th>
                                                <th>Price</th>
                                                <th>Discount</th>
                                                <th>Stock Quantity</th>
                                                <th>Units Sold</th>
                                                <th>Material</th>
                                                <th>Weight</th>
                                                <th>Color</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $category = isset($_GET['category']) ? $_GET['category'] : 'All';
                                            $gender = isset($_GET['gender']) ? $_GET['gender'] : 'All';
                                            $material = isset($_GET['material']) ? $_GET['material'] : 'All';
                                            $occasion = isset($_GET['occasion']) ? $_GET['occasion'] : 'All';
                                            $show = isset($_GET['show']) ? $_GET['show'] : '0';
                                            $sql = "SELECT 
                                                    p.*,
                                                    i.img_1, 
                                                    c.category_name
                                                    FROM products p
                                                    JOIN categories c ON p.category = c.ID
                                                    JOIN product_img i ON p.product_id = i.img_to_pro";
                                            if ($category !== 'All') {
                                                $sql .= " AND p.category = '$category'";
                                            }

                                            if ($gender !== 'All') {
                                                $sql .= " AND p.gender = '$gender'";
                                            }

                                            if ($material !== 'All') {
                                                $sql .= " AND p.material = '$material'";
                                            }

                                            if ($occasion !== 'All') {
                                                $sql .= " AND p.occasion = '$occasion'";
                                            }

                                            $sql .= " ORDER BY ";

                                            switch ($show) {
                                                case '1':
                                                    $sql .= "p.date_added DESC";
                                                    break;
                                                case '2':
                                                    $sql .= "p.units_sold DESC";
                                                    break;
                                                case '3':
                                                    $sql .= "p.product_id DESC";
                                                    break;
                                                case '4':
                                                    $sql .= "p.stock_quantity DESC";
                                                    break;
                                                case '5':
                                                    $sql .= "p.weight DESC";
                                                    break;
                                                case '6':
                                                    $sql .= "p.discount DESC";
                                                    break;
                                                default:
                                                    $sql .= "p.date_added DESC";
                                            }
                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                $sno = 0;
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo '
                                                    <td cell-name="S No.">
                                                    <div>
                                                        ' . ++$sno . '
                                                    </div>
                                                    </td>
                                                    <td cell-name="image">
                                                        <div class="product-img" style="background-image: url(images/products/' . $row['img_1'] . ');">
                                                        </div>
                                                    </td>
                                                    <td cell-name="Product">
                                                        <div class="product-name">
                                                        <a href="Product-details.php?pro=' . $row['product_id'] . '"><span>' . $row['product_name'] . ' </span></a>
                                                        </div>
                                                    </td>
                                                    ';
                                                    echo "<td cell-name='Category'>" . $row['category_name'] . "</td>";
                                                    echo "<td cell-name='Price' class='text-end'>₹" . number_format($row['price'], 2) . "</td>";
                                                    echo "<td cell-name='Size' class='text-end'>" . $row['discount'] . "%</td>";
                                                    echo "<td cell-name='Stock Quantity' class='text-end'>" . number_format($row['stock_quantity']) . "</td>";
                                                    echo "<td cell-name='Units Sold' class='text-end'>" . number_format($row['units_sold']) . "</td>";
                                                    echo "<td cell-name='Material'>" . $row['material'] . "</td>";
                                                    echo "<td cell-name='Weight' class='text-nowrap'>" . $row['weight'] . " g</td>";
                                                    echo "<td cell-name='Color'>" . $row['color_plating'] . "</td>";
                                                    echo '
                                                        <td>
                                                            <button type="button" class="edit-btn edit-product m-1" title="Edit Product" data-toggle="modal" data-target="#editModal" data-product-id="' . $row['product_id'] . '">
                                                                <img width="20" src="icon/edit.svg">
                                                            </button>

                                                            <button type="button" data-toggle="modal" data-target="#deleteProductModal"
                                                                onclick="setDeleteId(\'deleteProductWithId\', \'' . $row['product_id'] . '\')" 
                                                                class="delete-btn m-1" title="delete">
                                                                <img width="20" src="icon/delete.svg">
                                                            </button>
                                                        </td>';
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td class='text-center' colspan='13'>No Product found</td></tr>";
                                            }
                                            $result->close();
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="orders-section col-md animate__fadeIn layout_padding me-3 table-responsive px-3 px-md-auto 
                <?php echo isActive('Orders', $activePage) == "active" ? "" : "d-none"; ?>">
                <div class="heading_container heading_center mb-3">
                    <h2>Orders</h2>
                </div>
                <div class="universal_table">
                    <table class="table cell-name-active">
                        <!-- Table Header -->
                        <thead class="thead-primary">
                            <tr>
                                <th>ID</th>
                                <th>Status</th>
                                <th>Receiver Name</th>
                                <th>Phone No.</th>
                                <th>Email</th>
                                <th>Total Amount</th>
                                <th>Payment Method</th>
                                <th>Payment Status</th>
                                <th>Shipping Address</th>
                                <th>Items</th>
                                <th>Message</th>
                                <th>Order Date</th>
                            </tr>
                        </thead>

                        <!-- Table Body -->
                        <tbody>
                            <?php $orders = fetchOrders(); ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td cell-name="Order ID">
                                        <?= strval($order['order_id']) ?>
                                    </td>
                                    <td cell-name="Status">
                                        <form action="php/update_status.php" method="POST"
                                            class="d-flex justify-content-center align-items-center flex-column">
                                            <input type="hidden" name="orderId" value="<?= strval($order['order_id']) ?>">
                                            <select class="form-select" style="width: fit-content;" name="newStatus">
                                                <option value="Processing" <?= ($order['status'] == 'Processing') ? 'selected' : '' ?>>Processing
                                                </option>
                                                <option value="Dispatched" <?= ($order['status'] == 'Dispatched') ? 'selected' : '' ?>>Dispatched</option>
                                                <option value="Delivered" <?= ($order['status'] == 'Delivered') ? 'selected' : '' ?>>Delivered</option>
                                                <option value="Rejected" <?= ($order['status'] == 'Rejected') ? 'selected' : '' ?>>Rejected</option>
                                            </select>
                                            <button type="submit" class="status-btn mt-3">
                                                Change Status</button>
                                        </form>
                                    </td>
                                    <td cell-name="Receiver Name">
                                        <?= strval($order['receiver_name']) ?>
                                    </td>
                                    <td cell-name="Phone No." class="text-nowrap">
                                        <a class="text-reset theme-hover" href="tel:<?= $order['phone'] ?>">
                                            <?= $order['phone'] ?>
                                        </a>
                                    </td>
                                    <td cell-name="Email">
                                        <a class="text-reset theme-hover" href="mailto:<?= $order['email'] ?>">
                                            <?= $order['email'] ?>
                                        </a>
                                    </td>
                                    <td cell-name="Total Amount" class="text-end text-nowrap">₹
                                        <?= number_format($order['total_amount'], 2) ?>
                                    </td>
                                    <td cell-name="Payment Method">
                                        <?= $order['payment_method'] ?>
                                    </td>
                                    <td cell-name="Payment Status">
                                        <?= $order['payment_status'] ?>
                                    </td>
                                    <td cell-name="Shipping Address">
                                        <address>
                                            <?= $order['shipping_address'] ?>
                                        </address>
                                    </td>
                                    <td cell-name="Items">
                                        <?php if (!empty($order['items'])): ?>
                                            <ul>
                                                <?php foreach ($order['items'] as $item): ?>
                                                    <li class="text-nowrap">
                                                        <?= $item['items_count'] ?> x <a class="pro_link"
                                                            href="Product-details.php?pro=<?= $item['product_id'] ?>">
                                                            <?= $item['item_name'] ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else: ?>
                                            <div>No items found</div>
                                        <?php endif; ?>
                                    </td>
                                    <td cell-name="Message">
                                        <div style="height: 100px; width: 200px; overflow-y: scroll;">
                                            <?= $order['msg_seller'] ?>
                                        </div>
                                    </td>
                                    <td cell-name="Order Date" class="text-nowrap">
                                        <?= $order['order_date'] ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <?php if (empty($orders)): ?>
                                <tr>
                                    <td class="text-center" colspan="13">No Order found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="Workers-section col-md animate__fadeIn layout_padding me-3 table-responsive px-3 px-md-auto 
                <?php echo isActive('Workers', $activePage) == "active" ? "" : "d-none"; ?>">
                <div class="heading_container heading_center mb-3">
                    <h2>
                        Workers
                    </h2>
                </div>

                <div class="accordion" id="WorkersAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Add New Worker
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show"
                            data-bs-parent="#WorkersAccordion">
                            <div class="accordion-body">
                                <div class="p-4 mt-3 upping_shadow">
                                    <form action="php/process_worker.php" method="post" class="row g-3"
                                        enctype="multipart/form-data">

                                        <div class="workerProfilePhoto">
                                            <div class="d-flex justify-content-center mb-4">
                                                <img id="workerAvatar" src="images/profile.png" class="rounded-circle"
                                                    style="width: 150px; height: 150px; object-fit: cover;" />
                                            </div>

                                            <div class="d-flex justify-content-center">
                                                <div class="simple-btn">
                                                    <label class="form-label m-1" for="profilePhoto">Change Profile
                                                        Photo</label>
                                                    <input type="file" class="form-control d-none" id="profilePhoto"
                                                        name="profilePhoto" accept="image/*"
                                                        onchange="displaySelectedImage(event, 'workerAvatar')" />
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                        if ($workerMessage != "") {
                                            echo '<div id="signup_alert" class="alert' . ($workerMessage == "Error updating Profile information. Please try again." ? " alert-danger" : " alert-success") . '  alert-dismissible fade show" role="alert">
                                                    ' . $workerMessage . '
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>';
                                        }
                                        ?>
                                        <div class="col-md-4 form-floating mb-3">
                                            <input type="text" class="form-control" id="f_name" name="f_name"
                                                pattern="[A-Za-z]+" placeholder="First Name" required>
                                            <label for="f_name" class="form-label">First Name:</label>
                                        </div>

                                        <div class="col-md-4 form-floating mb-3">
                                            <input type="text" class="form-control" id="l_name" name="l_name"
                                                pattern="[A-Za-z]+" placeholder="Last Name" required>
                                            <label for="l_name" class="form-label">Last Name:</label>
                                        </div>
                                        <div class="col-md-3 form-floating mb-3">
                                            <select name="gender" class="form-select" required>
                                                <option value="">Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                            <label for="show">Gender:</label>
                                        </div>
                                        <div class="col-3 form-floating mb-3">
                                            <input type="text" class="form-control" id="phone" maxlength="10"
                                                pattern="[0-9]{10}" name="phone" placeholder="Phone:" required>
                                            <label for="phone" class="form-label">Phone:</label>
                                        </div>
                                        <div class="col-4 form-floating mb-3">
                                            <input type="email" class="form-control" id="email" name="email"
                                                placeholder="Email:" required>
                                            <label for="email" class="form-label">Email:</label>
                                        </div>

                                        <div class="col-md-4 form-floating mb-3">
                                            <select name="position" class="form-select" required>
                                                <option value="">Position</option>
                                                <option value="Appraiser">Appraiser</option>
                                                <option value="Engraver">Engraver</option>
                                                <option value="Gemologist">Gemologist</option>
                                                <option value="Goldsmith">Goldsmith</option>
                                                <option value="Jeweler">Jeweler</option>
                                                <option value="Jewelry Designer">Jewelry Designer</option>
                                                <option value="Manager">Manager</option>
                                                <option value="Sales Associate">Sales Associate</option>
                                            </select>
                                            <label for="position">Position:</label>
                                        </div>

                                        <div class="col-4 form-floating mb-3">
                                            <input type="number" class="form-control" id="salary" name="salary"
                                                placeholder="Salary:" step="any" required>
                                            <label for="salary" class="form-label">Salary:</label>
                                        </div>
                                        <hr>
                                        <div class="col-6 form-floating mb-3">
                                            <input type="text" class="form-control" id="userAddress" name="userAddress"
                                                placeholder="Address:" required>
                                            <label for="userAddress" class="form-label">Address:</label>
                                        </div>
                                        <div class="col-6 form-floating mb-3">
                                            <input type="text" class="form-control" id="userAddress_2"
                                                name="userAddress_2" placeholder=" Apartment, studio, or floor">
                                            <label for="userAddress_2" class="form-label">Apartment, studio, or
                                                floor:</label>
                                        </div>
                                        <div class="col-md-3 form-floating mb-3">
                                            <input type="text" class="form-control" id="userCity" name="userCity"
                                                placeholder="City" required>
                                            <label for="userCity" class="form-label">City:</label>
                                        </div>
                                        <div class="col-sm-3 col-6 mb-3">
                                            <div class="form-floating">
                                                <div class="form-floating">
                                                    <select required name="userState" class="form-select" id="userState"
                                                        placeholder="State">
                                                        <option value="">Select State</option>
                                                        <option value="Andaman and Nicobar Islands">Andaman and Nicobar
                                                            Islands</option>
                                                        <option value="Andhra Pradesh">Andhra Pradesh</option>
                                                        <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                                        <option value="Assam">Assam</option>
                                                        <option value="Bihar">Bihar</option>
                                                        <option value="Chandigarh">Chandigarh</option>
                                                        <option value="Chhattisgarh">Chhattisgarh</option>
                                                        <option value="Dadra and Nagar Haveli and Daman and Diu">Dadra
                                                            and Nagar Haveli and Daman and Diu</option>
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
                                                    <label for="userState">State</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2 form-floating mb-3">
                                            <input type="text" class="form-control" id="userZip" name="userZip"
                                                placeholder="Zip" maxlength="6" pattern="[0-9]{1,6}" required>
                                            <label for="userZip" class="form-label">Zip:</label>
                                        </div>

                                        <hr>

                                        <div class="col-12 form-floating mb-3">
                                            <textarea style="height: 150px;" class="form-control" id="description"
                                                name="description" placeholder="Write Description (Words remaining: 50)"
                                                required></textarea>
                                            <label id="wordCount" for="description" class="form-label">Write Description
                                                (Words remaining: 50):</label>
                                        </div>

                                        <div class="col-12 d-flex justify-content-center">
                                            <input type="hidden" name="do" id="addWorker" value="addWorker">
                                            <button type="submit" style="width: 200px; margin: 10px auto 0px;"
                                                class="theme-btn">
                                                Add Worker
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Workers Table
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#WorkersAccordion">
                            <div class="accordion-body overflow-x-scroll">
                                <div class="universal_table">
                                    <table class="table">
                                        <thead class="thead-primary">
                                            <tr>
                                                <th>S No.</th>
                                                <th>&nbsp;</th>
                                                <th>Name</th>
                                                <th>Position</th>
                                                <th>Gender</th>
                                                <th>Email</th>
                                                <th>Phone Number</th>
                                                <th>Salary</th>
                                                <th>Address</th>
                                                <th>Description</th>
                                                <th>Hire Date</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            $sqlWorkers = "SELECT * FROM workers";
                                            $workerResult = $conn->query($sqlWorkers);
                                            if ($workerResult->num_rows > 0) {
                                                $sNo = 0;
                                                while ($row = $workerResult->fetch_assoc()) {
                                                    echo '
                                                        <tr>
                                                            <td>' . ++$sNo . '</td>                                                            
                                                            <td cell-name="Image">
                                                                <div class="product-img" style="background-image: url(images/workers/' . $row['image'] . ');">
                                                                </div>
                                                            </td>
                                                            <td cell-name="Name">' . $row['name'] . '</td>
                                                            <td cell-name="Position">' . $row['position'] . '</td>
                                                            <td cell-name="Gender">' . $row['gender'] . '</td>
                                                            <td cell-name="Email">' . $row['email'] . '</td>
                                                            <td class="text-nowrap" cell-name="Phone Number">' . $row['phone_number'] . '</td>
                                                            <td cell-name="Salary">₹' . number_format($row['salary'], 2) . '</td>
                                                            <td cell-name="Address">' . preg_replace("/\//", ", ", $row['address']) . '</td>
                                                            <td cell-name="Description">' . preg_replace("/\//", ", ", $row['description']) . '</td>
                                                            <td class="text-nowrap" cell-name="Hire Date">' . $row['hire_date'] . '</td>
                                                            <td>
                                                                <button type="button" class="edit-btn edit-worker m-1" title="Edit Worker" data-toggle="modal" data-target="#editWorkerModal" data-worker-id="' . $row['worker_id'] . '">
                                                                    <img width="20" src="icon/edit.svg">
                                                                </button>
                                                        
                                                                <button type="button" data-toggle="modal" data-target="#deleteWorkerModal"
                                                                    onclick="setDeleteId(\'deleteWorkerWithId\', \'' . $row['worker_id'] . '\')" 
                                                                    class="delete-btn m-1" title="delete">
                                                                    <img width="20" src="icon/delete.svg">
                                                                </button>

                                                            </td>
                                                        </tr>
                                                        ';
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="Enquiries-section col-md animate__fadeIn layout_padding me-3 table-responsive px-3 px-md-auto 
                <?php echo isActive('Enquiries', $activePage) == "active" ? "" : "d-none"; ?>">
                <div class="heading_container heading_center mb-3">
                    <h2>Enquiries</h2>
                </div>
                <div class="universal_table">
                    <table class="table cell-name-active">
                        <!-- Table Header -->
                        <thead class="thead-primary">
                            <tr>
                                <th>S No.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Message</th>
                            </tr>
                        </thead>

                        <!-- Table Body -->
                        <tbody>
                            <?php
                            $contacts = fetchContacts();
                            $sno = 0;
                            ?>
                            <?php foreach ($contacts as $contact): ?>
                                <tr>
                                    <td cell-name="S No.">
                                        <?= ++$sno; ?>
                                    </td>
                                    <td cell-name="Name" class="text-nowrap">
                                        <?= $contact['name'] ?>
                                    </td>
                                    <td cell-name="Email">
                                        <a class="text-reset theme-hover" href="mailto:<?= $contact['email'] ?>">
                                            <?= $contact['email'] ?>
                                        </a>
                                    </td>

                                    <td cell-name="Message">
                                        <?= $contact['Message'] ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <?php if (empty($contacts)): ?>
                                <tr>
                                    <td class="text-center" colspan="4">No Contacts found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </main>
    <!--End Admin -->

    <!-- Edit product Modal  -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 700px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Product : </h5>
                    <button type="button" class="op-btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="php/process_product.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="do" value="updateProduct">
                    <div class="modal-body">

                    </div>

                    <div class="modal-footer">
                        <button type="button" title="close" class="op-btn" data-dismiss="modal">Close</button>
                        <button type="submit" title="Update Product data" class="simple-btn">Save
                            changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit worker Modal -->
    <div class="modal fade" id="editWorkerModal" tabindex="-1" role="dialog" aria-labelledby="editWorkerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 700px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editWorkerModalLabel">Edit Worker:</h5>
                    <button type="button" class="op-btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="php/process_worker.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body">

                    </div>

                    <div class="modal-footer">
                        <button type="button" title="close" class="op-btn" data-dismiss="modal">Close</button>
                        <button type="submit" title="Update Worker data" class="simple-btn">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Product Modal -->
    <div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteProductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteProductModalLabel">Delete Product Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this product?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="php/process_product.php" method="post">
                        <input type="hidden" name="do" id="deleteProduct" value="deleteProduct">
                        <input type="hidden" id="deleteProductWithId" name="deleteProductWithId" value=" ">
                        <button class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Worker Modal -->
    <div class="modal fade" id="deleteWorkerModal" tabindex="-1" role="dialog" aria-labelledby="deleteWorkerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteWorkerModalLabel">Delete Worker Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this worker?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="php/process_worker.php" method="post">
                        <input type="hidden" name="do" id="deleteWorker" value="deleteWorker">
                        <input type="hidden" id="deleteWorkerWithId" name="deleteWorkerWithId" value=" ">
                        <button class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
        aria-hidden="true">
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
                    <form action="php/logout.php" method="post">
                        <input type="hidden" name="logout" value="admin_logOut">
                        <button class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
    <?php getScripts(); ?>
    <!-- End Scripts -->

</body>

</html>