<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

// Fetch user details to auto-fill the form
$user_query = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'") or die('query failed');
if (mysqli_num_rows($user_query) > 0) {
    $fetch_user = mysqli_fetch_assoc($user_query);
}

// Check if the order was successful
if (isset($_SESSION['order_successful']) && $_SESSION['order_successful']) {
    $order_successful = true;
} else {
    $order_successful = false;
}

if (isset($_POST['order'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, 'flat no. ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code']);
    $placed_on = date('d-M-Y');

    $cart_total = 0;
    $cart_products = [];

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    if (mysqli_num_rows($cart_query) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ') ';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }

    $total_products = implode(', ', $cart_products);

    $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

    if ($cart_total == 0) {
        $message[] = 'Your cart is empty!';
    } elseif (mysqli_num_rows($order_query) > 0) {
        $message[] = 'Order placed already!';
    } else {
        mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
        mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');

        $_SESSION['order_successful'] = true; // Set session for successful order
        header('Location: checkout.php'); // Redirect to avoid form resubmission
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* Success message styling */
        .success-message {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 50px;
            border: 1px solid #ddd;
            background-color: #f0f8ff;
            text-align: center;
        }

        .success-message h3 {
            font-size: 24px;
            color: #28a745;
        }

        .success-message a {
            margin-top: 20px;
            background-color: #28a745;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .success-message a:hover {
            background-color: #218838;
        }

        .empty {
            color: red;
            display: block;
        }
    </style>
</head>
<body>

<?php @include 'header.php'; ?>

<section class="heading">
    <h3>Checkout Order</h3>
    <p><a href="home.php">Home</a> / Checkout</p>
</section>

<section class="checkout">
    <?php if ($order_successful): ?>
        <!-- Success message and continue shopping button -->
        <div class="success-message">
            <h3>Your order has been placed successfully!</h3>
            <h4>You can cancel the order within 2 hours</h4>
            <a href="home.php">Continue Shopping</a>
        </div>

        <?php 
        // Clear session variable after displaying success message
        unset($_SESSION['order_successful']); 
        ?>

    <?php else: ?>
        <!-- Display order summary -->
        <section class="display-order">
            <?php
                $grand_total = 0;
                $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                if (mysqli_num_rows($select_cart) > 0) {
                    while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                        $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                        $grand_total += $total_price;
            ?>
            <p> <?php echo $fetch_cart['name'] ?> <span>(<?php echo '₹' . $fetch_cart['price'] . '/-' . ' x ' . $fetch_cart['quantity'] ?>)</span></p>
            <?php
                    }
                } else {
                    echo '<p class="empty">Your cart is empty</p>';
                }
            ?>
            <div class="grand-total">Grand Total: <span>₹<?php echo $grand_total; ?>/-</span></div>
        </section>

        <!-- Order form -->
        <form action="" method="POST">
            <h3>Place Your Order</h3>

            <div class="flex">
                <!-- Form fields -->
                <div class="inputBox">
                    <span>Your Name :</span>
                    <input type="text" name="name" value="<?php echo $fetch_user['name']; ?>" required>
                </div>
                <div class="inputBox">
                    <span>Your Number :</span>
                    <input type="number" name="number" value="<?php echo $fetch_user['mobile_number']; ?>" required>
                </div>
                <div class="inputBox">
                    <span>Your Email :</span>
                    <input type="email" name="email" value="<?php echo $fetch_user['email']; ?>" required>
                </div>
                <div class="inputBox">
                    <span>Payment Method :</span>
                    <select name="method" required>
                        <option value="cash on delivery">Cash on Delivery</option>
                        <option value="credit card">Credit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="paytm">Paytm</option>
                    </select>
                </div>
                <div class="inputBox">
                    <span>Flat No. :</span>
                    <input type="text" name="flat" placeholder="Enter flat number" required>
                </div>
                <div class="inputBox">
                    <span>Street Name :</span>
                    <input type="text" name="street" placeholder="Enter street name" required>
                </div>
                <div class="inputBox">
                    <span>City :</span>
                    <select name="city" id="city" required onchange="updateStateCountry()">
                        <option value="Bhind">Bhind</option>
                        <option value="Morena">Morena</option>
                        <option value="Datia">Datia</option>
                        <option value="Gwalior">Gwalior</option>
                    </select>
                </div>
                <div class="inputBox">
                    <span>State :</span>
                    <input type="text" id="state" name="state" value="Madhya Pradesh" readonly>
                </div>
                <div class="inputBox">
                    <span>Country :</span>
                    <input type="text" id="country" name="country" value="India" readonly>
                </div>
                <div class="inputBox">
                    <span>Pin Code :</span>
                    <input type="number" name="pin_code" placeholder="Enter pin code" required>
                </div>
            </div>

            <input type="submit" name="order" value="Order Now" class="btn">
        </form>
    <?php endif; ?>
</section>

<?php @include 'footer.php'; ?>

<script>
    // Auto-fill state and country when city is selected
    function updateStateCountry() {
        document.getElementById('state').value = 'Madhya Pradesh';
        document.getElementById('country').value = 'India';
    }
</script>

<script src="js/script.js"></script>

</body>
</html>
