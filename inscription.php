<?php

require_once "./functions.php";

checkSecretKey();

ob_clean();
header_remove();

$data = json_decode(file_get_contents('php://input'), true);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header('Content-Type: application/json');
http_response_code(200);

// Try to connect to the database
$db = connectToDatabase();

// Try to create a new inscription
if (!insertNewInscription($data, $db)) {
    http_response_code(400);
    echo json_encode(["error" => "Couldn't create new inscription"]);
    exit();
}
