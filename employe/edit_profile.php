<?php

require_once '../backend/db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// Fetch current user data
$sql = "SELECT * FROM employes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $education = $_POST['education'];

    // Update username, email, dob, age, education
    $update_sql = "UPDATE employes SET username = ?, email = ?, dob = ?, age = ?, education = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssii", $username, $email, $dob, $age, $education, $user_id);
    $update_stmt->execute();
    if ($update_stmt->error) {
        $message = 'Error updating profile: ' . $update_stmt->error;
    } else {
        $_SESSION['username'] = $username;  // Update session variable
        $message = '<span style="color: green;">Profile updated successfully.</span>';
        header('Location: profile.php');
        exit();
    }
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../style/profile.css">
    <?php include '../pages/nav.php'; ?>
</head>
<body>
<?php include '../pages/side.php'; ?>
    <?php include '../pages/navbar.php'; ?>
    <div class="container main-content">
        <h1>Edit Profile</h1>
        <p><?php echo $message; ?></p>
        <form action="edit_profile.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($userData['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" name="dob" value="<?php echo htmlspecialchars($userData['dob']); ?>" required>
            </div>
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" name="age" value="<?php echo htmlspecialchars($userData['age']); ?>" required>
            </div>
            <div class="form-group">
                <label for="education">Education:</label>
                <input type="text" name="education" value="<?php echo htmlspecialchars($userData['education']); ?>" required>
            </div>
            <div class="form-actions">
                <a href="profile.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </form>
    </div>
</body>
</html>
