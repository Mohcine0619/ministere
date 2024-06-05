<?php
session_start();

// Assuming these session variables are set when the user logs in
$username = $_SESSION['username']; // Retrieve the logged-in user's name from the session
$_SESSION['company'] = 'Direction de Tresor'; // Example company name
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../pages/home.php">
    <title>HOME</title>
    <?php include 'boot.php'; ?>
    <?php include 'nav.php'; ?>
    
</head>

<body>
    <?php include 'side.php'; ?>
    
    <?php include 'navbar.php'; ?>
    <div class="container main-content">
        <h1>Home</h1>
    </div>
    <?php include 'scboot.php'; ?>
    <?php include 'footer.php'; ?>
</body>


</html>