<?php
class Menu {
    protected $text;
    protected $sessionId;
    protected $pdo;

    public function __construct($text, $sessionId, $pdo) {
        $this->text = $text;
        $this->sessionId = $sessionId;
        $this->pdo = $pdo;
    }

    public function mainMenuUnregistered() {
        $response = "CON Welcome to Our Investors Contacts Record Keeping System\n";
        $response .= "1. Register\n";
        echo $response;
    }

    public function mainMenuRegistered() {
        $response = "CON Welcome back to Our Investors Contacts Record Keeping System\n";
        $response .= "1. Add Investor\n";
        $response .= "2. Remove Investor\n";
        $response .= "3. Show Investors\n";
        $response .= "4. Update Investor\n";
        $response .= "5. List Registered Users\n";
        $response .= "6. Add Investors Business\n"; // Option to list investors' businesses
        $response .= "7. List Investors Businesses\n"; // Added option to add an investor's business
        echo $response;
    }

    public function menuRegister($textArray) {
        $level = count($textArray);
        $backOption = "0. Back\n";
        
        if ($level == 1) {
            echo "CON Enter your Phone Number:\n$backOption";
        } elseif ($level == 2) {
            echo "CON Set your Password:\n$backOption";
        } elseif ($level == 3) {
            $phone = trim($textArray[1]);
            $password = trim($textArray[2]);
            
            $stmt = $this->pdo->prepare("INSERT INTO users_information (Phone_Number, Password) VALUES (?, ?)");
            $stmt->execute([$phone, password_hash($password, PASSWORD_DEFAULT)]);
            echo "END Thank you for registering.";
        }
    }

    public function menuAddInvestor($textArray) {
        $level = count($textArray);
        $backOption = "0. Back\n";
        
        if ($level == 1) {
            echo "CON Enter Investor's full name:\n$backOption";
        } elseif ($level == 2) {
            echo "CON Enter Investor's phone number:\n$backOption";
        } elseif ($level == 3) {
            echo "CON Enter Investor's share amount:\n$backOption";
        } elseif ($level == 4) {
            $fullName = trim($textArray[1]);
            $phoneNumber = trim($textArray[2]);
            $shares = trim($textArray[3]);
            
            $stmt = $this->pdo->prepare("INSERT INTO members_contacts_info (Phone_Number, Members_Full_Names, Shares) VALUES (?, ?, ?)");
            $stmt->execute([$phoneNumber, $fullName, $shares]);
            echo "END Investor added successfully.";
        }
    }

    public function menuRemoveInvestor($textArray) {
        $backOption = "0. Back\n";
        
        if (count($textArray) == 2) {
            $phoneNumber = trim($textArray[1]);
            
            $stmt = $this->pdo->prepare("DELETE FROM members_contacts_info WHERE Phone_Number = ?");
            $stmt->execute([$phoneNumber]);
            echo $stmt->rowCount() > 0 ? "END Investor removed successfully." : "END No Investor found with that phone number.";
        } else {
            echo "CON Enter the Investor's phone number to remove:\n$backOption";
        }
    }

    public function menuShowInvestors() {
        $stmt = $this->pdo->query("SELECT Members_Full_Names, Phone_Number, Shares FROM members_contacts_info");
        $members = $stmt->fetchAll();
        
        if ($members) {
            $response = "END Investors List:\n";
            foreach ($members as $member) {
                $response .= "{$member['Members_Full_Names']} - {$member['Phone_Number']} - Shares: {$member['Shares']}\n";
            }
            echo $response;
        } else {
            echo "END No Investors found.";
        }
    }

    public function menuUpdateInvestor($textArray) {
        $level = count($textArray);
        $backOption = "0. Back\n";
        
        if ($level == 1) {
            echo "CON Enter the Investor's phone number you want to update:\n$backOption";
        } elseif ($level == 2) {
            echo "CON Enter Investor's new full name:\n$backOption";
        } elseif ($level == 3) {
            $phoneNumber = trim($textArray[1]);
            $newFullName = trim($textArray[2]);
            
            $stmt = $this->pdo->prepare("UPDATE members_contacts_info SET Members_Full_Names = ? WHERE Phone_Number = ?");
            $stmt->execute([$newFullName, $phoneNumber]);
            echo "END Investor updated successfully.";
        }
    }

    public function menuListRegisteredUsers() {
            $stmt = $this->pdo->query("SELECT Phone_Number FROM users_information");
            $users = $stmt->fetchAll();
    
            if ($users) {
                $response = "END Registered Users:\n";
                foreach ($users as $user) {
                    $response .= "{$user['Phone_Number']}\n";
                }
                echo $response;
            } else {
                echo "END No registered users found.";
            }
        
    }

    public function menuAddInvestorsBusiness($textArray) {
        $level = count($textArray);
        $backOption = "0. Back\n";
        
        if ($level == 1) {
            echo "CON Enter Business Name:\n$backOption";
        } elseif ($level == 2) {
            echo "CON Enter Investor's Name:\n$backOption";
        } elseif ($level == 3) {
            $businessName = trim($textArray[1]);
            $investorName = trim($textArray[2]);
            
            // Prepare and execute the insert statement to add the new business
            $stmt = $this->pdo->prepare("INSERT INTO Investors_businesses (Business_Name, Investor_Name) VALUES (?, ?)");
            $stmt->execute([$businessName, $investorName]);
            echo "END Business added successfully.";
        }
    }

    public function menuListInvestorsBusinesses() {
        // Updated to select Investor_Name since there's no Investor_ID column
        $stmt = $this->pdo->query("SELECT Business_Name, Investor_Name FROM Investors_businesses");
        $businesses = $stmt->fetchAll();
    
        if ($businesses) {
            $response = "END Investors' Businesses List:\n";
            foreach ($businesses as $business) {
                // Now correctly referencing Investor_Name
                $response .= "Business: {$business['Business_Name']}, Investor: {$business['Investor_Name']}\n";
            }
            echo $response;
        } else {
            echo "END No investors' businesses found.";
        }
    }
    public function addUser($data){
        $phoneNumber=$data['number'];
        $password=$data['password'];
        $stmt = $this->pdo->prepare("SELECT Phone_Number FROM users_information WHERE Phone_Number = ?");
        $stmt->execute([$data['number']]);
        $userExists = $stmt->fetch();
    
        if ($userExists) {
            return "END User already registered with this phone number.";
        }
    
        // If the user does not exist, proceed with registration
        $stmt = $this->pdo->prepare("INSERT INTO users_information (Phone_Number, Password) VALUES (?, ?)");
        $result = $stmt->execute([$phoneNumber, $password]);
     
        echo "END Thank you for registering.";



        if ($result) {
            return "END Thank you for registering, " . $data['number'] . ".";
        } else {
            return "END Registration failed. Please try again.";
        }

    }
    public function checkUserExistsByPhone($phoneNumber) {
       // Prepare a SQL query to check if a phone number exists in the users_information table
    $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users_information WHERE Phone_Number = ?");
    $stmt->execute([$phoneNumber]);
    $count = $stmt->fetchColumn();

    // Return true if a record exists (count > 0), false otherwise
    return $count > 0;
    }
    
    
}
