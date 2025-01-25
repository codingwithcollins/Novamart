<?php
session_start();
include('db_connection.php'); // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'You must be logged in.']);
        exit();
    }

    $wishlist_id = $_POST['wishlist_id'];

    // Delete the item from the wishlist
    $query = "DELETE FROM wishlist WHERE wishlist_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $wishlist_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Item removed from wishlist.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to remove item.']);
    }

    $stmt->close();
    $conn->close();
}
?>
