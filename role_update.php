<?php
session_start();
header('Content-Type: application/json'); // Déclare que la réponse est du JSON

if ($_SESSION['role'] != 'admin') {
    alert("Modification refusée : vous n'êtes pas administrateur.");
    header("Location: accueil.php");
    exit();
}

$fileJson = 'json/users.json';
$users = json_decode(file_get_contents($fileJson), true);


// Read JSON request
$data = json_decode(file_get_contents("php://input"), true);

// if data valide
if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_array($data)) {
    $index = 0;
    // find user to update
    if ($users !== null) {
    foreach ($users as $u) { // get the right user
        if($u['email'] == $data['user_email']){
            $user_role = $u['role'];
            break;
        }
        else{
            $index += 1;
        }
    }
    }
    // update JSON
    $users[$index]['role'] = $data['role'] ?? '';

    // Save JSON
    file_put_contents($fileJson, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // Update session if the users modifier is himself
    if($index == $_SESSION['index']){
        $_SESSION['role'] = $data['role'];
        exit();
    }
    $users['email'] = $users[$index];
    $_SESSION['role'] = $users[$index]['role'];

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