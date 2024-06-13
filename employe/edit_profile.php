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
    $role = htmlspecialchars($_POST['role']);
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
                <label for="grade">Grade:</label>
                <input type="text" name="grade" class="form-control" value="<?php echo htmlspecialchars($userData['grade'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select name="role" class="form-control">
                    <?php while ($row = $roles->fetch_assoc()) {
                        echo '<option value="' . $row['role'] . '"' . ($row['role'] == $userData['role'] ? ' selected' : '') . '>' . $row['role'] . '</option>';
                    } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="corps">Corps:</label>
                <select name="corps" id="corps" class="form-control" onchange="updateFonctionOptions()">
                    <option value="">Select Corps</option>
                    <option value="Corps1">Corps1</option>
                    <option value="Corps2">Corps2</option>
                    <!-- Add more corps options as needed -->
                </select>
            </div>
            <div class="form-group">
                <label for="fonction">Fonction:</label>
                <select name="fonction" id="fonction" class="form-control">
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
function updateFonctionOptions() {
    var corps = document.getElementById('corps').value;
    var fonctionSelect = document.getElementById('fonction');
    fonctionSelect.innerHTML = ''; // Clear existing options

    // Assuming you have a way to fetch functions based on corps, e.g., via an API or a predefined object
    // For demonstration, using static values:
    if (corps === 'Corps1') {
        fonctionSelect.innerHTML += '<option value="Fonction1A">Fonction1A</option>';
        fonctionSelect.innerHTML += '<option value="Fonction1B">Fonction1B</option>';
        fonctionSelect.innerHTML += '<option value="Fonction1C">Fonction1C</option>';
        fonctionSelect.innerHTML += '<option value="Fonction1D">Fonction1D</option>';
    } else if (corps === 'Corps2') {
        fonctionSelect.innerHTML += '<option value="Fonction2A">Fonction2A</option>';
        fonctionSelect.innerHTML += '<option value="Fonction2B">Fonction2B</option>';
        fonctionSelect.innerHTML += '<option value="Fonction2C">Fonction2C</option>';
        fonctionSelect.innerHTML += '<option value="Fonction2D">Fonction2D</option>';
    }
    // Add more conditions and options based on different corps
}

document.getElementById('corps').addEventListener('change', updateFonctionOptions);
</script>
