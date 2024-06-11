<?php
require_once'../backend/db.php';
session_start();
if(!isset($_SESSION['user_id'])){
    header('Location:login.php');
    exit();
}
$search=$_GET['search']??'';
$sql="SELECT employes.*, departements.nom as division, services.nom as service, poles.nom as pole FROM employes 
        LEFT JOIN departements ON employes.id_departement=departements.id 
        LEFT JOIN services ON employes.id_service=services.id 
        LEFT JOIN poles ON employes.id_pole=poles.id 
        WHERE employes.fullName LIKE ? OR departements.nom LIKE ? OR services.nom LIKE ? OR poles.nom LIKE ? OR employes.role LIKE ? OR employes.occupation LIKE ? OR employes.email LIKE ? OR employes.username LIKE ? OR employes.nb_post LIKE ? OR employes.nb_bureau LIKE ? OR employes.corps LIKE ?";
$stmt=$conn->prepare($sql);
$searchTerm="%$search%";
$stmt->bind_param("sssssssssss",$searchTerm,$searchTerm,$searchTerm,$searchTerm,$searchTerm,$searchTerm,$searchTerm,$searchTerm,$searchTerm,$searchTerm,$searchTerm);
$stmt->execute();
$result=$stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechercher des Employés</title>
    <?php include'../pages/boot.php';?>
    <link rel="stylesheet" href="../style/chercher_emp.css?v=<?php echo time();?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <?php include'../pages/nav.php';?>
</head>
<body>
<?php include'../pages/side.php';?>
    <?php include'../pages/navbar.php';?>
    <div class="container main-content">
        <img src="../uploads/entreprise.jpg" alt="Photo de l'Entreprise" class="entreprise-photo">
        <button class="back-button" onclick="window.history.back();"><i class="fas fa-arrow-left"></i></button>
        <h1>Rechercher des Employés</h1>
        <form action="chercher_emp.php" method="get">
            <input type="text" name="search" placeholder="Rechercher par..." value="<?php echo htmlspecialchars($search);?>">
            <button type="submit">Rechercher</button>
        </form>
        <?php if($result->num_rows>0):?>
            <ul>
                <?php while($row=$result->fetch_assoc()):?>
                    <li class="employee-info">
                        <img src="<?php echo htmlspecialchars($row['photo']);?>" alt="Photo de l'Employé" class="employee-photo">
                        <div><strong>Nom Complet:</strong><?php echo htmlspecialchars($row['fullName']);?></div>
                        <div><strong>Terme de Recherche:</strong><?php echo htmlspecialchars($search);?></div>
                        <button class="expand-button" onclick="expandDetails(this)">Détails</button>
                        <div class="details" style="display:none;">
                            <div><strong>Division:</strong><?php echo htmlspecialchars($row['division']);?></div>
                            <div><strong>Service:</strong><?php echo htmlspecialchars($row['service']);?></div>
                            <div><strong>Pole:</strong><?php echo htmlspecialchars($row['pole']);?></div>
                            <div><strong>Rôle:</strong><?php echo htmlspecialchars($row['role']);?></div>
                            <div><strong>Occupation:</strong><?php echo htmlspecialchars($row['occupation']);?></div>
                            <div><strong>Email:</strong><?php echo htmlspecialchars($row['email']);?></div>
                            <div><strong>Nom d'utilisateur:</strong><?php echo htmlspecialchars($row['username']);?></div>
                            <div><strong>Nombre de post:</strong><?php echo htmlspecialchars($row['nb_post']);?></div>
                            <div><strong>Nombre de bureau:</strong><?php echo htmlspecialchars($row['nb_bureau']);?></div>
                            <div><strong>Corps:</strong><?php echo htmlspecialchars($row['corps']);?></div>
                            <button class="shorten-button" onclick="shortenDetails(this)" style="display:none;">Réduire</button>
                        </div>
                    </li>
                <?php endwhile;?>
            </ul>
        <?php else:?>
            <p>Aucun résultat trouvé.</p>
        <?php endif;?>
        <?php
        // Fermer la déclaration et la connexion
        $stmt->close();
        $conn->close();
        ?>
    </div>
    <?php include'../pages/scboot.php';?>
    <script>
        function expandDetails(button){
            var detailsDiv=button.nextElementSibling;
            detailsDiv.style.display='block';
            button.style.display='none';
            detailsDiv.querySelector('.shorten-button').style.display='inline'; // Afficher le bouton réduire
        }
        function shortenDetails(button){
            var detailsDiv=button.parentElement;
            detailsDiv.style.display='none';
            button.style.display='none';
            detailsDiv.previousElementSibling.style.display='inline'; // Afficher le bouton détails
        }
    </script>
    <?php include'../pages/footer.php';?>
</body>
</html>





