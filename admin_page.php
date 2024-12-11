<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'admin_header.php'; ?>


<section class="dashboard">

   <h1 class="title">Admin dashboard</h1>

   <div class="box-container">
       <!-- Total Delivered Orders -->
       <div class="box">
         <?php
            $total_pending_orders = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `orders` WHERE payment_status = 'Delivered'") or die('query failed');
            $pending_orders = mysqli_fetch_assoc($total_pending_orders);
         ?>
         <h3><?php echo $pending_orders['total']; ?></h3>
         <p>Delivered Orders</p>
      </div>

      <!-- Total Pending Orders -->
      <div class="box">
         <?php
            $total_pending_orders = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `orders` WHERE payment_status = 'pending'") or die('query failed');
            $pending_orders = mysqli_fetch_assoc($total_pending_orders);
         ?>
         <h3><?php echo $pending_orders['total']; ?></h3>
         <p>Pending Orders</p>
      </div>

      <!-- Total Dispatched Orders -->
      <div class="box">
         <?php
            $total_dispatched_orders = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `orders` WHERE payment_status = 'Dispatch'") or die('query failed');
            $dispatched_orders = mysqli_fetch_assoc($total_dispatched_orders);
         ?>
         <h3><?php echo $dispatched_orders['total']; ?></h3>
         <p>Dispatched Orders</p>
      </div>

      <!-- Total Cancelled Orders -->
      <div class="box">
         <?php
            $total_cancelled_orders = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `orders` WHERE payment_status = 'Canceled'") or die('query failed');
            $cancelled_orders = mysqli_fetch_assoc($total_cancelled_orders);
         ?>
         <h3><?php echo $cancelled_orders['total']; ?></h3>
         <p>Cancelled Orders</p>
      </div>

      <!-- Total Pending Amount -->
      <div class="box">
         <?php
            $total_pendings = 0;
            $select_pendings = mysqli_query($conn, "SELECT * FROM `orders` WHERE payment_status = 'pending'") or die('query failed');
            while($fetch_pendings = mysqli_fetch_assoc($select_pendings)){
               $total_pendings += $fetch_pendings['total_price'];
            };
         ?>
         <h3>₹<?php echo $total_pendings; ?>/-</h3>
         <p>Pending Amount</p>
      </div>

      <!-- Total Completed Payments -->
      <div class="box">
         <?php
            $total_completes = 0;
            $select_completes = mysqli_query($conn, "SELECT * FROM `orders` WHERE payment_status = 'Delivered'") or die('query failed');
            while($fetch_completes = mysqli_fetch_assoc($select_completes)){
               $total_completes += $fetch_completes['total_price'];
            };
         ?>
         <h3>₹<?php echo $total_completes; ?>/-</h3>
         <p>Completed Payments</p>
      </div>

      <!-- Total Orders Placed -->
      <div class="box">
         <?php
            $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
            $number_of_orders = mysqli_num_rows($select_orders);
         ?>
         <h3><?php echo $number_of_orders; ?></h3>
         <p>Orders Placed</p>
      </div>

      <!-- Total Products Added -->
      <div class="box">
         <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
            $number_of_products = mysqli_num_rows($select_products);
         ?>
         <h3><?php echo $number_of_products; ?></h3>
         <p>Products Added</p>
      </div>

      <!-- Total Users -->
      <div class="box">
         <?php
            $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user'") or die('query failed');
            $number_of_users = mysqli_num_rows($select_users);
         ?>
         <h3><?php echo $number_of_users; ?></h3>
         <p>Normal Users</p>
      </div>

      <!-- Total Admins -->
      <div class="box">
         <?php
            $select_admin = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'admin'") or die('query failed');
            $number_of_admin = mysqli_num_rows($select_admin);
         ?>
         <h3><?php echo $number_of_admin; ?></h3>
         <p>Admin Users</p>
      </div>

      <!-- Total Accounts -->
      <div class="box">
         <?php
            $select_account = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
            $number_of_account = mysqli_num_rows($select_account);
         ?>
         <h3><?php echo $number_of_account; ?></h3>
         <p>Total Accounts</p>
      </div>

      <!-- New Messages -->
      <div class="box">
         <?php
            $select_messages = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
            $number_of_messages = mysqli_num_rows($select_messages);
         ?>
         <h3><?php echo $number_of_messages; ?></h3>
         <p>New Messages</p>
      </div>

   </div>

</section>

<script src="js/admin_script.js"></script>

</body>
</html>
