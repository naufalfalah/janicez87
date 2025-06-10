<?php

// Connect to the SQLite database
try {
    $db = new PDO('mysql:host=localhost;dbname=dbmudbaf00gr1e', 'uhjdojqel1xrp', '3yfrehsrblkl');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
} 


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



if ($_SERVER['REQUEST_METHOD'] === 'POST') { 

    // Check for HTTP Basic Authentication
    $authHeader = apache_request_headers();
    
    if (!isset($authHeader['Authorization']) && !strpos($authHeader['Authorization'], 'Basic ') === 0) {
        $db = null;
        die('Forbidden Access!');  
    }

    // Extract and decode the credentials
    $base64Credentials = substr($authHeader['Authorization'], 6);
    $credentials = base64_decode($base64Credentials);

    if ($credentials !== 'jomejourneywebsite@gmail.com:P@$$word090218leads!#') {
        
        $db = null;
        die('Forbidden Access !');
    }

    // Get the JSON data from the request body
    $json = file_get_contents('php://input');
    
    if ($json) {
        // Attempt to decode the JSON
        $data = json_decode($json, true); // Change to `false` for an object
      
        if ($data !== null) {
            saveLeadData($db, $data);

            echo 'data Inserted';
        } else {
            // JSON parsing failed
            echo "Failed to parse JSON data.";
        }
    } else {
        // No JSON data found in the request
        echo "No JSON data received.";
    }
} else {
    // This is not a POST request
    echo "Only accepts POST requests.";
}

$db = null;

die();


// Define a function to save lead data
function saveLeadData($db, $data) {
    try {
        // List of fields you expect in the leads data
        $commonFields = ["source_url", "client_id", "project_id", "ip_address", "firstname", "email", "ph_number", "status", "is_send_discord", "is_verified"];

        // Intersect to only keep the fields present in both $data and $commonFields
        $leadsData = array_intersect_key($data, array_flip($commonFields));

        // Make sure all fields are present before running the query
        // var_dump($leadsData);  // Debugging - check the data before executing

        // Prepare the query
        $stmt = $db->prepare("INSERT INTO leads (source_url, client_id, project_id, ip_address, name, email, phone_number, status, is_send_discord, is_verified) 
                            VALUES (:source_url, :client_id, :project_id, :ip_address, :firstname, :email, :ph_number, :status, :is_send_discord, :is_verified)");

        // Execute the statement
        $stmt->execute($leadsData);

        // Get the last inserted ID
        $leadId = $db->lastInsertId();
        $otherFields = array_diff_key($data, array_flip($commonFields));
        if (!empty($otherFields)) {
            $stmt = $db->prepare("INSERT INTO lead_details (lead_id, lead_form_key, lead_form_value) VALUES (:lead_id, :lead_form_key, :lead_form_value)");
            foreach ($otherFields as $key => $value) {
                $stmt->execute([
                    ':lead_id' => $leadId,
                    ':lead_form_key' => "$key",
                    ':lead_form_value' => $value
                ]);
            }
        }
        echo "Lead inserted with ID: " . $leadId;

    } catch (PDOException $e) {
        // Catch and display any errors
        echo "Error: " . $e->getMessage();
    }
}