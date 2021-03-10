<?php

function checkSecretKey() {

    $secretKey = 'nX?3Wc9Kfr=@AjFe';

    if (!isset($_GET['secretKey']) || $_GET['secretKey'] !== $secretKey) {
        http_response_code(400);
        echo json_encode(["error" => "Secret key invalid"]);
        exit();
    }
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
        return $conn;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        return null;
    }
}

function quote($string)
{
    return '"' . str_replace('"', '', $string) . '"';
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

function updateVoyage($data, $db)
{
    $sql = "UPDATE voyage SET offertPar = :offertPar, commentaire = :commentaire WHERE id = :id";
    $query = $db->prepare($sql);

    try {
        $query->execute(array(
            ':offertPar' => quote($data['offertPar']),
            ':commentaire' => quote($data['commentaire']),
            ':id' => $data['id']
        ));
        return true;
    } catch (Exception $e) {
        return false;
    }
}
