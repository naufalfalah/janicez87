<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $domain_url = $_POST['domain_url'];
    $mobile_number = $_POST['mobile_number'];
    $status = $_POST['status'] ?? "";
    
    $host = 'localhost';
    $db = 'dbmudbaf00gr1e'; 
    $user = 'uhjdojqel1xrp'; 
    $pass = '3yfrehsrblkl'; 
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $parsedUrl = parse_url($domain_url);
        $domain_url = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . '/';

        $pdo = new PDO($dsn, $user, $pass, $options);

        $sql = "UPDATE leads 
                SET status = :status 
                WHERE source_url LIKE :domain_url 
                AND phone_number = :mobile_number";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':status' => $status,
            ':domain_url' => $domain_url . '%',
            ':mobile_number' => $mobile_number
        ]);

        if ($stmt->rowCount() > 0) {
            // echo "Status updated successfully.";
        } else {
            // echo "No matching records found.";
        }
    } catch (PDOException $e) {
        // echo "Error: " . $e->getMessage();
    }
}
?>
