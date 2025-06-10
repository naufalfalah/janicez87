<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userNumber = $_POST['userNumber'];

    $apiUrl = 'https://api.p.2chat.io/open/whatsapp/check-number/+6589469107/' . urlencode($userNumber);
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'X-User-API-Key: UAK32c243e8-e2ca-417a-ba7a-b3e1ee7b3d4c'
        ),
    ));

    $response = curl_exec($curl);

    if(curl_errno($curl)) {
        echo json_encode(array('error' => curl_error($curl)));
    } else {
        echo $response;
    }

    curl_close($curl);
} else {
    echo json_encode(array('error' => 'Invalid request method'));
}
?>
