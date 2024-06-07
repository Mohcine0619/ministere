<?php
session_start();
require_once '../backend/db.php';

// Fetch counts from the database
$totalPoles = $conn->query("SELECT COUNT(*) as count FROM poles")->fetch_assoc()['count'];
$totalDepartments = $conn->query("SELECT COUNT(*) as count FROM departements")->fetch_assoc()['count'];
$totalServices = $conn->query("SELECT COUNT(*) as count FROM services")->fetch_assoc()['count'];
$totalEmployees = $conn->query("SELECT COUNT(*) as count FROM employes")->fetch_assoc()['count'];
$totalRH = $conn->query("SELECT COUNT(*) as count FROM employes WHERE role='rh'")->fetch_assoc()['count'];
$totalDirectors = $conn->query("SELECT COUNT(*) as count FROM employes WHERE role='directeur'")->fetch_assoc()['count'];
$totalChefs = $conn->query("SELECT COUNT(*) as count FROM employes WHERE role='chef de service'")->fetch_assoc()['count'];
$totalRegularEmployees = $conn->query("SELECT COUNT(*) as count FROM employes WHERE role='employe'")->fetch_assoc()['count'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/home.css?v=<?php echo time(); ?>">
    <title>HOME</title>
    <?php include 'boot.php'; ?>
    <?php include 'nav.php'; ?>
</head>

<body>
    <?php include 'side.php'; ?>
    <?php include 'navbar.php'; ?>
    <div class="container main-content">
        <h1>Home</h1>
        <div class="dashboard">
            <a href="../Modification/liste_branche.php" class="card">
                <h2><?php echo $totalPoles; ?></h2>
                <p>Total Poles</p>
            </a>
            <a href="../Modification/liste_branche.php" class="card">
                <h2><?php echo $totalDepartments; ?></h2>
                <p>Total Departments</p>
            </a>
            <a href="../Modification/liste_branche.php" class="card">
                <h2><?php echo $totalServices; ?></h2>
                <p>Total Services</p>
            </a>
            <a href="../employe/chercher_emp.php" class="card">
                <h2><?php echo $totalEmployees; ?></h2>
                <p>Total Employees</p>
            </a>
            <a href="../employe/chercher_emp.php" class="card">
                <h2><?php echo $totalRH; ?></h2>
                <p>Total RH</p>
            </a>
            <a href="../employe/chercher_emp.php" class="card">
                <h2><?php echo $totalDirectors; ?></h2>
                <p>Total Directors</p>
            </a>
            <a href="../Modification/liste_employes.php" class="card">
                <h2><?php echo $totalChefs; ?></h2>
                <p>Total Chefs</p>
            </a>
            <a href="../Modification/liste_employes.php" class="card">
                <h2><?php echo $totalRegularEmployees; ?></h2>
                <p>Total Regular Employees</p>
            </a>
        </div>
    </div>
    <?php include 'scboot.php'; ?>
    <?php include 'footer.php'; ?>
</body>

</html>