<?php
// Activer l'affichage des erreurs (DEBUG)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Se connecter à la base de données
include("db_connect.php");

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        if (!empty($_GET["id"])) {
            // Récupérer un utilisateur par ID
            $id = intval($_GET["id"]);
            getUserById($id);
        } elseif (!empty($_GET["email"])) {
            // Récupérer un utilisateur par email
            getUser($_GET["email"]); // ✅ Correction ici
        } else {
            // Récupérer tous les utilisateurs
            getUsers();
        }
        break;

    case 'POST':
        // Ajouter un utilisateur (exemple)
        AddUser();
        break;

    case 'PUT':
        // Modifier un utilisateur
        $id = intval($_GET["id"]);
        updateUser($id);
        break;

    case 'DELETE':
        // Supprimer un utilisateur
        $id = intval($_GET["id"]);
        deleteUser($id);
        break;

    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

// ✅ Fonction pour récupérer tous les utilisateurs
function getUsers()
{
    global $conn;
    $query = "SELECT * FROM utilisateur";
    $response = array();
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $response[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
}

// ✅ Fonction pour récupérer un utilisateur par email
function getUser($mail = "")
{
    global $conn;

    if (empty($mail)) {
        echo json_encode(["error" => "Aucun email fourni"]);
        return;
    }

    // Sécuriser l'email contre l'injection SQL
    $mail = mysqli_real_escape_string($conn, $mail);
    $query = "SELECT * FROM utilisateur WHERE email = '$mail' LIMIT 1";

    $result = mysqli_query($conn, $query);
    $response = array();

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $response[] = $row;
        }
    } else {
        $response["error"] = "Utilisateur non trouvé";
    }

    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
}
