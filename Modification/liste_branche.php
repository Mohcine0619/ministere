<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session if it hasn't been started yet
}

require_once '../backend/db.php'; // Adjust the path as needed to connect to your database

// Fetch poles from the database
$poles = [];
$result = $conn->query("SELECT id, nom, nom_directeur FROM poles");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $poles[] = $row;
    }
    $result->free();
}

// Fetch departments from the database
$departments = [];
$result = $conn->query("SELECT id, nom, nom_directeur, nom_pole FROM departements");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
    $result->free();
}

// Fetch services from the database
$services = [];
$result = $conn->query("SELECT services.id, services.nom, services.nom_chef, departements.nom AS nom_departement FROM services JOIN departements ON services.id_departement = departements.id");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
    $result->free();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Branches</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <?php include '../pages/boot.php'; ?>
    <?php include '../pages/nav.php'; ?>
    <style>
        .container {
            font-family: "Arial", sans-serif;
            font-size: 14px;
            max-width: 800px;
            margin: 20px auto;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
<?php include '../pages/side.php'; ?>
<?php include '../pages/navbar.php'; ?>
<div class="container main-content">
    <h2>Liste des Poles</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Nom Directeur</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($poles as $pole): ?>
                <tr>
                    <td><?php echo htmlspecialchars($pole['id']); ?></td>
                    <td><?php echo htmlspecialchars($pole['nom']); ?></td>
                    <td><?php echo htmlspecialchars($pole['nom_directeur']); ?></td>
                    <td class="action-buttons">
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modifyModal" data-id="<?php echo $pole['id']; ?>" data-type="pole">Modify</button>
                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" data-id="<?php echo $pole['id']; ?>" data-type="pole">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Liste des Départements</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Nom Directeur</th>
                <th>Nom Pole</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($departments as $department): ?>
                <tr>
                    <td><?php echo htmlspecialchars($department['id']); ?></td>
                    <td><?php echo htmlspecialchars($department['nom']); ?></td>
                    <td><?php echo htmlspecialchars($department['nom_directeur']); ?></td>
                    <td><?php echo htmlspecialchars($department['nom_pole']); ?></td>
                    <td class="action-buttons">
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modifyModal" data-id="<?php echo $department['id']; ?>" data-type="department">Modify</button>
                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" data-id="<?php echo $department['id']; ?>" data-type="department">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Liste des Services</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Nom Chef</th>
                <th>Nom Département</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $service): ?>
                <tr>
                    <td><?php echo htmlspecialchars($service['id']); ?></td>
                    <td><?php echo htmlspecialchars($service['nom']); ?></td>
                    <td><?php echo htmlspecialchars($service['nom_chef']); ?></td>
                    <td><?php echo htmlspecialchars($service['nom_departement']); ?></td>
                    <td class="action-buttons">
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modifyModal" data-id="<?php echo $service['id']; ?>" data-type="service">Modify</button>
                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" data-id="<?php echo $service['id']; ?>" data-type="service">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modify Modal -->
<div class="modal fade" id="modifyModal" tabindex="-1" role="dialog" aria-labelledby="modifyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modifyModalLabel">Modify Entry</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="modifyForm" method="POST" action="modifyEntry.php">
                    <input type="hidden" id="modify-id" name="id">
                    <input type="hidden" id="modify-type" name="type">
                    <div class="form-group">
                        <label for="modify-nom">Nom:</label>
                        <input type="text" class="form-control" id="modify-nom" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label for="modify-nom-directeur">Nom Directeur:</label>
                        <input type="text" class="form-control" id="modify-nom-directeur" name="nom_directeur">
                    </div>
                    <div class="form-group" id="modify-nom-pole-group">
                        <label for="modify-nom-pole">Nom Pole:</label>
                        <select class="form-control" id="modify-nom-pole" name="nom_pole">
                            <?php foreach ($poles as $pole): ?>
                                <option value="<?php echo htmlspecialchars($pole['nom']); ?>"><?php echo htmlspecialchars($pole['nom']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group" id="modify-id-departement-group">
                        <label for="modify-id-departement">Nom Département:</label>
                        <select class="form-control" id="modify-id-departement" name="id_departement">
                            <?php foreach ($departments as $department): ?>
                                <option value="<?php echo $department['id']; ?>"><?php echo htmlspecialchars($department['nom']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this entry?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action="deleteEntry.php">
                    <input type="hidden" id="delete-id" name="id">
                    <input type="hidden" id="delete-type" name="type">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../pages/scboot.php'; ?>
<?php include '../pages/footer.php'; ?>

<script>
    $('#modifyModal').on('show.bs.modal', function (event) {
        console.log('Modify modal triggered');
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var type = button.data('type');
        console.log('ID:', id, 'Type:', type);
        var modal = $(this);
        modal.find('#modify-id').val(id);
        modal.find('#modify-type').val(type);

        // Fetch existing data and populate the form
        $.ajax({
            url: 'fetchEntry.php',
            method: 'GET',
            data: { id: id, type: type },
            success: function (data) {
                console.log('Data fetched:', data);
                var entry = JSON.parse(data);
                modal.find('#modify-nom').val(entry.nom);
                
                if (type === 'service') {
                    modal.find('label[for="modify-nom-directeur"]').text('Nom Chef:');
                    modal.find('#modify-nom-directeur').val(entry.nom_chef);
                    modal.find('#modify-id-departement-group').show();
                    modal.find('#modify-id-departement').val(entry.id_departement);
                } else {
                    modal.find('label[for="modify-nom-directeur"]').text('Nom Directeur:');
                    modal.find('#modify-nom-directeur').val(entry.nom_directeur);
                    modal.find('#modify-id-departement-group').hide();
                }

                if (type === 'department') {
                    modal.find('#modify-nom-pole-group').show();
                    modal.find('#modify-nom-pole').val(entry.nom_pole);
                } else {
                    modal.find('#modify-nom-pole-group').hide();
                }
            }
        });
    });

    $('#deleteModal').on('show.bs.modal', function (event) {
        console.log('Delete modal triggered');
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var type = button.data('type');
        console.log('ID:', id, 'Type:', type);
        var modal = $(this);
        modal.find('#delete-id').val(id);
        modal.find('#delete-type').val(type);
    });
</script>
</body>
</html>