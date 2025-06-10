<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

// Read the incoming JSON data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$hardcoded_url = "https://www.99.co/singapore/condos-apartments/lentoria";
// if (isset($data['last_user_message']) && strpos($data['last_user_message'], $hardcoded_url) !== false) {
    if (!empty($data['last_user_message'])) {
    http_response_code(200);
    echo json_encode([
        'status' => 200,
        'success' => true
    ]);
}else{
    http_response_code(400);
    echo json_encode([
        'status' => 200,
        'success' => 11
    ]);
}
die();
// http_response_code(200);
//     echo json_encode([
//         'status' => 200,
//         'success' => $data['from_number']
//     ]);


if (!empty($data['last_user_message']) && !empty($data['from_number'])) {
    http_response_code(200);
    echo json_encode([
        'status' => 200,
        'success' => true
    ]);
    
}
  die();