<?php
require_once '../backend/db.php';
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion si non connecté
    header('Location: login.php');
    exit();
}

// Supposons que user_id est stocké dans la session et que les données utilisateur sont récupérées d'une base de données
$user_id = $_SESSION['user_id'];

// Récupérer les données utilisateur
$sql = "SELECT employes.*, departements.nom as division, services.nom as service, poles.nom as pole FROM employes 
        LEFT JOIN departements ON employes.id_departement = departements.id 
        LEFT JOIN services ON employes.id_service = services.id 
        LEFT JOIN poles ON employes.id_pole = poles.id 
        WHERE employes.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

// Fermer la déclaration et la connexion
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
        <!-- Bouton Retour -->
        <?php if ($userData): ?>
            <h2>Profil</h2>
            <div class="profile-container">
                <div class="profile-photo-container">
                    <?php if (!empty($userData['photo'])): ?>
                        <img src="<?php echo htmlspecialchars($userData['photo']); ?>" alt="Photo de Profil" class="profile-photo">
                    <?php else: ?>
                        <p>Aucune photo de profil disponible.</p>
                    <?php endif; ?>
                    <a href="edit_photo.php" class="btn btn-primary change-photo">Changer la photo</a>
                </div>
                <div class="profile-info">
                    <p>Nom complet: <?php echo htmlspecialchars($userData['fullName']); ?></p>
                    <p>Email: <?php echo htmlspecialchars($userData['email']); ?></p>
                    <p>Nom d'utilisateur: <?php echo htmlspecialchars($userData['username']); ?></p>
                    <p>Rôle: <?php echo htmlspecialchars($userData['role']); ?></p>
                    <p>Pôle: <?php echo htmlspecialchars($userData['pole']); ?></p>
                    <p>Division: <?php echo htmlspecialchars($userData['division']); ?></p>
                    <p>Service: <?php echo htmlspecialchars($userData['service']); ?></p>
                    <p>Nombre de postes: <?php echo htmlspecialchars($userData['nb_post']); ?></p>
                    <p>Occupation: <?php echo htmlspecialchars($userData['occupation']); ?></p>
                    <p>Nombre de bureaux: <?php echo htmlspecialchars($userData['nb_bureau']); ?></p>
                    <p class="corps">Corps: <?php echo htmlspecialchars($userData['corps']); ?></p>
                    <!-- Bouton Modifier le Profil -->
                    <div class="form-actions">
                        <a href="../pages/home.php" class="btn btn-primary">Retour</a>
                        <a href="edit_profile.php" class="btn btn-primary">Modifier le Profil</a>
                    </div>
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
