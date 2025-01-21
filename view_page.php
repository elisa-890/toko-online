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

if(isset($_POST['add_to_wishlist'])){

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    
    $check_wishlist_numbers = $conn->query("SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'");
    $check_cart_numbers = $conn->query("SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'");

    if($check_wishlist_numbers->num_rows > 0){
        $message[] = 'sudah ditambahkan ke wishlist';
    } elseif($check_cart_numbers->num_rows > 0){
        $message[] = 'sudah ditambahkan ke keranjang';
    } else {
        $conn->query("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_image')");
        $message[] = 'produk berhasil ditambahkan ke wishlist';
    }

}

if(isset($_POST['add_to_cart'])){

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = $conn->query("SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'");

    if($check_cart_numbers->num_rows > 0){
        $message[] = 'sudah ditambahkan ke keranjang';
    } else {
        $check_wishlist_numbers = $conn->query("SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'");

        if($check_wishlist_numbers->num_rows > 0){
            $conn->query("DELETE FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'");
        }

        $conn->query("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')");
        $message[] = 'produk berhasil ditambahkan ke keranjang';
    }

}

?>

<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>lihat cepat</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="quick-view">
    <h1 class="title">detail produk</h1>

    <?php  
        if(isset($_GET['pid'])){
            $pid = $_GET['pid'];
            $select_products = $conn->query("SELECT * FROM `products` WHERE id = '$pid'");
         if($select_products->num_rows > 0){
            while($fetch_products = $select_products->fetch_assoc()){
    ?>
    <form action="" method="POST">
         <img src="flowers/<?php echo $fetch_products['image']; ?>" alt="" class="image">
         <div class="name"><?php echo $fetch_products['name']; ?></div>
         <input type="number" name="product_quantity" value="1" min="0" class="qty">
         <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
         <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
         <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
         <input type="submit" value="tambahkan ke wishlist" name="add_to_wishlist" class="option-btn">
         <input type="submit" value="tambahkan ke keranjang" name="add_to_cart" class="btn">
      </form>
    <?php
            }
        } else {
        echo '<p class="empty">tidak ada detail produk yang tersedia!</p>';
        }
    }
    ?>

    <div class="more-btn">
        <a href="home.php" class="option-btn">kembali ke halaman utama</a>
    </div>

</section>

<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
