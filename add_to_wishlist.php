<?php
session_start();
include('db_connection.php'); // Ensure the correct path

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Something went wrong'];

try {
    // Ensure the request method is POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_SESSION['user_id'], $_POST['product_id'])) {
            $user_id = intval($_SESSION['user_id']);
            $product_id = intval($_POST['product_id']);
            
            // Check if the user exists
            $user_check_query = "SELECT * FROM users WHERE user_id = ?";
            $stmt = $conn->prepare($user_check_query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $user_result = $stmt->get_result();

            if ($user_result->num_rows === 0) {
                $response['message'] = 'User does not exist.';
            } else {
                // Check if product already exists in wishlist
                $check_query = "SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?";
                $stmt = $conn->prepare($check_query);
                $stmt->bind_param("ii", $user_id, $product_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 0) {
                    // Insert into wishlist
                    $insert_query = "INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)";
                    $stmt = $conn->prepare($insert_query);
                    $stmt->bind_param("ii", $user_id, $product_id);
                    if ($stmt->execute()) {
                        $response = ['status' => 'success', 'message' => 'Item added to wishlist'];
                    } else {
                        $response['message'] = 'Failed to add item to wishlist.';
                    }
                } else {
                    $response = ['status' => 'success', 'message' => 'Item already in wishlist'];
                }
            }
        } else {
            $response['message'] = 'Invalid request. Ensure user is logged in and product ID is provided.';
        }
    } else {
        $response['message'] = 'Invalid request method.';
    }
} catch (Exception $e) {
    $response['message'] = 'An error occurred: ' . $e->getMessage();
}

echo json_encode($response);
?>
