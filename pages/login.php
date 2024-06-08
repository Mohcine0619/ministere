<?php
session_start(); // Start the session at the beginning of the script
require_once '../backend/db.php';

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = htmlspecialchars($_POST['username']); // Changed from email to username
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, role FROM employes WHERE username = ?"); // Fetch role as well
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($user_id, $db_username, $db_password, $db_role); // Bind role
            $stmt->fetch();
            if (password_verify($password, $db_password)) {
                // Set session variables
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $db_username; // Changed email to username
                $_SESSION['role'] = $db_role; // Store role in session
                $_SESSION['logged_in'] = true;

                // Redirect to dashboard or home page
                header('Location: home.php');
                exit();
            } else {
                $message = "Invalid username or password.";
            }
        } else {
            $message = "Invalid username or password.";
        }
        $stmt->close();
    } else {
        $message = "Error preparing statement: " . $conn->error;
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/login.css">
</head>
<body>
<div class="container mt-5">
    <h2>Login Form</h2>
    <?php 
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>';
            unset($_SESSION['success_message']); // Clear the message after displaying it
        }
    if (!empty($message)) {
        echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
    } ?>
    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary">Login</button>
        <a href="signup.php">Not registered yet?</a>
    </form>
</div>
</body>
</html>

