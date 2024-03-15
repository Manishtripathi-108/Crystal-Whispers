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
  <?php getHeader("Jewellery") ?>
  <!-- end header section -->

  <main id="jewellery-page" class="animate__fadeIn">
    <!-- Filter -->
    <div id="filter-bar" class="animate__slideDown pt-4">
      <div class="container-fluid">
        <nav class="navbar navbar-expand-lg custom_nav-container">
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#filter"
            aria-controls="filter" aria-expanded="false" aria-label="Toggle filter">
            <span class="navbar-toggler-icon"></span>
          </button>

          <a class="navbar-brand">
            <h2>
              Filter
            </h2>
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
                  <select name="gender" class="form-select" style="width: 200px;" id="Gender">
                    <option>All</option>
                    <option value="Male" <?php echo (isset($_GET['gender']) && $_GET['gender'] == 'Male') ? 'selected'
                      : ''; ?>>Male</option>
                    <option value="Female" <?php echo (isset($_GET['gender']) && $_GET['gender'] == 'Female')
                      ? 'selected' : ''; ?>>Female</option>
                  </select>
                  <label for="Gender">Gender</label>
                </div>

                <!-- Material -->
                <div class="form-floating">
                  <select name="material" class="form-select" style="width: 200px;" id="Material">
                    <option>All</option>
                    <option value="Gold" <?php echo (isset($_GET['material']) && $_GET['material'] == 'Gold')
                      ? 'selected' : ''; ?>>Gold</option>
                    <option value="Silver" <?php echo (isset($_GET['material']) && $_GET['material'] == 'Silver')
                      ? 'selected' : ''; ?>>Silver</option>
                    <option value="Platinum" <?php echo (isset($_GET['material']) && $_GET['material'] == 'Platinum')
                      ? 'selected' : ''; ?>>Platinum</option>
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

                    $occ_result->close();
                    ?>
                  </select>
                  <label for="occasion">Occasion</label>
                </div>

                <!-- display -->
                <div class="form-floating">
                  <select name="OrderBy" class="form-select" style="width: 200px;" id="OrderBy">
                    <option>All Products</option>
                    <option value="1" <?php echo (isset($_GET['OrderBy']) && $_GET['OrderBy'] == '1') ? 'selected' : ''; ?>>
                      Latest Products</option>
                    <option value="2" <?php echo (isset($_GET['OrderBy']) && $_GET['OrderBy'] == '2') ? 'selected' : ''; ?>>
                      Bestsellers</option>
                    <option value="3" <?php echo (isset($_GET['OrderBy']) && $_GET['OrderBy'] == '3') ? 'selected' : ''; ?>>
                      Discount</option>
                    <option value="4" <?php echo (isset($_GET['OrderBy']) && $_GET['OrderBy'] == '4') ? 'selected' : ''; ?>>
                      Top Rated</option>
                  </select>
                  <label for="OrderBy">Order By</label>
                </div>

                <input name="display-filter" type="hidden" value="yes">

                <div class="form-floating">
                  <button class="simple-btn" type="submit" title="filter">
                    Filter
                  </button>
                  <button class="simple-btn" type="submit" title="Reset Filter">
                    <a class="text-reset" href="jewellery.php">Reset</a>
                  </button>
                </div>

              </div>
            </div>
          </form>
        </nav>
      </div>
    </div>
    <!-- End Filter -->

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['display-filter'])) {
      $category = isset($_GET['category']) ? $_GET['category'] : 'All';
      $gender = isset($_GET['gender']) ? $_GET['gender'] : 'All';
      $material = isset($_GET['material']) ? $_GET['material'] : 'All';
      $occasion = isset($_GET['occasion']) ? $_GET['occasion'] : 'All';
      $OrderBy = isset($_GET['OrderBy']) ? $_GET['OrderBy'] : '0';

      // $sql = "SELECT
      //       p.product_id,
      //       p.product_name,
      //       p.gender,
      //       p.price,
      //       p.discount,
      //       p.material,
      //       p.occasion,
      //       i.img_1,
      //       c.category_name AS category
      //   FROM
      //       products p
      //   JOIN
      //       product_img i ON p.product_id = i.img_to_pro
      //   JOIN
      //       categories c ON p.category = c.ID
      //   WHERE 1";

      // if ($category !== 'All') {
      //   $sql .= " AND p.category = '$category'";
      // }

      // if ($gender !== 'All') {
      //   $sql .= " AND p.gender = '$gender'";
      // }

      // if ($material !== 'All') {
      //   $sql .= " AND p.material = '$material'";
      // }

      // if ($occasion !== 'All') {
      //   $sql .= " AND p.occasion = '$occasion'";
      // }

      // $sql .= " ORDER BY ";

      // switch ($OrderBy) {
      //   case '1':
      //     $sql .= "p.date_added DESC";
      //     break;
      //   case '2':
      //     $sql .= "p.units_sold DESC";
      //     break;
      //   default:
      //     $sql .= "p.date_added DESC";
      // }

      // $sql .= " LIMIT 50";
      // $result = $conn->query($sql);

      echo '
        <section class="shop_section layout_padding">
          <div class="mx-5">
            <div class="heading_container heading_center">
              <h2>
                Filtered :
              </h2>
            </div>
            <div class="row">';

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $getRating = "SELECT AVG(rating) AS rating FROM reviews WHERE product_id=" . $row['product_id'];
          $rating = $conn->query($getRating);
          $rating = $rating->fetch_assoc();
          $rating = round($rating['rating']);
          echo '
            <div class="col-sm-6 col-md-4 col-lg-3 p-3">
              <a class="text-reset" href="Product-details.php?pro=' . $row['product_id'] . '">
                <div class="product-card">
                <div class="p-badge">';

          if ($rating > 0) {
            for ($i = 1; $i <= $rating; $i++) {
              echo '<i class="fa fa-star"></i>';
            }
          } else {
            echo '<i class="fa fa-star-o"></i>';
          }

          echo '
            </div>
            <div class="product-tumb d-flex justify-content-center align-items-center">
              <img 
                src="images/products/' . $row['img_1'] . '" 
                alt="Product Image">
            </div>
            <div class="product-details">
              <span class="product-category">' . $row['gender'] . ',' . $row['category'] . '</span>
              <h5><div >' . $row['product_name'] . '</div></h5>
              <div class="product-bottom-details d-flex justify-content-between align-items-center">
                <div class="product-price">
                <small>₹' . number_format($row['price'], 2) . '</small>
                ₹' . number_format(($row['price'] * ((100 - $row['discount']) / 100)), 2) .
            '</div>
                <div class="product-links">
                  <form method="post" action="php/add_to_cart.php">
                    <input type="hidden" name="product_id" value="' . $row['product_id'] . '">
                    <button type="submit"><i class="fa fa-shopping-cart"></i></button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          </a>
        </div>';
        }

        echo '        
          </div>
      </div>
    </section>';
      } else {
        echo "We apologize, but there are no products available based on the selected filters.";
      }

      $result->close();
    } else {
      echo '
            <!-- Latest section -->
            <section id="latest" class="shop_section layout_padding">
              <div class="mx-5">
                <div class="heading_container heading_center">
                  <h2>
                    Latest Products
                  </h2>
                </div>
                <div class="row">';

      $sql = "SELECT
                    p.product_id,
                    p.product_name,
                    p.gender,
                    p.price,
                    p.discount,
                    i.img_1,
                    c.category_name AS category
                    FROM
                      products p
                    JOIN
                      product_img i ON p.product_id = i.img_to_pro
                    JOIN
                      categories c ON p.category = c.ID
                    ORDER BY 
                    p.date_added DESC
                  LIMIT 16";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $getRating = "SELECT AVG(rating) AS rating FROM reviews WHERE product_id=" . $row['product_id'];
          $rating = $conn->query($getRating);
          $rating = $rating->fetch_assoc();
          $rating = round($rating['rating']);
          echo '
                          <div class="col-sm-6 col-md-4 col-lg-3 p-3">
                          <a class="text-reset" href="Product-details.php?pro=' . $row['product_id'] . '">
                            <div class="product-card">
                              <div class="p-badge">';

          if ($rating > 0) {
            for ($i = 1; $i <= $rating; $i++) {
              echo '<i class="fa fa-star"></i>';
            }
          } else {
            echo '<i class="fa fa-star-o"></i>';
          }

          echo '
                      </div>
                      <div class="product-tumb d-flex justify-content-center align-items-center">
                        <img 
                          src="images/products/' . $row['img_1'] . '" 
                          alt="Product Image">
                      </div>
                      <div class="product-details">
                        <span class="product-category">' . $row['gender'] . ',' . $row['category'] . '</span>
                        <h5><a href="Product-details.php?pro=' . $row['product_id'] . '">' . $row['product_name'] . '</a></h5>
                        <div class="product-bottom-details d-flex justify-content-between align-items-center">
                          <div class="product-price">
                          <small>₹' . number_format($row['price'], 2) . '</small>
                          ₹' . number_format(($row['price'] * ((100 - $row['discount']) / 100)), 2) .
            '</div>
                          <div class="product-links">
                            <form method="post" action="php/add_to_cart.php">
                              <input type="hidden" name="product_id" value="' . $row['product_id'] . '">
                              <button type="submit"><i class="fa fa-shopping-cart"></i></button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                    </a>
                  </div>';
        }
      } else {
        echo "We apologize, but there are no products available at the moment. Please check back later or contact our support team for assistance.";
      }

      $result->close();
      echo '
        </div>
      </div>
            </section>

            <!-- Bestsellers -->
            <section id="bestseller" class="shop_section layout_padding">
              <div class="mx-5">
                <div class="heading_container heading_center">
                  <h2>
                    Bestsellers
                  </h2>
                </div>
                <div class="row">';


      $sql = "SELECT
                  p.product_id,
                  p.product_name,
                  p.gender,
                  p.price,
                  p.discount,
                  i.img_1,
                  c.category_name AS category
                  FROM
                    products p
                  JOIN
                    product_img i ON p.product_id = i.img_to_pro
                  JOIN
                    categories c ON p.category = c.ID
                  ORDER BY 
                  p.units_sold DESC
                LIMIT 16";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $getRating = "SELECT AVG(rating) AS rating FROM reviews WHERE product_id=" . $row['product_id'];
          $rating = $conn->query($getRating);
          $rating = $rating->fetch_assoc();
          $rating = round($rating['rating']);
          echo '
                        <div class="col-sm-6 col-md-4 col-lg-3 p-3">
                        <a class="text-reset" href="Product-details.php?pro=' . $row['product_id'] . '">
                          <div class="product-card">
                            <div class="p-badge">';

          if ($rating > 0) {
            for ($i = 1; $i <= $rating; $i++) {
              echo '<i class="fa fa-star"></i>';
            }
          } else {
            echo '<i class="fa fa-star-o"></i>';
          }

          echo '
                    </div>
                    <div class="product-tumb d-flex justify-content-center align-items-center">
                      <img 
                        src="images/products/' . $row['img_1'] . '" 
                        alt="Product Image">
                    </div>
                    <div class="product-details">
                      <span class="product-category">' . $row['gender'] . ',' . $row['category'] . '</span>
                      <h5><a href="Product-details.php?pro=' . $row['product_id'] . '">' . $row['product_name'] . '</a></h5>
                      <div class="product-bottom-details d-flex justify-content-between align-items-center">
                        <div class="product-price">
                        <small>₹' . number_format($row['price'], 2) . '</small>
                        ₹' . number_format(($row['price'] * ((100 - $row['discount']) / 100)), 2) .
            '</div>
                        <div class="product-links">
                          <form method="post" action="php/add_to_cart.php">
                            <input type="hidden" name="product_id" value="' . $row['product_id'] . '">
                            <button type="submit"><i class="fa fa-shopping-cart"></i></button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  </a>
                </div>';
        }
      } else {
        echo "We apologize, but there are no products available at the moment. Please check back later or contact our support team for assistance.";
      }
      $result->close();
      echo '
                </div>
              </div>
            </section>
              ';
    }
    ?>
  </main>

  <!-- Footer -->
  <?php getFooter(); ?>
  <!-- End footer -->

  <!-- Get Scripts -->
  <?php getScripts(); ?>
  <!-- End Scripts -->

</body>

</html>