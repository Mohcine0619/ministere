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
$sql = "SELECT employes.*, departements.nom as departement, services.nom as service, poles.nom as pole FROM employes 
        LEFT JOIN departements ON employes.id_departement = departements.id 
        LEFT JOIN services ON employes.id_service = services.id 
        LEFT JOIN poles ON employes.id_pole = poles.id 
        WHERE employes.fullName LIKE ? OR departements.nom LIKE ? OR services.nom LIKE ? OR poles.nom LIKE ? OR employes.role LIKE ? OR employes.occupation LIKE ? OR employes.email LIKE ? OR employes.username LIKE ? OR employes.nb_post LIKE ? OR employes.nb_bureau LIKE ? OR employes.corps LIKE ?";
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
    <title>Search Employees</title>
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
        <h1>Search Employees</h1>
        <form action="liste_employes.php" method="get">
            <input type="text" name="search" placeholder="Search by..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
        <a href="add_employee.php" class="add-button">Ajouter Employee</a>

        <?php if ($result->num_rows > 0): ?>
            <ul>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li class="employee-info">
                        <img src="<?php echo htmlspecialchars($row['photo']); ?>" alt="Employee Photo" class="employee-photo">
                        <div><strong>Full Name:</strong> <?php echo htmlspecialchars($row['fullName']); ?></div>
                        <div><strong>Search Term:</strong> <?php echo htmlspecialchars($search); ?></div>
                        <button class="expand-button" onclick="expandDetails(this)">Details</button>
                        <div class="details" style="display:none;">
                            <div><strong>Department:</strong> <?php echo htmlspecialchars($row['departement']); ?></div>
                            <div><strong>Service:</strong> <?php echo htmlspecialchars($row['service']); ?></div>
                            <div><strong>Pole:</strong> <?php echo htmlspecialchars($row['pole']); ?></div>
                            <div><strong>Role:</strong> <?php echo htmlspecialchars($row['role']); ?></div>
                            <div><strong>Occupation:</strong> <?php echo htmlspecialchars($row['occupation']); ?></div>
                            <div><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></div>
                            <div><strong>Username:</strong> <?php echo htmlspecialchars($row['username']); ?></div>
                            <div><strong>Nombre de post:</strong> <?php echo htmlspecialchars($row['nb_post']); ?></div>
                            <div><strong>Nombre de bureau:</strong> <?php echo htmlspecialchars($row['nb_bureau']); ?></div>
                            <div><strong>Corps:</strong> <?php echo htmlspecialchars($row['corps']); ?></div>
                            <button class="shorten-button" onclick="shortenDetails(this)" style="display:none;">Shorten</button>
                            <button class="modify-button" onclick="showModifyModal(<?php echo $row['id']; ?>)">Modify</button>
                            <button class="delete-button" onclick="showDeleteModal(<?php echo $row['id']; ?>)">Delete</button>
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
                    <h5 class="modal-title" id="modifyModalLabel">Modify Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Do you want to change employee info?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmModifyButton">Modify</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this employee?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
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
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Handle success
                    alert('Employee deleted successfully.');
                    location.reload(); // Reload the page to reflect changes
                } else if (xhr.readyState === 4) {
                    // Handle error
                    alert('Error deleting employee.');
                }
            };
            xhr.send('id=' + id);
        }
    </script>
    <?php include '../pages/footer.php'; ?>
</body>
</html>

