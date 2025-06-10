<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$dsn = 'mysql:host=localhost;dbname=dbmudbaf00gr1e';
$username = 'uhjdojqel1xrp';
$password = '3yfrehsrblkl';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file']['tmp_name'];
    $spreadsheet = IOFactory::load($file);
    $worksheet = $spreadsheet->getActiveSheet();
    $highestRow = $worksheet->getHighestRow();

    $emailColumn = 'B';
    $phoneNumberColumn = 'C';
    $emailStatusColumn = 'D';
    $phoneNumberStatusColumn = 'E';

    $worksheet->setCellValue($emailStatusColumn . '1', 'Email Status');
    $worksheet->setCellValue($phoneNumberStatusColumn . '1', 'Phone Number Status');

    for ($row = 2; $row <= $highestRow; ++$row) {
        $email = $worksheet->getCell($emailColumn . $row)->getValue();
        $phoneNumber = $worksheet->getCell($phoneNumberColumn . $row)->getValue();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM leads WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $emailCount = $stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM leads WHERE phone_number = :phone_number");
        $stmt->execute(['phone_number' => $phoneNumber]);
        $phoneNumberCount = $stmt->fetchColumn();

        $emailStatus = $emailCount > 0 ? 'Matched' : 'Not Matched';
        $phoneNumberStatus = $phoneNumberCount > 0 ? 'Matched' : 'Not Matched';

        $worksheet->setCellValue($emailStatusColumn . $row, $emailStatus);
        $worksheet->setCellValue($phoneNumberStatusColumn . $row, $phoneNumberStatus);
    }

    $writer = new Xlsx($spreadsheet);
    $outputFileName = 'processed_file.xlsx';
    $writer->save($outputFileName);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . basename($outputFileName) . '"');
    header('Content-Length: ' . filesize($outputFileName));
    readfile($outputFileName);
    unlink($outputFileName);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Excel File</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-6 mx-auto mt-5">
                <div class="card shadow">
                    <div class="card-header">
                        Upload Excel File
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data">
                            <label for="excel_file">Select Excel file to upload:</label> <br>
                            <input type="file" name="excel_file" class="form-control mt-3 mb-3" accept=".xlsx,.xls" id="excel_file" required>
                            <button type="submit" class="btn btn-primary">Upload Excel File</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
