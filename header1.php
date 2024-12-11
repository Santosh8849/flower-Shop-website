<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

    <div class="flex">

    <p style="font-size: 2rem; text-decoration: none; text-align: left;">
    <a href="home.php" style="text-decoration: none; color: inherit;">Flowers Shop</a>
</p>


        <nav class="navbar">
            <ul>
                <li><a href="home1.php">home</a></li>
                
                        <li><a href="about1.php">about</a></li>
                        <li><a href="contact1.php">contact</a></li>
                
                <!-- <li><a href="shop.php">shop</a></li>
                <li><a href="orders.php">orders</a></li> -->
                <!-- <li><a href="#">account +</a>
                    <ul>
                        <li><a href="login.php">login</a></li>
                        <li><a href="register.php">register</a></li>
                    </ul>
                </li> -->
            </ul>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search1.php" class="fas fa-search"></a>
            <div id="user-btn" class="fas fa-user"></div>
            <a href="wishlist.php"><i class="fas fa-heart"></i><span>(0)</span></a>
            <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(0)</span></a>
        </div>

        <div class="account-box">
            
            <p>usre : <span>Not logged in</span></p>
            <a href="login.php" class="delete-btn">login</a>   <a href="register.php" class="delete-btn">register</a>
        </div>

    </div>

</header>
