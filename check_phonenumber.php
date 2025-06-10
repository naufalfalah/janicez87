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
  
if (isset($_POST['phone_number'])) {
    $phone_number = $_POST['phone_number'] ?? '';
    $source_url = $_POST['source_url'] ?? '';

   
    try { 
        echo json_decode(check_number($db,$phone_number,$source_url));
        return;
    } catch (Exception $e) {
        echo json_decode(false);
        return;
    }
}

$db = null;



// Define a function to save lead data
function check_number($db, $phone_number, $source_url) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM leads WHERE phone_number = :phone_number AND source_url = :source_url");
    $stmt->bindParam(':phone_number', $phone_number);
    $stmt->bindParam(':source_url', $source_url);
    $stmt->execute();

    $count = $stmt->fetchColumn();

    // Check if the phone_number exists
    return $count;
}
