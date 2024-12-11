<?php

@include 'config.php';

// Check for user session and redirect to login page if not logged in
session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Add to wishlist functionality
if (isset($_POST['add_to_wishlist'])) {
   if ($user_id) {
      $product_id = $_POST['product_id'];
      $product_name = $_POST['product_name'];
      $product_price = $_POST['product_price'];
      $product_image = $_POST['product_image'];

      $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
      $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

      if (mysqli_num_rows($check_wishlist_numbers) > 0) {
         $message[] = 'Already added to wishlist';
      } elseif (mysqli_num_rows($check_cart_numbers) > 0) {
         $message[] = 'Already added to cart';
      } else {
         mysqli_query($conn, "INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_image')") or die('query failed');
         $message[] = 'Product added to wishlist';
      }
   } else {
      header('location:login.php'); // Redirect to login page if not logged in
   }
}

// Add to cart functionality
if (isset($_POST['add_to_cart'])) {
   if ($user_id) {
      $product_id = $_POST['product_id'];
      $product_name = $_POST['product_name'];
      $product_price = $_POST['product_price'];
      $product_image = $_POST['product_image'];
      $product_quantity = $_POST['product_quantity'];

      $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

      if (mysqli_num_rows($check_cart_numbers) > 0) {
         $message[] = 'Already added to cart';
      } else {
         // Remove from wishlist if the product is in it
         $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
         if (mysqli_num_rows($check_wishlist_numbers) > 0) {
            mysqli_query($conn, "DELETE FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
         }

         mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
         $message[] = 'Product added to cart';
      }
   } else {
      header('location:login.php'); // Redirect to login page if not logged in
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header1.php'; ?>

<section class="home">
   <div class="content">
      <h3>New Collections</h3>
      <marquee>
    <p>Winter season flowers coming soon</p>
</marquee>


      <!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime reiciendis, modi placeat sit cumque molestiae.</p> -->
      <a href="about1.php" class="btn">Discover more</a>
   </div>
</section>

<section class="products">
   <h3 class="title">Latest Products</h3>
   <div class="box-container">
      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 100") or die('query failed');
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
      ?>
      <form action="" method="POST" class="box">
         <!-- Wrap the product image with an anchor tag linking to view1.php with product id -->
         <a href="view1.php?pid=<?php echo $fetch_products['id']; ?>">
            <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="image">
         </a>
         <div class="price">₹<?php echo $fetch_products['price']; ?>/-</div>
         <div class="name"><?php echo $fetch_products['name']; ?></div>
         <!-- <input type="number" name="product_quantity" value="1" min="1" class="qty"> -->
         <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
         <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
         <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
         <!-- <input type="submit" value="Add to Wishlist" name="add_to_wishlist" class="option-btn"> -->
         <!-- <input type="submit" value="Add to Cart" name="add_to_cart" class="btn"> -->
      </form>
      <?php
         }
      } else {
         echo '<p class="empty">No products added yet!</p>';
      }
      ?>
   </div>
</section>


<section class="home-contact">
   <div class="content">
      <h3>Have any questions?</h3>
      <p>You can ask me for product, order, payment related Question? </p>
      <a href="contact1.php" class="btn">Contact Us</a>
   </div>
</section>

<?php @include 'footer1.php'; ?>

<script src="js/script.js"></script>


</body>
</html>
