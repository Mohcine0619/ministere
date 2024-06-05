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
    $nb_post = $_POST['nb_post'];
    $occupation = $_POST['occupation'];
    $nb_bureau = $_POST['nb_bureau'];
    $corps = $_POST['corps'];

    // Update username, email, nb_post, occupation, nb_bureau, corps
    $update_sql = "UPDATE employes SET username = ?, email = ?, nb_post = ?, occupation = ?, nb_bureau = ?, corps = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssisssi", $username, $email, $nb_post, $occupation, $nb_bureau, $corps, $user_id);
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
    <?php include '../pages/boot.php'; ?>
    <link rel="stylesheet" href="../style/profile.css?v=<?php echo time(); ?>">
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
                <label for="nb_post">Nombre de post:</label>
                <input type="number" name="nb_post" value="<?php echo htmlspecialchars($userData['nb_post']); ?>" required>
            </div>
            <div class="form-group">
                <label for="occupation">Occupation:</label>
                <input type="text" name="occupation" value="<?php echo htmlspecialchars($userData['occupation']); ?>" required>
            </div>
            <div class="form-group">
                <label for="nb_bureau">Nombre de bureau:</label>
                <input type="number" name="nb_bureau" value="<?php echo htmlspecialchars($userData['nb_bureau']); ?>" required>
            </div>
            <div class="form-group">
                <label for="corps">Corps:</label>
                <textarea name="corps" style="height: 150px;" required><?php echo htmlspecialchars($userData['corps']); ?></textarea>
            </div>
            <div class="form-actions">
                <a href="profile.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </form>
    </div>
    <?php include '../pages/footer.php'; ?>
</body>
</html>
