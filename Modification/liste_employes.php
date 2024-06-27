<?php
require_once '../backend/db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$search = $_GET['search'] ?? ''; // Get the search term from the URL parameter

// Prepare and execute search query
$sql = "SELECT employe.*, COALESCE(departement.nom, 'No Department') as division, COALESCE(services.nom, 'No Service') as service, COALESCE(poles.nom, 'No Pole') as pole FROM employe 
LEFT JOIN departement ON employe.departement_id = departement.id 
LEFT JOIN services ON employe.service_id = services.id 
LEFT JOIN poles ON employe.pole_id = poles.id 
WHERE employe.fullName LIKE ? OR departement.nom LIKE ? OR services.nom LIKE ? OR poles.nom LIKE ? OR employe.grade LIKE ? OR employe.fonction LIKE ? OR employe.email LIKE ? OR employe.username LIKE ? OR employe.nb_post LIKE ? OR employe.nb_bureau LIKE ? OR employe.corps LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$search%";
$stmt->bind_param("sssssssssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechercher des Employés</title>
    <?php include '../pages/boot.php'; ?>
    <link rel="stylesheet" href="../style/chercher_emp.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <?php include '../pages/nav.php'; ?>
    <style>
        .modify-button {
            background-color: green;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            margin-left: 5px;
        }
        .delete-button {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            margin-left: 5px;
        }
        .add-button {
            background-color: #007bff; /* Bootstrap primary color */
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            margin-top: 10px;
            display: inline-block;
            text-decoration: none;
            border-radius: 5px; /* Rounded corners */
            transition: background-color 0.3s ease; /* Smooth transition */
        }

        .add-button:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }
    </style>
</head>
<body>
<?php include '../pages/side.php'; ?>
    <?php include '../pages/navbar.php'; ?>
    
    <div class="container main-content">
        <img src="../uploads/entreprise.jpg" alt="Entreprise Photo" class="entreprise-photo">
        <button class="back-button" onclick="window.history.back();"><i class="fas fa-arrow-left"></i></button>
        <h1>Rechercher des Employés</h1>
        <form action="liste_employes.php" method="get">
            <input type="text" name="search" placeholder="Rechercher par..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Rechercher</button>
        </form>
        <a href="add_employee.php" class="add-button">Ajouter un Employé</a>

        <?php if ($result->num_rows > 0): ?>
            <ul>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li class="employee-info">
                        <img src="<?php echo htmlspecialchars($row['photo']); ?>" alt="Employee Photo" class="employee-photo">
                        <div><strong>Nom complet:</strong> <?php echo htmlspecialchars($row['fullName']); ?></div>
                        <div><strong>Matricule:</strong> <?php echo htmlspecialchars($row['matricule']); ?></div>
                        <div><strong>Tel:</strong> <?php echo htmlspecialchars($row['tel']); ?></div>
                        <div><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></div>
                        <div><strong>Terme de recherche:</strong> <?php echo htmlspecialchars($search); ?></div>
                        <button class="expand-button" onclick="expandDetails(this)">Détails</button>
                        <div class="details" style="display:none;">
                            <div><strong>Division:</strong> <?php echo htmlspecialchars($row['division']); ?></div>
                            <div><strong>Service:</strong> <?php echo htmlspecialchars($row['service']); ?></div>
                            <div><strong>Pole:</strong> <?php echo htmlspecialchars($row['pole']); ?></div>
                            <div><strong>Grade:</strong> <?php echo htmlspecialchars($row['grade']); ?></div>
                            <div><strong>Fonction:</strong> <?php echo htmlspecialchars($row['fonction']); ?></div>
                            <div><strong>Username:</strong> <?php echo htmlspecialchars($row['username']); ?></div>
                            <div><strong>Nombre de post:</strong> <?php echo htmlspecialchars($row['nb_post']); ?></div>
                            <div><strong>Nombre de bureau:</strong> <?php echo htmlspecialchars($row['nb_bureau']); ?></div>
                            <div><strong>Corps:</strong> <?php echo htmlspecialchars($row['corps']); ?></div>
                            <button class="shorten-button" onclick="shortenDetails(this)" style="display:none;">Réduire</button>
                            <button class="modify-button" onclick="showModifyModal(<?php echo $row['id']; ?>)">Modifier</button>
                            <button class="delete-button" onclick="showDeleteModal(<?php echo $row['id']; ?>)">Supprimer</button>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No results found.</p>
        <?php endif; ?>

        <?php
        // Close the statement and connection
        $stmt->close();
        $conn->close();
        ?>
    </div>

    <!-- Modify Modal -->
    <div class="modal fade" id="modifyModal" tabindex="-1" aria-labelledby="modifyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modifyModalLabel">Modifier l'employé</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    Voulez-vous modifier les informations de l'employé?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="confirmModifyButton">Modifier</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Supprimer un Employé</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    Êtes-vous sr de vouloir supprimer cet employé?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Supprimer</button>
                </div>
            </div>
        </div>
    </div>

    <?php include '../pages/scboot.php'; ?>
    <script>
        function expandDetails(button) {
            var detailsDiv = button.nextElementSibling;
            detailsDiv.style.display = 'block';
            button.style.display = 'none';
            detailsDiv.querySelector('.shorten-button').style.display = 'inline'; // Show the shorten button
        }

        function shortenDetails(button) {
            var detailsDiv = button.parentElement;
            detailsDiv.style.display = 'none';
            button.style.display = 'none';
            detailsDiv.previousElementSibling.style.display = 'inline'; // Show the expand button
        }

        function showModifyModal(id) {
            var modifyModal = new bootstrap.Modal(document.getElementById('modifyModal'));
            document.getElementById('confirmModifyButton').onclick = function() {
                window.location.href = 'modifier_emp.php?id=' + id;
            };
            modifyModal.show();
        }

        function showDeleteModal(id) {
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            document.getElementById('confirmDeleteButton').onclick = function() {
                deleteEmployee(id);
            };
            deleteModal.show();
        }

        function deleteEmployee(id) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_employee.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    var responseText = xhr.responseText.trim();
                    if (xhr.status === 200) {
                        if (responseText.startsWith("Error:")) {
                            alert(responseText); // Display the error message from the server
                        } else {
                            alert(responseText); // Display success or other messages from the server
                            location.reload(); // Reload the page to reflect changes
                        }
                    } else {
                        alert('Error deleting employee: ' + xhr.status);
                    }
                }
            };
            xhr.send('id=' + id);
        }
    </script>
    <?php include '../pages/footer.php'; ?>
</body>
</html>
