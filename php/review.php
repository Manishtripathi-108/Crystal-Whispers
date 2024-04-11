<?php
session_start();
// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    include "connection.php";

    // Sanitize and validate input data
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $product_id = isset($_POST['Product_id']) ? $_POST['Product_id'] : null;
    $rating = isset($_POST['rating']) ? $_POST['rating'] : null;
    $review_text = isset($_POST['review_text']) ? $_POST['review_text'] : null;


    // Check if any required data is missing
    if (!$user_id || !$product_id || !$rating || !$review_text) {
        $_SESSION['reviewErrorMessage'] = "Error: Missing data. Please try again.";
        header('Location: ../public/Product-details.php?pro=' . $product_id);
        exit;
    }

    // Insert review in database
    $sql = "INSERT INTO reviews (UserID, ProductID, Rating, ReviewText) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $user_id, $product_id, $rating, $review_text);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['reviewSuccessMessage'] = "Review added successfully.";
            header('Location: ../public/Product-details.php?pro=' . $product_id);
            exit;
        } else {
            $_SESSION['reviewErrorMessage'] = "Error: Unable to add the review. Please try again.";
        }

        $stmt->close();
    } else {
        $_SESSION['reviewErrorMessage'] = "Error: Unable to prepare the statement. Please try again.";
    }

    $conn->close();
}

header('Location: ../public/Product-details.php?pro=' . $product_id);
exit;
