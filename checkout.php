<?php

$host = 'localhost'; 
$username = 'root';  
$password = '';    
$dbname = 'shop_db'; 

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
};

if (isset($_POST['order'])) {

    $name = $conn->real_escape_string($_POST['name']);
    $number = $conn->real_escape_string($_POST['number']);
    $email = $conn->real_escape_string($_POST['email']);
    $method = $conn->real_escape_string($_POST['method']);
    $address = $conn->real_escape_string('flat no. '. $_POST['flat'].', '. $_POST['street'].', '. $_POST['city'].', '. $_POST['country'].' - '. $_POST['pin_code']);
    $placed_on = date('d-M-Y');

    $cart_total = 0;
    $cart_products[] = '';

    $cart_query = $conn->query("SELECT * FROM `cart` WHERE user_id = '$user_id'");
    if ($cart_query->num_rows > 0) {
        while ($cart_item = $cart_query->fetch_assoc()) {
            $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }

    $total_products = implode(',', $cart_products);

    $order_query = $conn->query("SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'");

    if ($cart_total == 0) {
        $message[] = 'keranjang anda kosong!';
    } elseif ($order_query->num_rows > 0) {
        $message[] = 'pesanan sudah ditempatkan!';
    } else {
        $conn->query("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')");
        $conn->query("DELETE FROM `cart` WHERE user_id = '$user_id'");
        $message[] = 'pesanan berhasil ditempatkan!';
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="heading">
    <h3>checkout pesanan</h3>
    <p> <a href="home.php">beranda</a> / checkout </p>
</section>

<section class="display-order">
    <?php
        $grand_total = 0;
        $select_cart = $conn->query("SELECT * FROM `cart` WHERE user_id = '$user_id'");
        if ($select_cart->num_rows > 0) {
            while ($fetch_cart = $select_cart->fetch_assoc()) {
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
    ?>    
    <p> <?php echo $fetch_cart['name'] ?> <span>(<?php echo 'Rp.'.$fetch_cart['price'].'/-'.' x '.$fetch_cart['quantity']  ?>)</span> </p>
    <?php
        }
        } else {
            echo '<p class="empty">keranjang anda kosong</p>';
        }
    ?>
    <div class="grand-total">total grand : <span>Rp.<?php echo $grand_total; ?>/-</span></div>
</section>

<section class="checkout">

    <form action="" method="POST">

        <h3>tempatkan pesanan anda</h3>

        <div class="flex">
            <div class="inputBox">
                <span>nama anda :</span>
                <input type="text" name="name" placeholder="masukkan nama anda">
            </div>
            <div class="inputBox">
                <span>nomor anda :</span>
                <input type="number" name="number" min="0" placeholder="masukkan nomor anda">
            </div>
            <div class="inputBox">
                <span>email anda :</span>
                <input type="email" name="email" placeholder="masukkan email anda">
            </div>
            <div class="inputBox">
                <span>metode pembayaran :</span>
                <select name="method">
                    <option value="cash on delivery">cash on delivery</option>
                    <option value="credit card">kartu kredit</option>
                    <option value="paypal">paypal</option>
                    <option value="paytm">paytm</option>
                </select>
            </div>
            <div class="inputBox">
                <span>kota :</span>
                <input type="text" name="city" placeholder="misalnya jakarta">
            </div>
            <div class="inputBox">
                <span>provinsi :</span>
                <input type="text" name="state" placeholder="misalnya jawa barat">
            </div>
            <div class="inputBox">
                <span>negara :</span>
                <input type="text" name="country" placeholder="misalnya indonesia">
            </div>
            <div class="inputBox">
                <span>kode pos :</span>
                <input type="number" min="0" name="pin_code" placeholder="misalnya 123456">
            </div>
        </div>

        <input type="submit" name="order" value="pesan sekarang" class="btn">

    </form>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>      
