<?php
// Start session and check for admin session
session_start();
if (!isset($_SESSION['Admin'])) {
    header("Location: Admin_login.php");
    exit;
}

// Include database connection
require 'db.php';

// Handling form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $phoneNumber = filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_STRING);

        if ($action === 'add') {
            $sql = "INSERT INTO users_information (Phone_Number) VALUES (:phoneNumber)";
        } elseif ($action === 'edit') {
            $userId = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
            $sql = "UPDATE users_information SET Phone_Number = :phoneNumber WHERE Users_ID = :userId";
            $params = [':phoneNumber' => $phoneNumber, ':userId' => $userId];
        }

        $stmt = $pdo->prepare($sql);
        isset($params) ? $stmt->execute($params) : $stmt->execute([':phoneNumber' => $phoneNumber]);

        header("Location: manage_users.php");
        exit;
    }
}

// Handling delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $userId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $sql = "DELETE FROM users_information WHERE Users_ID = :userId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':userId' => $userId]);
    header("Location: manage_users.php");
    exit;
}

// Fetch all approved users
$stmt = $pdo->prepare("SELECT * FROM users_information WHERE is_approved = 1");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Registered Users</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 20px; }
        .container { max-width: 800px; }
        .modal-dialog { max-width: 500px; }
        .table { margin-top: 20px; }
        h2 { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Registered Users</h2>
        <a href="Admin_Dashboard.php" class="btn btn-info">Return to Dashboard</a>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Add User</button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['Users_ID']); ?></td>
                    <td><?= htmlspecialchars($user['Phone_Number']); ?></td>
                    <td>
                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#editUserModal-<?= $user['Users_ID']; ?>">Edit</button>
                        <a href="?action=delete&id=<?= $user['Users_ID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Add User Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="phoneNumber">Phone Number</label>
                                <input type="text" class="form-control" id="phoneNumber" name="phone_number" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="action" value="add">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit User Modals -->
        <?php foreach ($users as $user): ?>
        <div class="modal fade" id="editUserModal-<?= $user['Users_ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel-<?= $user['Users_ID']; ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUserModalLabel-<?= $user['Users_ID']; ?>">Edit User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="phoneNumber-<?= $user['Users_ID']; ?>">Phone Number</label>
                                <input type="text" class="form-control" id="phoneNumber-<?= $user['Users_ID']; ?>" name="phone_number" value="<?= htmlspecialchars($user['Phone_Number']); ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="user_id" value="<?= $user['Users_ID']; ?>">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
