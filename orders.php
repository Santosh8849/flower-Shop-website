<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
    .delete-btn {
         color: #fff;
         background-color: #e74c3c;
         font-size: 10px;
         padding: 5px 10px;
         border-radius: 5px;
         text-decoration: none;
         display: inline-block;
      }
      .delete-btn:hover {
         background-color: #c0392b;
      }

       .placed-orders {
           background: white;
           padding: 20px;
           border-radius: 5px;
           /* box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); */
       }

       table {
        width: 102.7%;
         border-collapse: collapse;
         margin: 30px -17px;
         font-size: 15px;
         text-align: left;
       }

       th, td {
           padding: 12px;
           border: 2px solid #ddd;
           font-size: 1.3rem;
           text-align: center;
       }

       /* th {
           background-color: #f2f2f2;
       } */

       /* tr:nth-child(even) {
           background-color: #f9f9f9;
       }

       tr:hover {
           background-color: #f1f1f1;
       } */

       .invoice-link {
           color: blue;
           text-decoration: none;
       }

       .invoice-link:hover {
           text-decoration: underline;
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
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>Your Orders</h3>
    <p><a href="home.php">Home</a> / Orders</p>
</section>

<section class="placed-orders">

    <h1 class="title">Placed Orders</h1>

   <div class="search-container">
      <input type="text" id="searchInput" class="search-input" onkeyup="searchTable()" placeholder="Search for orders...">
   </div>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Placed On</th>
                <!-- <th>Name</th>
                <th>Number</th>
                <th>Email</th> -->
                <th>Address</th>
                <th>Payment Method</th>
                <th>Total Products</th>
                <th>Total Price</th>
                <th>Order Status</th>
                <th>Invoice</th>
                <th>Cancelled</th>
            </tr>
        </thead>
        <tbody>
    <?php
        $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select_orders) > 0){
            while($fetch_orders = mysqli_fetch_assoc($select_orders)){
    ?>
        <tr>
            <td><?php echo $fetch_orders['id']; ?></td>
            <td><?php echo $fetch_orders['placed_on']; ?></td>
            <td><?php echo $fetch_orders['address']; ?></td>
            <td><?php echo $fetch_orders['method']; ?></td>
            <td><?php echo $fetch_orders['total_products']; ?></td>
            <td>₹<?php echo $fetch_orders['total_price']; ?>/-</td>
            <td style="color:<?php echo $fetch_orders['payment_status'] == 'pending' ? 'tomato' : 'green'; ?>">
                <?php echo $fetch_orders['payment_status']; ?>
            </td>
            <td><a href="invoice.php?order_id=<?php echo $fetch_orders['id']; ?>" class="invoice-link" target="_blank">View Invoice</a></td>
            <td>
                <!-- Cancel Order Button -->
                <?php if($fetch_orders['payment_status'] == 'pending'){ ?>
                    <a href="cancel_order.php?order_id=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('Are you sure you want to cancel this order?');" class="delete-btn">Cancel Order</a>
                <?php } else { ?>
                    <span style="color:grey;">Cancel Order</span>
                <?php } ?>
            </td>
        </tr>
    <?php
        }
    }else{
        echo '<tr><td colspan="9" style="text-align:center;">No orders placed yet!</td></tr>';
    }
    ?>
</tbody>

    </table>

</section>

<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
