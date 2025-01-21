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

if (isset($_POST['submit'])) {

    // Sanitasi dan filter input
    $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $email = $conn->real_escape_string($filter_email);
    $filter_pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
    $pass = $conn->real_escape_string(md5($filter_pass));

    // Query untuk mengecek pengguna berdasarkan email dan password
    $select_users = $conn->query("SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'");

    if ($select_users->num_rows > 0) {

        
        $row = $select_users->fetch_assoc();

        if ($row['user_type'] == 'admin') {

            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_id'] = $row['id'];
            header('location:admin_page.php');

        } elseif ($row['user_type'] == 'user') {

            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_id'] = $row['id'];
            header('location:home.php');

        } else {
            $message[] = 'no user found!';
        }

    } else {
        $message[] = 'incorrect email or password!';
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '
            <div class="message">
                <span>' . $message . '</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>
            ';
        }
    }
    ?>

    <section class="form-container">

        <form action="" method="post">
            <h3>login now</h3>
            <input type="email" name="email" class="box" placeholder="enter your email" required>
            <input type="password" name="pass" class="box" placeholder="enter your password" required>
            <input type="submit" class="btn" name="submit" value="login now">
            <p>don't have an account? <a href="register.php">register now</a></p>
        </form>

    </section>

</body>

</html>
