<?php
session_start(); // Start the session
require_once '../backend/db.php';

// Fetch poles, departments, and services from the database
$poles = [];
$departments = [];
$services = [];

// Fetching poles
$poleResult = $conn->query("SELECT id, nom FROM poles");
if ($poleResult) {
    while ($poleRow = $poleResult->fetch_assoc()) {
        $poles[] = $poleRow;
    }
    $poleResult->free();
}

// Fetching departments with nom_pole
$deptResult = $conn->query("SELECT id, nom, nom_pole FROM departement");
if ($deptResult) {
    while ($deptRow = $deptResult->fetch_assoc()) {
        $departments[] = $deptRow;
    }
    $deptResult->free();
}

// Fetch services with id_departement
$serviceResult = $conn->query("SELECT id, nom, id_departement FROM services");
if ($serviceResult) {
    while ($serviceRow = $serviceResult->fetch_assoc()) {
        $services[] = $serviceRow;
    }
    $serviceResult->free();
}


$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Collect and sanitize input data
    $fullName = htmlspecialchars($_POST['fullName']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $tel = htmlspecialchars($_POST['tel']);
    $matricule = htmlspecialchars($_POST['matricule']);
    $grade = htmlspecialchars($_POST['grade']);
    $fonction = htmlspecialchars($_POST['fonction']); // Collect fonction
    $role = null; // Collect role
    $corps = htmlspecialchars($_POST['corps']);
    $_SESSION['corps'] = $corps; // Collect corps
    $nb_post = intval($_POST['nb_post']);
    $nb_bureau = intval($_POST['nb_bureau']);
    $pole = isset($_POST['pole']) && $_POST['pole'] !== '' ? intval($_POST['pole']) : null;
    $departement = isset($_POST['departement']) && $_POST['departement'] !== '' ? intval($_POST['departement']) : null;
    $service = isset($_POST['service']) && $_POST['service'] !== '' ? intval($_POST['service']) : null;
    $gender = htmlspecialchars($_POST['gender']); // Collect gender

    $photoPath = null; // Default value

    // Handling file upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $uploadDir = '../uploads/';
        $fileName = time() . basename($_FILES['photo']['name']); // Using time() to prefix filenames to make them unique
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
            $photoPath = $uploadFile;
            echo "File is uploaded successfully. Path: $photoPath";
        } else {
            $message = "Error uploading file.";
            echo $message;
        }
    }

    // Store corps in session



    // Prepare SQL statement to insert data into the database, including photo path and gender
    $stmt = $conn->prepare("INSERT INTO employe (matricule, fullName, username, tel, email, role, corps, fonction, grade, nb_post, nb_bureau, pole_id, departement_id, service_id, photo, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die('Prepare failed: ' . $conn->error);
    }
    $stmt->bind_param("sssssssssiiiiiss", $matricule, $fullName, $username, $tel, $email, $role, $corps, $fonction, $grade, $nb_post, $nb_bureau, $pole, $departement, $service, $photoPath, $gender);
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        $_SESSION['success_message'] = "New record created successfully!";
        header('Location: login.php');
        exit();
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Form</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../style/signup.css">
</head>
<body>
<div class="container mt-5">
    <h2>Signup Form</h2>
    <?php
    if (!empty($message)) {
        echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
    }
    ?>
    <form action="signup.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="fullName">Full Name:</label>
            <input type="text" class="form-control" id="fullName" name="fullName" required>
        </div>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="tel">Telephone:</label>
            <input type="text" class="form-control" id="tel" name="tel" required>
        </div>
        <div class="form-group">
            <label for="matricule">Matricule:</label>
            <input type="text" class="form-control" id="matricule" name="matricule" required>
        </div>
        <div class="form-group">
            <label for="corps">Corps:</label>
            <select class="form-control" id="corps" name="corps" onchange="updateGradeOptions(); toggleVisibilityBasedOnCorps();">
                <option value="">Select Corps</option>
                <option value="rh">RH</option>
                <option value="Adjoints Administratifs">Adjoints Administratifs</option>
                <option value="Adjoints Techniques">Adjoints Techniques</option>
                <option value="Administrateurs">Administrateurs</option>
                <option value="Interministeriels des Ingénieurs"> Interministeriels des Ingénieurs</option>
                <option value="Particuliier des adjoints Techniques"> Particuliier des adjoints Techniques</option>
                <option value="Personnel des Douanes"> Personnel des Douanes</option>
                <option value="Ingénieurs et Architectes">Ingénieurs et Architectes</option>
                <option value="Inspecteurs des Finances">Inspecteurs des Finances</option>
                <option value="Rédacteurs">Rédacteurs</option>
                <option value="Techniciens">Techniciens</option>
                <!-- Add more corps options as needed -->
            </select>
        </div>
        <div class="form-group">
            <label for="grade">Grade:</label>
            <select class="form-control" id="grade" name="grade">
                <!-- Dynamically populated based on corps -->
            </select>
        </div>
        <div class="form-group">
            <label for="fonction">Fonction:</label>
            <input type="text" class="form-control" id="fonction" name="fonction" required>
        </div>
        <div class="form-group">
            <label for="nb_post">Numéro de post:</label>
            <input type="number" class="form-control" id="nb_post" name="nb_post" required>
        </div>
        <div class="form-group">
            <label for="nb_bureau">Numéro de bureau:</label>
            <input type="number" class="form-control" id="nb_bureau" name="nb_bureau" required>
        </div>
        <div class="form-group" id="poleSelection">
            <label for="pole">Pole:</label>
            <select class="form-control" id="pole" name="pole" onchange="filterDepartements()">
                <option value="">None</option>
                <?php
                foreach ($poles as $pole) {
                    echo '<option value="' . $pole['id'] . '">' . $pole['nom'] . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group" id="departement">
            <label for="departement">Département:</label>
            <select class="form-control" name="departement">
                <option value="">Select Department</option>
                <?php foreach ($departments as $department): ?>
                    <option value="<?= $department['id'] ?>" data-pole="<?= $department['nom_pole'] ?>" style="display: none;">
                        <?= $department['nom'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" id="serviceSelection">
            <label for="service">Service:</label>
            <select class="form-control" id="service" name="service">
                <option value="">Select Service</option>
                <?php foreach ($services as $service): ?>
                    <option value="<?= $service['id'] ?>" data-departement="<?= $service['id_departement'] ?>" style="display: none;">
                        <?= $service['nom'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="photo">Photo:</label>
            <input type="file" class="form-control" id="photo" name="photo" required>
        </div>
        <div class="form-group">
            <label for="gender">Gender:</label>
            <select class="form-control" id="gender" name="gender" required>
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>

        <button type="submit" name="submit" class="btn btn-primary" value="SignUp">SignUp</button>
        <a href="login.php">Déjà inscrit ?</a>
    </form>
</div>

<script>
function updateGradeOptions() {
    var corps = document.getElementById('corps').value;
    var gradeSelect = document.getElementById('grade');
    gradeSelect.innerHTML = ''; // Clear existing options

    if (corps === 'Ingénieurs et Architectes') {
        gradeSelect.innerHTML = '<option value="ARCHITECTE 1ER GRADE">ARCHITECTE 1ER GRADE</option><option value="ARCHITECTE EN CHEF 1ER GRADE">ARCHITECTE EN CHEF 1ER GRADE</option>';
        gradeSelect.innerHTML += '<option value="ARCHITECTE EN CHEF GRADE PRINCIPALE">ARCHITECTE EN CHEF GRADE PRINCIPALE</option><option value="ARCHITECTE GRADE PRINCIPALE">ARCHITECTE GRADE PRINCIPALE</option>';
        gradeSelect.innerHTML += '<option value="INGENIEUR D\'ETAT 1ER GRADE">INGENIEUR D\'ETAT 1ER GRADE</option><option value="INGENIEUR D\'ETAT GRADE PRINCIPALE">INGENIEUR D\'ETAT GRADE PRINCIPALE</option>';
        gradeSelect.innerHTML += '<option value="INGENIEUR D\'ETAT ENCHEF 1ER GRADE">INGENIEUR D\'ETAT EN CHEF 1ER GRADE</option><option value="INGENIEUR EN CHEF GRADE PRINCIPALE">INGENIEUR EN CHEF GRADE PRINCIPALE</option>';
    } else {
        gradeSelect.innerHTML = '<option value="Grade1">Grade1</option><option value="Grade2">Grade2</option>';
        gradeSelect.innerHTML += '<option value="Grade3">Grade3</option><option value="Grade4">Grade4</option>';
    }
    // Add more conditions and options based on different corps
}
function toggleVisibilityBasedOnCorps() {
    var corps = document.getElementById('corps').value;
    var poleSection = document.getElementById('poleSelection');
    var departementSection = document.getElementById('departement');
    var serviceSection = document.getElementById('serviceSelection');

    if (corps === 'rh') {
        poleSection.style.display = 'none';
        departementSection.style.display = 'none';
        serviceSection.style.display = 'none';
    } else {
        poleSection.style.display = 'block';
        departementSection.style.display = 'block';
        serviceSection.style.display = 'block';
    }
}
function filterDepartements() {
    var selectedPoleName = document.getElementById('pole').options[document.getElementById('pole').selectedIndex].text;
    var departementSelect = document.getElementById('departement').querySelector('select');
    var options = departementSelect.options;

    for (var i = 0; i < options.length; i++) {
        var option = options[i];
        if (option.dataset.pole === selectedPoleName || option.value === "") {
            option.style.display = 'block'; // Show option
        } else {
            option.style.display = 'none'; // Hide option
        }
    }
}

function filterServices() {
    var selectedDepartmentId = document.getElementsByName('departement')[0].value;
    var serviceSelect = document.getElementById('service');
    var options = serviceSelect.options;

    for (var i = 0; i < options.length; i++) {
        var option = options[i];
        if (option.dataset.departement === selectedDepartmentId || option.value === "") {
            option.style.display = 'block'; // Show option
        } else {
            option.style.display = 'none'; // Hide option
        }
    }
}

document.getElementById('departement').querySelector('select').addEventListener('change', filterServices);

document.addEventListener('DOMContentLoaded', function() {
    updateGradeOptions(); // Set initial grade options based on the default or existing corps value
    toggleVisibilityBasedOnCorps(); // Set initial visibility based on the default or existing grade value
    filterDepartements(); // Initially filter departements based on the selected pole
    filterServices(); // Initially filter services based on the selected department
});
</script>
</body>
</html>
