<?php
session_start();
include('db_connection.php');  // Include your DB connection

$response = array('status' => 'error', 'message' => 'Invalid request');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'User not logged in';
    echo json_encode($response);
    exit();
}

// Check if cart_id is sent
if (isset($_POST['cart_id'])) {
    $cart_id = intval($_POST['cart_id']);
    $user_id = $_SESSION['user_id'];  // Assuming user ID is stored in session

    // Remove the item from the cart
    $delete_query = "DELETE FROM cart WHERE cart_id = ? AND user_id = ?";
    $stmt = $conn->prepare($delete_query);
    if (!$stmt) {
        $response['message'] = 'Prepare failed: ' . $conn->error;
        echo json_encode($response);
        exit();
    }
    $stmt->bind_param("ii", $cart_id, $user_id);
    if (!$stmt->execute()) {
        $response['message'] = 'Execute failed: ' . $stmt->error;
        echo json_encode($response);
        exit();
    }

    // Calculate the new total price
    $total_query = "SELECT SUM(products.discounted_price * cart.quantity) AS total_price 
                    FROM cart 
                    JOIN products ON cart.product_id = products.id 
                    WHERE cart.user_id = ?";
    $stmt = $conn->prepare($total_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total_price = $row['total_price'];

    // Respond with success and updated total price
    $response = array('status' => 'success', 'total_price' => $total_price);
}

header('Content-Type: application/json');
echo json_encode($response);
?>
