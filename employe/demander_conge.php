<?php
session_start(); // Démarrer la session
require_once '../backend/db.php'; // Inclure la connexion à la base de données
$message="";
$message_class="alert-info"; // Classe de message par défaut
// Vérifier si user_id est défini dans la session
if(!isset($_SESSION['user_id'])){
    die("Erreur : L'ID utilisateur n'est pas défini dans la session.");
}
$employee_id=$_SESSION['user_id']; // Utiliser user_id de la session comme employee_id

// Handle delete request
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $conge_id = (int)$_POST['conge_id']; // Cast to int for safety
    $delete_stmt = $conn->prepare("DELETE FROM conges WHERE id = ?");
    $delete_stmt->bind_param("i", $conge_id);
    if($delete_stmt->execute()){
        $message = "Demande de congé supprimée avec succès !";
        $message_class = "alert-success";
    } else {
        $message = "Erreur lors de la suppression : " . $delete_stmt->error;
    }
    $delete_stmt->close();
    // Refresh the page to reflect the change
    header("Location: demander_conge.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){
    // Collect and sanitize input data
    $start_date = htmlspecialchars($_POST['start_date']);
    $end_date = htmlspecialchars($_POST['end_date']);
    $reason = htmlspecialchars($_POST['reason']);
    $categorie = isset($_POST['category']) ? htmlspecialchars($_POST['category']) : 'default_category';
    $responsable_id = isset($_POST['responsable']) ? (int)$_POST['responsable'] : 0; // Cast to int for safety

    // Prepare SQL statement to insert data into the database
    $stmt = $conn->prepare("INSERT INTO conges (id_employe, categorie, date_debut, date_fin, motif, statut, id_responsable) VALUES (?, ?, ?, ?, ?, 'pending', ?)");
    if($stmt){
        $stmt->bind_param("issssi", $employee_id, $categorie, $start_date, $end_date, $reason, $responsable_id);
        // Execute the statement
        if($stmt->execute()){
            $message = "Demande de congé soumise avec succès !";
            $message_class = "alert-success";
            // Redirect to the same page to prevent form resubmission
            header("Location: demander_conge.php?success=1");
            exit();
        } else {
            $message = "Erreur : " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Erreur lors de la préparation de la déclaration : " . $conn->error;
    }
}
$manager_query = "SELECT id, fullName FROM employe WHERE role = 'RH'";
$manager_result = $conn->query($manager_query);
$managers = $manager_result->fetch_all(MYSQLI_ASSOC);
// Récupérer toutes les demandes de congé pour l'utilisateur connecté

$query = "SELECT c.id, c.categorie, c.date_debut, c.date_fin, c.motif, c.statut, e.fullName, r.fullName AS responsableName FROM conges c JOIN employe e ON c.id_employe = e.id LEFT JOIN employe r ON c.id_responsable = r.id WHERE c.id_employe = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$leave_requests = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close(); // Fermer la connexion après toutes les opérations
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de demande de congé</title>
    <?php include '../pages/boot.php'; ?>
    <?php include '../pages/nav.php'; ?>
    <style>
        /* Style général du conteneur */
        .container{
            font-family:"Arial",sans-serif; /* Définit la famille de polices à Arial */
            font-size:14px; /* Définit la taille de police de base */
            max-width:500px; /* Correspond à la largeur maximale du formulaire d'inscription */
            margin:20px auto; /* Centré avec une marge supérieure plus petite */
            background-color:#f8f9fa;
            padding:10px; /* Correspond au padding du formulaire d'inscription */
            border-radius:8px;
            box-shadow:0 4px 8px rgba(0,0,0,0.1);
        }
        /* Style du formulaire */
        form{
            background-color:#f2f2f2;
            padding:20px;
            border-radius:5px;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
        }
        /* Style des champs de saisie */
        input[type="text"],
        select{
            width:100%;
            padding:10px;
            margin:10px 0;
            display:inline-block;
            border:1px solid #ccc;
            border-radius:4px;
            box-sizing:border-box;
        }
        /* Style des boutons */
        .btn-primary{
            width:100%;
            background-color:#0097a7;
            color:white;
            padding:14px 20px;
            margin:8px 0;
            border:none;
            border-radius:4px;
            cursor:pointer;
        }
        .btn-primary:hover{
            background-color:#333;
        }
        .btn-cancel{
            width:100%;
            background-color:grey;
            color:white;
            padding:14px 20px;
            margin:8px 0;
            border:none;
            border-radius:4px;
            cursor:pointer;
        }
        .btn-cancel:hover{
            background-color:#333;
        }
        /* Style des liens */
        a{
            display:block;
            margin-top:10px;
            text-align:center;
        }
        /* Style des messages d'alerte */
        .alert{
            padding:10px;
            border-radius:4px;
            margin-bottom:20px;
            color:#721c24; /* Couleur de texte par défaut pour les erreurs */
            background-color:#f8d7da; /* Couleur de fond par défaut pour les erreurs */
            border-color:#f5c6cb; /* Couleur de bordure par défaut pour les erreurs */
        }
        /* Style des messages de succès */
        .alert.alert-success{
            color:#155724; /* Couleur de texte verte pour le succès */
            background-color:#d4edda; /* Couleur de fond verte pour le succès */
            border-color:#c3e6cb; /* Couleur de bordure verte pour le succès */
        }
    </style>
    <script>
        function cancelRequest(){
            window.location.href = '../pages/home.php';
        }
    </script>
</head>
<body>
<?php include '../pages/side.php'; ?>
    <?php include '../pages/navbar.php'; ?>
<div class="container main-content">
    <h2> Demande de congé</h2>
    <?php
    if(!empty($message)){
        echo '<div class="alert '.$message_class.'" role="alert">'.$message.'</div>';
    }
   ?>
<form action="demander_conge.php" method="POST">
<div class="form-group">
        <label for="category">Catégorie de congé :</label>
        <select class="form-control" id="category" name="category" required>
            <option value="annuel">Congé annuel</option>
            <option value="maladie">Congé de maladie</option>
            <option value="maternite">Congé de maternité</option>
            <option value="formation">Congé de formation</option>
        </select>
    </div>
    <div class="form-group">
        <label for="start_date">Date de début :</label>
        <input type="date" class="form-control" id="start_date" name="start_date" required>
    </div>
    <div class="form-group">
        <label for="end_date">Date de fin :</label>
        <input type="date" class="form-control" id="end_date" name="end_date" required>
    </div>
    <div class="form-group">
        <label for="reason">Motif :</label>
        <textarea class="form-control" id="reason" name="reason" required></textarea>
    </div>
    <div class="form-group">
    <label for="responsable">Responsable :</label>
    <select class="form-control" id="responsable" name="responsable" required>
        <?php foreach($managers as $manager): ?>
            <option value="<?php echo $manager['id']; ?>"><?php echo htmlspecialchars($manager['fullName']); ?></option>
        <?php endforeach; ?>
    </select>
</div>

    <button type="submit" name="submit" class="btn btn-primary">Soumettre</button>
    <button type="button" class="btn btn-cancel" onclick="cancelRequest()">Annuler</button>
</form>
    <h2>Demandes de congé</h2>
    <?php if(!empty($leave_requests)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Motif</th>
                    <th>Catégorie</th>
                    <th>Responsable</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
    <?php foreach($leave_requests as $leave): ?>
        <tr>
            <td><?php echo htmlspecialchars($leave['date_debut']); ?></td>
            <td><?php echo htmlspecialchars($leave['date_fin']); ?></td>
            <td><?php echo htmlspecialchars($leave['motif']); ?></td>
            <td><?php echo htmlspecialchars($leave['categorie']); ?></td>
            <td><?php echo htmlspecialchars($leave['responsableName']); ?></td>
            <td><?php echo htmlspecialchars($leave['statut']); ?></td>
            <td>
                <form action="demander_conge.php" method="POST">
                    <input type="hidden" name="conge_id" value="<?php echo $leave['id']; ?>">
                    <button type="submit" name="delete" class="btn btn-danger">Supprimer</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info" role="alert">Aucune demande de congé trouvée.</div>
    <?php endif; ?>
</div>
<?php include '../pages/footer.php'; ?>
</body>
</html>
