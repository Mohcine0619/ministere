<?php

require_once '../backend/db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// Fetch current user data
$sql = "SELECT * FROM employe WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

// Fetch roles, corps, and functions for selection
$roles = $conn->query("SELECT DISTINCT role FROM employe");
$corps = $conn->query("SELECT DISTINCT corps FROM employe");
$functions = $conn->query("SELECT DISTINCT fonction FROM employe");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form data
    $fullName = htmlspecialchars($_POST['fullName']);
    $email = htmlspecialchars($_POST['email']);
    $tel = htmlspecialchars($_POST['tel']);
    $matricule = htmlspecialchars($_POST['matricule']);
    $grade = htmlspecialchars($_POST['grade']);
    $role = null; // Set role to null
    $corps = htmlspecialchars($_POST['corps']);
    $fonction = htmlspecialchars($_POST['fonction']);
    $nb_post = intval($_POST['nb_post']);
    $nb_bureau = intval($_POST['nb_bureau']);

    // Update query
    $update_sql = "UPDATE employe SET fullName = ?, email = ?, tel = ?, matricule = ?, grade = ?, role = ?, corps = ?, fonction = ?, nb_post = ?, nb_bureau = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssssssiii", $fullName, $email, $tel, $matricule, $grade, $role, $corps, $fonction, $nb_post, $nb_bureau, $user_id);
    $update_stmt->execute();

    if ($update_stmt->error) {
        $message = 'Error updating profile: ' . $update_stmt->error;
    } else {
        $_SESSION['username'] = $fullName;  // Update session variable if needed
        $message = '<span style="color: green;">Profile updated successfully.</span>';
        header('Location: profile.php');
        exit();
    }
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <?php include '../pages/boot.php'; ?>
    <link rel="stylesheet" href="../style/profile.css?v=<?php echo time(); ?>">
    <?php include '../pages/nav.php'; ?>
</head>
<body>
<?php include '../pages/side.php'; ?>
    <?php include '../pages/navbar.php'; ?>
    <div class="container main-content">
        <h1>Edit Profile</h1>
        <p><?php echo $message; ?></p>
        <form action="edit_profile.php" method="post">
            <div class="form-group">
                <label for="fullName">Full Name:</label>
                <input type="text" name="fullName" class="form-control" value="<?php echo htmlspecialchars($userData['fullName'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="tel">Tel:</label>
                <input type="tel" name="tel" class="form-control" value="<?php echo htmlspecialchars($userData['tel'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="matricule">Matricule:</label>
                <input type="text" name="matricule" class="form-control" value="<?php echo htmlspecialchars($userData['matricule'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="corps">Corps:</label>
                <select name="corps" id="corps" class="form-control" onchange="updateGradeOptions();">
                    <option value="">Select Corps</option>
                    <option value="rh">RH</option>
                    <option value="Adjoints Administratifs">Adjoints Administratifs</option>
                    <option value="Adjoints Techniques">Adjoints Techniques</option>
                    <option value="Administrateurs">Administrateurs</option>
                    <option value="Interministeriels des Ingénieurs">Interministeriels des Ingénieurs</option>
                    <option value="Particuliier des adjoints Techniques">Particuliier des adjoints Techniques</option>
                    <option value="Personnel des Douanes">Personnel des Douanes</option>
                    <option value="Ingénieurs et Architectes">Ingénieurs et Architectes</option>
                    <option value="Inspecteurs des Finances">Inspecteurs des Finances</option>
                    <option value="Rédacteurs">Rédacteurs</option>
                    <option value="Techniciens">Techniciens</option>
                    <!-- Add more corps options as needed -->
                </select>
            </div>
            <div class="form-group">
                <label for="grade">Grade:</label>
                <select name="grade" id="grade" class="form-control">
                    <!-- Dynamically populated based on corps -->
                </select>
            </div>
            <div class="form-group">
                <label for="fonction">Fonction:</label>
                <select name="fonction" id="fonction" class="form-control">
                    <option value="">Select Fonction</option>
                    <option value="Fonction1">Fonction1</option>
                    <option value="Fonction2">Fonction2</option>
                    <option value="Fonction3">Fonction3</option>
                    <!-- Options will be populated based on corps selection -->
                </select>
            </div>
            <div class="form-group">
                <label for="nb_post">Nombre de post:</label>
                <input type="number" name="nb_post" class="form-control" value="<?php echo htmlspecialchars($userData['nb_post'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="nb_bureau">Nombre de bureau:</label>
                <input type="number" name="nb_bureau" class="form-control" value="<?php echo htmlspecialchars($userData['nb_bureau'] ?? ''); ?>" required>
            </div>
            <div class="form-actions">
                <a href="profile.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </form>
    </div>
    <?php include '../pages/footer.php'; ?>
</body>
</html>
<script>
function updateGradeOptions() {
    var corps = document.getElementById('corps').value;
    var gradeSelect = document.getElementById('grade');
    gradeSelect.innerHTML = ''; // Clear existing options

    // Assuming you have a way to fetch grades based on corps, e.g., via an API or a predefined object
    // For demonstration, using static values:
    if (corps === 'Ingénieurs et Architectes') {
        gradeSelect.innerHTML = '<option value="ARCHITECTE 1ER GRADE">ARCHITECTE 1ER GRADE</option><option value="ARCHITECTE EN CHEF 1ER GRADE">ARCHITECTE EN CHEF 1ER GRADE</option>';
        gradeSelect.innerHTML += '<option value="ARCHITECTE EN CHEF GRADE PRINCIPALE">ARCHITECTE EN CHEF GRADE PRINCIPALE</option><option value="ARCHITECTE GRADE PRINCIPALE">ARCHITECTE GRADE PRINCIPALE</option>';
        gradeSelect.innerHTML += '<option value="INGENIEUR D\'ETAT 1ER GRADE">INGENIEUR D\'ETAT 1ER GRADE</option><option value="INGENIEUR D\'ETAT GRADE PRINCIPALE">INGENIEUR D\'ETAT GRADE PRINCIPALE</option>';
        gradeSelect.innerHTML += '<option value="INGENIEUR D\'ETAT EN CHEF 1ER GRADE">INGENIEUR D\'ETAT EN CHEF 1ER GRADE</option><option value="INGENIEUR EN CHEF GRADE PRINCIPALE">INGENIEUR EN CHEF GRADE PRINCIPALE</option>';
    } else {
        gradeSelect.innerHTML = '<option value="Grade1">Grade1</option><option value="Grade2">Grade2</option>';
        gradeSelect.innerHTML += '<option value="Grade3">Grade3</option><option value="Grade4">Grade4</option>';
    }
    // Add more conditions and options based on different corps
}

document.getElementById('corps').addEventListener('change', updateGradeOptions);
</script>
