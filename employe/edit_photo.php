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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle photo upload
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $photo_sql = "UPDATE employes SET photo = ? WHERE id = ?";
            $photo_stmt = $conn->prepare($photo_sql);
            $photo_stmt->bind_param("si", $target_file, $user_id);
            $photo_stmt->execute();
            $message = 'Photo updated successfully.';
            header('Location: profile.php');
            exit();
        } else {
            $message = 'Error uploading file.';
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Photo</title>
    <?php include '../pages/boot.php'; ?>

    <?php include '../pages/nav.php'; ?>

    <link rel="stylesheet" href="../style/profile.css">
</head>
<body>
<?php include '../pages/side.php'; ?>
    <?php include '../pages/navbar.php'; ?>
    <div class="container main-container">
        <h1>Changer photo de profil</h1>
        <p><?php echo $message; ?></p>
        <form action="edit_photo.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="photo">Photo de profil :</label>
                <input type="file" name="photo" required>
            </div>
            <div class="form-actions">
                <a href="profile.php" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Télécharger la Photo</button>
            </div>
        </form>
    </div>
    <?php include '../pages/footer.php'; ?>
</body>
</html>
