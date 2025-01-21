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

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>pesanan</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="heading">
    <h3>pesanan anda</h3>
    <p> <a href="home.php">beranda</a> / pesanan </p>
</section>

<section class="placed-orders">

    <h1 class="title">pesanan yang telah dibuat</h1>

    <div class="box-container">

    <?php
        $stmt = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($fetch_orders = $result->fetch_assoc()) {
    ?>
    <div class="box">
        <p> ditempatkan pada : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
        <p> nama : <span><?php echo $fetch_orders['name']; ?></span> </p>
        <p> nomor : <span><?php echo $fetch_orders['number']; ?></span> </p>
        <p> email : <span><?php echo $fetch_orders['email']; ?></span> </p>
        <p> alamat : <span><?php echo $fetch_orders['address']; ?></span> </p>
        <p> metode pembayaran : <span><?php echo $fetch_orders['method']; ?></span> </p>
        <p> pesanan anda : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
        <p> total harga : <span>Rp.<?php echo $fetch_orders['total_price']; ?>/-</span> </p>
        <p> status pembayaran : <span style="color:<?php echo ($fetch_orders['payment_status'] == 'pending') ? 'tomato' : 'green'; ?>"><?php echo $fetch_orders['payment_status']; ?></span> </p>
    </div>
    <?php
            }
        } else {
            echo '<p class="empty">belum ada pesanan yang dibuat!</p>';
        }
    ?>
    </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
