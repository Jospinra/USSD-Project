<?php
session_start();
if (!isset($_SESSION['User'])) {
    header("Location:index.php");
    exit;
}

require 'db.php';

// Sanitize and validate 'Member_ID' GET parameter
if (isset($_GET['Member_ID']) && filter_var($_GET['Member_ID'], FILTER_VALIDATE_INT)) {
    $Member_ID = $_GET['Member_ID'];

    // Prepare a statement to fetch member data
    $stmt = $pdo->prepare("SELECT * FROM members_contacts_info WHERE Member_ID = ?");
    $stmt->execute([$Member_ID]);
    $member = $stmt->fetch();

    if (!$member) {
        echo "Member not found!";
        exit;
    }
} else {
    echo "Error: Invalid or missing Member_ID.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Member</title>
    <!-- Style definitions here -->
</head>
<body>

<h2>Update Member</h2>
<form action="update_member_process.php" method="post">
    <input type="hidden" name="Member_ID" value="<?php echo htmlspecialchars($member['Member_ID'] ?? '', ENT_QUOTES); ?>">
    
    <label for="Members_Full_Names">Full Name:</label>
    <input type="text" name="Members_Full_Names" id="Members_Full_Names" value="<?php echo htmlspecialchars($member['Members_Full_Names'] ?? '', ENT_QUOTES); ?>"><br><br>
    
    <label for="Phone_number">Phone Number:</label>
    <input type="text" name="Phone_number" id="Phone_number" value="<?php echo htmlspecialchars($member['Phone_Number'] ?? '', ENT_QUOTES); ?>">
    
    <input type="submit" value="Update">
</form>

</body>
</html>
