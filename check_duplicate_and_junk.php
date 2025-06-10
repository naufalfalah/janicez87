<?php
// Connect to the MySQL database using PDO
try {
    $db = new PDO('mysql:host=localhost;dbname=dbmudbaf00gr1e', 'uhjdojqel1xrp', '3yfrehsrblkl');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
} 

// Allow CORS and specify allowed methods and headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Get the JSON input from the client
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

// Ensure that at least one identifier (email or phone) is provided.
if (isset($input['email']) || isset($input['ph_number'])) {
    $email = $input['email'] ?? '';
    $phone = $input['ph_number'] ?? '';
    $source_url = $input['source_url'] ?? '';
    $ip = $input['ip'] ?? ''; // You may want to use this for additional logic like rate-limiting

    // Sanitize the email if provided
    if (!empty($email)) {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    }


    /*
     * First, check for duplicates in leads with status "CLEAR"
     * Query:
     *    SELECT COUNT(*) FROM leads
     *    WHERE status = 'CLEAR' AND source_url = :source_url AND (email = :email OR phone_number = :phone)
     * If email is not provided, only the phone number is used.
     */
    $duplicateCount = check_duplicate($db, $email, $phone, $source_url);
    if ($duplicateCount > 0) {
        echo json_encode([
            'isValid' => false,
            'reason'  => 'duplicate',
            'msg'     => 'Duplicate lead detected.'
        ]);
        exit;
    }

    /*
     * Next, check if the lead exists with status "Junk"
     * Query:
     *    SELECT COUNT(*) FROM leads
     *    WHERE status = 'Junk' AND source_url = :source_url AND (email = :email OR phone_number = :phone)
     * If email is not provided, the query will only check phone_number.
     */
    $junkCount = check_junk($db, $email, $phone, $source_url);
    if ($junkCount > 0) {
        echo json_encode([
            'isValid' => false,
            'reason'  => 'junk',
            'msg'     => 'The provided lead is marked as junk.'
        ]);
        exit;
    }

    

    // If no junk or duplicate record is found, the lead is considered valid.
    echo json_encode([
        'isValid' => true
    ]);
    exit;
}

$db = null;
die();

/**
 * Check for junk leads.
 * Looks for records with status 'Junk' matching the given source_url and either email or phone.
 *
 * @param PDO    $db         The database connection.
 * @param string $email      The email address (may be empty).
 * @param string $phone      The phone number.
 * @param string $source_url The source URL.
 *
 * @return int   The count of junk leads found.
 */
function check_junk($db, $email, $phone, $source_url) {
    if (!empty($email)) {
        $query = "SELECT COUNT(*) FROM leads 
                  WHERE status = 'Junk' 
                    AND source_url = :source_url 
                    AND (email = :email OR phone_number = :phone)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':source_url', $source_url);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
    } else {
        // If email is not provided, check only the phone number.
        $query = "SELECT COUNT(*) FROM leads 
                  WHERE status = 'Junk' 
                    AND source_url = :source_url 
                    AND phone_number = :phone";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':source_url', $source_url);
        $stmt->bindParam(':phone', $phone);
    }
    $stmt->execute();
    return $stmt->fetchColumn();
}

/**
 * Check for duplicate leads with status 'CLEAR'.
 * Looks for records matching the given source_url and either email or phone.
 * If the email is not provided, only the phone number is checked.
 *
 * @param PDO    $db         The database connection.
 * @param string $email      The email address (may be empty).
 * @param string $phone      The phone number.
 * @param string $source_url The source URL.
 *
 * @return int   The count of duplicate leads found.
 */
function check_duplicate($db, $email, $phone, $source_url) {
    if (!empty($email)) {
        $query = "SELECT COUNT(*) FROM leads 
                  WHERE status = 'CLEAR' 
                    AND source_url = :source_url 
                    AND (email = :email OR phone_number = :phone)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':source_url', $source_url);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
    } else {
        // If email is not provided, check only the phone number.
        $query = "SELECT COUNT(*) FROM leads 
                  WHERE status = 'CLEAR' 
                    AND source_url = :source_url 
                    AND phone_number = :phone";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':source_url', $source_url);
        $stmt->bindParam(':phone', $phone);
    }
    $stmt->execute();
    return $stmt->fetchColumn();
}
?>
