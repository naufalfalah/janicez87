<?php
// Set the response header to return JSON and a 200 status code
http_response_code(200);
header('Content-Type: application/json');

// Return the JSON response
echo json_encode(["message" => "Form Submitted Successfully"]);
?>
