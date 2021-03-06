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

// If the form was posted, deal with it
if($data['id']) {
    if (!updateVoyage($data, $db)) {
        http_response_code(400);
        echo json_encode(["error" => "Couldn't update voyage"]);
        exit();
    } else {
        echo json_encode(["ok" => "We did it!"]);
        exit();
    }
}
// Otherwise, send the list of gifts
else {
    $sql = "SELECT * FROM voyage;";
    $res = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($res);
    exit();
}


