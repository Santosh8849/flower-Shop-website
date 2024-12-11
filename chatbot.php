<?php
@include 'config.php';  // Include your existing database config

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Retrieve incoming user message
$data = json_decode(file_get_contents("php://input"), true);
$userMessage = strtolower($data['message']);  // Convert message to lowercase

// Define dynamic responses
$response = '';

// Example: Respond to product price requests
if (strpos($userMessage, 'price') !== false) {
    $query = "SELECT price FROM products WHERE name LIKE '%some product%'";  // Example product name
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $response = "The price of the product is ₹" . $row['price'];
    } else {
        $response = "Product not found.";
    }
}
// Example: Respond to delivery status inquiries
elseif (strpos($userMessage, 'delivery status') !== false) {
    $order_id = 1;  // Replace with actual logic to find order ID for the user
    $query = "SELECT status FROM orders WHERE id = $order_id";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $response = "Your order status is: " . $row['status'];
    } else {
        $response = "Order not found.";
    }
}
// Default response for unknown queries
else {
    $response = "I am not sure how to respond to that. Please ask about products, prices, or delivery status.";
}

// Return the response in JSON format
echo json_encode(["response" => $response]);
?>
