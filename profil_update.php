<?php
session_start();
header('Content-Type: application/json'); // Déclare que la réponse est du JSON

$fileJson = 'json/users.json';
$users = json_decode(file_get_contents($fileJson), true);

// Lire le corps brut de la requête JSON
$data = json_decode(file_get_contents("php://input"), true);

// Vérifie que les données sont valides
if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_array($data)) {
    $index = $_SESSION['index'];

    // Mise à jour des informations
    $users[$index]['informations']['civilite'] = $data['civilite'] ?? '';
    $users[$index]['informations']['nom'] = $data['nom'] ?? '';
    $users[$index]['informations']['prenom'] = $data['prenom'] ?? '';
    $users[$index]['informations']['telephone'] = $data['telephone'] ?? '';
    $users[$index]['informations']['photo'] = $data['photo'] ?? '';
    $users[$index]['informations']['preferences'] = $data['preferences'] ?? '';
    $users[$index]['informations']['passeport'] = $data['passeport'] ?? '';

    // Optionnel : mise à plat des infos au même niveau (comme dans votre version)
    $users[$index]['civilite'] = $data['civilite'] ?? '';
    $users[$index]['nom'] = $data['nom'] ?? '';
    $users[$index]['prenom'] = $data['prenom'] ?? '';
    $users[$index]['telephone'] = $data['telephone'] ?? '';
    $users[$index]['photo'] = $data['photo'] ?? '';
    $users[$index]['preferences'] = $data['preferences'] ?? '';
    $users[$index]['passeport'] = $data['passeport'] ?? '';

    // Enregistre dans le fichier JSON
    file_put_contents($fileJson, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // Met à jour la session
    $_SESSION['user'] = $users[$index];

    // Réponse au client (JavaScript)
    echo json_encode([
        "success" => true,
        "message" => "Mise à jour réussie"
    ]);
    exit();
} else {
    // Requête incorrecte
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Données invalides ou méthode incorrecte"
    ]);
    exit();
}
?>