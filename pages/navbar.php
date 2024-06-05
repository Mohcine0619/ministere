<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session if it hasn't been started yet
}

// Check if session variables are set
if (isset($_SESSION['role'])) {
    // Access session variables safely
    $user_role = $_SESSION['role'];
} else {
    // Handle the case where session variables are not set
    $user_role = 'default_role';
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<div class="sidebar">
    <a href="../pages/home.php"><i class="fas fa-home"></i> <span class="link-text">Home</span></a>
    <a href="../employe/profile.php"><i class="fas fa-user"></i> <span class="link-text">Profile</span></a>
    <a href="../employe/chercher_emp.php"><i class="fas fa-search"></i> <span class="link-text">Chercher Employes</span></a>
    
    <?php if ($user_role === 'rh'): ?>
    <div class="dropdown">
        <a href="#"><i class="fas fa-wrench"></i> <span class="link-text">Advanced Settings</span></a>
        <div class="dropdown-content">
            <a href="../modification/addPole.php"><i class="fas fa-plus"></i> <span class="link-text">Add Pole</span></a>
            <a href="../modification/addDepartement.php"><i class="fas fa-plus"></i> <span class="link-text">Add Departement</span></a>
            <a href="../modification/addService.php"><i class="fas fa-plus"></i> <span class="link-text">Add Service</span></a>
            <a href="../modification/liste_employes.php"><i class="fas fa-list"></i> <span class="link-text">Liste des Employes</span></a>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="dropdown">
        <a href="#"><i class="fas fa-cog"></i> <span class="link-text">Settings</span></a>
        <div class="dropdown-content">
            <a href="../employe/change_password.php"><i class="fas fa-key"></i> <span class="link-text">Changer Mot de Passe</span></a>
        </div>
    </div>    

    <a href="../pages/logout.php"><i class="fas fa-sign-out-alt"></i> <span class="link-text">Logout</span></a>
</div>

<style>
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #333; /* Set the background color */
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: white; /* Set the text color */
        background-color: #333; /* Ensure the same background color */
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #575757; /* Optional: Change color on hover */
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }
</style>
