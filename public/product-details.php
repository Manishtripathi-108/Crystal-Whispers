<?php
include "../php/functions.php";
$userIDD = checkGetUserLoginStatus(true);

function fetchProductDetails($productID)
{
    global $conn;

    $sql = "SELECT
    p.ProductID,
    p.ProductName,
    p.ProductTargetGender,
    p.ProductPrice,
    p.ProductDiscount,
    p.ProductMaterial,
    p.ProductColor as color,
    o.OccasionName as occasion,
    p.ProductWeight,
    p.ProImg1,
    p.ProImg2,
    p.ProImg3,
    c.CategoryName AS category
    FROM
        products p
        JOIN
        categories c ON p.CategoryID = c.CategoryID
        JOIN
        occasions o ON p.OccasionID = o.OccasionID
    WHERE
        p.ProductID = " . $productID . "
    ORDER BY 
        p.ProductCreatedAt DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

function getRating($productID)
{
    global $conn;

    $getRating = "SELECT ProductRating FROM products WHERE ProductID=" . $productID;
    $rating = $conn->query($getRating);
    $rating = $rating->fetch_assoc();
    $rating = round($rating['ProductRating']);

    if ($rating > 0) {
        for ($i = 1; $i <= $rating; $i++) {
            echo '<i class="fa fa-star"></i>';
        }
    } else {
        echo '<i class="fa fa-star-o"></i>';
    }
}

function getReview($productID)
{
    global $conn;
    $reviews = [];

    $sql = "SELECT reviews.Rating, 
                reviews.ReviewText, 
                users.FirstName,
                users.UserImage,
                users.LastName
            FROM reviews
            JOIN users ON reviews.UserID = users.UserID
            WHERE reviews.ProductID = $productID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $review = [
                'rating' => $row['Rating'],
                'review_text' => $row['ReviewText'],
                'name' => $row['FirstName'] . ' ' . $row['LastName'],
                'user_profilePhoto' => $row['UserImage']
            ];

            $reviews[] = $review;
        }
    }
    $result->close();

    return $reviews;
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

    <script src="https://kit.fontawesome.com/34176f497f.js" crossorigin="anonymous"></script>
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
    <?php getHeader("../", "") ?>
    <!-- end header section -->

    <?php
    if (isset($_SESSION['reviewSuccessMessage']) || isset($_SESSION['reviewErrorMessage'])) {
        echo '<div id="signup_alert" class="alert' . (isset($_SESSION['reviewErrorMessage']) ? " alert-danger" : " alert-success") . '  alert-dismissible fade show" role="alert">
            ' . (isset($_SESSION['reviewErrorMessage']) ? $_SESSION['reviewErrorMessage'] : $_SESSION['reviewSuccessMessage']) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';

        if (isset($_SESSION['reviewErrorMessage'])) {
            unset($_SESSION['reviewErrorMessage']);
        } else if (isset($_SESSION['reviewSuccessMessage'])) {
            unset($_SESSION['reviewSuccessMessage']);
        }
    }
    ?>

    <main id="product-details-page" class="animate__fadeIn">

        <section id="product-details-carousel" class="layout_padding">
            <div class="container">
                <div class="row m-0">
                    <div class="col-lg-6 mb-4">
                        <div id="testimonialCarousel" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                <?php
                                $product = fetchProductDetails($_GET["pro"]);
                                $active = true;
                                foreach (['ProImg1', 'ProImg2', 'ProImg3'] as $imgKey) {
                                    if (!empty($product[$imgKey])) {
                                        echo '<div class="carousel-item ' . ($active ? 'active' : '') . '">
                                                    <img src="../assets/images/products/' . $product[$imgKey] . '" alt="">
                                                </div>';
                                        $active = false;
                                    }
                                }
                                ?>
                            </div>
                            <div class="carousel_btn-container">
                                <a class="carousel-control-prev" href="#testimonialCarousel" role="button" data-slide="prev">
                                    <i class="fa fa-long-arrow-left" aria-hidden="true"></i>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#testimonialCarousel" role="button" data-slide="next">
                                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 p-4">
                        <div class="ps-lg-3">
                            <h4 class="title theme-color">
                                <?= $product['ProductName'] ?>
                            </h4>
                            <p>
                                <?= $product['category'] ?>,
                                <?= $product['ProductTargetGender'] ?>,
                                <?= $product['occasion'] ?>,
                                <?= $product['color'] ?>
                            </p>
                            <div class="d-flex flex-row my-3">
                                <div class="text-warning mb-1 me-2">
                                    <?php getRating($_GET["pro"]); ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <span class="h4 theme-color">
                                    <small style="margin-right: 5px;display: inline-block;color: black;text-decoration: line-through;">
                                        ₹
                                        <?= number_format($product['ProductPrice'], 2) ?>
                                    </small>
                                    ₹
                                    <?= number_format(($product['ProductPrice'] * ((100 - $product['ProductDiscount']) / 100)), 2) ?>
                                </span>
                            </div>
                            <div class="row">
                                <dt class="col-3 theme-color">Category:</dt>
                                <dd class="col-9">
                                    <?= $product['category'] ?>
                                </dd>

                                <dt class="col-3 theme-color">Color:</dt>
                                <dd class="col-9">
                                    <?= $product['color'] ?>
                                </dd>

                                <dt class="col-3 theme-color">Occasion:</dt>
                                <dd class="col-9">
                                    <?= $product['occasion'] ?>
                                </dd>
                            </div>
                            <hr />
                            <form action="../php/products/add_to_cart.php" method="post">
                                <div class="row mb-4">

                                    <div class="col-md-4 col-6 mb-3">
                                        <label class="mb-2 d-block">Quantity</label>
                                        <div class="input-group mb-3">
                                            <select name="quantity" class=" text-center form-select border border-secondary" style="height: 35px;">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-6 mb-3">
                                        <label class="mb-2 d-block">Weight</label>
                                        <div class="input-group mb-3" style="width: 100px;">
                                            <input class="text-center" type="text" value="<?= $product['ProductWeight'] . ' g' ?>" disabled>
                                        </div>
                                    </div>
                                </div>

                                <input name="product_id" value="<?= $_GET["pro"] ?>" type="hidden">
                                <button class="theme-btn ms-auto" title="Cart" type="submit">
                                    <i class="fa-solid fa-bag-shopping"></i> Add to Cart
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="product-detail-table" class="layout_padding">
            <div class="container">
                <div class="heading_container heading_center">
                    <h2>
                        Product Details
                    </h2>
                </div>
                <div class="row m-0 pt-5">
                    <div class="col-12 col-md-6 mx-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Attribute</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Brand</td>
                                    <td>Crystal Whispers</td>
                                </tr>
                                <tr>
                                    <td>Material</td>
                                    <td>
                                        <?= $product['ProductMaterial'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Gender</td>
                                    <td>
                                        <?= $product['ProductTargetGender'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Occasion</td>
                                    <td>
                                        <?= $product['occasion'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Material Colour</td>
                                    <td>
                                        <?= $product['color'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jewellery Type</td>
                                    <td>
                                        <?= $product['category'] ?> Jewellery
                                    </td>
                                </tr>
                                <tr>
                                    <td>Weight</td>
                                    <td>
                                        <?= $product['ProductWeight'] . ' g' ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-none d-md-block col-md-4 mx-3">
                        <div class="p-1 w-100">
                            <img class="w-100" src="../assets/images/products/<?= $product['ProImg1'] ?>" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end detail-table -->

        <!-- reviews -->
        <section id="product-review" class="layout_padding">
            <div class="container">
                <div class="heading_container heading_center">
                    <h2>
                        Product Reviews
                    </h2>
                </div>
                <div class="row justify-content-around m-0 pt-5">
                    <div class="col-12 col-md-5 mb-4">

                        <?php
                        $reviews = getReview($_GET["pro"]);
                        if (!empty($reviews)) :

                            foreach ($reviews as $review) : ?>
                                <div class="review-item">
                                    <div class="review-author">
                                        <div class="review-author-Image">
                                            <img src="images/users/<?= $review['user_profilePhoto'] ?>" alt="">
                                        </div>
                                        <div class="review-author-Info">
                                            <span>
                                                <?= $review['name'] ?>
                                                <br>

                                                <?php for ($i = 1; $i <= round($review['rating']); $i++) : ?>
                                                    <?= '<i class="fa fa-star" style="color: #ffd43b;"></i>' ?>
                                                <?php endfor; ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="review-text">
                                        <p>
                                            <?= $review['review_text'] ?>
                                        </p>
                                    </div>
                                </div>
                        <?php
                            endforeach;
                        else :
                            echo '<p class="text-center fs-4 fst-italic mb-4 fw-medium">No reviews yet</p>';
                        endif;
                        ?>
                    </div>

                    <div class="col-12 col-md-5  mb-4">
                        <?php if ($userIDD) : ?>
                            <form action="../php/review.php" method="post">
                                <h2 class="text-center mb-4 fs-3 fw-bolder">Write a Review</h2>
                                <div class="form-floating mb-3">
                                    <div class="rating">
                                        <input type="radio" id="star5" name="rating" value="5" required>
                                        <label for="star5">&#9733;</label>
                                        <input type="radio" id="star4" name="rating" value="4" required>
                                        <label for="star4">&#9733;</label>
                                        <input type="radio" id="star3" name="rating" value="3" required>
                                        <label for="star3">&#9733;</label>
                                        <input type="radio" id="star2" name="rating" value="2" required>
                                        <label for="star2">&#9733;</label>
                                        <input type="radio" id="star1" name="rating" value="1" required>
                                        <label for="star1">&#9733;</label>
                                    </div>
                                </div>

                                <div class="form-floating mb-3">
                                    <textarea name="review_text" style="height: 200px;" type="text" class="form-control" id="Review" placeholder="Write your review here" required></textarea>
                                    <label for="Review">Review</label>
                                </div>
                                <input name="Product_id" value="<?php echo $_GET["pro"] ?>" type="hidden">
                                <button title="Submit Review" type="submit" class="theme-btn">Submit</button>
                            </form>
                        <?php else : ?>
                            <p class="text-center mb-4 fs-3 fst-italic fw-medium">Login to write a review</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <!-- end reviews -->
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