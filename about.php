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
  <?php getHeader("About") ?>
  <!-- end header section -->

  <main id="about-page" class="animate__fadeIn">
    <!-- about section -->
    <section class="about_section layout_padding">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <div class="img-box">
              <img src="images/about-img.jpg" alt="About Us Image">
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
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- end about section -->

    <!-- Shop's Story Section -->
    <section class="shop_history_section col_rev_responsive about_section layout_padding">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <div class="detail-box">
              <div class="heading_container">
                <h2>
                  Our Story
                </h2>
              </div>
              <p>
                Founded in 2005 by <span class="owner-name">Ashborn</span>, Crystal Whispers creates jewelry that
                embodies
                elegance and timeless beauty, carrying stories that touch hearts.
              </p>
              <p>
                We've achieved significant milestones, collaborating with renowned designers and expanding collections,
                earning trust worldwide.
              </p>
              <p>
                Crystal Whispers reflects our commitment to crafting artful pieces with stories to tell. Explore our
                collections and join our journey in fine jewelry.
              </p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="img-box">
              <img src="images/about-history.png" alt="Our History">
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- end Shop's Story Section -->

    <!-- Mission and Values Section -->
    <section class="mission_values_section about_section layout_padding">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <div class="img-box">
              <img src="images/mission-values.png" alt="Mission and Values">
            </div>
          </div>
          <div class="col-md-6">
            <div class="detail-box">
              <div class="heading_container">
                <h2>Mission and Values</h2>
              </div>
              <p>
                At <span class="shop-name">Crystal Whispers</span>, our mission is to craft jewelry that embodies
                elegance, resonates with emotions, and inspires timeless
                beauty.
              </p>
              <p>
                We are committed to exceeding customer expectations with quality craftsmanship, ethical practices, and a
                passion for excellence.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--End Mission and Values Section -->

    <!-- Craftsmanship Section -->
    <section class="craftsmanship_section col_rev_responsive about_section layout_padding">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <div class="detail-box">
              <div class="heading_container">
                <h2>
                  Craftsmanship
                </h2>
              </div>
              <p>
                Our jewelry is a testament to the exceptional level of craftsmanship and artistry that we pour into each
                piece. Our artisans are masters in their craft, using intricate techniques to bring our designs to life.
              </p>
              <p>
                Every gemstone is carefully selected, and every detail is meticulously crafted, ensuring that each piece
                is a unique work of art. We take pride in delivering the highest quality jewelry that reflects elegance
                and timeless beauty.
              </p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="img-box">
              <img src="images/craftsmanship.jpeg" alt="Craftsmanship">
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End Craftsmanship Section -->

    <!-- Artisans and Team Section -->
    <section class="artisans_team_section about_section layout_padding">
      <div class="container">
        <div class="detail-box">
          <h2>Artisans and Team</h2>
          <p>
            At <span class="shop-name">Crystal Whispers</span>, our team consists of passionate and skilled artisans and
            designers who breathe life into
            every jewelry piece. Meet the creative minds behind our elegant designs and exquisite craftsmanship.
          </p>
          <div class="team-members team-carousel">
            <?php
            $workersData = fetchWorkerDetails();
            if (!empty ($workersData)):
              foreach ($workersData as $worker): ?>
                <div class="team-member">
                  <div class="img-container">
                    <div class="img-inner">
                      <div class="inner-skew"
                        style="background-repeat: no-repeat;background-size: cover;object-fit: contain;background-image: url(images/workers/<?= $worker['image'] ?>);">
                        <span data-position="<?= htmlspecialchars($worker['position']) ?>"></span>
                        <!-- <img src=""> -->
                      </div>
                    </div>
                  </div>
                  <div class="text-container">
                    <h3>
                      <?= $worker['name'] ?>
                    </h3>
                    <div>
                      <?= $worker['description'] ?>
                    </div>
                  </div>
                </div>
              <?php endforeach;
            else: ?>
              <p>No workers data available.</p>
            <?php endif; ?>
          </div>
          <div class="d-flex gap-2 justify-content-center align-items-center">
            <button class="prev-button simple-btn" onclick="prevSlide()">
              <i class="fa fa-long-arrow-left" aria-hidden="true"></i>
              <span class="sr-only">Previous</span>
            </button>
            <button class="next-button simple-btn" onclick="nextSlide()">
              <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
              <span class="sr-only">Next</span>
            </button>
          </div>
        </div>
      </div>
    </section>
    <!-- Artisans and Team Section -->

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
            if (!empty ($shopReviewData)):
              foreach ($shopReviewData as $shopReview):
                ?>
                <div class="carousel-item <?= ($i == 0 ? 'active' : '') ?> ">
                  <div class="row">
                    <div class="col-md-11 col-lg-10 mx-auto">
                      <div class="box">
                        <div class="img-box">
                          <img src="images/users/<?= $shopReview['UserImage'] ?>">
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

            else:
              ?>
              <div class="carousel-item active">
                <div class="row">
                  <div class="col-md-11 col-lg-10 mx-auto">
                    <div class="box">
                      <div class="img-box">
                        <img src="images/client.jpg" alt="Client Image 2">
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
  <?php getFooter(); ?>
  <!-- End footer -->

  <!-- Get Scripts -->
  <?php getScripts(); ?>
  <!-- End Scripts -->
</body>

</html>