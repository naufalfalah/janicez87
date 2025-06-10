<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commonData = [];
    $additionalData = [];
    $name = '';
    $mobileNumber = '';
    $email = '';

    foreach ($_POST as $key => $val) {
        $val = htmlspecialchars(trim($val));
        if (strpos(strtolower($key), 'name') !== false) {
            $name = $val;
        } else if (strpos(strtolower($key), 'email') !== false) {
            $email = $val;
        } else if (strpos(strtolower($key), 'contact') !== false) {
            $mobileNumber = $val;
        } else {
            $additionalData[$key] = $val;
        }
    }

    $commonData = [
        "name" => $name,
        "mobile_number" => $mobileNumber,
        "email" => $email,
        "additional_data" => $additionalData
    ];

    $checkJunk = checkJunk(json_encode($commonData));
    if (isset($checkJunk['Terms']) && !empty($checkJunk['Terms']) && count($checkJunk['Terms']) > 0) {
        http_response_code(400);
        echo 'Junk content detected';
    } else {
        if (sendFrequencyLead($commonData)) {
            http_response_code(200);
            echo 'Lead sent successfully';
        } else {
            http_response_code(500);
            echo 'Failed to send lead';
        }
    }
} else {
    http_response_code(405);
    echo 'Invalid request method';
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

function sendFrequencyLead($data)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://batch3.datapoco.ai/api/lead_frequency/add_lead',
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
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    return $httpCode === 200;
}
?>
