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

$inputJSON = file_get_contents('php://input');

// Try to decode the received JSON
$input = json_decode($inputJSON, true);
 

if (isset($input['ph_number'])) {
    $phone_number = $input['ph_number'] ?? '';
    $source_url = $input['source_url'] ?? '';
    $ip = $input['ip'] ?? '';

    $checkJunkPhoneNumber = checK_junks($db,'phone_number',$phone_number);
    if($checkJunkPhoneNumber > 0){
        echo json_encode([
            'isValid' => false,
            'reason' => 'junk-phone',
            'msg' => 'Oops! Something went wrong. Please try again later.'
        ]);
        return;
    }

    $checkPhoneNumber = check_phone_number($db,$phone_number,$source_url);


    if($checkPhoneNumber > 0){
        echo json_encode([
            'isValid' => false,
            'reason' => 'duplicate',
            'msg' => 'Phone Number is already exist!.'
        ]);
        return;
    }

    echo json_encode([
        'isValid' => true,
    ]);
    return;
}

$db = null;

die();

function checK_junks($db,$col,$val) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM leads WHERE $col = :col AND status = :status");
    $stmt->bindParam(':col', $val);
    $status = 'junk'; // Assign 'junk' to a variable
    $stmt->bindParam(':status', $status);
    $stmt->execute();
    $count = $stmt->fetchColumn(); 
    return $count;
}

function check_phone_number($db, $phone_number, $source_url) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM leads WHERE phone_number = :phone_number AND source_url = :source_url");
    $stmt->bindParam(':phone_number', $phone_number);
    $stmt->bindParam(':source_url', $source_url);
    $stmt->execute();

    $count = $stmt->fetchColumn();

    // Check if the email exists
    return $count;
}

function check_time($db,$source_url,$ip_address) {
    $currentDateTime = date("Y-m-d H:i:s");
    $previousDateTime = date("Y-m-d H:i:s", strtotime("-5 seconds"));

    $stmt = $db->prepare("SELECT COUNT(*) FROM leads WHERE source_url = :source_url AND ip_address = :ip_address AND created_at BETWEEN :previousDateTime AND :currentDateTime");
    $stmt->bindParam(':source_url', $source_url);
    $stmt->bindParam(':ip_address', $ip_address);
    $stmt->bindParam(':previousDateTime', $previousDateTime);
    $stmt->bindParam(':currentDateTime', $currentDateTime);
    $stmt->execute();

    $count = $stmt->fetchColumn();

    // Check if the record exists
    return $count;
}