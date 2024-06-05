<?php
$srvr = "localhost";
$db = "ministere";
$user = "root";
$pass = "";
$conn = new mysqli($srvr, $user, $pass, $db);
if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
?>

