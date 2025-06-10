<?php

$curl = curl_init();

// Encode the URL properly
$params = array(
    'name' => 'One Marina Gardens'
);
$url = 'https://portal.datapoco.ai/api/images?' . http_build_query($params);

curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30, // Set a reasonable timeout
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_SSL_VERIFYPEER => true, // Enable SSL verification
    CURLOPT_HTTPHEADER => array(
        'Accept: application/json', // Specify the expected response format
        'Authorization: Bearer ' . "dhf7asdf9ad0f9asdfj908dj9", // Use environment variable for the token
    ),
));

$response = curl_exec($curl);

// Check for cURL errors
if (curl_errno($curl)) {
    echo 'cURL Error Number: ' . curl_errno($curl) . "\n";
    echo 'cURL Error Message: ' . curl_error($curl) . "\n";
    echo 'HTTP Status Code: ' . curl_getinfo($curl, CURLINFO_HTTP_CODE) . "\n";
    echo 'Response: ' . $response;
} else {
    echo $response;
}

curl_close($curl);
?>