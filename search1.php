<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>search page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header1.php'; ?>

<section class="heading">
    <h3>search page</h3>
    <p> <a href="home1.php">home</a> / search </p>
</section>

<section class="search-form">
    <form action="" method="POST">
        <input type="text" class="box" placeholder="search products..." name="search_box">
        <input type="submit" class="btn" value="search" name="search_btn">
    </form>
</section>

<section class="products" style="padding-top: 0;">
   <div class="box-container">

   <?php
   // Include config.php to make sure $conn is initialized
   include 'config.php';

   // Check if the connection is established
   if(!$conn) {
       die('Connection failed: ' . mysqli_connect_error());
   }

   if(isset($_POST['search_btn'])) {
       // Escape the search input to prevent SQL Injection
       $search_box = mysqli_real_escape_string($conn, $_POST['search_box']);
       
       // Query to search for products
       $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%{$search_box}%'") or die('Query failed');
       
       if(mysqli_num_rows($select_products) > 0) {
           while($fetch_products = mysqli_fetch_assoc($select_products)) {
   ?>
       <!-- Product box to display each product -->
       <div class="box">
          <a href="view1.php?pid=<?php echo $fetch_products['id']; ?>">
             <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="image">
          </a>
          <div class="name"><?php echo $fetch_products['name']; ?></div>
          <div class="price">₹<?php echo $fetch_products['price']; ?>/-</div>
       </div>
   <?php
           }
       } else {
           echo '<p class="empty">No results found!</p>';
       }
   } else {
       echo '<p class="empty">Search something!</p>';
   }
   ?>

   </div>
</section>

<?php @include 'footer1.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
