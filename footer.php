<?php
@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<section class="footer">

    <div class="box-container">

        <div class="box">
            <h3>quick links</h3>
            <a href="home.php">home</a>
            <a href="about.php">about</a>
            <a href="contact.php">contact</a>
           
        </div>

        <div class="box">
            <h3>extra links</h3>
            <?php if(!isset($_SESSION['user_id'])): ?>
                <a href="login.php">login</a>
                <a href="register.php">register</a>
            <?php endif; ?>
            <a href="orders.php">my orders</a>
            <a href="cart.php">my cart</a>
            <a href="home.php">shop</a>
        </div>

        <div class="box">
            <h3>contact info</h3>
            <p> <i class="fas fa-phone"></i> 8770566564 </p>
            <p> <i class="fas fa-phone"></i> 8877225634 </p>
            <p> <i class="fas fa-envelope"></i> shivam@gmail.com </p>
            <p> <i class="fas fa-map-marker-alt"></i> Gwalior (m.p), india - 474005 </p>
        </div>

        <div class="box">
            <h3>follow us</h3>
            <a href="#"><i class="fab fa-facebook-f"></i>facebook</a>
            <a href="#"><i class="fab fa-twitter"></i>twitter</a>
            <a href="#"><i class="fab fa-instagram"></i>instagram</a>
            <a href="#"><i class="fab fa-linkedin"></i>linkedin</a>
        </div>

    </div>

    <div class="credit">&copy; copyright @ <?php echo date('Y'); ?> by <span>mr. web designer</span> </div>

</section>
