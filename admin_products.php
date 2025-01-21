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

if (isset($_POST['add_product'])) {

    $name = $conn->real_escape_string($_POST['name']);
    $price = $conn->real_escape_string($_POST['price']);
    $details = $conn->real_escape_string($_POST['details']);
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'flowers/' . $image;

    $select_product_name = $conn->query("SELECT name FROM `products` WHERE name = '$name'");

    if ($select_product_name->num_rows > 0) {
        $message[] = 'product name already exists!';
    } else {
        $insert_product = $conn->query("INSERT INTO `products`(name, details, price, image) VALUES('$name', '$details', '$price', '$image')");

        if ($insert_product) {
            if ($image_size > 2000000) {
                $message[] = 'image size is too large!';
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'product added successfully!';
            }
        }
    }

}

if (isset($_GET['delete'])) {

    $delete_id = $_GET['delete'];
    $select_delete_image = $conn->query("SELECT image FROM `products` WHERE id = '$delete_id'");
    $fetch_delete_image = $select_delete_image->fetch_assoc();
    unlink('flowers/' . $fetch_delete_image['image']);
    $conn->query("DELETE FROM `products` WHERE id = '$delete_id'");
    $conn->query("DELETE FROM `wishlist` WHERE pid = '$delete_id'");
    $conn->query("DELETE FROM `cart` WHERE pid = '$delete_id'");
    header('location:admin_products.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="add-products">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>add new product</h3>
      <input type="text" class="box" required placeholder="enter product name" name="name">
      <input type="number" min="0" class="box" required placeholder="enter product price" name="price">
      <textarea name="details" class="box" required placeholder="enter product details" cols="30" rows="10"></textarea>
      <input type="file" accept="image/jpg, image/jpeg, image/png" required class="box" name="image">
      <input type="submit" value="add product" name="add_product" class="btn">
   </form>

</section>

<section class="show-products">

   <div class="box-container">

      <?php
         $select_products = $conn->query("SELECT * FROM `products`");
         if ($select_products->num_rows > 0) {
            while ($fetch_products = $select_products->fetch_assoc()) {
      ?>
      <div class="box">
         <div class="price">Rs.<?php echo $fetch_products['price']; ?>/-</div>
         <img class="image" src="flowers/<?php echo $fetch_products['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_products['name']; ?></div>
         <div class="details"><?php echo $fetch_products['details']; ?></div>
         <a href="admin_update_product.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">update</a>
         <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
      <?php
         }
      } else {
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>
   

</section>

<script src="js/admin_script.js"></script>

</body>
</html>
