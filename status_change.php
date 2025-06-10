<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=dbmudbaf00gr1e', 'uhjdojqel1xrp', '3yfrehsrblkl');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: ". $e->getMessage());
}

$postData = file_get_contents('php://input');

$data = json_decode($postData, true); 

foreach ($data as $lead) {
    $email = $lead['email'] ?? '';
    $phone = $lead['phone'] ?? '';
    $source_url = $lead['source_url'] ?? '';
    $status = $lead['status'] ?? '';



    if (empty($email) || empty($phone) || empty($source_url) || empty($status)) {
        echo json_encode(["error" => "Incomplete lead data."]);
        exit;
    }

    $allowedStatuses = ['junk', 'clear', 'unmarked', 'dnc'];
    if (!in_array($status, $allowedStatuses)) {
        echo json_encode(["error" => "Invalid status provided for a lead."]);
        exit;
    }

    $stmt = $db->prepare("UPDATE leads SET status = :newStatus WHERE email = :email AND source_url = :source_url AND phone = :phone");
    $stmt->bindParam(':newStatus', $status);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':source_url', $source_url);
    $stmt->bindParam(':phone', $phone);
    if (!$stmt->execute()) {
        echo json_encode(["error" => "Failed to update lead status."]);
        exit;
    }
}

echo json_encode(["success" => "All lead statuses updated successfully."]);

$db = null;
?>
