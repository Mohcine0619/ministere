<?php
session_start(); // Start the session
require_once '../backend/db.php'; // Include the database connection

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password && strlen($new_password) >= 6) {
        // Check if the current password is correct
        $stmt = $conn->prepare("SELECT password FROM employes WHERE username = ?");
        $stmt->bind_param("s", $_SESSION['username']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (password_verify($current_password, $user['password'])) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $update_stmt = $conn->prepare("UPDATE employes SET password = ? WHERE username = ?");
            $update_stmt->bind_param("ss", $hashed_password, $_SESSION['username']);
            if ($update_stmt->execute()) {
                $message = "Password changed successfully!";
            } else {
                $message = "Error updating password.";
            }
            $update_stmt->close();
        } else {
            $message = "Current password is incorrect.";
        }
        $stmt->close();
    } else {
        if ($new_password !== $confirm_password) {
            $message = "Passwords do not match.";
        } else if (strlen($new_password) < 6) {
            $message = "Password must be at least 6 characters long.";
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <?php include '../pages/boot.php'; ?>
    <?php include '../pages/nav.php'; ?>
</head>
<body>
    <?php include '../pages/navbar.php'; ?>
    <?php include '../pages/side.php'; ?>
<div class="container main-content">
    <h2>Change Password</h2>
    <?php if (!empty($message)) {
    if ($message === "Password changed successfully!") {
        echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
    }
}

    ?>
    <form action="change_password.php" method="POST">
        <div class="form-group">
            <label for="current_password">Current Password:</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>
        <div class="form-group">
            <label for="new_password">New Password:</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Change Password</button>
    </form>
</div>
</body>
</html>