<?php
include "../php/functions.php";
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
  <?php getHeader("Jewellery") ?>
  <!-- end header section -->

  <main id="jewellery-page" class="animate__fadeIn">
    <!-- Filter -->
    <div id="filter-bar" class="animate__slideDown pt-4">
      <div class="container-fluid">
        <nav class="navbar navbar-expand-lg custom_nav-container">
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#filter" aria-controls="filter" aria-expanded="false" aria-label="Toggle filter">
            <span class="navbar-toggler-icon"></span>
          </button>

          <a class="navbar-brand">
            <h2>Filter</h2>
          </a>

          <form action="jewellery.php" method="get">
            <div class="collapse navbar-collapse" id="filter">
              <div class="d-flex gap-2 ms-auto flex-column flex-lg-row align-items-center flex-wrap">
                <!-- Category -->
                <div class="form-floating">
                  <select name="category" class="form-select" style="width: 200px;" id="category">
                    <option>All</option>
                    <?php
                    $sql = "SELECT * FROM categories";
                    $cat_result = $conn->query($sql);

                    if ($cat_result->num_rows > 0) {
                      while ($row = $cat_result->fetch_assoc()) {
                        if (isset($_GET['category']) && $row['ID'] == $_GET['category']) {
                          echo '<option value="' . $row['ID'] . '" selected>' . $row['category_name'] . '</option>';
                        } else {
                          echo '<option value="' . $row['ID'] . '">' . $row['category_name'] . '</option>';
                        }
                      }
                    }
                    ?>
                  </select>
                  <label for="category">Category</label>
                </div>

                <!-- Gender -->
                <div class="form-floating">
                  <select name="gender" class="form-select" style="width: 200px;" id="Gender">
                    <option>All</option>
                    <option value="Male" <?php echo (isset($_GET['gender']) && $_GET['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo (isset($_GET['gender']) && $_GET['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                  </select>
                  <label for="Gender">Gender</label>
                </div>

                <!-- Material -->
                <div class="form-floating">
                  <select name="material" class="form-select" style="width: 200px;" id="Material">
                    <option>All</option>
                    <option value="Gold" <?php echo (isset($_GET['material']) && $_GET['material'] == 'Gold') ? 'selected' : ''; ?>>Gold</option>
                    <option value="Silver" <?php echo (isset($_GET['material']) && $_GET['material'] == 'Silver') ? 'selected' : ''; ?>>Silver</option>
                    <option value="Platinum" <?php echo (isset($_GET['material']) && $_GET['material'] == 'Platinum') ? 'selected' : ''; ?>>Platinum</option>
                  </select>
                  <label for="Material">Material</label>
                </div>

                <!-- Occasion -->
                <div class="form-floating">
                  <select name="occasion" class="form-select" style="width: 200px;" id="occasion">
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
                    ?>
                  </select>
                  <label for="occasion">Occasion</label>
                </div>

                <!-- display -->
                <div class="form-floating">
                  <select name="orderBy" class="form-select" style="width: 200px;" id="orderBy">
                    <option>All Products</option>
                    <option value="1" <?php echo (isset($_GET['orderBy']) && $_GET['orderBy'] == '1') ? 'selected' : ''; ?>>Latest Products</option>
                    <option value="2" <?php echo (isset($_GET['orderBy']) && $_GET['orderBy'] == '2') ? 'selected' : ''; ?>>Bestsellers</option>
                    <option value="3" <?php echo (isset($_GET['orderBy']) && $_GET['orderBy'] == '3') ? 'selected' : ''; ?>>Discount</option>
                    <option value="4" <?php echo (isset($_GET['orderBy']) && $_GET['orderBy'] == '4') ? 'selected' : ''; ?>>Top Rated</option>
                  </select>
                  <label for="orderBy">Order By</label>
                </div>

                <input name="display-filter" type="hidden" value="yes">

                <div class="form-floating">
                  <button class="simple-btn" type="submit" title="filter">Filter</button>
                  <button class="simple-btn" type="reset" title="Reset Filter">Reset</button>
                </div>

              </div>
            </div>
          </form>
        </nav>
      </div>
    </div>
    <!-- End Filter -->

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['display-filter'])) :
      $category = isset($_GET['category']) ? $_GET['category'] : 'All';
      $gender = isset($_GET['gender']) ? $_GET['gender'] : 'All';
      $material = isset($_GET['material']) ? $_GET['material'] : 'All';
      $occasion = isset($_GET['occasion']) ? $_GET['occasion'] : 'All';
      $orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : '0';
    ?>

      <!-- Filter Section -->
      <section class="shop_section layout_padding">
        <div class="mx-5">
          <div class="heading_container heading_center">
            <h2>Filtered :</h2>
          </div>
          <div class="row">
            <?php
            $productData = fetchProducts($category, $gender, $material, $occasion, $orderBy, 50);
            if ($productData) :
              foreach ($productData as $product) :
            ?>
                <div class="col-sm-6 col-md-4 col-lg-3 p-3">
                  <a class="text-reset" href="Product-details.php?pro=<?= $product['id'] ?>">
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
                        <img src="../assets/images/products/<?= $product['image'] ?>" alt="Product Image">
                      </div>
                      <div class="product-details">
                        <span class="product-category"><?= $product['Gender'] . ', ' . $product['category'] ?></span>
                        <h5><a href="Product-details.php?pro=<?= $product['id'] ?>"><?= $product['name'] ?></a></h5>
                        <div class="product-bottom-details d-flex justify-content-between align-items-center">
                          <div class="product-price">
                            <small>₹<?= number_format($product['price'], 2) ?></small>
                            ₹<?= number_format(($product['price'] * ((100 - $product['discount']) / 100)), 2) ?>
                          </div>
                          <div class="product-links">
                            <form method="post" action="php/add_to_cart.php">
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
              echo '<div class="text-center p-3">We apologize, but there are no products available based on the selected filters.</div>';
            endif;
            ?>
          </div>
        </div>
      </section>

    <?php
    else :
    ?>

      <!-- Latest section -->
      <section id="latest" class="shop_section layout_padding">
        <div class="mx-5">
          <div class="heading_container heading_center">
            <h2>Latest Products</h2>
          </div>
          <div class="row">

            <?php
            $productData = fetchProducts('All', 'All', 'All', 'All', 1, 30);
            if ($productData) :
              foreach ($productData as $product) :
            ?>
                <div class="col-sm-6 col-md-4 col-lg-3 p-3">
                  <a class="text-reset" href="Product-details.php?pro=<?= $product['id'] ?>">
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
                        <img src="../assets/images/products/<?= $product['image'] ?>" alt="Product Image">
                      </div>
                      <div class="product-details">
                        <span class="product-category"><?= $product['Gender'] . ', ' . $product['category'] ?></span>
                        <h5><a href="Product-details.php?pro=<?= $product['id'] ?>"><?= $product['name'] ?></a></h5>
                        <div class="product-bottom-details d-flex justify-content-between align-items-center">
                          <div class="product-price">
                            <small>₹<?= number_format($product['price'], 2) ?></small>
                            ₹<?= number_format(($product['price'] * ((100 - $product['discount']) / 100)), 2) ?>
                          </div>
                          <div class="product-links">
                            <form method="post" action="php/add_to_cart.php">
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
              echo '<div class="text-center p-3">We apologize, but there are no products available at the moment. Please check back later or contact our support team for assistance.</div>';
            endif;
            ?>

          </div>
        </div>
      </section>

      <!-- Bestsellers -->
      <section id="bestseller" class="shop_section layout_padding">
        <div class="mx-5">
          <div class="heading_container heading_center">
            <h2>Bestsellers</h2>
          </div>
          <div class="row">

            <?php
            $productData = fetchProducts('All', 'All', 'All', 'All', 2, 30);
            if ($productData) :
              foreach ($productData as $product) :
            ?>
                <div class="col-sm-6 col-md-4 col-lg-3 p-3">
                  <a class="text-reset" href="Product-details.php?pro=<?= $product['id'] ?>">
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
                        <img src="../assets/images/products/<?= $product['image'] ?>" alt="Product Image">
                      </div>
                      <div class="product-details">
                        <span class="product-category"><?= $product['Gender'] . ', ' . $product['category'] ?></span>
                        <h5><a href="Product-details.php?pro=<?= $product['id'] ?>"><?= $product['name'] ?></a></h5>
                        <div class="product-bottom-details d-flex justify-content-between align-items-center">
                          <div class="product-price">
                            <small>₹<?= number_format($product['price'], 2) ?></small>
                            ₹<?= number_format(($product['price'] * ((100 - $product['discount']) / 100)), 2) ?>
                          </div>
                          <div class="product-links">
                            <form method="post" action="php/add_to_cart.php">
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
              echo '<div class="text-center p-3">We apologize, but there are no products available at the moment. Please check back later or contact our support team for assistance.</div>';
            endif;
            ?>

          </div>
        </div>
      </section>

    <?php
    endif;
    ?>
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