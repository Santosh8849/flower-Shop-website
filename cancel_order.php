<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
   exit();
}

if(isset($_GET['order_id'])){
    $order_id = $_GET['order_id'];

    // Check if the order exists and is pending
    $check_order = mysqli_query($conn, "SELECT * FROM `orders` WHERE id = '$order_id' AND user_id = '$user_id' AND payment_status = 'pending'") or die('query failed');
    
    if(mysqli_num_rows($check_order) > 0){
        // Update the order status to canceled
        $update_order = mysqli_query($conn, "UPDATE `orders` SET payment_status = 'Canceled' WHERE id = '$order_id'") or die('query failed');
        echo "<script>alert('Order has been canceled!');</script>";
        header('location:orders.php');
    } else {
        echo "<script>alert('Order cannot be canceled!');</script>";
        header('location:orders.php');
    }

} else {
    echo "<script>alert('No order ID specified!');</script>";
    header('location:orders.php');
}
?>
