<?php
session_start(); // Ensure session starts at the very beginning

require 'db.php'; // Include your database connection

// Redirect to login page if user is not logged in
if (!isset($_SESSION['User'])) {
    header("Location: index.php");
    exit;
}
// Handling POST requests for adding and updating members
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    if ($action == 'add') {
        $fullNames = $_POST['fullNames'];
        $phoneNumber = $_POST['phoneNumber'];
        $shares = $_POST['shares'] ?? '';
        $stmt = $pdo->prepare("INSERT INTO members_contacts_info (Members_Full_Names, Phone_Number, Shares) VALUES (?, ?, ?)");
        $stmt->execute([$fullNames, $phoneNumber, $shares]);
    } elseif ($action == 'update') {
        $memberId = $_POST['memberId'];
        $fullNames = $_POST['fullNames'];
        $phoneNumber = $_POST['phoneNumber'];
        $shares = $_POST['shares'] ?? '';
        $stmt = $pdo->prepare("UPDATE members_contacts_info SET Members_Full_Names = ?, Phone_Number = ?, Shares = ? WHERE Member_ID = ?");
        $stmt->execute([$fullNames, $phoneNumber, $shares, $memberId]);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handling GET request for deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['memberId'])) {
    $memberId = $_GET['memberId'];
    $stmt = $pdo->prepare("DELETE FROM members_contacts_info WHERE Member_ID = ?");
    $stmt->execute([$memberId]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch all members to display
$stmt = $pdo->query("SELECT * FROM members_contacts_info ORDER BY Member_ID ASC");
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container {
            width: 80%;
        }
        .btn-space {
            margin-right: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Dashboard</h2>
    <div class="links">
        <a href="#addMemberModal" data-toggle="modal" class="btn btn-primary">Add New Member</a>
        <a href="logout.php" class="btn btn-warning">Logout</a>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Member ID</th>
                <th>Full Names</th>
                <th>Phone Number</th>
                <th>Shares</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($members as $member): ?>
            <tr>
                <td><?= htmlspecialchars($member['Member_ID']); ?></td>
                <td><?= htmlspecialchars($member['Members_Full_Names']); ?></td>
                <td><?= htmlspecialchars($member['Phone_Number']); ?></td>
                <td><?= htmlspecialchars($member['Shares']); ?></td>
                <td>
                    <a href="#updateMemberModal<?= $member['Member_ID']; ?>" data-toggle="modal" class="btn btn-info btn-space">Update</a>
                    <a href="?action=delete&memberId=<?= $member['Member_ID']; ?>" onclick="return confirm('Are you sure?');" class="btn btn-danger btn-space">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Member Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1" role="dialog" aria-labelledby="AddNewMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="AddNewMemberModalLabel">Add New Member</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group">
                        <label for="fullNames">Full Names</label>
                        <input type="text" class="form-control" name="fullNames" id="fullNames" required>
                    </div>
                    <div class="form-group">
                        <label for="phoneNumber">Phone Number</label>
                        <input type="text" class="form-control" name="phoneNumber" id="phoneNumber" required>
                    </div>
                    <div class="form-group">
                        <label for="shares">Shares</label>
                        <input type="text" class="form-control" name="shares" id="shares">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Member Modals -->
<?php foreach ($members as $member): ?>
<div class="modal fade" id="updateMemberModal<?= $member['Member_ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="UpdateMemberModalLabel<?= $member['Member_ID']; ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="UpdateMemberModalLabel<?= $member['Member_ID']; ?>">Update Member</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="memberId" value="<?= $member['Member_ID']; ?>">
                    <div class="form-group">
                        <label for="fullNames<?= $member['Member_ID']; ?>">Full Names</label>
                        <input type="text" class="form-control" name="fullNames" id="fullNames<?= $member['Member_ID']; ?>" value="<?= htmlspecialchars($member['Members_Full_Names']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phoneNumber<?= $member['Member_ID']; ?>">Phone Number</label>
                        <input type="text" class="form-control" name="phoneNumber" id="phoneNumber<?= $member['Member_ID']; ?>" value="<?= htmlspecialchars($member['Phone_Number']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="shares<?= $member['Member_ID']; ?>">Shares</label>
                        <input type="text" class="form-control" name="shares" id="shares<?= $member['Member_ID']; ?>" value="<?= htmlspecialchars($member['Shares']); ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Member</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
