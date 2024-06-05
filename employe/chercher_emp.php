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
$sql = "SELECT employes.*, departements.nom as departement, services.nom as service, employes.role, employes.dob, employes.occupation, employes.createdAt, employes.photo FROM employes LEFT JOIN departements ON employes.id_departement = departements.id LEFT JOIN services ON employes.id_service = services.id WHERE employes.fullName LIKE ? OR departements.nom LIKE ? OR services.nom LIKE ? OR employes.role LIKE ? OR employes.dob LIKE ? OR employes.occupation LIKE ? OR employes.createdAt LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$search%";
$stmt->bind_param("sssssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
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
    <link rel="stylesheet" href="../style/chercher_emp.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <?php include '../pages/nav.php'; ?>
</head>
<body>
<?php include '../pages/side.php'; ?>
    <?php include '../pages/navbar.php'; ?>
    
    <div class="container main-content">
        <img src="../uploads/entreprise.jpg" alt="Entreprise Photo" class="entreprise-photo">
        <button class="back-button" onclick="window.history.back();"><i class="fas fa-arrow-left"></i></button>
        <h1>Search Employees</h1>
        <form action="chercher_emp.php" method="get">
            <input type="text" name="search" placeholder="Search by..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>

        <?php if ($result->num_rows > 0): ?>
            <ul>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li class="employee-info">
                        <img src="<?php echo htmlspecialchars($row['photo']); ?>" alt="Employee Photo" class="employee-photo">
                        <div><strong>Full Name:</strong> <?php echo htmlspecialchars($row['fullName']); ?></div>
                        <div><strong>Search Term:</strong> <?php echo htmlspecialchars($search); ?></div>
                        <button class="expand-button" onclick="expandDetails(this)">Expand</button>
                        <div class="details" style="display:none;">
                            <div><strong>Department:</strong> <?php echo htmlspecialchars($row['departement']); ?></div>
                            <div><strong>Service:</strong> <?php echo htmlspecialchars($row['service']); ?></div>
                            <div><strong>Role:</strong> <?php echo htmlspecialchars($row['role']); ?></div>
                            <div><strong>Date of Birth:</strong> <?php echo htmlspecialchars($row['dob']); ?></div>
                            <div><strong>Occupation:</strong> <?php echo htmlspecialchars($row['occupation']); ?></div>
                            <div><strong>Created At:</strong> <?php echo htmlspecialchars($row['createdAt']); ?></div>
                            <button class="shorten-button" onclick="shortenDetails(this)" style="display:none;">Shorten</button>
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
    </script>
</body>
</html>