<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email_Address = $_POST['Email_Address'] ?? '';
    $Password = $_POST['Password'] ?? ''; 

    $stmt = $pdo->prepare("SELECT * FROM admins_information WHERE Email_Address = ? AND Password = ?");
    $stmt->execute([$Email_Address, $Password]); 
    $user = $stmt->fetch();

   if ($user) {
        //Debugging line
       error_log("User authenticated, redirecting...");

        //$_SESSION['Members_ID'] = $user['Admins_ID'];
       $_SESSION['Admin'] = $user['Admin_ID'];
        header("Location: Admin_Dashboard.php");
        echo "im in";
      ?>
      <script>
        window.location.href='Admin_Dashboard.php';
      </script>
      <?php


         
    } else {
        echo "<p style='color:red;text-align:center;'>Invalid login credentials.</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        form {
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input[type=email], input[type=password] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box; /* Added for proper box sizing */
        }
        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type=submit]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

 <form method="post">
    <div style="max-width: 300px; margin: auto;">
     <h3>
        <label for="Email_Address">Email:</label>
        <input type="email" id="Email_Address" name="Email_Address" required><br>
        <label for="Password">Password:</label>
        <input type="password" id="Password" name="Password" required><br>
     </h3>
        <input type="submit" value="Login">
        <!--<a href="register.php" style="display: inline-block; margin-top: 10px; text-decoration: none; background-color: #007bff; color: white; padding: 8px 15px; border-radius: 5px;">Register</a>-->
    </div>
 </form>


</body>
</html>
