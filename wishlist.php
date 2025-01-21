<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'shop_db';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['add_to_cart'])){

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = 1;

    $check_cart_numbers = $conn->query("SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'");

    if($check_cart_numbers->num_rows > 0){
        $message[] = 'produk sudah ada di keranjang';
    } else {

        $check_wishlist_numbers = $conn->query("SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'");

        if($check_wishlist_numbers->num_rows > 0){
            $conn->query("DELETE FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'");
        }

        $conn->query("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')");
        $message[] = 'produk ditambahkan ke keranjang';
    }

}

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $conn->query("DELETE FROM `wishlist` WHERE id = '$delete_id'");
    header('location:wishlist.php');
}

if(isset($_GET['delete_all'])){
    $conn->query("DELETE FROM `wishlist` WHERE user_id = '$user_id'");
    header('location:wishlist.php');
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>wishlist</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>wishlist Anda</h3>
    <p> <a href="home.php">beranda</a> / wishlist </p>
</section>

<section class="wishlist">

    <h1 class="title">produk yang ditambahkan</h1>

    <div class="box-container">

    <?php
        $grand_total = 0;
        $select_wishlist = $conn->query("SELECT * FROM `wishlist` WHERE user_id = '$user_id'");
        if($select_wishlist->num_rows > 0){
            while($fetch_wishlist = $select_wishlist->fetch_assoc()){
    ?>
    <form action="" method="POST" class="box">
        <a href="wishlist.php?delete=<?php echo $fetch_wishlist['id']; ?>" class="fas fa-times" onclick="return confirm('hapus ini dari wishlist?');"></a>
        <a href="view_page.php?pid=<?php echo $fetch_wishlist['pid']; ?>" class="fas fa-eye"></a>
        <img src="flowers/<?php echo $fetch_wishlist['image']; ?>" alt="" class="image">
        <div class="name"><?php echo $fetch_wishlist['name']; ?></div>
        <div class="price">Rp.<?php echo $fetch_wishlist['price']; ?>/-</div>
        <input type="hidden" name="product_id" value="<?php echo $fetch_wishlist['pid']; ?>">
        <input type="hidden" name="product_name" value="<?php echo $fetch_wishlist['name']; ?>">
        <input type="hidden" name="product_price" value="<?php echo $fetch_wishlist['price']; ?>">
        <input type="hidden" name="product_image" value="<?php echo $fetch_wishlist['image']; ?>">
        <input type="submit" value="tambahkan ke keranjang" name="add_to_cart" class="btn">
    </form>
    <?php
    $grand_total += $fetch_wishlist['price'];
        }
    } else {
        echo '<p class="empty">wishlist Anda kosong</p>';
    }
    ?>
    </div>

    <div class="wishlist-total">
        <p>total keseluruhan : <span>Rp.<?php echo $grand_total; ?>/-</span></p>
        <a href="shop.php" class="option-btn">lanjutkan berbelanja</a>
        <a href="wishlist.php?delete_all" class="delete-btn <?php echo ($grand_total > 1)?'':'disabled' ?>" onclick="return confirm('hapus semua dari wishlist?');">hapus semua</a>
    </div>

</section>

<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
