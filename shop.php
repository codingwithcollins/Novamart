<?php
session_start();
include('db_connection.php');  // Include your DB connection

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);

if (!$is_logged_in) {
    header('Location: login.php');
    exit();
}

// Handle the search and filter parameters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : 10000;

// Pagination
$limit = 12;  // Products per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query to fetch products based on filters and search
$query = "SELECT * FROM products WHERE quantity_in_stock > 0 
          AND (name LIKE '%$search%' OR description LIKE '%$search%') 
          AND (original_price BETWEEN $min_price AND $max_price)
          AND (category_id LIKE '%$category_filter%') 
          ORDER BY name ASC LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $query);

// Pagination controls
$total_query = "SELECT COUNT(*) FROM products WHERE quantity_in_stock > 0";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_array($total_result);
$total_products = $total_row[0];
$total_pages = ceil($total_products / $limit);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>NovaMart - Shop</title>
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

    <style>
        .search-filter {
            margin-bottom: 20px;
        }
        .search-filter form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .search-filter input, .search-filter select, .search-filter button {
            flex: 1;
            min-width: 150px;
        }
        .product-listings {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .product-card {
    border: 1px solid #ddd;
    padding: 15px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
    width: calc(25% - 20px); /* Four cards per row with spacing */
    min-width: 250px; /* Ensures the card doesn't get too narrow */
    box-sizing: border-box;
}
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-card img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .product-card h3 {
            font-size: 1.25rem;
            margin-bottom: 10px;
        }
        .product-card p {
            margin-bottom: 10px;
        }
        .product-card .original-price {
            text-decoration: line-through;
            color: #888;
        }
        .product-card .stock-alert {
            color: red;
            font-weight: bold;
        }
        .product-card form {
            display: inline-block;
            margin-right: 10px;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            margin: 0 5px;
            padding: 5px 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
        }
        .pagination a.active {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }
        .recommended-products {
            margin-top: 40px;
        }
        .recommended-products h3 {
            margin-bottom: 20px;
        }
        .wishlist-icon {
            color: #ccc;
            cursor: pointer;
        }
        .wishlist-icon.active {
            color: red;
        }
    </style>
</head>
<body>

<!-- Header Section -->
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
                        <a class="nav-link" href="wishlist.php">
                            <i class="fas fa-heart"></i>
                            <span id="wishlist-count" class="badge bg-danger">
                                <?php echo $_SESSION['wishlist_count'] ?? 0; ?>
                            </span>
                        </a>
                    </li>
                            <li class="nav-item">
                    <a class="nav-link" href="cart.php">
                      <i class="fas fa-cart-shopping"></i>
                      <span id="cart-count" class="badge bg-danger"><?php echo $_SESSION['cart_count'] ?? 0; ?></span>
                    </a>
                  </li>
                  <!-- Profile Icon -->
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
        </div>
    </div>
</header>

<!-- Search and Filter Section -->
<section class="search-filter container my-4">
    <form method="GET" action="shop.php">
        <input type="text" name="search" placeholder="Search products..." value="<?php echo $search; ?>" class="form-control">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <form method="GET" action="shop.php" class="mt-3">
        <label>Category:</label>
        <select name="category" class="form-select">
            <option value="">All Categories</option>
            <?php
            // Populate categories from database
            $categories_query = "SELECT * FROM categories";
            $categories_result = mysqli_query($conn, $categories_query);
            while ($category = mysqli_fetch_assoc($categories_result)) {
                echo "<option value='{$category['id']}'" . ($category['id'] == $category_filter ? ' selected' : '') . ">{$category['name']}</option>";
            }
            ?>
        </select>

        <label>Price Range:</label>
        <input type="number" name="min_price" placeholder="Min Price" value="<?php echo $min_price; ?>" class="form-control">
        <input type="number" name="max_price" placeholder="Max Price" value="<?php echo $max_price; ?>" class="form-control">

        <button type="submit" class="btn btn-primary mt-3">Filter</button>
    </form>
</section>

<!-- Product Listings Section -->
<section class="product-listings container">
    <div class="row">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="product-card">
                    <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>" class="product-image">
                    <h3><?php echo $row['name']; ?></h3>
                    <p><?php echo $row['description']; ?></p>
                    <p><strong>Price:</strong> $<?php echo $row['discounted_price']; ?> 
                        <span class="original-price">$<?php echo $row['original_price']; ?></span>
                    </p>

                    <!-- Stock Alert -->
                    <?php if ($row['quantity_in_stock'] <= 3): ?>
                        <p class="stock-alert">Only <?php echo $row['quantity_in_stock']; ?> left in stock!</p>
                    <?php endif; ?>

                    <!-- Button Area -->
                    <div class="button-area p-3 pt-0">
                        <div class="row g-1 mt-2">
                            <div class="col-4">
                                <button class="btn btn-primary w-100 add-to-cart" data-product-id="<?php echo $row['id']; ?>">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-secondary w-100 add-to-wishlist" data-product-id="<?php echo $row['id']; ?>">
                                    <i class="fas fa-heart wishlist-icon"></i>
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-outline-info w-100 quick-view-btn" data-id="<?php echo $row['id']; ?>">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Ratings and Reviews -->
                    <p><strong>Rating:</strong> <?php echo $row['rating']; ?> ★</p>
                    <a href="product_reviews.php?product_id=<?php echo $row['id']; ?>">View Reviews</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<!-- Pagination -->
<div class="pagination container">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="shop.php?page=<?php echo $i; ?>&search=<?php echo $search; ?>&category=<?php echo $category_filter; ?>&min_price=<?php echo $min_price; ?>&max_price=<?php echo $max_price; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>
</div>

<!-- Recommended Products Section -->
<section class="recommended-products container">
    <h3>Recommended for You</h3>
    <div class="row">
        <?php
        // Fetch featured products (as an example)
        $recommended_query = "SELECT * FROM products WHERE is_featured = 1 LIMIT 4";
        $recommended_result = mysqli_query($conn, $recommended_query);
        while ($recommended = mysqli_fetch_assoc($recommended_result)) {
            echo "<div class='col-md-6 col-lg-4 col-xl-3'>
                    <div class='product-card'>
                        <img src='{$recommended['image_url']}' alt='{$recommended['name']}' class='product-image'>
                        <h3>{$recommended['name']}</h3>
                        <p>\${$recommended['discounted_price']}</p>
                        <div class='button-area p-3 pt-0'>
                            <div class='row g-1 mt-2'>
                                <div class='col-4'>
                                    <button class='btn btn-primary w-100 add-to-cart' data-product-id='{$recommended['id']}'>
                                        <i class='fas fa-cart-plus'></i>
                                    </button>
                                </div>
                                <div class='col-4'>
                                    <button class='btn btn-outline-secondary w-100 add-to-wishlist' data-product-id='{$recommended['id']}'>
                                        <i class='fas fa-heart wishlist-icon'></i>
                                    </button>
                                </div>
                                <div class='col-4'>
                                    <button class='btn btn-outline-info w-100 quick-view-btn' data-id='{$recommended['id']}'>
                                        <i class='fas fa-eye'></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <p><strong>Rating:</strong> {$recommended['rating']} ★</p>
                        <a href='product_reviews.php?product_id={$recommended['id']}'>View Reviews</a>
                    </div>
                </div>";
        }
        ?>
    </div>
</section>

<!-- Footer Section -->
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
                <p class="small mb-0"><i class="fas fa-map-marker-alt"></i> 123 Nova Street, Nairobi, Kenya</p>
            </div>
        </div>

        <hr class="border-light">

        <div class="row">
            <!-- Copyright -->
            <div class="col-md-6">
                <p class="mb-0 small">&copy; 2025 NovaMart. All rights reserved.</p>
            </div>

            <!-- Social Media -->
            <div class="col-md-6 text-md-end">
                <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-light"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </div>
</footer>

<!-- Toast Notification -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="cartToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">NovaMart</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Item added to cart!
        </div>
    </div>
</div>

<script src="js/jquery-1.11.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script src="js/plugins.js"></script>
<script src="js/script.js"></script>
<script>
$(document).ready(function() {
    $('.add-to-cart').click(function() {
        var product_id = $(this).data('product-id');
        var quantity = 1;  // Default quantity, modify as per your need
        
        console.log('Sending to server:', { product_id: product_id, quantity: quantity });

        $.ajax({
            url: 'add_to_cart.php',
            type: 'POST',
            data: {
                product_id: product_id,
                quantity: quantity
            },
            dataType: 'json',
            success: function(response) {
                console.log('Server response:', response);  // Log server response for debugging
                if (response.status === 'success') {
                    $('#cart-count').text(response.cart_count);
                    $('#cartToast').toast('show');  // Show toast notification
                } else {
                    alert('Failed to add to cart: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);  // Log any AJAX errors
                alert('An error occurred. Please try again.');
            }
        });
    });

    $('.add-to-wishlist').click(function() {
        var product_id = $(this).data('product-id');
        var $icon = $(this).find('.wishlist-icon');

        $.ajax({
            url: 'add_to_wishlist.php',
            type: 'POST',
            data: {
                product_id: product_id
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $icon.toggleClass('active');
                } else {
                    alert('Failed to add to wishlist: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                alert('An error occurred. Please try again.');
            }
        });
    });
});
</script>
</body>
</html>
