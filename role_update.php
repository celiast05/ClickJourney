<?php
session_start();
header('Content-Type: application/json'); // Déclare que la réponse est du JSON

if ($_SESSION['role'] != 'admin') {
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

    // client answer (JavaScript)

    $response = [
    "success" => true,
    "message" => "Update successful"
    ];

    // Update session if the user modified is himself
    if ($data['user_email'] == $_SESSION['email']) {
        $_SESSION['role'] = $data['role'];
        $response["redirect"] = "accueil.php";
    }
    echo json_encode($response);
    exit();
} else {
    // wrong request
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Invalide datas or method"
    ]);
    exit();
}
?>