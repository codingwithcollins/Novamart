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

// Check if product_id and quantity are sent
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $user_id = $_SESSION['user_id'];  // Assuming user ID is stored in session

    // Log the incoming data for debugging
    error_log("Received product_id: $product_id, quantity: $quantity, user_id: $user_id");

    // Check if the product is already in the cart
    $check_query = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($check_query);
    if (!$stmt) {
        $response['message'] = 'Prepare failed: ' . $conn->error;
        error_log('Prepare failed: ' . $conn->error);  // Log error
        echo json_encode($response);
        exit();
    }
    $stmt->bind_param("ii", $user_id, $product_id);
    if (!$stmt->execute()) {
        $response['message'] = 'Execute failed: ' . $stmt->error;
        error_log('Execute failed: ' . $stmt->error);  // Log error
        echo json_encode($response);
        exit();
    }
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If the product is already in the cart, update the quantity
        $update_query = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($update_query);
        if (!$stmt) {
            $response['message'] = 'Prepare failed: ' . $conn->error;
            error_log('Prepare failed: ' . $conn->error);  // Log error
            echo json_encode($response);
            exit();
        }
        $stmt->bind_param("iii", $quantity, $user_id, $product_id);
        if (!$stmt->execute()) {
            $response['message'] = 'Execute failed: ' . $stmt->error;
            error_log('Execute failed: ' . $stmt->error);  // Log error
            echo json_encode($response);
            exit();
        }
    } else {
        // If the product is not in the cart, insert a new record
        $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        if (!$stmt) {
            $response['message'] = 'Prepare failed: ' . $conn->error;
            error_log('Prepare failed: ' . $conn->error);  // Log error
            echo json_encode($response);
            exit();
        }
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        if (!$stmt->execute()) {
            $response['message'] = 'Execute failed: ' . $stmt->error;
            error_log('Execute failed: ' . $stmt->error);  // Log error
            echo json_encode($response);
            exit();
        }
    }

    // Get the updated cart count
    $count_query = "SELECT COUNT(*) AS cart_count FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($count_query);
    if (!$stmt) {
        $response['message'] = 'Prepare failed: ' . $conn->error;
        error_log('Prepare failed: ' . $conn->error);  // Log error
        echo json_encode($response);
        exit();
    }
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        $response['message'] = 'Execute failed: ' . $stmt->error;
        error_log('Execute failed: ' . $stmt->error);  // Log error
        echo json_encode($response);
        exit();
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $cart_count = $row['cart_count'];

    // Respond with success and updated cart count
    $response = array('status' => 'success', 'cart_count' => $cart_count);
}

header('Content-Type: application/json');
echo json_encode($response);
?>
