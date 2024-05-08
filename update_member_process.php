<?php
session_start();
// Redirect to login page if user is not logged in
if (!isset($_SESSION['User'])) {
    header("Location:index.php");
    exit;
}

require 'db.php';

// Check if the required POST data is set and not empty
if (!empty($_POST['Member_ID']) && !empty($_POST['Members_Full_Names']) && !empty($_POST['Phone_number'])) {
    // Extract and sanitize form data
    $Member_ID = filter_input(INPUT_POST, 'Member_ID', FILTER_SANITIZE_NUMBER_INT);
    $Members_Full_Names = filter_input(INPUT_POST, 'Members_Full_Names', FILTER_SANITIZE_STRING);
    $Phone_number = filter_input(INPUT_POST, 'Phone_number', FILTER_SANITIZE_STRING);

    // Validate the sanitized input
    if (filter_var($Member_ID, FILTER_VALIDATE_INT) === false) {
        echo "Error: Invalid Member ID.";
        exit;
    }
    // Assuming phone numbers are integers for the sake of example
    // This might need to be adjusted depending on your actual data format
    if (!preg_match('/^\d+$/', $Phone_number)) {
        echo "Error: Invalid Phone Number.";
        exit;
    }

    // Prepare an UPDATE statement to prevent SQL injection
    $stmt = $pdo->prepare("UPDATE members_contacts_info SET Members_Full_Names = ?, Phone_Number = ? WHERE Member_ID = ?");
    
    // Attempt to execute the prepared statement
    try {
        $stmt->execute([$Members_Full_Names, $Phone_number, $Member_ID]);
        // Check if a row was affected
        if ($stmt->rowCount() > 0) {
            $_SESSION['success_message'] = 'Member details updated successfully.';
        } else {
            // If no rows were affected, the Member_ID didn't match any records
            $_SESSION['error_message'] = 'No member found with that ID or no new data to update.';
        }
    } catch (PDOException $e) {
        // Handle potential errors during execution
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
    }
    // Redirect to dashboard with a success or error message
    header("Location: dashboard.php");
    exit;
} else {
    // Handle the error for missing form data
    echo "Error: Missing data. Please ensure all form fields are filled out.";
    exit;
}
