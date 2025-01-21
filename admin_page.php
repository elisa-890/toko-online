<?php

$host = 'localhost';
$username = 'root'; 
$password = '';      
$dbname = 'shop_db';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="dashboard">

   <h1 class="title">dashboard</h1>

   <div class="box-container">

      <div class="box">
         <?php
            $total_pendings = 0;
            $select_pendings = $conn->query("SELECT * FROM `orders` WHERE payment_status = 'pending'");
            while($fetch_pendings = $select_pendings->fetch_assoc()){
               $total_pendings += $fetch_pendings['total_price'];
            };
         ?>
         <h3>Rs.<?php echo $total_pendings; ?>/-</h3>
         <p>total pendings</p>
      </div>

      <div class="box">
         <?php
            $total_completes = 0;
            $select_completes = $conn->query("SELECT * FROM `orders` WHERE payment_status = 'completed'");
            while($fetch_completes = $select_completes->fetch_assoc()){
               $total_completes += $fetch_completes['total_price'];
            };
         ?>
         <h3>Rs.<?php echo $total_completes; ?>/-</h3>
         <p>completed payments</p>
      </div>

      <div class="box">
         <?php
            $select_orders = $conn->query("SELECT * FROM `orders`");
            $number_of_orders = $select_orders->num_rows;
         ?>
         <h3><?php echo $number_of_orders; ?></h3>
         <p>orders placed</p>
      </div>

      <div class="box">
         <?php
            $select_products = $conn->query("SELECT * FROM `products`");
            $number_of_products = $select_products->num_rows;
         ?>
         <h3><?php echo $number_of_products; ?></h3>
         <p>products added</p>
      </div>

      <div class="box">
         <?php
            $select_users = $conn->query("SELECT * FROM `users` WHERE user_type = 'user'");
            $number_of_users = $select_users->num_rows;
         ?>
         <h3><?php echo $number_of_users; ?></h3>
         <p>normal users</p>
      </div>

      <div class="box">
         <?php
            $select_admin = $conn->query("SELECT * FROM `users` WHERE user_type = 'admin'");
            $number_of_admin = $select_admin->num_rows;
         ?>
         <h3><?php echo $number_of_admin; ?></h3>
         <p>admin users</p>
      </div>

      <div class="box">
         <?php
            $select_account = $conn->query("SELECT * FROM `users`");
            $number_of_account = $select_account->num_rows;
         ?>
         <h3><?php echo $number_of_account; ?></h3>
         <p>total accounts</p>
      </div>

      <div class="box">
         <?php
            $select_messages = $conn->query("SELECT * FROM `message`");
            $number_of_messages = $select_messages->num_rows;
         ?>
         <h3><?php echo $number_of_messages; ?></h3>
         <p>new messages</p>
      </div>

   </div>

</section>

<script src="js/admin_script.js"></script>

</body>
</html>
