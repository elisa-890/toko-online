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
};

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $conn->query("DELETE FROM `cart` WHERE id = '$delete_id'");
    header('location:cart.php');
}

if (isset($_GET['delete_all'])) {
    $conn->query("DELETE FROM `cart` WHERE user_id = '$user_id'");
    header('location:cart.php');
}

if (isset($_POST['update_quantity'])) {
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];
    $conn->query("UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'");
    $message[] = 'jumlah barang di keranjang diperbarui!';
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>keranjang belanja</title>

   <!-- link font awesome cdn -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- link file css admin custom -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="heading">
    <h3>keranjang belanja</h3>
    <p> <a href="home.php">beranda</a> / keranjang </p>
</section>

<section class="shopping-cart">

    <h1 class="title">produk yang ditambahkan</h1>

    <div class="box-container">

    <?php
        $grand_total = 0;
        $select_cart = $conn->query("SELECT * FROM `cart` WHERE user_id = '$user_id'");
        if ($select_cart->num_rows > 0) {
            while ($fetch_cart = $select_cart->fetch_assoc()) {
    ?>
    <div  class="box">
        <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('hapus produk ini dari keranjang?');"></a>
        <a href="view_page.php?pid=<?php echo $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
        <img src="flowers/<?php echo $fetch_cart['image']; ?>" alt="" class="image">
        <div class="name"><?php echo $fetch_cart['name']; ?></div>
        <div class="price">Rp.<?php echo $fetch_cart['price']; ?>/-</div>
        <form action="" method="post">
            <input type="hidden" value="<?php echo $fetch_cart['id']; ?>" name="cart_id">
            <input type="number" min="1" value="<?php echo $fetch_cart['quantity']; ?>" name="cart_quantity" class="qty">
            <input type="submit" value="perbarui" class="option-btn" name="update_quantity">
        </form>
        <div class="sub-total"> sub-total : <span>Rp.<?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span> </div>
    </div>
    <?php
    $grand_total += $sub_total;
        }
    } else {
        echo '<p class="empty">keranjang Anda kosong</p>';
    }
    ?>
    </div>

    <div class="more-btn">
        <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1)?'':'disabled' ?>" onclick="return confirm('hapus semua dari keranjang?');">hapus semua</a>
    </div>

    <div class="cart-total">
        <p>total keseluruhan : <span>Rp.<?php echo $grand_total; ?>/-</span></p>
        <a href="shop.php" class="option-btn">lanjutkan berbelanja</a>
        <a href="checkout.php" class="btn  <?php echo ($grand_total > 1)?'':'disabled' ?>">lanjut ke pembayaran</a>
    </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
