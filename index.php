<?php
include "php/functions.php";
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
  <link rel="icon" href="assets/icon/favicon.png" type="image/gif" />
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

  addCssFiles("", $cssFiles);
  ?>
  <!-- End Styles -->

</head>

<body>

  <!-- header section starts -->
  <?php getHeader("", "public/", "Home") ?>
  <!-- end header section -->

  <main>
    <!-- display section -->
    <section class="display_section position-relative animate__fadeIn">
      <div class="display_img_container">
        <img class="display-img" src="assets/images/home/display 1.jpg" alt="display image">
        <img class="display-img" src="assets/images/home/display 3.jpg" alt="display image">
        <img class="display-img" src="assets/images/home/display 4.jpg" alt="display image">
        <img class="display-img" src="assets/images/home/display 5.jpg" alt="display image">
        <img class="display-img" src="assets/images/home/display 6.jpg" alt="display image">
      </div>
      <div class="container">
        <div class="col-md-9 col-lg-8">
          <div class="detail-box">
            <h1>
              Best Jewellery
              <br> Collection
            </h1>
            <p>
              Elevate your style with our exquisite selection of handcrafted jewelry. Explore the perfect fusion of
              artistry and sophistication that speaks to your unique elegance and grace.
            </p>
            <div>
              <a href="public/jewellery.php" class="display-link">
                Shop Now
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- end display section -->

    <!-- shop section -->
    <section class="shop_section layout_padding">
      <div class="mx-3">
        <div class="heading_container heading_center">
          <h2>
            Latest Products
          </h2>
        </div>
        <div class="row">

          <?php
          $productData = fetchProducts();
          if ($productData) :
            foreach ($productData as $product) :
          ?>
              <div class="col-sm-6 col-md-4 col-lg-3 p-3">
                <a class="text-reset" href="public/Product-details.php?pro=<?= $product['id'] ?>">
                  <div class="product-card">
                    <div class="p-badge">

                      <?php
                      $rating = $product['rating'];
                      if ($rating > 0) {
                        for ($i = 1; $i <= $rating; $i++) {
                          echo '<i class="fa fa-star"></i>';
                        }
                      } else {
                        echo '<i class="fa fa-star-o"></i>';
                      }
                      ?>

                    </div>
                    <div class="product-tumb d-flex justify-content-center align-items-center">
                      <img src="assets/images/products/<?= $product['image'] ?>" alt="Product Image">
                    </div>
                    <div class="product-details">
                      <span class="product-category">
                        <?= $product['Gender'] . ', ' . $product['category'] ?>
                      </span>
                      <h5><a href="public/Product-details.php?pro=<?= $product['id'] ?>">
                          <?= $product['name'] ?>
                        </a></h5>
                      <div class="product-bottom-details d-flex justify-content-between align-items-center">
                        <div class="product-price">
                          <small>₹
                            <?= number_format($product['price'], 2) ?>
                          </small>
                          ₹
                          <?= number_format(($product['price'] * ((100 - $product['discount']) / 100)), 2) ?>
                        </div>
                        <div class="product-links">
                          <form method="post" action="php/products/add_to_cart.php">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <button type="submit"><i class="fa fa-shopping-cart"></i></button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
          <?php
            endforeach;
          else :
            echo '
            <div class="text-center p-3">
              We apologize, but there are no products available at the moment. Please check back later or contact our support team for assistance.
            </div>';
          endif;
          ?>

        </div>
        <div class="d-flex justify-content-center align-items-center">
          <a href="public/jewellery.php" class="simple-btn">
            View All Products
          </a>
        </div>
      </div>
    </section>
    <!-- end shop section -->

    <!-- about section -->
    <section class="about_section">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <div class="img-box">
              <img src="assets/images/about/about-img.jpg" alt="About Us Image">
            </div>
          </div>
          <div class="col-md-6">
            <div class="detail-box">
              <div class="heading_container">
                <h2>
                  About Us
                </h2>
              </div>
              <p>
                Welcome to <span class="shop-name">Crystal Whispers</span>, where we are dedicated to crafting
                exceptional
                jewelry pieces that tell a story of elegance and timeless beauty. Our commitment to quality and
                craftsmanship ensures that every piece is a work of art. Discover our passion for jewelry and explore
                our
                collections today.
              </p>
              <a href="public/about.php">
                Read More
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- end about section -->

    <!-- offer section -->
    <section class="offer_section layout_padding">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-7 px-0">
            <div class="box offer-box1">
              <img src="assets/images/o1.jpg" alt="">
              <div class="detail-box">
                <h2>
                  Upto 15% Off
                </h2>
                <a href="public/jewellery.php?category=1&gender=All&material=All&occasion=All&show=All+Products&display-filter=yes">
                  Shop Now
                </a>
              </div>
            </div>
          </div>
          <div class="col-md-5 px-0">
            <div class="box offer-box2">
              <img src="assets/images/o2.jpg" alt="">
              <div class="detail-box">
                <h2>
                  Upto 10% Off
                </h2>
                <a href="public/jewellery.php?category=3&gender=All&material=All&occasion=All&show=All+Products&display-filter=yes">
                  Shop Now
                </a>
              </div>
            </div>
            <div class="box offer-box3">
              <img src="assets/images/o3.jpg" alt="">
              <div class="detail-box">
                <h2>
                  Upto 20% Off
                </h2>
                <a href="public/jewellery.php?category=6&gender=All&material=All&occasion=All&show=All+Products&display-filter=yes">
                  Shop Now
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- end offer section -->

    <!-- client section -->
    <section class="client_section layout_padding">
      <div class="container">
        <div class="heading_container">
          <h2>
            Client Testimonials
          </h2>
        </div>
        <div id="testimonialCarousel" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner">
            <?php
            $shopReviewData = fetchShopReviews();
            $i = 0;
            if (!empty($shopReviewData)) :
              foreach ($shopReviewData as $shopReview) :
            ?>
                <div class="carousel-item <?= ($i == 0 ? 'active' : '') ?> ">
                  <div class="row">
                    <div class="col-md-11 col-lg-10 mx-auto">
                      <div class="box">
                        <div class="img-box">
                          <img src="assets/images/users/<?= $shopReview['UserImage'] ?>">
                        </div>
                        <div class="detail-box">
                          <div class="name">
                            <h6>
                              <?= $shopReview['UserName'] ?>
                            </h6>
                          </div>
                          <p>
                            "
                            <?= $shopReview['review'] ?>"
                          </p>
                          <i class="fa fa-quote-left" aria-hidden="true"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php $i++;
              endforeach;

            else :
              ?>
              <div class="carousel-item active">
                <div class="row">
                  <div class="col-md-11 col-lg-10 mx-auto">
                    <div class="box">
                      <div class="img-box">
                        <img src="assets/images/client.jpg" alt="Client Image 2">
                      </div>
                      <div class="detail-box">
                        <div class="name">
                          <h6>
                            Alex Turner
                          </h6>
                        </div>
                        <p>
                          "The quality of the jewelry at Crystal Whispers is unparalleled. Each piece tells a unique
                          story, and I appreciate the artistry that goes into every creation."
                        </p>
                        <i class="fa fa-quote-left" aria-hidden="true"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>

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
    </section>
    <!-- end client section -->
  </main>

  <!-- Footer -->
  <?php getFooter(""); ?>
  <!-- End footer -->

  <!-- Get Scripts -->
  <?php
  $jsFiles = array(
    'jquery-3.4.1.min.js',
    'bootstrap.js',
    'bootstrap.bundle.js',
    'custom.js'
  );

  addJsFiles("", $jsFiles);
  ?>
  <!-- End Scripts -->
  <script>
    // Display Image Carousal
    const intervalTime = 5000;
    let currentImageIndex = 0;

    const images = document.querySelectorAll(".display-img");
    const container = document.querySelector(".display_img_container");

    function showNextImage() {
      images[currentImageIndex].style.display = "none";
      currentImageIndex = (currentImageIndex + 1) % images.length;
      images[currentImageIndex].style.display = "block";

      setTimeout(() => {
        container.style.backgroundImage = `url('${images[currentImageIndex].src}')`;
      }, 2500);
    }
    setInterval(showNextImage, intervalTime);

    images[currentImageIndex].style.display = "block";
    container.style.backgroundImage = `url('${images[currentImageIndex].src}')`;
    // End Display Image Carousal
  </script>

</body>

</html>