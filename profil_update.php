<?php
include 'session.php';
header('Content-Type: application/json'); // Declare that answer is JSON

$fileJson = 'json/users.json';
$users = json_decode(file_get_contents($fileJson), true);

// read JSON request
$data = json_decode(file_get_contents("php://input"), true);

// Vérifie que les données sont valides
if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_array($data)) {
    $index = $_SESSION['index'];

    // Update datas
    $users[$index]['informations']['civilite'] = $data['civilite'] ?? '';
    $users[$index]['informations']['nom'] = $data['nom'] ?? '';
    $users[$index]['informations']['prenom'] = $data['prenom'] ?? '';
    $users[$index]['informations']['telephone'] = $data['telephone'] ?? '';
    $users[$index]['informations']['photo'] = $data['photo'] ?? '';
    $users[$index]['informations']['preferences'] = $data['preferences'] ?? '';
    $users[$index]['informations']['passeport'] = $data['passeport'] ?? '';

    // Save new datas in JSON File
    file_put_contents($fileJson, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // update session
    $_SESSION['user'] = $users[$index];

    // client answer (JavaScript)
    echo json_encode([
        "success" => true,
        "message" => "Mise à jour réussie"
    ]);
    exit();
} else {
    // wrong request
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Données invalides ou méthode incorrecte"
    ]);
    exit();
}
?>