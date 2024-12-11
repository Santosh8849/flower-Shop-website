<?php

@include 'config.php';

// Initialize error array
$errors = [];

if (isset($_POST['submit'])) {

    // Username validation (only letters and spaces allowed)
    $filter_name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    if (!preg_match("/^[a-zA-Z\s]+$/", $filter_name)) {
        $errors['name'] = 'Username should contain only letters and spaces!';
    } else {
        $name = mysqli_real_escape_string($conn, $filter_name);
    }

    // Email validation (valid format and unique email check)
    $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($filter_email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format!';
    } else {
        $email = mysqli_real_escape_string($conn, $filter_email);

        $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');
        if (mysqli_num_rows($select_users) > 0) {
            $errors['email'] = 'Email already exists!';
        }
    }
            // Mobile number validation (must start with 6, 7, 8, or 9 and have exactly 10 digits)
        $filter_mobile_number = filter_var($_POST['mobile_number'], FILTER_SANITIZE_NUMBER_INT);
        if (!preg_match("/^[6-9][0-9]{9}$/", $filter_mobile_number)) {
            $errors['mobile_number'] = 'Mobile number must start with 6, 7, 8, or 9 and be 10 digits long!';
        } else {
            $mobile_number = mysqli_real_escape_string($conn, $filter_mobile_number);
        }


    // Password validation (max length 30 and standard password requirements)
    $filter_pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
    if (strlen($filter_pass) > 20) {
        $errors['pass'] = 'Password should not exceed 20 characters!';
    } elseif (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{5,}$/", $filter_pass)) {
        $errors['pass'] = 'Password must be at least 5 characters long, contain letters, numbers, and special characters!';
    } else {
        $pass = mysqli_real_escape_string($conn, md5($filter_pass));
        $filter_cpass = filter_var($_POST['cpass'], FILTER_SANITIZE_STRING);
        $cpass = mysqli_real_escape_string($conn, md5($filter_cpass));

        if ($pass != $cpass) {
            $errors['cpass'] = 'Confirm password does not match!';
        }
    }

    // If no errors, proceed to insert data into the database
    if (empty($errors)) {
      mysqli_query($conn, "INSERT INTO `users`(name, email, mobile_number, password) VALUES('$name', '$email', '$mobile_number', '$pass')") or die('query failed');
      echo "<script>
              alert('Registered successfully!');
              window.location.href = 'login.php';
            </script>";
  }
  
  
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>

 <!-- Font awesome CDN link -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Custom CSS file link -->
<link rel="stylesheet" href="css/style.css">

<style>
   body {
      background-image: url('https://c4.wallpaperflare.com/wallpaper/812/587/812/desktop-still-life-roses-vase-flower-arrangement-wallpaper-preview.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      height: 100vh;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
   }
  
   .error {
      color: red;
      font-size: 12px;
   }
</style>
</head>
<body>

<section class="form-container">

   <form action="" method="post">
      <h3>Register Now</h3>

      <!-- Username Input -->
      <input type="text" id="name" name="name" class="box" placeholder="Enter your name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
      <span id="nameError" class="error"><?php if (isset($errors['name'])) echo $errors['name']; ?></span>

      <!-- Email Input -->
      <input type="email" id="email" name="email" class="box" placeholder="Email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
      <span id="emailError" class="error"><?php if (isset($errors['email'])) echo $errors['email']; ?></span>
        <!-- Mobile Number -->
      <input type="mobile_number" id="mobile_number" name="mobile_number" class="box" placeholder="Mobile Number" value="<?php echo isset($_POST['mobile_number']) ? htmlspecialchars($_POST['mobile_number']) : ''; ?>" required>
      <span id="numberError" class="error"><?php if (isset($errors['mobile_number'])) echo $errors['mobile_number']; ?></span>

      <!-- Password Input -->
      <input type="password" id="pass" name="pass" class="box" placeholder="Password" required>
      <span id="passError" class="error"><?php if (isset($errors['pass'])) echo $errors['pass']; ?></span>

      <!-- Confirm Password Input -->
      <input type="password" id="cpass" name="cpass" class="box" placeholder="Confirm password" required>
      <span id="cpassError" class="error"><?php if (isset($errors['cpass'])) echo $errors['cpass']; ?></span>

      <input type="submit" class="btn" name="submit" value="Register Now">
      <p>Already have an account? <a href="login.php">Login now</a></p>
   </form>

</section>

</body>
</html>
