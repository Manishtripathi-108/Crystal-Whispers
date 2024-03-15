<?php
session_start();
require_once("connection.php");

// Check if it's a POST request and the required parameters are set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["product_id"]) && isset($_SESSION["user_id"])) {
    $productID = $_POST["product_id"];
    $userID = $_SESSION["user_id"];

    // Check if the product is already in the user's cart
    $cartExists = doesCartExist($conn, $userID, $productID);

    // Update or add the product to the cart based on its existence
    if ($cartExists) {
        updateCart($conn, $userID, $productID);
    } else {
        addToCart($conn, $userID, $productID);
    }

    header("Location: ../cart.php");
    exit;
} else {
    header("Location: ../cart.php");
    exit;
}

// Function to check if the product is already in the cart
function doesCartExist($conn, $userID, $productID)
{
    $sql = "SELECT CartID FROM cart WHERE UserID = ? AND ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $userID, $productID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Function to update the quantity of a product in the cart
function updateCart($conn, $userID, $productID)
{
    $sql = !isset($_POST["quantity"])
        ? "UPDATE cart SET Quantity = CASE WHEN Quantity = 0 THEN 1 ELSE LEAST(Quantity + 1, 5) END WHERE UserID = ? AND ProductID = ?"
        : "UPDATE cart SET Quantity = ? WHERE UserID = ? AND ProductID = ?";

    $stmt = $conn->prepare($sql);

    if (isset($_POST["quantity"])) {
        $stmt->bind_param("sss", $_POST["quantity"], $userID, $productID);
    } else {
        $stmt->bind_param("ss", $userID, $productID);
    }

    $stmt->execute();
    $stmt->close();
}

// Function to add a product to the cart
function addToCart($conn, $userID, $productID)
{
    $quantity = !isset($_POST["quantity"]) ? 1 : $_POST["quantity"];
    $sql = "INSERT INTO cart (`UserID`, `Quantity`, `ProductID`) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $userID, $quantity, $productID);
    $stmt->execute();
    $stmt->close();
}
?>