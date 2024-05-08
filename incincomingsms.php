<?php
include 'user_handler.php';  // Ensure this path is correct
include 'db.php';

// Receive data from the gateway 
$phoneNumber = $_POST['from'] ?? null;
$text = $_POST['text'] ?? null;  // Using null coalescing operator to handle undefined indexes

if (!$phoneNumber || !$text) {
    echo "END Please provide all required data.";
    exit;
}

$textArray = explode(" ", $text);

// Define $sessionId or pass it as an argument when creating the Menu object
$sessionId = "";  // Define or obtain the session ID as needed
$pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass, $options);  // Configure as necessary

if (count($textArray) >= 1) {
    $password = $textArray[0];

    if ($password == '') {
        echo "END Fill your password";
    } else {
        $menu = new Menu($textArray, $sessionId, $pdo);
        $isRegistered = $menu->checkUserExistsByPhone($phoneNumber);
        if ($isRegistered) {
            echo "END Already registered";
        } else {
            $data = [
                'number' => $phoneNumber,
                'password' => $password
            ];
            $insert = $menu->addUser($data);
            // echo $insert['message'] . $data['number'];
        }
    }
} else {
    echo "END Your SMS must contain all required information.";
}
?>
