<?php
// Ensure the required files and definitions are included
include_once 'user_handler.php'; // Assuming user_handler.php contains the Menu class and related functions
require 'db.php'; // Ensures $pdo is available for database operations

// Collect data from the POST request
$sessionId = $_POST['sessionId'] ?? '';
$serviceCode = $_POST['serviceCode'] ?? '';
$text = $_POST['text'] ?? '';

// Initialize $phoneNumber to a default value to ensure it's always set
$phoneNumber = "";

// Check if "Phone_Number" is provided in the POST request
if (isset($_POST['Phone_Number']) && !empty($_POST['Phone_Number'])) {
    $phoneNumber = $_POST['Phone_Number'];
}

// Define the function to check user registration by phone number
function checkUserRegistrationByPhoneNumber($pdo, $phoneNumber) {
    // If no phone number is provided, treat the user as not registered
    if (empty($phoneNumber)) {
        return false;
    }
    
    $sql = "SELECT Users_ID FROM users_information WHERE Phone_Number = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$phoneNumber]);
    $user = $stmt->fetch();
    return $user !== false; // Returns true if user exists, false otherwise
}

// Check if a user is registered by their phone number
$isRegistered = checkUserRegistrationByPhoneNumber($pdo, $phoneNumber);

// Instantiate the Menu class with necessary parameters
$menu = new Menu($text, $sessionId, $pdo);

if ($text == "") {
    // Main menu determination based on registration status
    if (!$isRegistered) {
        // This now includes the scenario where no phone number is provided
        $menu->mainMenuUnregistered();
    } else {
        $menu->mainMenuRegistered();
    }
} else {
    // Process USSD input text
    $textArray = explode("*", $text);

    // Back functionality - If the user inputs '0', show the main menu again
    if (end($textArray) === "0") {
        if (!$isRegistered) {
            $menu->mainMenuUnregistered();
        } else {
            $menu->mainMenuRegistered();
        }
        return; // Stop further processing to ensure we don't proceed to other cases
    }

    if (!$isRegistered) {
        // Unregistered user menu options
        switch ($textArray[0]) {
            case 1: // Register
                $menu->menuRegister($textArray);
                break;
            default:
                echo "END Invalid option. Please try again.";
                break;
        }
    } else {
        // Registered user menu options
        switch ($textArray[0]) {
            case 1: // Add Member
                $menu->menuAddInvestor($textArray);
                break;
            case 2: // Remove Member
                $menu->menuRemoveInvestor($textArray);
                break;
            case 3: // Show Members
                $menu->menuShowInvestors();
                break;
            case 4: // Update Member
                $menu->menuUpdateInvestor($textArray);
                break;
           case 5: //List Registered Users
               $menu->menuListRegisteredUsers();
                break;
            case 6: // option number for adding an investor's business
                    $menu->menuAddInvestorsBusiness($textArray);
                    break;
            case 7: // List Investors Businesses
                $menu->menuListInvestorsBusinesses();
                break;
            default:
                echo "END Invalid choice. Please try again.";
                break;
        }
    }
}
