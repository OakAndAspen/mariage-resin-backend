<?php

ob_clean();
header_remove();

$secretKey = 'nX?3Wc9Kfr=@AjFe';
$data = json_decode(file_get_contents('php://input'), true);

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header('Content-Type: application/json');
http_response_code(200);

// Get the secret key
if (!isset($data['secretKey']) || $data['secretKey'] !== $secretKey) {
    http_response_code(400);
    echo json_encode(["error" => "Secret key invalid"]);
    exit();
}

// Try to connect to the database
$db = connectToDatabase();
if (!$db) {
    http_response_code(400);
    echo json_encode(["error" => "Couldn't connect to database"]);
    exit();
}

// Try to create a new inscription
if (!insertNewInscription($data, $db)) {
    http_response_code(400);
    echo json_encode(["error" => "Couldn't create new inscription"]);
    exit();
}

function connectToDatabase()
{
    $servername = "localhost";
    $dbname = "mariage-resin";
    $username = "root";
    $password = "";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connected successfully";
        return $conn;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        return null;
    }
}

function insertNewInscription($data, $db)
{
    $nom = quote($data['nom']);
    $nbCeremonie = quote($data['participants']['ceremonie']);
    $nbApero = quote($data['participants']['apero']);
    $nbSouper = quote($data['participants']['souper']);
    $nbDodo = quote($data['participants']['dodo']);
    $covoitDe = quote($data['covoit']['de']);
    $covoitOffre = quote($data['covoit']['offre']);
    $covoitCherche = quote($data['covoit']['cherche']);
    $aideInstall = quote($data['aide']['install']);
    $aideApero = quote($data['aide']['apero']);
    $aideColonie = quote($data['aide']['colonie']);
    $commentaire = quote($data['commentaire']);


    $sql = "INSERT INTO inscription(nom, nbCeremonie, nbApero, nbSouper, nbDodo, covoitDe, covoitOffre, covoitCherche, aideInstall, aideApero, aideColonie, commentaire) " .
        "VALUES ($nom, $nbCeremonie, $nbApero, $nbSouper, $nbDodo, $covoitDe, $covoitOffre, $covoitCherche, $aideInstall, $aideApero, $aideColonie, $commentaire);";

    try {
        $db->exec($sql);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function quote($string)
{
    return '"' . str_replace('"', '', $string) . '"';
}
