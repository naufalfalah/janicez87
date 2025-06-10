<?php

// Database connection
try {
    $db = new PDO('mysql:host=localhost;dbname=dbmudbaf00gr1e', 'uhjdojqel1xrp', '3yfrehsrblkl');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["error" => "Database connection failed: " . $e->getMessage()]));
}

// Set CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Authentication Token
$authToken = "LfCEcWskT46iVTMCyLi6";
$headers = getallheaders();
if (!isset($headers['Authorization']) || trim($headers['Authorization']) !== $authToken) {
    http_response_code(401);
    die(json_encode(["error" => "Unauthorized access. Invalid token."]));
}

// Pagination parameters
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 50; // Default: 50 rows per request
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;     // Default page = 1
$offset = ($page - 1) * $limit;  

// Sorting (AG Grid sends sortModel)
$sortModel = isset($_GET['sortModel']) ? json_decode($_GET['sortModel'], true) : [];
$sortQuery = "ORDER BY created_at DESC"; // Default sorting

if (!empty($sortModel)) {
    $sortField = $sortModel[0]['colId']; // Column name
    $sortOrder = strtoupper($sortModel[0]['sort']); // 'ASC' or 'DESC'
    $allowedColumns = ['name', 'email', 'phone_number', 'status', 'source_url', 'created_at'];

    if (in_array($sortField, $allowedColumns)) {
        $sortQuery = "ORDER BY $sortField $sortOrder";
    }
}


// Filtering (AG Grid sends filterModel)
$filterModel = isset($_GET['filterModel']) ? json_decode($_GET['filterModel'], true) : [];
$filterQuery = "";
$filterParams = [];
$isFiltered = !empty($filterModel); // ✅ Track if filtering is applied

if (!empty($filterModel)) {
    $filters = [];
    
    foreach ($filterModel as $field => $filter) {
        if (!in_array($field, ['name', 'email', 'phone_number', 'status', 'source_url', 'created_at'])) {
            continue;
        }

       $filterType = $filter['type'];
$filterValue = $filter['filter'];
$dateFrom = $filter['dateFrom'] ?? null;
$dateTo = $filter['dateTo'] ?? null;

switch ($filterType) {
    case "equals":
        $filters[] = "$field = :$field";
        if ($dateFrom) {
            $filterParams[":$field"] = $dateFrom;
        } elseif ($dateTo) {
            $filterParams[":$field"] = $dateTo;
        } else {
            $filterParams[":$field"] = $filterValue;
        }
        break;
    case "notEqual":
        $filters[] = "$field != :$field";
        if ($dateFrom) {
            $filterParams[":$field"] = $dateFrom;
        } elseif ($dateTo) {
            $filterParams[":$field"] = $dateTo;
        } else {
            $filterParams[":$field"] = $filterValue;
        }
        break;
    case "contains":
        $filters[] = "$field LIKE :$field";
        $filterParams[":$field"] = "%$filterValue%";
        break;
    case "notContains":
        $filters[] = "$field NOT LIKE :$field";
        $filterParams[":$field"] = "%$filterValue%";
        break;
    case "startsWith":
        $filters[] = "$field LIKE :$field";
        $filterParams[":$field"] = "$filterValue%";
        break;
    case "endsWith":
        $filters[] = "$field LIKE :$field";
        $filterParams[":$field"] = "%$filterValue";
        break;
    case "blank":
        $filters[] = "($field IS NULL OR $field = '')";
        break;
    case "notBlank":
        $filters[] = "($field IS NOT NULL AND $field != '')";
        break;
    case "greaterThan":
        $filters[] = "$field > :$field";
        if ($dateFrom) {
            $filterParams[":$field"] = $dateFrom;
        } elseif ($dateTo) {
            $filterParams[":$field"] = $dateTo;
        } else {
            $filterParams[":$field"] = $filterValue;
        }
        break;
    case "lessThan":
        $filters[] = "$field < :$field";
        if ($dateFrom) {
            $filterParams[":$field"] = $dateFrom;
        } elseif ($dateTo) {
            $filterParams[":$field"] = $dateTo;
        } else {
            $filterParams[":$field"] = $filterValue;
        }
        break;
    case "inRange":
        if (!empty($dateFrom) && !empty($dateTo)) {
            $filters[] = "$field BETWEEN :${field}_from AND :${field}_to";
            $filterParams[":${field}_from"] = $dateFrom;
            $filterParams[":${field}_to"] = $dateTo;
        } elseif (!empty($dateFrom)) {
            $filters[] = "$field >= :${field}_from";
            $filterParams[":${field}_from"] = $dateFrom;
        } elseif (!empty($dateTo)) {
            $filters[] = "$field <= :${field}_to";
            $filterParams[":${field}_to"] = $dateTo;
        }
        break;
}

    }

    if (!empty($filters)) {
        $filterQuery = "WHERE " . implode(" AND ", $filters);
    }
}


try {
     // ✅ Get the correct total count based on filtering
    if ($isFiltered) {
        $countStmt = $db->prepare("SELECT COUNT(*) as total FROM leads $filterQuery");
        foreach ($filterParams as $key => $value) {
            $countStmt->bindValue($key, $value, PDO::PARAM_STR);
        }
    } else {
        $countStmt = $db->query("SELECT COUNT(*) as total FROM leads");
    }

    $countStmt->execute();
    $totalLeads = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Fetch paginated leads
    $stmt = $db->prepare("SELECT * FROM leads $filterQuery $sortQuery LIMIT :limit OFFSET :offset");

    foreach ($filterParams as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }

    $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
    $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
    $stmt->execute();
    $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    echo json_encode([
        "success" => true,
        "totalLeads" => $totalLeads,
        "page" => $page,
        "limit" => $limit,
        "totalPages" => ceil($totalLeads / $limit),
        "data" => $leads
    ]);

} catch (PDOException $e) {
    echo json_encode(["error" => "Query failed: " . $e->getMessage()]);
}
