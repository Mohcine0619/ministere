<?php
require_once '../backend/db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header('Location: login.php');
    exit();
}

// Assuming user_id is stored in the session and user data is retrieved from a database
$user_id = $_SESSION['user_id'];

// Retrieve user data
$sql = "SELECT employe.*, poles.nom as pole, departement.nom as departement, services.nom as service FROM employe 
        LEFT JOIN poles ON employe.pole_id = poles.id 
        LEFT JOIN departement ON employe.departement_id = departement.id 
        LEFT JOIN services ON employe.service_id = services.id 
        WHERE employe.id = ?";
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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <?php include '../pages/boot.php'; ?>
    <link rel="stylesheet" href="../style/profile.css?v=<?php echo time(); ?>">
    <?php include '../pages/nav.php'; ?>
</head>
<body>
<?php include '../pages/side.php'; ?>
    <?php include '../pages/navbar.php'; ?>
    <div class="container main-content">
        <?php if ($userData): ?>
            <h2>Profil</h2>
            <div class="profile-container">
                <div class="profile-photo-info-container">
                    <div class="profile-photo-container">
                        <?php if (!empty($userData['photo'])): ?>
                            <img src="<?php echo htmlspecialchars($userData['photo']); ?>" alt="Photo de Profil" class="profile-photo">
                            <a href="edit_photo.php" class="btn btn-primary change-photo">Changer la photo</a>
                        <?php else: ?>
                            <p>Aucune photo de profil disponible.</p>
                            <a href="edit_photo.php" class="btn btn-primary change-photo">Ajouter une photo</a>
                        <?php endif; ?>
                    </div>
                    <div class="profile-info-container">
                        <div class="profile-info">
                            <h3>Identité</h3>
                            <p>Nom complet: <?php 
                                echo ($userData['gender'] === 'female' ? 'Mme ' : 'Mr ') . htmlspecialchars($userData['fullName']); 
                            ?></p>
                            <p>Grade: <?php echo htmlspecialchars($userData['grade']); ?></p>
                            <p>Fonction: <?php echo htmlspecialchars($userData['fonction']); ?></p>
                        </div>
                        <div class="profile-info">
                            <h3>Coordonnées</h3>
                            <p>Email: <?php echo htmlspecialchars($userData['email']); ?></p>
                            <p>Téléphone: <?php echo htmlspecialchars($userData['tel']); ?></p>
                            <p>Matricule: <?php echo htmlspecialchars($userData['matricule']); ?></p>
                            <p>Corps: <?php echo htmlspecialchars($userData['corps']); ?></p>
                            <p>Pôle: <?php echo htmlspecialchars($userData['pole']); ?></p>
                            <p>Département: <?php echo htmlspecialchars($userData['departement']); ?></p>
                            <p>Service: <?php echo htmlspecialchars($userData['service']); ?></p>
                            <p>Nombre de postes: <?php echo htmlspecialchars($userData['nb_post']); ?></p>
                            <p>Nombre de bureaux: <?php echo htmlspecialchars($userData['nb_bureau']); ?></p>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <a href="edit_profile.php" class="btn btn-primary">Modifier le Profil</a>
                </div>
            </div>
        <?php else: ?>
            <p>Utilisateur non trouvé.</p>
        <?php endif; ?>
    </div>
    <?php include '../pages/scboot.php'; ?>
    <?php include '../pages/footer.php'; ?>
</body>
</html>
