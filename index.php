<?php
session_start();
include('db_connection.php');  // Include your DB connection

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>NovaMart - Your Online Grocery Store</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/vendor.css">
    <link rel="stylesheet" type="text/css" href="style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Open+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  </head>
  <body>

    <header>
      <div class="container-fluid">
        <div class="row py-3 border-bottom">
          <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
            <div class="container">
              <img src="images/logo.png" alt="NovaMart logo" class="img-fluid" style="max-width: 150px; height: auto;">
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="index.php">Home</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="shop.php">Shop</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#about">About</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="cart.php">
                      <i class="fas fa-cart-shopping"></i>
                      <span id="cart-count" class="badge bg-danger"><?php echo $_SESSION['cart_count'] ?? 0; ?></span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="wishlist.php">
                      <i class="fas fa-heart"></i>
                      <span id="wishlist-count" class="badge bg-danger"><?php echo $_SESSION['wishlist_count'] ?? 0; ?></span>
                    </a>
                  </li>
                  <?php if ($is_logged_in): ?>
                    <!-- Profile Icon -->
                    <li class="nav-item">
                      <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#userInfoModal">
                        <i class="fas fa-user"></i>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                  <?php else: ?>
                    <li class="nav-item">
                      <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="register.php">Signup</a>
                    </li>
                  <?php endif; ?>
                </ul>
              </div>
            </div>
          </nav>
          <div class="col-sm-8 col-lg-2 d-flex gap-5 align-items-center justify-content-center justify-content-sm-end">
            <ul class="d-flex justify-content-end list-unstyled m-0">
              <li>
                <a href="#" class="p-2 mx-1">
                  <svg width="24" height="24"><use xlink:href="#user"></use></svg>
                </a>
              </li>
              <li>
                <a href="#" class="p-2 mx-1">
                  <svg width="24" height="24"><use xlink:href="#wishlist"></use></svg>
                </a>
              </li>
              <li>
                <a href="#" class="p-2 mx-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
                  <svg width="24" height="24"><use xlink:href="#shopping-bag"></use></svg>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </header>
    
    <!-- User Info Modal -->
    <div class="modal fade" id="userInfoModal" tabindex="-1" aria-labelledby="userInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userInfoModalLabel">User Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="user-info-content">
                    <!-- User info will be injected here by JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="cartToast" class="toast align-items-center text-bg-success" role="alert" aria-live="assertive" aria-atomic="true" style="min-width: 300px;">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check-circle"></i> Item added to cart!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <section style="background-image: url('images/banner-1.jpg');background-repeat: no-repeat;background-size: cover;">
      <div class="container-lg">
        <div class="row">
          <div class="col-lg-6 pt-5 mt-5">
            <h2 class="display-1 ls-1"><span class="fw-bold text-primary">NovaMart</span> - Your Online <span class="fw-bold">Grocery Store</span></h2>
            <p class="fs-4">Your one-stop shop for all your needs.</p>
            <div class="d-flex gap-3">
              <a href="#" class="btn btn-primary text-uppercase fs-6 rounded-pill px-4 py-3 mt-3">Start Shopping</a>
              <a href="#" class="btn btn-dark text-uppercase fs-6 rounded-pill px-4 py-3 mt-3">Join Now</a>
            </div>
            <div class="row my-5">
              <div class="col">
                <div class="row text-dark">
                  <div class="col-auto"><p class="fs-1 fw-bold lh-sm mb-0">1k+</p></div>
                  <div class="col"><p class="text-uppercase lh-sm mb-0">Product Varieties</p></div>
                </div>
              </div>
              <div class="col">
                <div class="row text-dark">
                  <div class="col-auto"><p class="fs-1 fw-bold lh-sm mb-0">10k+</p></div>
                  <div class="col"><p class="text-uppercase lh-sm mb-0">Happy Customers</p></div>
                </div>
              </div>
              <div class="col">
                <div class="row text-dark">
                  <div class="col-auto"><p class="fs-1 fw-bold lh-sm mb-0">10+</p></div>
                  <div class="col"><p class="text-uppercase lh-sm mb-0">Store Locations</p></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="row row-cols-1 row-cols-sm-3 row-cols-lg-3 g-0 justify-content-center">
          <!-- Fresh from Farm -->
          <div class="col">
            <div class="card border-0 bg-primary rounded-0 p-4 text-light">
              <div class="row">
                <div class="col-md-3 text-center">
                  <i class="fas fa-seedling fa-3x"></i> <!-- Fresh Icon -->
                </div>
                <div class="col-md-9">
                  <div class="card-body p-0">
                    <h5 class="text-light">Fresh from Farm</h5>
                    <p class="card-text">
                      Get the freshest produce sourced directly from local farmers. Delivered straight to your door.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        
          <!-- 100% Organic -->
          <div class="col">
            <div class="card border-0 bg-secondary rounded-0 p-4 text-light">
              <div class="row">
                <div class="col-md-3 text-center">
                  <i class="fas fa-leaf fa-3x"></i> <!-- Organic Icon -->
                </div>
                <div class="col-md-9">
                  <div class="card-body p-0">
                    <h5 class="text-light">100% Organic</h5>
                    <p class="card-text">
                      Shop with confidence knowing all our products are certified organic and free from harmful chemicals.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        
          <!-- Free Delivery -->
          <div class="col">
            <div class="card border-0 bg-danger rounded-0 p-4 text-light">
              <div class="row">
                <div class="col-md-3 text-center">
                  <i class="fas fa-truck fa-3x"></i> <!-- Delivery Icon -->
                </div>
                <div class="col-md-9">
                  <div class="card-body p-0">
                    <h5 class="text-light">Free Delivery</h5>
                    <p class="card-text">
                      Enjoy free delivery on every order, ensuring a hassle-free shopping experience from start to finish.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </section>
    <section class="py-5 overflow-hidden">
      <div class="container-lg">
        <div class="row">
          <div class="col-md-12">
            <div class="section-header d-flex flex-wrap justify-content-between mb-5">
              <h2 class="section-title">Category</h2>
              <div class="d-flex align-items-center">
                <a href="#" class="btn btn-primary me-2">View All</a>
                <div class="swiper-buttons">
                  <button class="swiper-prev category-carousel-prev btn btn-yellow">❮</button>
                  <button class="swiper-next category-carousel-next btn btn-yellow">❯</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Category carousel -->
        <div class="row">
          <div class="col-md-12">
            <div class="category-carousel swiper">
              <div class="swiper-wrapper">
                <?php 
                // Query to get categories
                $category_query = "SELECT * FROM categories"; 
                $category_result = mysqli_query($conn, $category_query);

                // Check if there are categories
                if (mysqli_num_rows($category_result) > 0): 
                  while ($category_row = mysqli_fetch_assoc($category_result)): ?>
                    <a href="category.php?id=<?php echo $category_row['id']; ?>" class="nav-link swiper-slide text-center">
                      <img src="images/<?php echo htmlspecialchars($category_row['image']); ?>" class="rounded-circle" alt="Category Thumbnail">
                      <h4 class="fs-6 mt-3 fw-normal category-title"><?php echo htmlspecialchars($category_row['name']); ?></h4>
                    </a>
                  <?php endwhile; 
                else: ?>
                  <p>No categories found.</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Best Selling Products Section -->
    <section class="pb-5">
      <div class="container-lg">
        <div class="row">
          <div class="col-md-12">
            <div class="section-header d-flex flex-wrap justify-content-between my-4">
              <h2 class="section-title">Best selling products</h2>
              <div class="d-flex align-items-center">
                <a href="#" class="btn btn-primary rounded-1">View All</a>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5">
              <?php
              // Query to get the best selling products
              $best_selling_query = "SELECT * FROM products WHERE is_best_selling = 1 LIMIT 20"; 
              $best_selling_result = mysqli_query($conn, $best_selling_query);

              // Check if products were found
              if (mysqli_num_rows($best_selling_result) > 0) {
                while ($product_row = mysqli_fetch_assoc($best_selling_result)) {
                  // Fetch product data
                  $product_id = $product_row['id'];
                  $product_name = $product_row['name'];
                  $product_price = $product_row['discounted_price']; // Use discounted_price
                  $product_discount = $product_row['discount_percentage']; // Use discount_percentage
                  $product_image = $product_row['image_url']; // Use image_url
                  $product_old_price = $product_row['original_price']; // Use original_price
                  $product_rating = $product_row['rating'];
                  $product_reviews = $product_row['reviews_count'];

                  // Display the product
                  echo "
                  <div class='col'>
                    <div class='product-item' style='height: 100%;'>
                      <figure>
                        <a href='product_detail.php?id=$product_id' title='$product_name'>
                          <img src='$product_image' alt='$product_name' class='tab-image'>
                        </a>
                      </figure>
                      <div class='d-flex flex-column text-center'>
                        <h3 class='fs-6 fw-normal'>$product_name</h3>
                        <div>
                          <span class='rating'>";
                  
                          // Display the rating stars
                          for ($i = 0; $i < 5; $i++) {
                            $star_class = ($i < $product_rating) ? 'text-warning' : 'text-muted';
                            echo "<svg width='18' height='18' class='$star_class'><use xlink:href='#star-full'></use></svg>";
                          }

                  echo "
                          </span>
                          <span>($product_reviews)</span>
                        </div>
                        <div class='d-flex justify-content-center align-items-center gap-2'>
                          <del>$$product_old_price</del>
                          <span class='text-dark fw-semibold'>$$product_price</span>";

                          // Show discount if applicable
                          if ($product_discount > 0) {
                            echo "<span class='badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary'>{$product_discount}% OFF</span>";
                          }

                  echo "
                        </div>
                        <div class='button-area p-3 pt-0'>
                          <div class='row g-1 mt-2'>
                            <div class='col-3'><input type='number' name='quantity' class='form-control border-dark-subtle input-number quantity' value='1'></div>
                            <div class='col-7'><a href='#' class='btn btn-primary rounded-1 p-2 fs-7 btn-cart'><svg width='18' height='18'><use xlink:href='#cart'></use></svg> Add to Cart</a></div>
                            <div class='col-2'><a href='#' class='btn btn-outline-dark rounded-1 p-2 fs-6'><svg width='18' height='18'><use xlink:href='#heart'></use></svg></a></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>";
                }
              } else {
                echo "<p>No products found.</p>";
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Just Arrived Products Section -->
    <section id="latest-products" class="products-carousel">
      <div class="container-lg overflow-hidden pb-5">
        <div class="row">
          <div class="col-md-12">
            <div class="section-header d-flex justify-content-between my-4">
              <h2 class="section-title">Just arrived</h2>
              <div class="d-flex align-items-center">
                <a href="#" class="btn btn-primary me-2">View All</a>
                <div class="swiper-buttons">
                  <button class="swiper-prev products-carousel-prev btn btn-primary">❮</button>
                  <button class="swiper-next products-carousel-next btn btn-primary">❯</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="swiper">
              <div class="swiper-wrapper">
                <?php
                // Query to fetch just arrived products
                $just_arrived_query = "SELECT * FROM products WHERE is_just_arrived = 1 ORDER BY created_at DESC LIMIT 5"; 
                $just_arrived_result = mysqli_query($conn, $just_arrived_query);

                // Check if there are products in the result
                if (mysqli_num_rows($just_arrived_result) > 0) {
                    // Loop through the results and display each product
                    while ($product = mysqli_fetch_assoc($just_arrived_result)) {
                        $productId = $product['id'];
                        $productName = $product['name'];
                        $productImage = $product['image_url']; // Ensure this matches the column name in the database
                        $productPrice = $product['discounted_price']; // Ensure this matches the column name in the database
                        $productOldPrice = $product['original_price']; // Ensure this matches the column name in the database
                        $productDiscount = $product['discount_percentage']; // Use discount_percentage
                        $productRating = $product['rating']; // Use rating
                        $productReviews = $product['reviews_count']; // Use reviews_count

                        // Display the product
                        echo "
                        <div class='product-item swiper-slide' style='height: 100%;'>
                            <figure>
                                <a href='product_detail.php?id=$productId' title='" . htmlspecialchars($productName) . "'>
                                    <img src='$productImage' alt='Product Thumbnail' class='tab-image'>
                                </a>
                            </figure>
                            <div class='d-flex flex-column text-center'>
                                <h3 class='fs-6 fw-normal'>" . htmlspecialchars($productName) . "</h3>
                                <div>
                                    <span class='rating'>";
                        
                                    // Display rating stars based on the product rating (example: 5-star rating)
                                    for ($i = 0; $i < 5; $i++) {
                                        if ($i < $productRating) {
                                            echo '<svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>';
                                        } else {
                                            echo '<svg width="18" height="18" class="text-warning"><use xlink:href="#star-empty"></use></svg>';
                                        }
                                    }

                        echo "
                                    </span>
                                    <span>($productReviews)</span>
                                </div>
                                <div class='d-flex justify-content-center align-items-center gap-2'>
                                    <del>$" . number_format($productOldPrice, 2) . "</del>
                                    <span class='text-dark fw-semibold'>$" . number_format($productPrice, 2) . "</span>
                                    <span class='badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary'>{$productDiscount}% OFF</span>
                                </div>
                                <div class='button-area p-3 pt-0'>
                                    <div class='row g-1 mt-2'>
                                        <div class='col-3'><input type='number' name='quantity' class='form-control border-dark-subtle input-number quantity' value='1'></div>
                                        <div class='col-7'><a href='#' class='btn btn-primary rounded-1 p-2 fs-7 btn-cart'><svg width='18' height='18'><use xlink:href='#cart'></use></svg> Add to Cart</a></div>
                                        <div class='col-2'><a href='#' class='btn btn-outline-dark rounded-1 p-2 fs-6'><svg width='18' height='18'><use xlink:href='#heart'></use></svg></a></div>
                                    </div>
                                </div>
                            </div>
                        </div>";
                    }
                } else {
                    // If no just arrived products are found
                    echo "<p>No just arrived products found.</p>";
                }
                ?>
              </div>
            </div>
            <!-- / products-carousel -->
          </div>
        </div>
      </div>
    </section>

    <section class="py-3">
      <div class="container-lg">
        <div class="row">
          <div class="col-12 col-md-4">
            <div class="banner-ad d-flex align-items-center large bg-info block-1" style="background: url('images/banner-ad-1.jpg') no-repeat; background-size: cover;">
              <div class="banner-content p-5">
                <div class="content-wrapper text-light">
                  <h3 class="banner-title text-light">Items on SALE</h3>
                  <p>Discounts up to 30%</p>
                  <a href="#" class="btn-link text-white">Shop Now</a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-md-4">
            <div class="banner-ad bg-success-subtle block-2" style="background:url('images/banner-ad-2.jpg') no-repeat;background-size: cover;">
              <div class="banner-content align-items-center p-5">
                <div class="content-wrapper text-light">
                  <h3 class="banner-title text-light">Combo Offers</h3>
                  <p>Discounts up to 50%</p>
                  <a href="#" class="btn-link text-white">Shop Now</a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-md-4">
            <div class="banner-ad bg-danger block-3" style="background:url('images/banner-ad-3.jpg') no-repeat;background-size: cover;">
              <div class="banner-content align-items-center p-5">
                <div class="content-wrapper text-light">
                  <h3 class="banner-title text-light">Discount Coupons</h3>
                  <p>Discounts up to 40%</p>
                  <a href="#" class="btn-link text-white">Shop Now</a>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!--featured products-->
    <section id="featured-products" class="products-carousel"> 
      <div class="container-lg overflow-hidden py-5">
        <div class="row">
          <div class="col-md-12">

            <div class="section-header d-flex flex-wrap justify-content-between my-4">
              <h2 class="section-title">Featured products</h2>

              <div class="d-flex align-items-center">
                <a href="products.php" class="btn btn-primary me-2">View All</a>
                <div class="swiper-buttons">
                  <button class="swiper-prev products-carousel-prev btn btn-primary">❮</button>
                  <button class="swiper-next products-carousel-next btn btn-primary">❯</button>
                </div>  
              </div>
            </div>
            
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">

            <div class="swiper">
              <div class="swiper-wrapper">

                <?php
                // Connect to the database
                include('db_connection.php');
                
                // Query to fetch featured products
                $query = "SELECT * FROM products WHERE is_featured = 1 LIMIT 10"; // Use is_featured
                $result = mysqli_query($conn, $query);
                
                // Check if there are products in the result
                if (mysqli_num_rows($result) > 0) {
                  // Loop through the results and display each product
                  while ($row = mysqli_fetch_assoc($result)) {
                    $product_id = $row['id'];
                    $product_name = $row['name'];
                    $product_image = $row['image_url']; // Use image_url
                    $product_price = $row['discounted_price']; // Use discounted_price
                    $product_old_price = $row['original_price']; // Use original_price
                    $product_rating = $row['rating']; // Use rating
                    $product_reviews = $row['reviews_count']; // Use reviews_count

                    // Display the product
                    echo "
                    <div class='product-item swiper-slide' style='height: 100%;'>
                      <figure>
                        <a href='product_detail.php?id=$product_id' title='" . htmlspecialchars($product_name) . "'>
                          <img src='$product_image' alt='Product Thumbnail' class='tab-image'>
                        </a>
                      </figure>
                      <div class='d-flex flex-column text-center'>
                        <h3 class='fs-6 fw-normal'>" . htmlspecialchars($product_name) . "</h3>
                        <div>
                          <span class='rating'>";
                  
                          // Display rating stars based on the product rating (example: 5-star rating)
                          for ($i = 0; $i < 5; $i++) {
                            if ($i < $product_rating) {
                              echo '<svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>';
                            } else {
                              echo '<svg width="18" height="18" class="text-warning"><use xlink:href="#star-empty"></use></svg>';
                            }
                          }

                  echo "
                          </span>
                          <span>($product_reviews)</span>
                        </div>
                        <div class='d-flex justify-content-center align-items-center gap-2'>
                          <del>$" . number_format($product_old_price, 2) . "</del>
                          <span class='text-dark fw-semibold'>$" . number_format($product_price, 2) . "</span>
                          <span class='badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary'>10% OFF</span>
                        </div>
                        <div class='button-area p-3 pt-0'>
                          <div class='row g-1 mt-2'>
                            <div class='col-3'><input type='number' name='quantity' class='form-control border-dark-subtle input-number quantity' value='1'></div>
                            <div class='col-7'><a href='#' class='btn btn-primary rounded-1 p-2 fs-7 btn-cart'><svg width='18' height='18'><use xlink:href='#cart'></use></svg> Add to Cart</a></div>
                            <div class='col-2'><a href='#' class='btn btn-outline-dark rounded-1 p-2 fs-6'><svg width='18' height='18'><use xlink:href='#heart'></use></svg></a></div>
                          </div>
                        </div>
                      </div>
                    </div>";
                  }
                } else {
                  // If no featured products are found
                  echo "<p>No featured products found.</p>";
                }

                // Close the database connection
                mysqli_close($conn);
                ?>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!--newsletter-->
    <section>
      <div class="container-lg">
        <div class="bg-secondary text-light py-5 my-5" style="background: url('images/banner-newsletter.jpg') no-repeat; background-size: cover;">
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-md-5 p-3">
                <div class="section-header">
                  <h2 class="section-title display-5 text-light">Get 25% Discount on your first purchase</h2>
                </div>
                <p>Just Sign Up & Register it now to become member.</p>
              </div>
              <div class="col-md-5 p-3">
                <form>
                  <div class="mb-3">
                    <label for="name" class="form-label d-none">Name</label>
                    <input type="text"
                      class="form-control form-control-md rounded-0" name="name" id="name" placeholder="Name">
                  </div>
                  <div class="mb-3">
                    <label for="email" class="form-label d-none">Email</label>
                    <input type="email" class="form-control form-control-md rounded-0" name="email" id="email" placeholder="Email Address">
                  </div>
                  <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-dark btn-md rounded-0">Submit</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!--popular products -->
    <section id="popular-products" class="products-carousel">
      <div class="container-lg overflow-hidden py-5">
        <div class="row">
          <div class="col-md-12">
            <div class="section-header d-flex justify-content-between my-4">
              <h2 class="section-title">Most popular products</h2>
              <div class="d-flex align-items-center">
                <a href="#" class="btn btn-primary me-2">View All</a>
                <div class="swiper-buttons">
                  <button class="swiper-prev products-carousel-prev btn btn-primary">❮</button>
                  <button class="swiper-next products-carousel-next btn btn-primary">❯</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="swiper">
              <div class="swiper-wrapper">
                
                <?php
                // Fetch and display each product dynamically
                while ($product = mysqli_fetch_assoc($result)) {
                    $productId = $product['id']; // assuming the product has an 'id' column
                    $productName = $product['name'];
                    $productImage = $product['image']; // assuming image path is stored in 'image' column
                    $productPrice = $product['price'];
                    $productDiscountPrice = $product['discount_price'];
                    $productRating = $product['rating']; // assuming rating is out of 5
                    $productReviews = $product['reviews']; // number of reviews
                ?>
                
                <div class="product-item swiper-slide" style="height: 100%;">
                  <figure>
                    <a href="product.php?id=<?php echo $productId; ?>" title="<?php echo $productName; ?>">
                      <img src="images/<?php echo $productImage; ?>" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal"><?php echo $productName; ?></h3>
                    <div>
                      <span class="rating">
                        <?php
                        // Output stars based on product rating (5-star scale)
                        for ($i = 0; $i < floor($productRating); $i++) {
                            echo '<svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>';
                        }
                        if ($productRating - floor($productRating) >= 0.5) {
                            echo '<svg width="18" height="18" class="text-warning"><use xlink:href="#star-half"></use></svg>';
                        }
                        ?>
                      </span>
                      <span>(<?php echo $productReviews; ?>)</span>
                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <del>$<?php echo number_format($productPrice, 2); ?></del>
                      <span class="text-dark fw-semibold">$<?php echo number_format($productDiscountPrice, 2); ?></span>
                      <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span>
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                        <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                      </div>
                    </div>
                  </div>
                </div>
        
                <?php } ?>
                
              </div>
            </div>
          </div>
        </div>
        
                <div class="product-item swiper-slide" style="height: 100%;">
                  <?php while($product = mysqli_fetch_assoc($result)): ?>
                    <figure>
                      <a href="product_detail.php?id=<?php echo $product['id']; ?>" title="<?php echo $product['name']; ?>">
                        <img src="images/<?php echo $product['image']; ?>" alt="Product Thumbnail" class="tab-image">
                      </a>
                    </figure>
                    <div class="d-flex flex-column text-center">
                      <h3 class="fs-6 fw-normal"><?php echo $product['name']; ?></h3>
                      <div>
                        <span class="rating">
                          <!-- Assuming ratings are stored in the database -->
                          <?php for ($i = 0; $i < round($product['rating']); $i++): ?>
                            <svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>
                          <?php endfor; ?>
                        </span>
                        <span>(<?php echo $product['reviews_count']; ?>)</span>
                      </div>
                      <div class="d-flex justify-content-center align-items-center gap-2">
                        <del>$<?php echo number_format($product['original_price'], 2); ?></del>
                        <span class="text-dark fw-semibold">$<?php echo number_format($product['price'], 2); ?></span>
                        <?php if ($product['discount'] > 0): ?>
                          <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary"><?php echo $product['discount']; ?>% OFF</span>
                        <?php endif; ?>
                      </div>
                      <div class="button-area p-3 pt-0">
                        <div class="row g-1 mt-2">
                          <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                          <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                          <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                        </div>
                      </div>
                    </div>
                  <?php endwhile; ?>
                </div>
                  
              </div>
            </div>
            <!-- / products-carousel -->

          </div>
        </div>
      </div>
    </section>

    <!--lates products-->
    <section id="latest-products" class="products-carousel">
      <div class="container-lg overflow-hidden pb-5">
        <div class="row">
          <div class="col-md-12">
            <div class="section-header d-flex justify-content-between my-4">
              <h2 class="section-title">Just arrived</h2>
              <div class="d-flex align-items-center">
                <a href="#" class="btn btn-primary me-2">View All</a>
                <div class="swiper-buttons">
                  <button class="swiper-prev products-carousel-prev btn btn-primary">❮</button>
                  <button class="swiper-next products-carousel-next btn btn-primary">❯</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="swiper">
              <div class="swiper-wrapper">
                <?php
                // Include database connection
                include('db_connection.php');
    
                // Query to fetch products
                $query = "SELECT * FROM products ORDER BY created_at DESC LIMIT 5"; // Use created_at
                $result = mysqli_query($conn, $query);
    
                // Loop through products and display them
                if (mysqli_num_rows($result) > 0) {
                  while ($product = mysqli_fetch_assoc($result)) {
                    $productId = $product['id'];
                    $productName = $product['name'];
                    $productPrice = $product['price'];
                    $productImage = $product['image']; // Assuming you store the image path in 'image' column
                    $productOldPrice = $product['old_price']; // If you have an old price for discounts
                    $productDiscount = $product['discount']; // Assuming there's a discount column
                    $productRating = $product['rating']; // Assuming you store ratings
                    ?>
                    <div class="product-item swiper-slide" style="height: 100%;">
                      <figure>
                        <a href="product_detail.php?id=<?php echo $productId; ?>" title="<?php echo $productName; ?>">
                          <img src="<?php echo $productImage; ?>" alt="Product Thumbnail" class="tab-image">
                        </a>
                      </figure>
                      <div class="d-flex flex-column text-center">
                        <h3 class="fs-6 fw-normal"><?php echo $productName; ?></h3>
                        <div>
                          <span class="rating">
                            <?php
                            // Generate star rating
                            for ($i = 1; $i <= 5; $i++) {
                              if ($i <= $productRating) {
                                echo '<svg width="18" height="18" class="text-warning"><use xlink:href="#star-full"></use></svg>';
                              } else {
                                echo '<svg width="18" height="18" class="text-warning"><use xlink:href="#star-empty"></use></svg>';
                              }
                            }
                            ?>
                          </span>
                          <span>(<?php echo $productRating; ?>)</span>
                        </div>
                        <div class="d-flex justify-content-center align-items-center gap-2">
                          <?php if ($productOldPrice): ?>
                            <del>$<?php echo number_format($productOldPrice, 2); ?></del>
                          <?php endif; ?>
                          <span class="text-dark fw-semibold">$<?php echo number_format($productPrice, 2); ?></span>
                          <?php if ($productDiscount): ?>
                            <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary"><?php echo $productDiscount; ?>% OFF</span>
                          <?php endif; ?>
                        </div>
                        <div class="button-area p-3 pt-0">
                          <div class="row g-1 mt-2">
                            <div class="col-3"><input type="number" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1"></div>
                            <div class="col-7"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"><svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                            <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php
                  }
                } else {
                  echo '<p>No products found.</p>';
                }
                ?>
              </div>
            </div>
            <!-- / products-carousel -->
          </div>
        </div>
      </div>
    </section>

    <!-- Our Latest Blog Section -->
    <section id="latest-blog" class="pb-4">
      <div class="container-lg">
        <div class="row">
          <div class="section-header d-flex align-items-center justify-content-between my-4">
            <h2 class="section-title">Our Latest Blog</h2>
            <a href="#" class="btn btn-primary">View All</a>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <article class="post-item card border-0 shadow-sm p-3">
              <div class="image-holder zoom-effect">
                <a href="#">
                  <img src="images/post-thumbnail-1.jpg" alt="post" class="card-img-top">
                </a>
              </div>
              <div class="card-body">
                <div class="post-meta d-flex text-uppercase gap-3 my-2 align-items-center">
                  <div class="meta-date"><svg width="16" height="16"><use xlink:href="#calendar"></use></svg> 22 Jan 2025</div>
                  <div class="meta-categories"><svg width="16" height="16"><use xlink:href="#category"></use></svg> Healthy Eating</div>
                </div>
                <div class="post-header">
                  <h3 class="post-title">
                    <a href="#" class="text-decoration-none">5 Essential Superfoods You Should Add to Your Grocery List</a>
                  </h3>
                  <p>Discover the top five superfoods that can improve your health and should be part of every grocery shopping list.</p>
                </div>
              </div>
            </article>
          </div>
          <div class="col-md-4">
            <article class="post-item card border-0 shadow-sm p-3">
              <div class="image-holder zoom-effect">
                <a href="#">
                  <img src="images/post-thumbnail-2.jpg" alt="post" class="card-img-top">
                </a>
              </div>
              <div class="card-body">
                <div class="post-meta d-flex text-uppercase gap-3 my-2 align-items-center">
                  <div class="meta-date"><svg width="16" height="16"><use xlink:href="#calendar"></use></svg> 20 Jan 2025</div>
                  <div class="meta-categories"><svg width="16" height="16"><use xlink:href="#category"></use></svg> Grocery Shopping Tips</div>
                </div>
                <div class="post-header">
                  <h3 class="post-title">
                    <a href="#" class="text-decoration-none">How to Shop Smart: Tips for Saving on Your Next Grocery Trip</a>
                  </h3>
                  <p>Learn how to plan your grocery shopping trips to save time and money with these simple and effective tips.</p>
                </div>
              </div>
            </article>
          </div>
          <div class="col-md-4">
            <article class="post-item card border-0 shadow-sm p-3">
              <div class="image-holder zoom-effect">
                <a href="#">
                  <img src="images/post-thumbnail-3.jpg" alt="post" class="card-img-top">
                </a>
              </div>
              <div class="card-body">
                <div class="post-meta d-flex text-uppercase gap-3 my-2 align-items-center">
                  <div class="meta-date"><svg width="16" height="16"><use xlink:href="#calendar"></use></svg> 18 Jan 2025</div>
                  <div class="meta-categories"><svg width="16" height="16"><use xlink:href="#category"></use></svg> Organic Foods</div>
                </div>
                <div class="post-header">
                  <h3 class="post-title">
                    <a href="#" class="text-decoration-none">The Benefits of Going Organic: Why You Should Choose Organic Groceries</a>
                  </h3>
                  <p>Organic food isn't just healthier, but it's also better for the environment. Here are the key reasons why you should switch to organic groceries.</p>
                </div>
              </div>
            </article>
          </div>
        </div>
      </div>
    </section>

    <section class="py-4">
      <div class="container-lg">
        <h2 class="my-4">People are also looking for</h2>
        <a href="#" class="btn btn-warning me-2 mb-2">Blue diamon almonds</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Angie’s Boomchickapop Corn</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Salty kettle Corn</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Chobani Greek Yogurt</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Sweet Vanilla Yogurt</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Foster Farms Takeout Crispy wings</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Warrior Blend Organic</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Chao Cheese Creamy</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Chicken meatballs</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Blue diamon almonds</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Angie’s Boomchickapop Corn</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Salty kettle Corn</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Chobani Greek Yogurt</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Sweet Vanilla Yogurt</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Foster Farms Takeout Crispy wings</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Warrior Blend Organic</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Chao Cheese Creamy</a>
        <a href="#" class="btn btn-warning me-2 mb-2">Chicken meatballs</a>
      </div>
    </section>

    <section class="py-5 bg-light">
      <div class="container-lg">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">
          <div class="col">
            <div class="card shadow-sm border-0 p-3">
              <div class="text-primary mb-3">
                <i class="fas fa-truck fa-2x"></i>
              </div>
              <div class="card-body p-0">
                <h5 class="fw-bold">Free Delivery</h5>
                <p class="card-text text-muted">Enjoy fast and free delivery on all orders, no minimum required.</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card shadow-sm border-0 p-3">
              <div class="text-primary mb-3">
                <i class="fas fa-lock fa-2x"></i>
              </div>
              <div class="card-body p-0">
                <h5 class="fw-bold">100% Secure Payment</h5>
                <p class="card-text text-muted">Shop with confidence, all transactions are fully encrypted and secure.</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card shadow-sm border-0 p-3">
              <div class="text-primary mb-3">
                <i class="fas fa-certificate fa-2x"></i>
              </div>
              <div class="card-body p-0">
                <h5 class="fw-bold">Quality Guarantee</h5>
                <p class="card-text text-muted">We guarantee top-quality products that meet the highest standards.</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card shadow-sm border-0 p-3">
              <div class="text-primary mb-3">
                <i class="fas fa-tags fa-2x"></i>
              </div>
              <div class="card-body p-0">
                <h5 class="fw-bold">Guaranteed Savings</h5>
                <p class="card-text text-muted">Save big with our exclusive deals and promotions, every day.</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card shadow-sm border-0 p-3">
              <div class="text-primary mb-3">
                <i class="fas fa-gift fa-2x"></i>
              </div>
              <div class="card-body p-0"></div></div>
                <h5 class="fw-bold">Daily Offers</h5>
                <p class="card-text text-muted">Get access to new offers every day, just for you!</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer class="footer bg-dark text-light py-4">
      <div class="container">
        <div class="row">
          <!-- Company Info -->
          <div class="col-md-4 mb-3">
            <h5 class="text-uppercase fw-bold">About NovaMart</h5>
            <p class="small">
              NovaMart is your trusted online grocery store, delivering fresh, organic, and high-quality products right to your doorstep.
            </p>
          </div>
    
          <!-- Quick Links -->
          <div class="col-md-4 mb-3">
            <h5 class="text-uppercase fw-bold">Quick Links</h5>
            <ul class="list-unstyled">
              <li><a href="#" class="text-light text-decoration-none">Home</a></li>
              <li><a href="#" class="text-light text-decoration-none">Shop</a></li>
              <li><a href="#" class="text-light text-decoration-none">About Us</a></li>
              <li><a href="#" class="text-light text-decoration-none">Contact</a></li>
            </ul>
          </div>
    
          <!-- Contact Info -->
          <div class="col-md-4 mb-3">
            <h5 class="text-uppercase fw-bold">Contact Us</h5>
            <p class="small mb-1"><i class="fas fa-phone-alt"></i> +123 456 7890</p>
            <p class="small mb-1"><i class="fas fa-envelope"></i> support@novamart.com</p>
            <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
            <a href="#" class="text-light"><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>
      </div>
    </footer>
    
    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="js/plugins.js"></script>
    <script src="js/script.js"></script>
    <script>
    $(document).ready(function() {
        // When the profile icon is clicked
        $('.nav-link[data-bs-target="#userInfoModal"]').click(function() {
            $.ajax({
                url: 'get_user_info.php', // Create this PHP file to return user info
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Insert user info into modal
                        var userInfo = `
                            <p><strong>Name:</strong> ${response.name}</p>
                            <p><strong>Email:</strong> ${response.email}</p>
                            <p><strong>Cart Count:</strong> ${response.cart_count}</p>
                        `;
                        $('#user-info-content').html(userInfo);

                        // Show the modal
                        $('#userInfoModal').modal('show');
                    } else {
                        alert('User info not found');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('An error occurred while fetching user info');
                }
            });
        });

        // Function to show the toast notification
        function showToast() {
            var toast = new bootstrap.Toast(document.getElementById('cartToast'));
            toast.show();
        }

        // Add the success message after adding item to the cart
        $(document).on('click', '.add-to-cart', function() {
            var productId = $(this).data('product-id');
            var quantity = 1;  // Assuming quantity is 1 for now

            $.ajax({
                url: 'add_to_cart.php',
                type: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                success: function(response) {
                    if (response.status === 'success') {
                        // Show the toast message
                        showToast();
                        // Optionally, update cart count dynamically
                        $('#cart-count').text(response.cart_count);
                    } else {
                        alert('Failed to add item to cart');
                    }
                }
            });
        });
    });
    </script>
  </body>
</html>