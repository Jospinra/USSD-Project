<?php
session_start();
require 'db.php';

$error_message = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data, ensuring input names match those in your form
    $Phone_Number = $_POST['Phone_Number'] ?? '';
    $Password = $_POST['Password'] ?? '';

    // Prepare the SQL statement to prevent SQL injection and check if the user is approved
    $stmt = $pdo->prepare("SELECT * FROM users_information WHERE Phone_Number = ? AND is_approved = 1");
    $stmt->execute([$Phone_Number]);
    $user = $stmt->fetch();

    // Check if user exists and verify the password
    // Ensure the column name matches your database. Assuming 'Password' here.
    if ($user &&  $Password==$user['Password']) {
        // Set user session or any other session required
        $_SESSION['User'] = $user['Users_ID'];

        // Redirect to the dashboard
        header("Location: dashboard.php");
        exit;
    } 
    else {
        // Set error message to be displayed to the user
        $error_message = "Invalid login credentials or your account has not been approved yet.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 340px;
        }
        h3 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }
        .form-control {
            border-radius: 20px;
            margin-bottom: 20px;
        }
        .btn {
            width: 100%;
            border-radius: 20px;
        }
        .login-btn {
            background-color: #007bff;
            border: none;
        }
        .register-btn {
            background-color: #28a745;
            border: none;
            margin-top: 10px;
        }
        .error {
            color: #d9534f;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <form method="POST">
        <h3>Welcome Back</h3>
        <?php //if (!empty($error_message)): ?>
            <p class="error"><?= htmlspecialchars($error_message); ?></p>
        <?php //endif; ?>
        <input type="tel" id="Phone_Number" name="Phone_Number" class="form-control" placeholder="Phone Number" required pattern="[0-9]{10}">
        <input type="password" id="Password" name="Password" class="form-control" placeholder="Password" required>
        <input type="submit" value="Login" class="btn btn-primary login-btn">
        <a href="register.php" class="btn btn-success register-btn">Register</a>
    </form>
</div>

</body>
</html>
