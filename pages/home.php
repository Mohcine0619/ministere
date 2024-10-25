<?php
session_start();
require_once '../backend/db.php';

// Fetch counts from the database
$totalPoles = $conn->query("SELECT COUNT(*) as count FROM poles")->fetch_assoc()['count'];
$totalDepartments = $conn->query("SELECT COUNT(*) as count FROM departement")->fetch_assoc()['count'];
$totalServices = $conn->query("SELECT COUNT(*) as count FROM services")->fetch_assoc()['count'];
$totalEmployees = $conn->query("SELECT COUNT(*) as count FROM employe")->fetch_assoc()['count'];
$totalRH = $conn->query("SELECT COUNT(*) as count FROM employe WHERE corps='rh'")->fetch_assoc()['count'];
$totalDirectors = $conn->query("SELECT COUNT(*) as count FROM employe WHERE corps='Administrateurs'")->fetch_assoc()['count'];
$totalChefs = $conn->query("SELECT COUNT(*) as count FROM employe WHERE corps='Techniciens'")->fetch_assoc()['count'];
$totalRegularEmployees = $conn->query("SELECT COUNT(*) as count FROM employe WHERE corps='Rédacteurs'")->fetch_assoc()['count'];

// Fetch the user's corps from the session
if (isset($_SESSION['corps'])) {
    // Accéder aux variables de session en toute sécurité
    $user_corps = $_SESSION['corps'];
} else {
    // Gérer le cas où les variables de session ne sont pas définies
    $user_corps = 'default_corps';
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/home.css?v=<?php echo time(); ?>">
    <title>ACCUEIL</title>
    <?php include 'boot.php'; ?>
    <?php include 'nav.php'; ?>
</head>

<body>
    <?php include 'side.php'; ?>
    <?php include 'navbar.php'; ?>
    <div class="container main-content">
        <h1>Accueil</h1>
        <div class="dashboard">
            <?php if ($user_corps == 'rh'): ?>
                <a href="../Modification/liste_branche.php" class="card">
                    <h2><?php echo $totalPoles; ?></h2>
                    <p>Total Pôles</p>
                </a>
                <a href="../Modification/liste_branche.php" class="card">
                    <h2><?php echo $totalDepartments; ?></h2>
                    <p>Total divisions</p>
                </a>
                <a href="../Modification/liste_branche.php" class="card">
                    <h2><?php echo $totalServices; ?></h2>
                    <p>Total Services</p>
                </a>
            <?php else: ?>
                <div class="card disabled">
                    <h2><?php echo $totalPoles; ?></h2>
                    <p>Total Pôles</p>
                </div>
                <div class="card disabled">
                    <h2><?php echo $totalDepartments; ?></h2>
                    <p>Total Départements</p>
                </div>
                <div class="card disabled">
                    <h2><?php echo $totalServices; ?></h2>
                    <p>Total Services</p>
                </div>
            <?php endif; ?>
            <a href="../employe/chercher_emp.php" class="card">
                <h2><?php echo $totalEmployees; ?></h2>
                <p>Total Employés</p>
            </a>
            <a href="../employe/chercher_emp.php" class="card">
                <h2><?php echo $totalRH; ?></h2>
                <p>Total RH</p>
            </a>
            <a href="../employe/chercher_emp.php" class="card">
                <h2><?php echo $totalDirectors; ?></h2>
                <p>Total Administrateurs</p>
            </a>
            <a href="../employe/chercher_emp.php" class="card">
                <h2><?php echo $totalChefs; ?></h2>
                <p>Total Techniciens</p>
            </a>
            <a href="../employe/chercher_emp.php" class="card">
                <h2><?php echo $totalRegularEmployees; ?></h2>
                <p>Total Rédacteurs</p>
            </a>
        </div>
    </div>
    <?php include 'scboot.php'; ?>
    <?php include 'footer.php'; ?>
</body>

</html>