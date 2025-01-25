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

// Check if cart_id and action are sent
if (isset($_POST['cart_id']) && isset($_POST['action'])) {
    $cart_id = intval($_POST['cart_id']);
    $action = $_POST['action'];
    $user_id = $_SESSION['user_id'];  // Assuming user ID is stored in session

    // Determine the new quantity based on the action
    if ($action === 'increment') {
        $update_query = "UPDATE cart SET quantity = quantity + 1 WHERE cart_id = ? AND user_id = ?";
    } elseif ($action === 'decrement') {
        $update_query = "UPDATE cart SET quantity = quantity - 1 WHERE cart_id = ? AND user_id = ? AND quantity > 1";
    } else {
        $response['message'] = 'Invalid action';
        echo json_encode($response);
        exit();
    }

    $stmt = $conn->prepare($update_query);
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
