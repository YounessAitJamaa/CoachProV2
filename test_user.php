<?php
// 1. Include necessary files
require_once 'config/database.php';
require_once 'repositories/UserRepository.php';

// 2. Initialize connection
$database = new Database();
$db = $database->getConnection();

// 3. Initialize the Repository
$userRepo = new UserRepository($db);

echo "<h2>Testing UserRepository...</h2>";

// --- TEST 1: Login with correct credentials ---
// Note: Make sure this user exists in your DB or use credentials you know.
$email = "messi@gmail.com";
$password = "12345"; 

$user = $userRepo->login($email, $password);

if ($user) {
    echo "✅ Success! Logged in as: " . $user->getNom();
    echo "<br>Object Type: " . get_class($user); // Should say 'Coach' or 'Sportif'
    
    // Test a specific method
    if ($user instanceof Coach) {
        echo "<br>Experience: " . $user->getExperience() . " years.";
    }
} else {
    echo "❌ Failed: Invalid credentials or user not found.";
}

?>