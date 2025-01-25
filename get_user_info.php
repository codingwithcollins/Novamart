<?php
session_start();
include('db_connection.php');  // Include your DB connection

$response = array('status' => 'error', 'message' => 'User not logged in');

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch user information
    $user_query = "SELECT username, email FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($user_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $user_info = $user_result->fetch_assoc();

    // Fetch cart count
    $cart_query = "SELECT COUNT(*) AS cart_count FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($cart_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cart_result = $stmt->get_result();
    $cart_info = $cart_result->fetch_assoc();

    if ($user_info) {
        $response = array(
            'status' => 'success',
            'name' => $user_info['username'],
            'email' => $user_info['email'],
            'cart_count' => $cart_info['cart_count']
        );
    } else {
        $response['message'] = 'User info not found';
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
