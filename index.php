<?php

// Start session
session_start();

// Check if the user is logged in
if(isset($_SESSION['user_id'])){
    // If logged in, redirect to home.php
    header('Location: home.php');
} else {
    // If not logged in, redirect to home1.php
    header('Location: home1.php');
}

exit;

?>
