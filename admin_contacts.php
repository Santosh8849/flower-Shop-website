<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_contacts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Messages Dashboard</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Custom Admin CSS File -->
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
      .delete-btn {
         color: #fff;
         background-color: #e74c3c;
         padding: 5px 10px;
         border-radius: 5px;
         text-decoration: none;
         display: inline-block;
      }
      .delete-btn:hover {
         background-color: #c0392b;
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

<section class="messages">

   <h1 class="title">Messages</h1>

   <div class="search-container">
      <input type="text" id="searchInput" class="search-input" onkeyup="searchTable()" placeholder="Search for orders...">
   </div>

   <div class="table-container">
      <table>
         <thead>
            <tr>
               <!-- <th>User ID</th> -->
               <th>Name</th>
               <th>Number</th>
               <th>Email</th>
               <th>Message</th>
               <th>Actions</th>
            </tr>
         </thead>
         <tbody>
         <?php
            $select_message = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
            if(mysqli_num_rows($select_message) > 0){
               while($fetch_message = mysqli_fetch_assoc($select_message)){
         ?>
            <tr>
               <!-- <td><?php echo $fetch_message['user_id']; ?></td> -->
               <td><?php echo $fetch_message['name']; ?></td>
               <td><?php echo $fetch_message['number']; ?></td>
               <td><?php echo $fetch_message['email']; ?></td>
               <td><?php echo $fetch_message['message']; ?></td>
               <td>
                  <a href="admin_contacts.php?delete=<?php echo $fetch_message['id']; ?>" class="delete-btn" onclick="return confirm('Delete this message?');">Delete</a>
               </td>
            </tr>
         <?php
               }
            }else{
               echo '<tr><td colspan="6" class="empty">You have no messages!</td></tr>';
            }
         ?>
         </tbody>
      </table>
   </div>

</section>

<script src="js/admin_script.js"></script>

</body>
</html>
