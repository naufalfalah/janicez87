<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otp = $_POST['otp'];
    $userNumber = $_POST['userNumber'];

    $mysqli = new mysqli('localhost', 'uhjdojqel1xrp', '3yfrehsrblkl', 'dbmudbaf00gr1e');
    if ($mysqli->connect_error) {
        echo json_encode(array('error' => 'Database connection failed: ' . $mysqli->connect_error));
        exit();
    }

    $stmt = $mysqli->prepare("SELECT * FROM otp_table WHERE otp = ? AND user_number = ? AND is_expire = 0");
    $stmt->bind_param("is", $otp, $userNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // OTP is valid, mark it as expired
        $update_stmt = $mysqli->prepare("UPDATE otp_table SET is_expire = 1 WHERE otp = ? AND user_number = ?");
        $update_stmt->bind_param("is", $otp, $userNumber);
        $update_stmt->execute();

        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('error' => 'Invalid OTP or OTP has expired'));
    }

    $stmt->close();
    $mysqli->close();
} else {
    echo json_encode(array('error' => 'Invalid request method'));
}
?>
