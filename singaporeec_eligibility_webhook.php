<?php
// Connect to the SQLite database
try {
    $db = new PDO('mysql:host=localhost;dbname=dbmudbaf00gr1e', 'uhjdojqel1xrp', '3yfrehsrblkl');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    // Open the file to append data
    $commonData = array();
    $additional_data = array();
    $name = '';
    $mobile_number = '';
    $email = '';
    $source_url = 'https://singaporeec.homes/';
    $webhook_data = array(
        'source_url' => "https://singaporeec.homes/",
        'client_id' => null,
        'project_id' => null,
        'ip_address' => null,
        "email" => '',
        'ph_number' => '',
    );
    $commonData = array(
        "name" => $_POST['Name'] ?? '',
        "mobile_number" => $_POST['Contact'] ?? '',
        "email" => $_POST['Email'] ?? '',
        "source_url" => 'https://singaporeec.homes/',
    );
    $data = "New Lead Please take note! New Lead Please take note! " . PHP_EOL;
    $data .= "Jome Official, you have a new lead:". PHP_EOL;
    $data .= "- Source Url: https://singaporeec.homes/ " . PHP_EOL;
  


// Have_you_or_any_of_the_buyers_bought_or_own_any_HDB/DBSS/EC_direct_from_HDB?
if(isset($_POST['Have_you_or_any_of_the_buyers_bought_or_own_any_HDB/DBSS/EC_direct_from_HDB?'])) {
    $key = 'Have_you_or_any_of_the_buyers_bought_or_own_any_HDB/DBSS/EC_direct_from_HDB?';
    $value = $_POST[$key];
    $additional_data[] = array(
        "key" => str_replace("_"," ",$key),
        "value" => $value
    );
    $data .= '- '.ucfirst(str_replace('_',' ',$key)) . ": " . $value . PHP_EOL;
}

// If_yes_to_the_above,_how_many_properties_(direct_from_HDB)_have_you_or_any_of_the_buyers_owned_before?
if(isset($_POST['If_yes_to_the_above,_how_many_properties_(direct_from_HDB)_have_you_or_any_of_the_buyers_owned_before?'])) {
    $key = 'If_yes_to_the_above,_how_many_properties_(direct_from_HDB)_have_you_or_any_of_the_buyers_owned_before?';
    $value = $_POST[$key];
    $additional_data[] = array(
        "key" => str_replace("_"," ",$key),
        "value" => $value
    );
    $data .= '- '.ucfirst(str_replace('_',' ',$key)) . ": " . $value . PHP_EOL;
}

// Are_you_still_holding_on_to_the_property?
if(isset($_POST['Are_you_still_holding_on_to_the_property?'])) {
    $key = 'Are_you_still_holding_on_to_the_property?';
    $value = $_POST[$key];
    $additional_data[] = array(
        "key" => str_replace("_"," ",$key),
        "value" => $value
    );
    $data .= '- '.ucfirst(str_replace('_',' ',$key)) . ": " . $value . PHP_EOL;
}

// Do_you_or_any_of_the_buyers_own_or_have_interest_in_a_Private_Property_now_or_within_the_past_30_months?
if(isset($_POST['Do_you_or_any_of_the_buyers_own_or_have_interest_in_a_Private_Property_now_or_within_the_past_30_months?'])) {
    $key = 'Do_you_or_any_of_the_buyers_own_or_have_interest_in_a_Private_Property_now_or_within_the_past_30_months?';
    $value = $_POST[$key];
    $additional_data[] = array(
        "key" => str_replace("_"," ",$key),
        "value" => $value
    );
    $data .= '- '.ucfirst(str_replace('_',' ',$key)) . ": " . $value . PHP_EOL;
}

// Have_you_or_any_of_the_buyers_taken_any_form_of_Housing_Grant_before_(excluding_Proximity_Grant)?
if(isset($_POST['Have_you_or_any_of_the_buyers_taken_any_form_of_Housing_Grant_before_(excluding_Proximity_Grant)?'])) {
    $key = 'Have_you_or_any_of_the_buyers_taken_any_form_of_Housing_Grant_before_(excluding_Proximity_Grant)?';
    $value = $_POST[$key];
    $additional_data[] = array(
        "key" => str_replace("_"," ",$key),
        "value" => $value
    );
    $data .= '- '.ucfirst(str_replace('_',' ',$key)) . ": " . $value . PHP_EOL;
}

// Nationality_Buyer_1
if(isset($_POST['Nationality_Buyer_1'])) {
    $key = 'Nationality_Buyer_1';
    $value = $_POST[$key];
    $additional_data[] = array(
        "key" => str_replace("_"," ",$key),
        "value" => $value
    );
    $data .= '- '.ucfirst(str_replace('_',' ',$key)) . ": " . $value . PHP_EOL;
}

// Age_Buyer_1
if(isset($_POST['Age_Buyer_1'])) {
    $key = 'Age_Buyer_1';
    $value = $_POST[$key];
    $additional_data[] = array(
        "key" => str_replace("_"," ",$key),
        "value" => $value
    );
    $data .= '- '.ucfirst(str_replace('_',' ',$key)) . ": " . $value . PHP_EOL;
}

// Estimated_Income_(per_annum)_Buyer_1
if(isset($_POST['Estimated_Income_(per_annum)_Buyer_1'])) {
    $key = 'Estimated_Income_(per_annum)_Buyer_1';
    $value = $_POST[$key];
    $additional_data[] = array(
        "key" => str_replace("_"," ",$key),
        "value" => $value
    );
    $data .= '- '.ucfirst(str_replace('_',' ',$key)) . ": " . $value . PHP_EOL;
}

// Relationship_to_Buyer_2
if(isset($_POST['Relationship_to_Buyer_2'])) {
    $key = 'Relationship_to_Buyer_2';
    $value = $_POST[$key];
    $additional_data[] = array(
        "key" => str_replace("_"," ",$key),
        "value" => $value
    );
    $data .= '- '.ucfirst(str_replace('_',' ',$key)) . ": " . $value . PHP_EOL;
}

// Nationality_Buyer_2
if(isset($_POST['Nationality_Buyer_2'])) {
    $key = 'Nationality_Buyer_2';
    $value = $_POST[$key];
    $additional_data[] = array(
        "key" => str_replace("_"," ",$key),
        "value" => $value
    );
    $data .= '- '.ucfirst(str_replace('_',' ',$key)) . ": " . $value . PHP_EOL;
}

// Age_Buyer_2
if(isset($_POST['Age_Buyer_2'])) {
    $key = 'Age_Buyer_2';
    $value = $_POST[$key];
    $additional_data[] = array(
        "key" => str_replace("_"," ",$key),
        "value" => $value
    );
    $data .= '- '.ucfirst(str_replace('_',' ',$key)) . ": " . $value . PHP_EOL;
}

// Estimated_Income_(per_annum)_Buyer_2
if(isset($_POST['Estimated_Income_(per_annum)_Buyer_2'])) {
    $key = 'Estimated_Income_(per_annum)_Buyer_2';
    $value = $_POST[$key];
    $additional_data[] = array(
        "key" => str_replace("_"," ",$key),
        "value" => $value
    );
    $data .= '- '.ucfirst(str_replace('_',' ',$key)) . ": " . $value . PHP_EOL;
}

// Relationship_to_Buyer_1
if(isset($_POST['Relationship_to_Buyer_1'])) {
    $key = 'Relationship_to_Buyer_1';
    $value = $_POST[$key];
    $additional_data[] = array(
        "key" => str_replace("_"," ",$key),
        "value" => $value
    );
    $data .= '- '.ucfirst(str_replace('_',' ',$key)) . ": " . $value . PHP_EOL;
}

// Name
if(isset($_POST['Name'])) {
    $key = 'Name';
    $value = $_POST[$key];
    $result = checkName($value);
    if ($result) {
        $is_discord = 0;
        $webhook_data['status'] = 'junk';
        $webhook_data['is_send_discord'] = 0;
    } 
    $data .= '- ' . ucfirst($key) . ": " . $value . PHP_EOL;
    $webhook_data['firstname'] = $value ?? '';
}

// Email
if(isset($_POST['Email'])) {
    $key = 'Email';
    $value = $_POST[$key];

    $data .= '- ' . ucfirst($key) . ": " . $value . PHP_EOL;
    $webhook_data['email'] = $value ?? '';
}

// Form type
if(isset($_POST['form_type'])) {
    $key = 'form_type';
    $value = $_POST[$key];
    $additional_data[] = array(
        "key" => ucfirst(str_replace('_', ' ', $key)),
        "value" => $value
    );
    $data .= '- ' . ucfirst(str_replace('_', ' ', $key)) . ": " . $value . PHP_EOL;
}

// Contact
if(isset($_POST['Contact'])) {
    $key = 'Contact';
    $value = $_POST[$key];

    $data .= '- ' . ucfirst($key) . ": https://wa.me/+65" . $value . PHP_EOL;
    $webhook_data['ph_number'] = $value ?? '';
}

    $is_discord = 1;
    $webhook_data['is_send_discord'] = 1;
    $webhook_data['status'] = 'clear';
    if (isset($_POST['Email'])) {
        $check_email = $_POST['Email'] ?? '';
        // Validate and sanitize the email input
        $check_email = filter_var($check_email, FILTER_SANITIZE_EMAIL);
        // $checkJunkEmail = check_junk_db($db,'email',$check_email);
        $checkDuplicateEmail = check_email($db,$check_email,$source_url);
        // if($checkJunkEmail > 0){
        //     $is_discord = 0;
        //     $webhook_data['status'] = 'junk';
        //     $webhook_data['is_send_discord'] = 0;
        // }
        if($checkDuplicateEmail > 0){
            $is_discord = 0;
            $webhook_data['status'] = 'junk';
            $webhook_data['is_send_discord'] = 0;
        }
    }
    if (isset($_POST['Contact'])) {
        $check_number = $_POST['Contact'] ?? '';
        // Validate and sanitize the email input
        // $checkJunkNumber = check_junk_db($db,'phone_number',$check_number);
        $checkDuplicateNumber = check_phone($db,$check_number,$source_url);
        // if($checkJunkNumber > 0){
        //     $is_discord = 0;
        //     $webhook_data['status'] = 'junk';
        //     $webhook_data['is_send_discord'] = 0;
        // }
        if($checkDuplicateNumber > 0){
            $is_discord = 0;
            $webhook_data['status'] = 'junk';
            $webhook_data['is_send_discord'] = 0;
        }
    }
    $webhook_data['is_verified'] = 0;
    unset($_POST['Name']);
    unset($_POST['Email']);
    unset($_POST['Contact']);
    $commonData['additional_data'] = $additional_data;
    $LeadManagement = $commonData;
    // JSON encode the lead data
    $jsonData = json_encode($LeadManagement);
    // Check for potential junk content
    $check_junk = checkJunk($jsonData);
    if (isset($check_junk['Terms']) && !empty($check_junk['Terms']) && count($check_junk['Terms']) > 0) {
        $webhook_data['status'] = 'junk';
        $webhook_data['is_send_discord'] = 0;
        $is_discord = 0;
    }
     // Merge $_POST data with webhook data
     $webhook_data = array_merge($webhook_data,$_POST);
    // if($is_discord == 1){
    //     sendDiscordMsg($data);
    // }
     // Send data to the endpoint
    sendFrequencyLead($LeadManagement); 
    sendData($webhook_data);
        $msg = [
            'success' => true,
        ];

        return response()->json($msg, 200);
}
// Function to send data via cURL
function sendData($data)
{  
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://janicez87.sg-host.com/wordpress_endpoint.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Basic am9tZWpvdXJuZXl3ZWJzaXRlQGdtYWlsLmNvbTpQQCQkd29yZDA5MDIxOGxlYWRzISM='
        ),
        CURLOPT_SSL_VERIFYPEER => false
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    echo $response;
}
function checkJunk($data)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://jomejourney.cognitiveservices.azure.com/contentmoderator/moderate/v1.0/ProcessText/Screen',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: text/plain',
            'Ocp-Apim-Subscription-Key: 453fe3c404554800bc2c22d7ef681542'
        ),
        CURLOPT_SSL_VERIFYPEER => false
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response, true);
}
// Define a function to save lead data
function check_email($db, $email, $source_url) {
    // Assuming you have a column 'created_at' storing the timestamp of when the lead was created
        $stmt = $db->prepare("
        SELECT COUNT(*) 
        FROM leads 
        WHERE email = :email 
        AND source_url = :source_url
        AND created_at >= (NOW() - INTERVAL 30 MINUTE)
        ");

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':source_url', $source_url);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        // Check if the email exists within the last 30 minutes
        return $count;

}
function check_phone($db, $check_number, $source_url) {
    $stmt = $db->prepare("
        SELECT COUNT(*) 
        FROM leads 
        WHERE phone_number = :phone_number 
        AND source_url = :source_url
        AND created_at >= (NOW() - INTERVAL 30 MINUTE)
    ");

    $stmt->bindParam(':phone_number', $check_number);
    $stmt->bindParam(':source_url', $source_url);
    $stmt->execute();

    $count = $stmt->fetchColumn();

    // Check if the phone number exists within the last 30 minutes
    return $count;
}
function check_junk_db($db,$col,$val) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM leads WHERE $col = :col AND status = :status");
    $stmt->bindParam(':col', $val);
    $status = 'junk'; // Assign 'junk' to a variable
    $stmt->bindParam(':status', $status);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count;
}
function sendDiscordMsg($data)
{ die();
    $post_array = array(
        "content" => $data,
        "embeds" => null,
        "attachments" => []
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => '',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($post_array),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: __dcfduid=8ec71370974011ed9aeb96cee56fe4d4; __sdcfduid=8ec71370974011ed9aeb96cee56fe4d49deabe12bc0fc3d686d23eaa0b49af957ffe68eadec722cff5170d5c750b00ea'
        ),
        CURLOPT_SSL_VERIFYPEER => false
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    echo $response;
}


// Function to send frequency lead
function sendFrequencyLead($data)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://roundrobin.datapoco.ai/api/lead_frequency/add_lead',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode('Client Management Portal:123456')
        ),
        CURLOPT_SSL_VERIFYPEER => false
    ));

    $response = curl_exec($curl);
    curl_close($curl);
}
function checkName($name) {
    // List of names to check against
    $names = ["Jome", "Joleen", "Janice", "John", "Abdul", "musadiq", "arpit"];
    
    // Check if the name exists in the list
    if (in_array($name, $names, true)) {
        return true;
    } else {
        return false;
    }
}
?>