<?php

@include 'config.php';

if(isset($_GET['order_id'])){
    $order_id = $_GET['order_id'];

    // Fetch order details from the database
    $select_order = mysqli_query($conn, "SELECT * FROM `orders` WHERE id = '$order_id'") or die('query failed');
    if(mysqli_num_rows($select_order) > 0){
        $order = mysqli_fetch_assoc($select_order);
    } else {
        die('Order not found!');
    }
} else {
    die('No order ID specified!');
}

// Total price without GST calculation
$total_price = $order['total_price'];

// Email setup
$to = $order['email']; // User email address
$subject = "Invoice for your order #" . $order['id'];
$headers = "From: no-reply@flowershop.com\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

// Create the email message
$message = "
<html>
<head>
  <title>Invoice</title>
</head>
<body>
  <h2>Thank you for your order!</h2>
  <p><strong>Order ID:</strong> {$order['id']}</p>
  <p><strong>Customer Name:</strong> {$order['name']}</p>
  <p><strong>Total Products:</strong> {$order['total_products']}</p>
  <p><strong>Total Payable:</strong> ₹" . number_format($total_price, 2) . "/-</p>
</body>
</html>";

// // Send email
// mail($to, $subject, $message, $headers);

// ?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Invoice</title>
   <style>
       body {
           font-family: Arial, sans-serif;
           padding: 20px;
           background-color: #f7f7f7;
       }
       .invoice-box {
           max-width: 700px;
           margin: auto;
           padding: 17px;
           border: 2px solid pink;
           box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
           background-color: #fff;
           font-size: 13px;
           line-height: 20px;
           color: #555;
       }
       .invoice-box table {
           width: 100%;
           line-height: inherit;
           text-align: left;
       }
       .invoice-box table td {
           padding: 10px;
           vertical-align: top;
       }
       .invoice-box table tr td:nth-child(2) {
           text-align: right;
       }
       .invoice-box table tr.top table td {
           padding-bottom: 15px;
       }
       .invoice-box table tr.heading td {
           background: #eee;
           border-bottom: 1px solid #ddd;
           font-weight: bold;
       }
       .invoice-box table tr.details td {
           padding-bottom: 20px;
       }
       .invoice-box table tr.item td {
           border-bottom: 1px solid #eee;
       }
       .invoice-box table tr.item.last td {
           border-bottom: none;
       }
       .invoice-box table tr.total td {
           border-top: 2px solid #eee;
           font-weight: bold;
       }
       .qr-code {
           text-align: center;
           margin-top: 20px;
       }
       .qr-code img {
           width: 150px;
       }
       .invoice-header {
           display: flex;
           justify-content: space-between;
           margin-bottom: 20px;
       }
       .company-info, .customer-info {
           width: 48%;
       }
       .total-table td {
           font-weight: bold;
       }
       .print-button {
           text-align: center;
           margin-top: 20px;
       }
       .print-button button {
           background-color: #4CAF50;
           color: white;
           padding: 10px 20px;
           border: none;
           cursor: pointer;
           font-size: 16px;
       }
       .print-button button:hover {
           background-color: #45a049;
       }
   </style>
   <script>
       function printInvoice() {
           window.print();
       }
   </script>
</head>
<body>

<div class="invoice-box">
<center> <h2><u>INVOICE</u></h2></center>

   <div class="invoice-header">
       <div class="company-info">
           <h2>Flower shop</h2>
           <p>Pragati Vihar Gola ka mandir<br>Gwalior, MP - 474005<br>Email: Shivam@gmail.com</p>
       </div>
       <div class="customer-info">
           <p><strong>Invoice to:</strong></p>
           <p><?php echo $order['name']; ?><br><?php echo $order['address']; ?><br>Email: <?php echo $order['email']; ?></p>
           <p>Order ID: <?php echo $order['id']; ?></p>
           <p>Invoice Date: <?php echo date("Y/m/d"); ?></p>
       </div>
   </div>

   <h3>Order Summary</h3>
   <hr>
   <table>
       <tr class="heading">
           <td>Description</td>
           <td>Total (₹)</td>
       </tr>
       <tr class="item">
           <td>Total Products</td>
           <td><?php echo $order['total_products']; ?></td>
       </tr>
       <tr class="item">
           <td>Subtotal</td>
           <td><?php echo number_format($total_price, 2); ?>/-</td>
       </tr>
       <tr class="total">
           <td></td>
           <td>Total Payable: ₹<?php echo number_format($total_price, 2); ?>/-</td>
       </tr>
   </table>

   <div class="company-signature">
       <p><strong>Authorized Signature</strong></p>
       <pre> <i>Santosh Kushwaha</i></pre>
   </div>
   <div class="print-button">
       <button onclick="printInvoice()">Print Invoice</button>
   </div>

</div>


</body>
</html>
