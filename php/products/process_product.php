<?php
session_start();
include "connection.php";

// Redirect to login page if admin is not logged in
if (!isset($_SESSION["admin_id"])) {
    header('Location: ../admin-login.php');
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['do'])) {
    $action = $_POST['do'];

    switch ($action) {
        case 'addProduct':
            handleProductAddition();
            break;

        case 'updateProduct':
            handleProductUpdate();
            break;

        case 'deleteProduct':
            handleProductDeletion();
            break;

        default:
            $_SESSION['productMessage'] = "Unknown action:" . $action;
            break;
    }
}

// Redirect to the admin page
redirectToAdminPage();

// Function to handle product addition
function handleProductAddition()
{
    global $conn;

    $productName = ucwords(strtolower($_POST['product_name']));
    $category = $_POST['category'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $weight = $_POST['weight'];
    $color = ucwords(strtolower($_POST['color']));
    $gender = $_POST['gender'];
    $material = $_POST['material'];
    $occasion = $_POST['occasion'];
    $stockQuantity = $_POST['stock_quantity'];

    $sql = "INSERT INTO products (product_name, category, price, discount, weight, color_plating, gender, material, occasion, stock_quantity)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdddsssss", $productName, $category, $price, $discount, $weight, $color, $gender, $material, $occasion, $stockQuantity);

    if ($stmt->execute()) {
        $_SESSION['productMessage'] = "Product added successfully.";

        $product_id = $conn->insert_id;
        handleImageUploads($product_id, $productName, $category, $gender, $color);


    } else {
        $_SESSION['productMessage'] = "Error: " . $stmt->error;
    }

    $stmt->close();
    redirectToAdminPage();
}

// Function to handle product update
function handleProductUpdate()
{
    global $conn;

    $productId = $_POST['product_id'];
    $updateProductName = ucwords(strtolower($_POST['update_product_name']));
    $updateCategory = $_POST['update_category'];
    $updatePrice = $_POST['update_price'];
    $updateDiscount = $_POST['update_discount'];
    $updateWeight = $_POST['update_weight'];
    $updateColor = ucfirst($_POST['update_color']);
    $updateGender = $_POST['update_gender'];
    $updateMaterial = $_POST['update_material'];
    $updateOccasion = $_POST['update_occasion'];
    $updateStockQuantity = $_POST['update_stock_quantity'];

    $sql = "UPDATE products SET product_name = ?, category = ?, price = ?, discount = ?, weight = ?, color_plating = ?, gender = ?, material = ?, occasion = ?, stock_quantity = ? WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdddsssssi", $updateProductName, $updateCategory, $updatePrice, $updateDiscount, $updateWeight, $updateColor, $updateGender, $updateMaterial, $updateOccasion, $updateStockQuantity, $productId);

    if ($stmt->execute()) {
        $_SESSION['productMessage'] = "Product updated successfully.";

        handleImageUploads($productId, $updateProductName, $updateCategory, $updateGender, $updateColor, true);
    } else {
        $_SESSION['productMessage'] = "Error: " . $stmt->error;
    }

    $stmt->close();
    redirectToAdminPage();
}

// Function to handle product deletion
function handleProductDeletion()
{
    global $conn;

    $product_id = $_POST['deleteProductWithId'];

    $getProductImages = "SELECT img_1, img_2, img_3 FROM product_img WHERE img_to_pro = ?";
    $stmt = $conn->prepare($getProductImages);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $productImagesResult = $stmt->get_result();
    $productImages = $productImagesResult->fetch_assoc();

    if ($productImages != null) {
        foreach ($productImages as $image) {
            if ($image != null) {
                $imagePath = "../images/products/" . $image;

                if ($image != 'add-image.png' && file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }
    }

    $sql = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $result = $stmt->execute();

    if ($result) {
        if ($stmt->affected_rows > 0) {
            $_SESSION["productMessage"] = "Product deleted successfully.";
        } else {
            $_SESSION["productMessage"] = "Deletion failed. Product not found.";
        }
    } else {
        $_SESSION['productMessage'] = "Error: " . $stmt->error;
    }
    $stmt->close();
    redirectToAdminPage();
}

// Function to handle image uploads
function handleImageUploads($product_id, $productName, $category, $gender, $color, $isUpdate = false)
{
    global $conn;

    $categoryName = getCategoryName($category);

    $imageFields = $isUpdate ? ['update_productImage1', 'update_productImage2', 'update_productImage3']
        : ['productImage1', 'productImage2', 'productImage3'];

    $target_dir = "../images/products/";

    foreach ($imageFields as $key => $image) {
        if (isset($_FILES[$image]['name']) && $_FILES[$image]['error'] === UPLOAD_ERR_OK) {
            $fileName = $_FILES[$image]['name'];
            $fileInfo = pathinfo($fileName);
            $fileExtension = strtolower($fileInfo['extension']);

            $img_name = generateImageName($gender, $color, $categoryName, $productName, $key, $product_id, $fileExtension);

            $target_file = $target_dir . basename($img_name);

            // Check if the first image is empty
            if ($key === 0 && empty($img_name)) {
                $_SESSION['productMessage'] .= "<br>Image 1 is required.";
                $dlt_sql = "DELETE FROM products WHERE product_id = ?";
                $stmt = $conn->prepare($dlt_sql);
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $stmt->close();
                return;
            }

            // Upload the file and update the database
            if (!empty($img_name) && move_uploaded_file($_FILES[$image]['tmp_name'], $target_file)) {
                handleImageDatabaseUpdate($product_id, $img_name, $key, $isUpdate);
            } else {
                $_SESSION['productMessage'] .= "<br>Error uploading image " . ($key + 1) . ".";
            }
        } else {
            $_SESSION['productMessage'] .= "<br>Image " . ($key + 1) . " not found or upload error.";
        }
    }
}

// Function to get the category name
function getCategoryName($category)
{
    global $conn;

    $getCategoryName = "SELECT category_name FROM categories WHERE ID = ?";
    $stmt = $conn->prepare($getCategoryName);
    $stmt->bind_param("i", $category);
    $stmt->execute();
    $categoryNameResult = $stmt->get_result();
    $categoryName = $categoryNameResult->fetch_assoc()['category_name'];

    return $categoryName;
}


// Function to generate a unique image name
function generateImageName($gender, $color, $categoryName, $productName, $key, $product_id, $fileExtension)
{
    $img_name = $gender . '_' . $color . '_' . $categoryName . '_' . $productName . '_' . $key . '_' . $product_id . '_' . time() . '.' . $fileExtension;
    $img_name = str_replace([' ', '(', ')'], ['_', '_', '_'], $img_name);

    return $img_name;
}

// Function to handle image database update
function handleImageDatabaseUpdate($product_id, $img_name, $key, $isUpdate)
{
    global $conn;

    $column_name = 'img_' . ($key + 1);

    if ($isUpdate) {
        $getOldImage = "SELECT $column_name FROM product_img WHERE img_to_pro = ?";
        $stmt = $conn->prepare($getOldImage);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $oldImageResult = $stmt->get_result();
        $oldImage = $oldImageResult->fetch_assoc()[$column_name];

        if ($oldImage != 'add-image.png' && $oldImage != null && file_exists("../images/products/" . $oldImage)) {
            unlink("../images/products/" . $oldImage);
        }

        $sql = "UPDATE product_img SET $column_name = ? WHERE img_to_pro = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $img_name, $product_id);

    } else {
        if ($key === 0) {
            $sql = "INSERT INTO product_img (img_to_pro, $column_name) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $product_id, $img_name);
        } else {
            $sql = "UPDATE product_img SET $column_name = ? WHERE img_to_pro = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $img_name, $product_id);
        }
    }

    if ($stmt->execute()) {
        $_SESSION['productMessage'] .= "<br>Image " . ($key + 1) . " uploaded successfully.";
    } else {
        $_SESSION['productMessage'] = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Function to redirect to admin page
function redirectToAdminPage()
{
    header('Location: ../admin.php');
    exit;
}
?>