<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Démarrer la session si elle n'a pas encore été démarrée
}

// Vérifier si les variables de session sont définies
if (isset($_SESSION['role'])) {
    // Accéder aux variables de session en toute sécurité
    $user_role = $_SESSION['role'];
} else {
    // Gérer le cas où les variables de session ne sont pas définies
    $user_role = 'default_role';
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<div class="sidebar">
    <a href="../pages/home.php"><i class="fas fa-home"></i> <span class="link-text">Accueil</span></a>
    <a href="../employe/profile.php"><i class="fas fa-user"></i> <span class="link-text">Profil</span></a>
    <a href="../employe/chercher_emp.php"><i class="fas fa-search"></i> <span class="link-text">Chercher Employés</span></a>
    <a href="../employe/demander_conge.php"><i class="fas fa-calendar-alt"></i> <span class="link-text">Demander Congé</span></a>

    
    <?php if ($user_role === 'rh' || $user_role === 'RH'): ?>
    <div class="dropdown">
        <a href="#"><i class="fas fa-wrench"></i> <span class="link-text">Paramètres Avancés</span></a>
        <div class="dropdown-content">
            <a href="../modification/addPole.php"><i class="fas fa-plus"></i> <span class="link-text">Ajouter un Pôle</span></a>
            <a href="../modification/addDepartement.php"><i class="fas fa-plus"></i> <span class="link-text">Ajouter un Département</span></a>
            <a href="../modification/addService.php"><i class="fas fa-plus"></i> <span class="link-text">Ajouter un Service</span></a>
            <a href="../modification/liste_branche.php"><i class="fas fa-list"></i> <span class="link-text">Liste des Branches</span></a>
            <a href="../modification/liste_employes.php"><i class="fas fa-list"></i> <span class="link-text">Liste des Employés</span></a>
            <a href="../modification/gerer_conge.php"><i class="fas fa-list"></i> <span class="link-text">Liste des Congés</span></a>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="dropdown">
        <a href="#"><i class="fas fa-cog"></i> <span class="link-text">Paramètres</span></a>
        <div class="dropdown-content">
            <a href="../employe/change_password.php"><i class="fas fa-key"></i> <span class="link-text">Changer Mot de Passe</span></a>
        </div>
    </div>    

    <a href="../pages/logout.php"><i class="fas fa-sign-out-alt"></i> <span class="link-text">Déconnexion</span></a>
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
        background-color: #0097a7; /* Optional: Change color on hover */
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }
</style>
