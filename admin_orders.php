<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['update_order'])){
   $order_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_id'") or die('query failed');
   $message[] = 'Order status has been updated!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/admin_style.css">

   <!-- Inline CSS for table borders -->
   <style>
      table {
         width: 99.2%;
         border-collapse: collapse;
         margin: 20px 5px;
         font-size: 13px;
         text-align: left;
      }
      th, td {
         border: 1px solid pink;
         padding: 12px 15px;
      }
      th {
         background-color: #f2f2f2;
      }
      .table-container {
         overflow-x: auto;
          margin-top: 2px;
      }
      .option-btn, .delete-btn, .invoice-btn {
         margin-top: 2px;
         padding: 2px 10px;
         text-decoration: none;
         color: #fff;
         background-color: #5cb85c;
         border: none;
         cursor: pointer;
      }
      .delete-btn {
         background-color: #d9534f;
      }
      .invoice-btn {
         background-color: #337ab7;
      }
      .search-container {
         text-align: right;
         margin: 1px 0px;
      }
      .search-input {
         padding: 7px;
         font-size: 14px;
      }
   </style>
   <script>
      // JavaScript for search functionality
      function searchTable() {
         var input, filter, table, tr, td, i, txtValue;
         input = document.getElementById("searchInput");
         filter = input.value.toUpperCase();
         table = document.querySelector("table tbody");
         tr = table.getElementsByTagName("tr");

         for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td");
            let found = false;
            for (let j = 0; j < td.length; j++) {
               if (td[j]) {
                  txtValue = td[j].textContent || td[j].innerText;
                  if (txtValue.toUpperCase().indexOf(filter) > -1) {
                     found = true;
                     break;
                  }
               }
            }
            tr[i].style.display = found ? "" : "none";
         }
      }
   </script>
</head>
<body>
   
<?php @include 'admin_header.php'; ?>

<section class="placed-orders">

   <h1 class="title">Placed Orders</h1>

   <div class="search-container">
      <input type="text" id="searchInput" class="search-input" onkeyup="searchTable()" placeholder="Search for orders...">
   </div>

   <div class="table-container">
      <table>
         <thead>
            <tr>
               <th>Order Id</th>
               <th>Placed On</th>
               <th>Name</th>
               <th>Number</th>
               <th>Email</th>
               <th>Address</th>
               <th>Total Products</th>
               <th>Total Price</th>
               <th>Payment Method</th>
               <th>Order Status</th>
               <th>Actions</th>
               <th>Invoice</th>
            </tr>
         </thead>
         <tbody>
         <?php
         
         $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
         if(mysqli_num_rows($select_orders) > 0){
            while($fetch_orders = mysqli_fetch_assoc($select_orders)){
         ?>
            <tr>
               <td><?php echo $fetch_orders['id']; ?></td>
               <td><?php echo $fetch_orders['placed_on']; ?></td>
               <td><?php echo $fetch_orders['name']; ?></td>
               <td><?php echo $fetch_orders['number']; ?></td>
               <td><?php echo $fetch_orders['email']; ?></td>
               <td><?php echo $fetch_orders['address']; ?></td>
               <td><?php echo $fetch_orders['total_products']; ?></td>
               <td>₹<?php echo $fetch_orders['total_price']; ?>/-</td>
               <td><?php echo $fetch_orders['method']; ?></td>
               <td>
   <form action="" method="post">
      <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
      
      <?php if($fetch_orders['payment_status'] == 'Canceled') { ?>
         <!-- Canceled order: show status but no options or buttons -->
         <span><?php echo $fetch_orders['payment_status']; ?></span>
      
      <?php } elseif($fetch_orders['payment_status'] == 'Delivered') { ?>
         <!-- Delivered order: no options or buttons, only show Delivered -->
         <span>Delivered</span>
      
      <?php } elseif($fetch_orders['payment_status'] == 'Dispatch') { ?>
         <!-- After Dispatch, only allow Delivered as an option -->
         <select name="update_payment">
            <option value="Delivered">Delivered</option>
         </select>
         <input type="submit" name="update_order" value="Update" class="option-btn">
      
      <?php } else { ?>
         <!-- For any other status, allow all options -->
         <select name="update_payment">
            <option disabled selected><?php echo $fetch_orders['payment_status']; ?></option>
            <!-- <option value="pending">Pending</option> -->
            <option value="Dispatch">Dispatch</option>
            <option value="Delivered">Delivered</option>
         </select>
         <input type="submit" name="update_order" value="Update" class="option-btn">
      <?php } ?>
   </form>
</td>

               <td>
                  <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Delete this order?');">Delete</a>
               </td>
               <td>
                  <a href="admin_invoice.php?order_id=<?php echo $fetch_orders['id']; ?>" class="invoice-btn">Invoice</a>
               </td>
            </tr>
         <?php
            }
         }else{
            echo '<tr><td colspan="12" class="empty">No orders placed yet!</td></tr>';
         }
         ?>
         </tbody>
      </table>
   </div>

</section>

<script src="js/admin_script.js"></script>

</body>
</html>
