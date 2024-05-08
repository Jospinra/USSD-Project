<?php
require_once 'session_check.php'; // Ensures the user is logged in
require 'db.php'; // Database connection

// Check if Member_ID is set and is a valid number
if (isset($_GET['Member_ID']) && filter_var($_GET['Member_ID'], FILTER_VALIDATE_INT)) { 
    // Prepare the SQL statement to prevent SQL injection
    $stmt = $pdo->prepare("DELETE FROM members_contacts_info WHERE Member_ID = ?");
    
    // Attempt to execute the prepared statement
    try {
        $stmt->execute([$_GET['Member_ID']]);
        // Check if a row was affected (i.e., a member was actually deleted)
        if ($stmt->rowCount() > 0) {
            $_SESSION['success_message'] = 'Member successfully deleted.';
        } else {
            // If no rows were affected, the Member_ID didn't match any records
            $_SESSION['error_message'] = 'No member found with that ID.';
        }
    } catch (PDOException $e) {
        // If an error occurred with the database interaction, store an error message in the session
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
    }
} else {
    // If Member_ID is not set or is not a valid number, store an error message in the session
    $_SESSION['error_message'] = "Error: Invalid or missing Member_ID.";
}

// Redirect to the dashboard
header("Location: dashboard.php");
exit;
