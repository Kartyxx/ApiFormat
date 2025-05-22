<?php
// Activer l'affichage des erreurs (DEBUG)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Se connecter à la base de données
include("db_connect.php");

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        getFormation();
        break;  // <=== Il manquait ce break ici

    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

// ✅ Fonction pour récupérer toutes les formations
function getFormation()
{
    global $conn;
    $query = "SELECT * FROM formations";
    $response = array();
    $result = mysqli_query($conn, $query);

    if (!$result) {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(["error" => "Erreur lors de la requête SQL"]);
        exit;
    }

    while ($row = mysqli_fetch_assoc($result)) {  // mysqli_fetch_assoc est plus courant ici
        $response[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
}
?>
