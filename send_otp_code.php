<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userNumber = $_POST['userNumber'];
    $otp = rand(1000, 9999);

    $mysqli = new mysqli('localhost', 'uhjdojqel1xrp', '3yfrehsrblkl', 'dbmudbaf00gr1e');
    if ($mysqli->connect_error) {
        echo json_encode(array('error' => 'Database connection failed: ' . $mysqli->connect_error));
        exit();
    }

    $stmt = $mysqli->prepare("INSERT INTO otp_table (user_number, otp, is_expire) VALUES (?, ?, 0)");
    $stmt->bind_param("si", $userNumber, $otp);
    if ($stmt->execute()) {
        $data = json_encode(array(
            'to_number' => $userNumber,
            'from_number' => '+6589469107',
            'text' => 'Your OTP code is: ' . $otp
        ));

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.p.2chat.io/open/whatsapp/send-message',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'X-User-API-Key: UAK32c243e8-e2ca-417a-ba7a-b3e1ee7b3d4c'
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            echo json_encode(array('error' => curl_error($curl)));
        } else {
            echo json_encode(array('success' => true, 'response' => $response));
        }
        curl_close($curl);
    } else {
        echo json_encode(array('error' => 'Failed to save OTP to database'));
    }

    $stmt->close();
    $mysqli->close();
} else {
    echo json_encode(array('error' => 'Invalid request method'));
}
?>
