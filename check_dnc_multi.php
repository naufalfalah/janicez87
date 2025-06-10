<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=dbmudbaf00gr1e', 'uhjdojqel1xrp', '3yfrehsrblkl');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: ". $e->getMessage());
}

$postData = file_get_contents('php://input');
$data = json_decode($postData, true);

if (!is_array($data) || empty($data)) {
    echo json_encode(["error" => "Invalid input data. Please provide an array of phone numbers."]);
    exit;
}

$placeholders = rtrim(str_repeat('?,', count($data)), ',');
$stmt = $db->prepare("SELECT phone_number, status FROM leads WHERE phone_number IN ($placeholders)");
$stmt->execute($data);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$response = [];
$phoneStatusMap = [];

foreach ($results as $result) {
    $phoneStatusMap[$result['phone_number']] = $result['status'];
}

foreach ($data as $phone) {
    if (empty($phone)) {
        $response[] = ["phone" => $phone, "status" => "error", "message" => "Phone number is empty."];
        continue;
    }

    if (isset($phoneStatusMap[$phone])) {
        $response[] = ["phone" => $phone, "status" => $phoneStatusMap[$phone]];
    } else {
        $response[] = ["phone" => $phone, "status" => "not found"];
    }
}

echo json_encode($response);

$db = null;
?>
