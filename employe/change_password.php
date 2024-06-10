<?php
session_start(); // Démarrer la session
require_once '../backend/db.php'; // Inclure la connexion à la base de données
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    if ($new_password === $confirm_password && strlen($new_password) >= 6) {
        // Vérifier si le mot de passe actuel est correct
        $stmt = $conn->prepare("SELECT password FROM employes WHERE username = ?");
        $stmt->bind_param("s", $_SESSION['username']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if (password_verify($current_password, $user['password'])) {
            // Hacher le nouveau mot de passe
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            // Mettre à jour le mot de passe dans la base de données
            $update_stmt = $conn->prepare("UPDATE employes SET password = ? WHERE username = ?");
            $update_stmt->bind_param("ss", $hashed_password, $_SESSION['username']);
            if ($update_stmt->execute()) {
                $message = "Mot de passe changé avec succès !";
            } else {
                $message = "Erreur lors de la mise à jour du mot de passe.";
            }
            $update_stmt->close();
        } else {
            $message = "Le mot de passe actuel est incorrect.";
        }
        $stmt->close();
    } else {
        if ($new_password !== $confirm_password) {
            $message = "Les mots de passe ne correspondent pas.";
        } else if (strlen($new_password) < 6) {
            $message = "Le mot de passe doit contenir au moins 6 caractères.";
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer le mot de passe</title>
    <?php include '../pages/boot.php'; ?>
    <?php include '../pages/nav.php'; ?>
    <style>
        /* Style général du conteneur */
        .container {
            font-family: "Arial", sans-serif; /* Définit la police à Arial */
            font-size: 14px; /* Définit la taille de la police de base */
            max-width: 500px; /* Correspond à la largeur maximale du formulaire d'inscription */
            margin: 20px auto; /* Centré avec une marge supérieure plus petite */
            background-color: #f8f9fa;
            padding: 10px; /* Correspond au padding du formulaire d'inscription */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        /* Style du formulaire */
        form {
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        /* Style des champs de saisie */
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        /* Style du bouton */
        button.btn.btn-primary {
            width: 100%;
            background-color: #0097a7; /* Couleur mise à jour */
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button.btn.btn-primary:hover {
            background-color: #007a8a; /* Teinte légèrement plus foncée pour l'effet de survol */
        }
        /* Style des liens */
        a {
            display: block;
            margin-top: 10px;
            text-align: center;
        }
        /* Style des messages d'alerte */
        .alert {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            color: #721c24; /* Couleur de texte par défaut pour les erreurs */
            background-color: #f8d7da; /* Couleur de fond par défaut pour les erreurs */
            border-color: #f5c6cb; /* Couleur de bordure par défaut pour les erreurs */
        }
        /* Style des messages de succès */
        .alert.alert-success {
            color: #155724; /* Couleur de texte verte pour le succès */
            background-color: #d4edda; /* Couleur de fond verte pour le succès */
            border-color: #c3e6cb; /* Couleur de bordure verte pour le succès */
        }
    </style>
</head>
<body>
    <?php include '../pages/navbar.php'; ?>
    <?php include '../pages/side.php'; ?>
<div class="container main-content">
    <h2>Changer le mot de passe</h2>
    <?php if (!empty($message)) {
    if ($message === "Mot de passe changé avec succès !") {
        echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
    }
}
    ?>
    <form action="change_password.php" method="POST">
        <div class="form-group">
            <label for="current_password">Mot de passe actuel :</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>
        <div class="form-group">
            <label for="new_password">Nouveau mot de passe :</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirmer le nouveau mot de passe :</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Changer le mot de passe</button>
    </form>
</div>
<?php include '../pages/footer.php'; ?>
</body>
</html>
