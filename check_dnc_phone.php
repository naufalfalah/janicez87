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
            'status' => false,
            'msg' => 'Oops! This is junk lead. Please try again later.'
        ]);
        return;
    }

    $check_dnc = check_dnc($db, $phone_number);
    if($check_dnc > 0){
        echo json_encode([
            'status' => true,
            'msg' => 'Oops! This is dnc lead. Please try again later.'
        ]);
        return;
    }else{
        echo json_encode([
            'status' => false,
            'msg' => "This record doesn't match dnc status"
        ]);
        return;
    }    
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



function check_dnc($db, $phone_number) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM leads WHERE phone_number = :phone_number AND status = 'DNC Registry'");
    $stmt->bindParam(':phone_number', $phone_number);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count;
}
