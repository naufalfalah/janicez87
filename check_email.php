<?php

// Connect to the SQLite database
try {
    $db = new PDO('mysql:host=localhost;dbname=dbmudbaf00gr1e', 'uhjdojqel1xrp', '3yfrehsrblkl');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
} 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
  
if (isset($_POST['email'])) {
    $email = $_POST['email'] ?? '';
    $source_url = $_POST['source_url'] ?? '';

   

    // Validate and sanitize the email input
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    try { 
        echo json_decode(check_email($db,$email,$source_url));
        return;
    } catch (Exception $e) {
        echo json_decode(false);
        return;
    }
}

$db = null;



// Define a function to save lead data
function check_email($db, $email, $source_url) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM leads WHERE email = :email AND source_url = :source_url");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':source_url', $source_url);
    $stmt->execute();

    $count = $stmt->fetchColumn();

    // Check if the email exists
    return $count;
}
