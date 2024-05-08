<?php
session_start();
if (!isset($_SESSION['User'])) {
    header("Location:index.php");
}
?>
<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collecting form data
    $Member_ID = $_POST['Member_ID'];
    $Members_Full_Names = $_POST['Members_Full_Names'];
    $Phone_Number = $_POST['Phone_Number'];
    $shares = $_POST['Shares'];
    
    // Preparing and executing the SQL statement
    $stmt = $pdo->prepare("INSERT INTO members_contacts_info (Member_ID, Members_Full_Names, Phone_Number, Shares) VALUES (?, ?, ?, ?)");
    $stmt->execute([$Member_ID, $Members_Full_Names, $Phone_Number, $shares]);

    // Redirecting to the dashboard after successful registration
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Member</title>
    <style>
        /* Styling omitted for brevity */
    </style>
</head>
<body>

<form method="post">
    <h2>Register Member</h2>

    <label for="Member_ID">Member ID:</label><br>
    <input type="number" id="Member_ID" name="Member_ID" required><br>

    <label for="Members_Full_Names">Members Full Names:</label><br>
    <input type="text" id="Members_Full_Names" name="Members_Full_Names" required><br>

    <label for="Phone_Number">Phone Number:</label><br>
    <input type="text" id="Phone_Number" name="Phone_Number" required><br>

    <label for="Shares">Shares:</label><br>
    <input type="number" id="Shares" name="Shares" required><br>

    <input type="submit" value="Register Member">
</form>

</body>
</html>
