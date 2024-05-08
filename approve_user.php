<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['Admin'])) {
    header("Location: Admin_login.php");
    exit;
}

require 'db.php';

// Check if 'id' is set in the URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Prepare the statement to update the user's approval status
    $stmt = $pdo->prepare("UPDATE users_information SET is_approved = 1 WHERE Users_ID = :userId");

    // Bind the parameter
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect back to the admin dashboard with a success message
        $_SESSION['message'] = 'User approved successfully.';
        header("Location: index.php");
    } else {
        // Redirect back to the admin dashboard with an error message
        $_SESSION['error'] = 'Failed to approve the user.';
        header("Location: Admin_Dashboard.php");
    }
} else {
    // Redirect back to the admin dashboard if 'id' is not set in the URL
    header("Location: Admin_Dashboard.php");
}

?>
