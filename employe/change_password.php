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
    <style>
        /* General container styling */
        .container {
            font-family: "Arial", sans-serif; /* Sets the font family to Arial */
            font-size: 14px; /* Sets the base font size */
            max-width: 500px; /* Matches the max-width of the signup form */
            margin: 20px auto; /* Centered with smaller top margin */
            background-color: #f8f9fa;
            padding: 10px; /* Matches the padding of the signup form */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Form styling */
        form {
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Input field styling */
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* Button styling */
        button.btn.btn-primary {
            width: 100%;
            background-color: #0097a7; /* Updated color */
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button.btn.btn-primary:hover {
            background-color: #007a8a; /* Slightly darker shade for hover effect */
        }

        /* Link styling */
        a {
            display: block;
            margin-top: 10px;
            text-align: center;
        }

        /* Alert message styling */
        .alert {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            color: #721c24; /* Default text color for error */
            background-color: #f8d7da; /* Default background color for error */
            border-color: #f5c6cb; /* Default border color for error */
        }

        /* Success message styling */
        .alert.alert-success {
            color: #155724; /* Green text color for success */
            background-color: #d4edda; /* Green background color for success */
            border-color: #c3e6cb; /* Green border color for success */
        }
    </style>
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
<?php include '../pages/footer.php'; ?>
</body>
</html>
