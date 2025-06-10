<?php
session_start();

$valid_username = "NTZkZTc3YTgxNGEwYmM5MDY4OTFhMTA2MTU4YzQxNDM=";
$valid_password = "YjY1MDJmYWZkMmJhMDRjNThmMTM2ODgwYzY3NzRlODk=";

$username = md5($_POST['username']);
$user_username = base64_encode($username);
$user_password = md5($_POST['password']);
$user_password = base64_encode($user_password);

if ($user_username !== $valid_username || $user_password !== $valid_password) {
    echo "Invalid username or password.";
    exit;
}

$filename = $_FILES['file']['tmp_name'];
$delimiter = ',';

if (!file_exists($filename) || !is_readable($filename)) {
    echo "File not found or not readable.";
    exit;
}

$servername = "localhost";
$db_username = "uhjdojqel1xrp";
$db_password = "3yfrehsrblkl";
$dbname = "dbmudbaf00gr1e";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("INSERT INTO `leads` (source_url, name, email, phone_number, status) 
                            VALUES (:source_url, :name, :email, :phone_number, :status)");
    $status = 'DNC Registry';
    if (($handle = fopen($filename, 'r')) !== false) {
        $header = fgetcsv($handle, 1000, $delimiter);

        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
            $data = array_combine($header, $row);
            
            $stmt->bindParam(':source_url', $data['SOURCE']);
            $stmt->bindParam(':name', $data['NAME']);
            $stmt->bindParam(':email', $data['EMAIL']);
            $stmt->bindParam(':phone_number', $data['CONTACT']);
            $stmt->bindParam(':status', $status);

            $stmt->execute();
        }
        fclose($handle);
    }

    $_SESSION['success_message'] = true;

    header("Location: index.php");
    exit;
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
