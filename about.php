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

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tentang Kami</title>

   <!-- link font awesome cdn -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- link file css admin custom -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php 
// Manually include header.php
include 'header.php'; 
?>

<section class="heading">
    <h3>tentang kami</h3>
    <p> <a href="home.php">beranda</a> / tentang </p>
</section>

<section class="about">

    <div class="flex">

        <div class="image">
            <img src="images/about-img-1.png" alt="">
        </div>

        <div class="content">
            <h3>mengapa memilih kami?</h3>
            <p>Elisa.Florist adalah toko bunga online yang mengantarkan bunga di daerah Medan Kami mengirimkan bunga berkualitas terbaik - bunga potong segar di kota Medan dengan bantuan jaringan afiliasi dan toko saluran kami yang kuat. Setiap bunga kami dipetik langsung pada tahap mekarnya yang tepat oleh ahli florist kami.</p>
            <a href="shop.php" class="btn">belanja sekarang</a>
        </div>

    </div>

    <div class="flex">

        <div class="content">
            <h3>apa yang kami sediakan?</h3>
            <p>Kami membawa rangkaian barang eksklusif dan layanan pengantaran ke pintu rumah yang dapat diandalkan, sehingga Anda tidak perlu khawatir tentang pesanan setelah itu dipesan. Selain mengantarkan bunga, kami juga berusaha untuk membuat momen spesial Anda dan orang-orang tersayang lebih spesial dengan mengantarkan hadiah kejutan seperti kue, cokelat, boneka teddy, dan permen.</p>
            <a href="contact.php" class="btn">hubungi kami</a>
        </div>

        <div class="image">
            <img src="images/about-img-2.jpg" alt="">
        </div>

    </div>

    <div class="flex">

        <div class="image">
            <img src="images/about-img-3.jpg" alt="">
        </div>

        <div class="content">
            <h3>siapa kami?</h3>
            <p>Elisa.Florist selalu ada ketika ‘Kata-kata tidak cukup..’. Kami di Elisa.Florist tahu betapa pentingnya mengekspresikan perasaan Anda dengan semangat yang sama seperti kehadiran Anda yang akan memperindah acara tersebut. Membawa senyuman di wajah Anda dan menjadi utusan Anda dengan sempurna adalah moto kami. Kami mendefinisikan kesuksesan kami melalui pelanggan yang puas.</p>
        </div>

    </div>

</section>

<?php 
// Manually include footer.php
include 'footer.php'; 
?>

<script src="js/script.js"></script>

</body>
</html>
