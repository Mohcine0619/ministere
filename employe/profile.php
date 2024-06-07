<?php
require_once '../backend/db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Assuming user_id is stored in session and user data is fetched from a database
$user_id = $_SESSION['user_id'];

// Fetch user data
$sql = "SELECT employes.*, departements.nom as departement, services.nom as service, poles.nom as pole FROM employes 
        LEFT JOIN departements ON employes.id_departement = departements.id 
        LEFT JOIN services ON employes.id_service = services.id 
        LEFT JOIN poles ON employes.id_pole = poles.id 
        WHERE employes.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

// Close the statement and connection
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <?php include '../pages/boot.php'; ?>
    <link rel="stylesheet" href="../style/profile.css?v=<?php echo time(); ?>">
    <?php include '../pages/nav.php'; ?>
</head>
<body>
    <?php include '../pages/side.php'; ?>
    <?php include '../pages/navbar.php'; ?>
    
    <div class="container main-content">
        <!-- Back Button -->
        <?php if ($userData): ?>
            <h2>Profile</h2>
            <div class="profile-container">
                <div class="profile-photo-container">
                    <?php if (!empty($userData['photo'])): ?>
                        <img src="<?php echo htmlspecialchars($userData['photo']); ?>" alt="Profile Photo" class="profile-photo">
                    <?php else: ?>
                        <p>No profile photo available.</p>
                    <?php endif; ?>
                    <a href="edit_photo.php" class="btn btn-primary change-photo">Change Photo</a>
                </div>
                <div class="profile-info">
                    <p>Fullname: <?php echo htmlspecialchars($userData['fullName']); ?></p>
                    <p>Email: <?php echo htmlspecialchars($userData['email']); ?></p>
                    <p>Username: <?php echo htmlspecialchars($userData['username']); ?></p>
                    <p>Role: <?php echo htmlspecialchars($userData['role']); ?></p>
                    <p>Pole: <?php echo htmlspecialchars($userData['pole']); ?></p>
                    <p>Departement: <?php echo htmlspecialchars($userData['departement']); ?></p>
                    <p>Service: <?php echo htmlspecialchars($userData['service']); ?></p>
                    <p>Nombre de post: <?php echo htmlspecialchars($userData['nb_post']); ?></p>
                    <p>Occupation: <?php echo htmlspecialchars($userData['occupation']); ?></p>
                    <p>Nombre de bureau: <?php echo htmlspecialchars($userData['nb_bureau']); ?></p>
                    <p class="corps">Corps: <?php echo htmlspecialchars($userData['corps']); ?></p>
                    <!-- Edit Profile Button -->
                    <div class="form-actions">
                        <a href="../pages/home.php" class="btn btn-primary">Back</a>
                        <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <p>User not found.</p>
        <?php endif; ?>
    </div>
    <?php include '../pages/scboot.php'; ?>
    <?php include '../pages/footer.php'; ?>
</body>
</html>
