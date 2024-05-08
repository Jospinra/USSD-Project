<?php
session_start();
if (!isset($_SESSION['Admin'])) {
    header("Location: Admin_login.php");
    exit;
}

require 'db.php';
// Fetch unapproved users
$stmt = $pdo->prepare("SELECT * FROM users_information WHERE is_approved = 0");
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
        }
        .sidebar {
            height: 100vh;
            width: 240px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #343a40;
            overflow-x: hidden;
            padding-top: 20px;
        }
        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 16px;
            color: #ddd;
            display: block;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background-color: #495057;
            color: white;
        }
        .main-content {
            margin-left: 240px;
            padding: 20px;
        }
        .welcome h2 {
            color: #333;
        }
        .welcome p {
            color: #666;
            font-size: 16px;
        }
        .table {
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .thead-dark th {
            background-color: #007bff;
            color: white;
        }
        .action-links a {
            color: #007bff;
            font-weight: 500;
        }
        .action-links a:hover {
            color: #0056b3;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="dashboard.php">Investors Records</a>
        <a href="manage_users.php">Registered Users</a>
        <a href="investors_businesses.php">Investors Businesses</a>
        <a href="Admin_Logout.php">Logout</a>
    </div>

    <div class="main-content">
        <div class="welcome">
            <h2>Welcome, Admin!</h2>
            <p>Hello Admin, Welcome to the Management of Our Investors Contacts Record Keeping System.</p>
        </div>
        <h3>User Registration Requests</h3>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Users ID</th>
                    <th>Phone Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['Users_ID']); ?></td>
                        <td><?= htmlspecialchars($user['Phone_Number']); ?></td>
                        <td class="action-links">
                            <a href="approve_user.php?id=<?= $user['Users_ID']; ?>">Approve</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
