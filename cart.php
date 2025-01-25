<?php 
session_start();
include('db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items for the logged-in user
$cart_query = "SELECT cart.cart_id, cart.quantity, products.name, products.image_url, products.discounted_price 
               FROM cart 
               JOIN products ON cart.product_id = products.id 
               WHERE cart.user_id = ?";
$stmt = $conn->prepare($cart_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_result = $stmt->get_result();

$total_price = 0;
$cart_items = [];
while ($row = $cart_result->fetch_assoc()) {
    $total_price += $row['discounted_price'] * $row['quantity'];
    $cart_items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cart - NovaMart</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        .cart {
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .cart .title {
            margin-bottom: 20px;
        }
        .cart .main {
            padding: 10px 0;
        }
        .cart .main img {
            max-width: 100%;
            border-radius: 5px;
        }
        .cart .main .col {
            display: flex;
            align-items: center;
        }
        .cart .main .col-2 {
            flex: 0 0 80px;
        }
        .cart .main .col span {
            margin: 0 10px;
        }
        .cart .main .close {
            cursor: pointer;
            color: #ff0000;
        }
        .summary {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .summary h5 {
            margin-bottom: 20px;
        }
        .summary .row {
            margin-bottom: 10px;
        }
        .summary .btn {
            width: 100%;
        }
        .back-to-shop {
            margin-top: 20px;
        }
        .back-to-shop a {
            text-decoration: none;
            color: #007bff;
        }
        .wishlist-btn {
            background: none;
            border: none;
            color: #ccc;
            cursor: pointer;
        }
        .wishlist-btn.active {
            color: red;
        }
        .text-danger {
            color: red !important;
        }
        .quantity-control {
            display: flex;
            align-items: center;
        }
        .quantity-control button {
            background: none;
            border: none;
            font-size: 1.2rem;
            padding: 0 10px;
            cursor: pointer;
        }
        .quantity-control input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 0 5px;
        }
    </style>
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
                  <!-- Profile Icon -->
                  <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#userInfoModal">
                      <i class="fas fa-user"></i>
                    </a>
                  </li>
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
<div class="container my-5">
    <div class="card">
        <div class="row">
            <!-- Cart Items Section -->
            <div class="col-md-8 cart">
                <div class="title">
                    <div class="row">
                        <div class="col"><h4><b>Shopping Cart</b></h4></div>
                        <div class="col align-self-center text-right text-muted">
                            <?php echo count($cart_items); ?> items
                        </div>
                    </div>
                </div>
                <?php if (!empty($cart_items)): ?>
                    <?php foreach ($cart_items as $item): ?>
                        <div class="row border-top border-bottom">
                            <div class="row main align-items-center">
                                <div class="col-2">
                                    <img class="img-fluid" src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>">
                                </div>
                                <div class="col">
                                    <div class="row"><?php echo $item['name']; ?></div>
                                </div>
                                <div class="col">
                                    <div class="quantity-control">
                                        <button class="update-quantity" data-cart-id="<?php echo $item['cart_id']; ?>" data-action="decrement">-</button>
                                        <input type="text" value="<?php echo $item['quantity']; ?>" readonly>
                                        <button class="update-quantity" data-cart-id="<?php echo $item['cart_id']; ?>" data-action="increment">+</button>
                                    </div>
                                </div>
                                <div class="col">
                                    &euro;<?php echo number_format($item['discounted_price'] * $item['quantity'], 2); ?>
                                    <span class="close remove-item" data-cart-id="<?php echo $item['cart_id']; ?>">&#10005;</span>
                                </div>
                                <div class="col">
                                    <button class="btn wishlist-btn" data-product-id="<?php echo $item['cart_id']; ?>">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="back-to-shop"><a href="shop.php">&leftarrow;</a><span class="text-muted">Back to shop</span></div>
                <?php else: ?>
                    <p>Your cart is empty.</p>
                <?php endif; ?>
            </div>

            <!-- Summary Section -->
            <div class="col-md-4 summary">
                <div><h5><b>Summary</b></h5></div>
                <hr>
                <div class="row">
                    <div class="col">ITEMS <?php echo count($cart_items); ?></div>
                    <div class="col text-right">&euro;<?php echo number_format($total_price, 2); ?></div>
                </div>
                <form>
                    <p>SHIPPING</p>
                    <select>
                        <option class="text-muted">Standard-Delivery- &euro;5.00</option>
                    </select>
                    <p>GIVE CODE</p>
                    <input id="code" placeholder="Enter your code">
                </form>
                <div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 2vh 0;">
                    <div class="col">TOTAL PRICE</div>
                    <div class="col text-right">&euro;<?php echo number_format($total_price + 5, 2); ?></div>
                </div>
                <a href="checkout.php" class="btn btn-primary">CHECKOUT</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/jquery-1.11.0.min.js"></script>
<script>
$(document).ready(function () {
    // Update quantity
    $('.update-quantity').click(function (e) {
        e.preventDefault();
        const cartId = $(this).data('cart-id');
        const action = $(this).data('action');
        const $input = $(this).siblings('input');

        $.post('update_cart.php', {cart_id: cartId, action: action}, function (response) {
            if (response.status === 'success') {
                if (action === 'increment') {
                    $input.val(parseInt($input.val()) + 1);
                } else if (action === 'decrement' && parseInt($input.val()) > 1) {
                    $input.val(parseInt($input.val()) - 1);
                }
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        }, 'json').fail(function (xhr, status, error) {
            console.error('AJAX error:', error);
            alert('An error occurred. Please try again.');
        });
    });

    // Remove item
    $('.remove-item').click(function () {
        const cartId = $(this).data('cart-id');
        $.post('remove_from_cart.php', {cart_id: cartId}, function (response) {
            if (response.status === 'success') {
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        }, 'json').fail(function (xhr, status, error) {
            console.error('AJAX error:', error);
            alert('An error occurred. Please try again.');
        });
    });

    // Add to wishlist
    $('.wishlist-btn').click(function () {
        var button = $(this);
        var product_id = button.data('product-id');

        $.ajax({
            url: 'add_to_wishlist.php',
            type: 'POST',
            data: { product_id: product_id },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    // Toggle the color of the wishlist icon
                    button.find('i').toggleClass('text-danger');
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', error);
                alert('An error occurred. Please try again.');
            }
        });
    });
});
</script>
</body>
</html>
