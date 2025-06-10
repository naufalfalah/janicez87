<?php

require_once __DIR__ . '/config.php';

$accessToken = env('ACCESS_TOKEN', 'default_token');

if (!isset($_GET['access_token']) || $_GET['access_token'] !== $accessToken) {
    die("Access denied. Invalid access token.");
}

$servername = env('DB_HOST', 'localhost');
$username   = env('DB_USER', 'root');
$password   = env('DB_PASSWORD', 'root');
$dbname     = env('DB_NAME', 'database_name');

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$whereConditions = [];
$params = [];

if (isset($_GET['status']) && !empty($_GET['status'])) {
    $status = $_GET['status'];
    $whereConditions[] = "status = ?";
    $params[] = $status;
}

if (isset($_GET['datefilter']) && !empty($_GET['datefilter'])) {
    $dateRange = explode('-', $_GET['datefilter']);
    $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
    $endDate   = date('Y-m-d 23:59:59', strtotime($dateRange[1]));

    $whereConditions[] = 'created_at BETWEEN ? AND ?';
    $params[] = $startDate;
    $params[] = $endDate;
}

// Construct the SQL query
$sql = "SELECT * FROM leads";

if (!empty($whereConditions)) {
    $sql .= " WHERE " . implode(" AND ", $whereConditions);
}

// Prepare and bind parameters
$stmt = $conn->prepare($sql);
if ($stmt) {
    $types = str_repeat('s', count($params));
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    echo "Error in query preparation: " . $conn->error;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <title>Leads Management</title>
</head>
<body>
    <div class="container mt-3">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <form action="" method="GET">
                    <h4>Filter Leads</h4>
                    <div class="row">
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="mb-3">
                                <label for="datefilter" class="form-label">Date Range</label>
                                <input type="text" name="datefilter" id="datefilter" value="<?php echo isset($_GET['datefilter']) ? htmlspecialchars($_GET['datefilter']) : ''; ?>" class="form-control" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">Select Status</option>
                                    <option value="clear" <?php echo (isset($_GET['status']) && $_GET['status'] === 'clear') ? 'selected' : ''; ?>>Clear</option>
                                    <option value="pending" <?php echo (isset($_GET['status']) && $_GET['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="blocked" <?php echo (isset($_GET['status']) && $_GET['status'] === 'blocked') ? 'selected' : ''; ?>>Blocked</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success float-end">Filter</button>
                </form>
            </div>
        </div>
    </div>

<?php if (isset($result) && $result->num_rows > 0) { ?>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <table class="table">
                    <thead>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </thead>
                    <tbody>
                        <?php
                        $key = 1;
                        while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $key ?></td>
                            <td><?php echo htmlspecialchars($row["name"]) ?></td>
                            <td><?php echo htmlspecialchars($row["email"]) ?></td>
                            <td><?php echo htmlspecialchars($row["phone_number"]) ?></td>
                            <td><?php echo htmlspecialchars($row["status"]) ?></td>
                            <td><?php echo htmlspecialchars($row["created_at"]) ?></td>
                        </tr>
                        <?php
                            $key++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
$(function() {
    $('input[name="datefilter"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
});
</script>
</html>