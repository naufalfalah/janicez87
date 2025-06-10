<?php
$servername = "localhost";

$username = "uhjdojqel1xrp";

$password = "3yfrehsrblkl";

$dbname = "dbmudbaf00gr1e";

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

if(isset($_GET['datefilter']) || isset($_GET['text_input']) || isset($_GET['emails']) || isset($_GET['phone_number'])){  
    
    $whereConditions = [];
    $params = [];
    
    if(isset($_GET['emails']) && !empty($_GET['emails'])){
        $ignoredEmails = array_map('trim', explode(',', $_GET['emails']));
    }

    if(isset($_GET['phone_number']) && !empty($_GET['phone_number'])){
        $ignoredPhone_number = array_map('trim', explode(',', $_GET['phone_number']));
    }

    if(isset($_GET['datefilter']) && !empty($_GET['datefilter'])) {
        // Date range provided
        $dateRange = explode('-', $_GET['datefilter']);
        $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
        $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
        
        $whereClause = 'created_at BETWEEN ? AND ?';
        $params[] = $startDate;
        $params[] = $endDate;

        // Add ignored emails condition
        if(isset($ignoredEmails) && !empty($ignoredEmails)) {
            $whereClause .= " AND email NOT IN ('" . implode("','", $ignoredEmails) . "')";
        }

        if(isset($ignoredPhone_number) && !empty($ignoredPhone_number)) {
            $whereClause .= " AND phone_number NOT IN ('" . implode("','", $ignoredPhone_number) . "')";
        }

        // Add date range condition
        $whereConditions[] = $whereClause;
        
    }

    if(isset($_GET['text_input']) && !empty(trim($_GET['text_input']))){
        // Text input provided
        $searchTerm = $_GET['text_input'];
        $escapedSearchTerm = mysqli_real_escape_string($conn, $searchTerm);

        // Add search condition
        $whereClause = "(name LIKE ? OR email LIKE ? OR phone_number LIKE ?)";
        $params[] = "%$escapedSearchTerm%";
        $params[] = "%$escapedSearchTerm%";
        $params[] = "%$escapedSearchTerm%";

        // Add ignored emails condition
        if(isset($ignoredEmails) && !empty($ignoredEmails)) {
            $whereClause .= " AND email NOT IN ('" . implode("','", $ignoredEmails) . "')";
        }

        if(isset($ignoredPhone_number) && !empty($ignoredPhone_number)) {
            $whereClause .= " AND phone_number NOT IN ('" . implode("','", $ignoredPhone_number) . "')";
        }

        $whereConditions[] = $whereClause;
    }

    // Construct the SQL query
    $sql = "SELECT domain, COUNT(*) AS total_leads FROM ( SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(source_url, '/', 3), '/', -1) AS domain, source_url, COUNT(*) AS total_leads 
            FROM leads";

    if (!empty($whereConditions)) {
        $sql .= " WHERE " . implode(" AND ", $whereConditions);
    }

    if(isset($_GET['source_url']) && $_GET['source_url'] != ''){
        $sql .= " AND source_url LIKE '%".$_GET['source_url']."%' ";
    }

    $sql .= " AND status = 'clear'  AND leads.email NOT LIKE '%jome%' AND leads.email NOT LIKE '%test%' GROUP BY email,phone_number ) AS TEMP GROUP BY domain;";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        // Bind parameters
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
        
        // Execute query
        $stmt->execute();
        
        // Get result
        $result = $stmt->get_result();
    } else {
        
        echo "Error in query preparation: " . $conn->error;
        
    }
}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <title>Lead DateFilter</title>
</head>
<body>

    <div class="container mt-3">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
            <form action="" method="GET">
                <h4>Search Leads</h4>
                <div class="row">
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="mb-3">
                            <label for="" class="form-label">Date Search Filter</label>
                            <input type="text" name="datefilter" id="datefilter" value="<?php echo isset($_GET['datefilter']) ? htmlspecialchars($_GET['datefilter']) : ''; ?>" class="form-control" required/>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="mb-3">
                            <label for="" class="form-label">Input Search Filter</label>
                            <input type="text" name="text_input" value="<?php echo isset($_GET['text_input']) ? htmlspecialchars($_GET['text_input']) : ''; ?>" placeholder="Name, Email, Phone Number" class="form-control" id="">
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="mb-3">
                            <label for="" class="form-label">Input Search Source URL</label>
                            <input type="text" name="source_url" value="<?php echo isset($_GET['source_url']) ? htmlspecialchars($_GET['source_url']) : ''; ?>" placeholder="Enter source url" class="form-control" id="">
                        </div>
                    </div>
                </div>

                <h4>Ignore Fields</h4>
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="mb-3">
                            <label for="" class="form-label">Ignore Email</label>
                            <input type="text" name="emails" class="form-control" value="<?php echo isset($_GET['emails']) ? htmlspecialchars($_GET['emails']) : ''; ?>" placeholder="test@test.com, test@test.com, test@test.com"/>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="mb-3">
                            <label for="" class="form-label">Ignore Phone</label>
                            <input type="text" name="phone_number" value="<?php echo isset($_GET['phone_number']) ? htmlspecialchars($_GET['phone_number']) : ''; ?>" placeholder="123456, 123456, 123456" class="form-control" id="">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success float-end">Search</button>
                </form>
            </div>
        </div>
    </div>
<?php if(isset( $result) && $result->num_rows > 0){ ?>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <table class="table" id="">
                    <thead>
                        <th>#</th>
                        <th>Source URL</th>
                        <th>Lead Count</th>
                        <th>Ad Spent</th>
                        <th>CPL</th>
                    </thead>
                    <tbody>
                      <?php
                        if ($result->num_rows > 0) {
                            $key = 1;
                            while ($row = $result->fetch_assoc()) {
                      ?>

                        <tr>
                        <td><?php echo $key ?></td>
                        <td><?php echo $row["domain"]?></td>
                        <td><?php echo $row["total_leads"] ?></td>
                        <td><input type="text" class="ad_spent"></td>
                        <td></td>
                        </tr>

                        <?php
                            $key++;
                            } }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>
</body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <?php
    if(!isset($_GET['datefilter']) && empty($_GET['datefilter'])) {
    ?>
    <script type="text/javascript">
$(function() {
    var current = moment(); // Current date

    function cb(start, end) {
        $('#datefilter').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
    }

    $('#datefilter').daterangepicker({
        startDate: current,
        endDate: current, // Both start and end set to current date
        locale: {
            format: 'MM/DD/YYYY' // Format set to MM/DD/YYYY
        }
    }, cb);

    // Set initial date range
    cb(current, current);
});
</script>
<?php } ?>
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
        $(document).on('input', '.ad_spent', function (){
            var tr = $(this).closest('tr');
            var adSpent = parseFloat($(this).val());
            var leadCount = parseInt(tr.find("td:eq(2)").html());
            if (leadCount !== 0) { 
                var result = adSpent / leadCount;
                tr.find("td:eq(4)").html(result.toFixed(2)); 
            } else {
                tr.find("td:eq(4)").html("Lead count is zero");
            }
        });
    </script>
</html>