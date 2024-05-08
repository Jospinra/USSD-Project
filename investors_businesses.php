<?php
// Start session and ensure the user is logged in as an admin
session_start();
if (!isset($_SESSION['Admin'])) {
    header("Location: Admin_login.php");
    exit;
}

// Include database connection
require 'db.php';

// Handling form submissions for adding and editing businesses
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $businessName = filter_input(INPUT_POST, 'business_name', FILTER_SANITIZE_STRING);
        $investorName = filter_input(INPUT_POST, 'investor_name', FILTER_SANITIZE_STRING);

        if ($action === 'add') {
            $sql = "INSERT INTO Investors_businesses (Business_Name, Investor_Name) VALUES (:businessName, :investorName)";
            $params = [':businessName' => $businessName, ':investorName' => $investorName];
        } elseif ($action === 'edit') {
            $businessId = filter_input(INPUT_POST, 'business_id', FILTER_SANITIZE_NUMBER_INT);
            $sql = "UPDATE Investors_businesses SET Business_Name = :businessName, Investor_Name = :investorName WHERE Business_ID = :businessId";
            $params = [':businessName' => $businessName, ':investorName' => $investorName, ':businessId' => $businessId];
        }

        $stmt = $pdo->prepare($sql);
        if ($stmt->execute($params)) {
            header("Location: investors_businesses.php");
            exit;
        } else {
            echo "An error occurred.";
        }
    }
}

// Handling the delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $businessId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $sql = "DELETE FROM Investors_businesses WHERE Business_ID = :businessId";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([':businessId' => $businessId])) {
        header("Location: investors_businesses.php");
        exit;
    } else {
        echo "An error occurred.";
    }
}

// Fetch all businesses
$stmt = $pdo->prepare("SELECT Business_ID, Business_Name, Investor_Name FROM Investors_businesses");
$stmt->execute();
$businesses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Businesses</title>
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
        <h2>Manage Businesses</h2>
          <!-- Return to Dashboard Button -->
        <a href="Admin_Dashboard.php" class="btn btn-info">Return to Dashboard</a>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addBusinessModal">Add Business</button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Business Name</th>
                    <th>Investor Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($businesses as $business): ?>
                <tr>
                    <td><?= htmlspecialchars($business['Business_ID']); ?></td>
                    <td><?= htmlspecialchars($business['Business_Name']); ?></td>
                    <td><?= htmlspecialchars($business['Investor_Name']); ?></td>
                    <td>
                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#editBusinessModal-<?= $business['Business_ID']; ?>">Edit</button>
                        <a href="?action=delete&id=<?= $business['Business_ID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Add Business Modal -->
        <div class="modal fade" id="addBusinessModal" tabindex="-1" role="dialog" aria-labelledby="addBusinessModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addBusinessModalLabel">Add Business</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="businessName">Business Name</label>
                                <input type="text" class="form-control" id="businessName" name="business_name" required>
                            </div>
                            <div class="form-group">
                                <label for="investorName">Investor Name</label>
                                <input type="text" class="form-control" id="investorName" name="investor_name" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="action" value="add">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Business</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Business Modal -->
        <?php foreach ($businesses as $business): ?>
        <div class="modal fade" id="editBusinessModal-<?= $business['Business_ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="editBusinessModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editBusinessModalLabel">Edit Business</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="businessName-<?= $business['Business_ID']; ?>">Business Name</label>
                                <input type="text" class="form-control" id="businessName-<?= $business['Business_ID']; ?>" name="business_name" value="<?= htmlspecialchars($business['Business_Name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="investorName-<?= $business['Business_ID']; ?>">Investor Name</label>
                                <input type="text" class="form-control" id="investorName-<?= $business['Business_ID']; ?>" name="investor_name" value="<?= htmlspecialchars($business['Investor_Name']); ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="business_id" value="<?= $business['Business_ID']; ?>">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
