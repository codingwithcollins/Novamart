<?php
session_start();
include('db_connection.php'); // Include the database connection

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch wishlist items for logged-in user
$user_id = $_SESSION['user_id'];
$query = "SELECT wishlist.id, products.name AS product_name, products.price AS product_price
          FROM wishlist
          INNER JOIN products ON wishlist.product_id = products.product_id
          WHERE wishlist.user_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$wishlist_items = [];

while ($row = $result->fetch_assoc()) {
    $wishlist_items[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
</head>
<body>
    <h1>Your Wishlist</h1>
    <?php if (empty($wishlist_items)): ?>
        <p>Your wishlist is empty.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($wishlist_items as $item): ?>
                <li>
                    <strong><?php echo htmlspecialchars($item['product_name']); ?></strong> - $<?php echo number_format($item['product_price'], 2); ?>
                    <button class="remove-btn" data-wishlist-id="<?php echo $item['id']; ?>">Remove</button>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
