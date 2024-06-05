<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<div class="sidebar">
    <a href="../pages/home.php"><i class="fas fa-home"></i> <span class="link-text">Home</span></a>
    <a href="../employe/profile.php"><i class="fas fa-user"></i> <span class="link-text">Profile</span></a>
    <a href="../employe/chercher_emp.php"><i class="fas fa-search"></i> <span class="link-text">Chercher Employes</span></a>
    <div class="dropdown">
        <a href="#"><i class="fas fa-cog"></i> <span class="link-text">Settings</span></a>
        <div class="dropdown-content">
            <a href="../employe/change_password.php"><i class="fas fa-key"></i> <span class="link-text">Changer Mot de Passe</span></a>
        </div>
    </div>
</div>
<style>
    .dropdown {
        
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown:hover .dropdown-content {
    display: block;
}
</style>