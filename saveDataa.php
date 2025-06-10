<?php
// Turn off error display (for production)
ini_set('display_errors', 0);
error_reporting(0);

// Connect to the MySQL database using PDO
try {
    $db = new PDO('mysql:host=localhost;dbname=dbmudbaf00gr1e', 'uhjdojqel1xrp', '3yfrehsrblkl');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode(['error' => "Error: " . $e->getMessage()]);
    exit;
}

// Set CORS and Content-Type headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check for HTTP Basic Authentication
    $authHeader = apache_request_headers();
    
    if (
    !isset($authHeader['Authorization']) ||
    strpos($authHeader['Authorization'], 'Basic ') !== 0
) {
    $db = null;
    die('Forbidden Access');
}

    
    // Extract and decode the credentials
    $base64Credentials = substr($authHeader['Authorization'], 6);
    $credentials = base64_decode($base64Credentials);
    
    if ($credentials !== 'jomejourneywebsite@gmail.com:P@$$word090218leads!#') {
        $db = null;
        echo json_encode(['error' => 'Forbidden Access']);
        exit;
    }
    
    // Get the JSON data from the request body
    $json = file_get_contents('php://input');
    
    if ($json) {
        // Attempt to decode the JSON
        $data = json_decode($json, true);
      
        if ($data !== null) {
            saveLeadData($db, $data);
            echo json_encode(['message' => 'Data Inserted']);
            exit;
        } else {
            echo json_encode(['error' => 'Failed to parse JSON data.']);
            exit;
        }
    } else {
        echo json_encode(['error' => 'No JSON data received.']);
        exit;
    }
} else {
    echo json_encode(['error' => 'Only accepts POST requests.']);
    exit;
}

$db = null;
exit();

// Define a function to save lead data
function saveLeadData($db, $data) {
    $commonFields = [
        "source_url",
        "client_id",
        "project_id",
        "ip_address",
        "name",
        "email",
        "ph_number",
        "status",
        "is_send_discord",
        "is_verified"
    ];
    $leadsData = array_intersect_key($data, array_flip($commonFields));
    
    $stmt = $db->prepare("INSERT INTO leads (source_url, client_id, project_id, ip_address, name, email, phone_number, status, is_send_discord, is_verified) VALUES (:source_url, :client_id, :project_id, :ip_address, :name, :email, :ph_number, :status, :is_send_discord, :is_verified)");
    $stmt->execute($leadsData);
    $leadId = $db->lastInsertId();
    
    $otherFields = array_diff_key($data, array_flip($commonFields));
    if (!empty($otherFields)) {
        $stmt = $db->prepare("INSERT INTO lead_details (lead_id, lead_form_key, lead_form_value) VALUES (:lead_id, :lead_form_key, :lead_form_value)");
        foreach ($otherFields as $key => $value) {
            $stmt->execute([
                ':lead_id' => $leadId,
                ':lead_form_key' => $key,
                ':lead_form_value' => $value
            ]);
        }
    }
}
