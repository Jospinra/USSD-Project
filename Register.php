<?php
session_start();
require 'db.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Phone_Number = filter_var($_POST['Phone_Number'], FILTER_SANITIZE_STRING);
    if (!preg_match("/^[0-9]{10}$/", $Phone_Number)) {
        $error_message = "Invalid phone number format.";
    }

    $Password = $_POST['Password'];
    if (empty($error_message) && strlen($Password) >= 8) {
        // Prepare and execute select statement to look for the user by phone number
        $stmt = $pdo->prepare("SELECT * FROM users_information WHERE Phone_Number = ?");
        $stmt->execute([$Phone_Number]);

        if ($stmt->rowCount() == 0) {
            // Directly store the password without hashing
            $insert_stmt = $pdo->prepare("INSERT INTO users_information (Phone_Number, Password) VALUES (?, ?)");
            if ($insert_stmt->execute([$Phone_Number, $Password])) {
                header("Location: index.php"); // Redirect to login page
                exit;
            } else {
                $error_message = "Error registering user.";
            }
        } else {
            $error_message = "Phone number already exists.";
        }
    } else {
        $error_message .= " Password must be at least 8 characters long.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="tel"], input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error {
            color: #d9534f;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<form method="post">
    <h2>Register</h2>
    <?php if (!empty($error_message)): ?>
        <p class="error"><?= htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <label for="Phone_Number">Phone Number:</label>
    <input type="tel" id="Phone_Number" name="Phone_Number" required pattern="[0-9]{10}">
    <label for="Password">Password (8+ characters):</label>
    <input type="password" id="Password" name="Password" required>
    <input type="submit" value="Register">
</form>

</body>
</html>
