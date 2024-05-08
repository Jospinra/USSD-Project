<?php
session_start();
require 'db.php'; // Make sure the path to your db.php file is correct.

// Check if user is logged in and has a user ID in the session
if (isset($_SESSION['Users_ID'])) {
    $userId = $_SESSION['Users_ID'];

    // Prepare a statement to select the approval status of the logged-in user
    $stmt = $pdo->prepare("SELECT is_approved FROM users_information WHERE Users_ID = :userId LIMIT 1");
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch();

    // Check if the user exists and is approved
    if (!$user || $user['is_approved'] == 0) {
        // User is not approved or does not exist, redirect to login page with an error
        $_SESSION['login_error'] = 'Your account is not approved or does not exist.';
        header("Location: index.php");
        exit;
    }
    header("Location: dashboard.php");
    // If the user is approved, the script does nothing, allowing the page to load normally
} else {
    // If the user is not logged in, redirect to login page
    header("Location: index.php");
    exit;
}
?>
