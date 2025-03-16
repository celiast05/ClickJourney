<?php

function add_user($nom,$prenom, $email, $password) {
    
    $fileJson = 'users.json'; // Fichier où les utilisateurs sont stockés
    
    if (file_exists($fileJson)) {
        $users = json_decode(file_get_contents($fileJson), true); // Lire les données existantes du fichier JSON et les trasforme en tableau
    } else {
        $users = []; 
    }

    $new_user = [
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'password' => $password,
        'admin' => 'non',
    ];

    $users[] = $new_user;
   
    file_put_contents($fileJson, json_encode($users));  // Sauvegarder les utilisateurs dans le fichier JSON

    header("Location: accueil.html");
    exit();
}

$error = 0; // error count

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Récupérer les informations du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
}

$fileJson = 'users.json';

$users = json_decode(file_get_contents($fileJson), true); // Parser le contenu JSON en fait un tableau
if ($users !== null) {
    foreach ($users as $u) { // on récupère les mails du fichier json
        $mail_list[] = $u['email'];
    }

    if(in_array($email,$mail_list)){
        $error = $error + 1;
        echo "Ce mail est déjà utilisé <br>";
        echo "Inscription Invalide, <a href='inscription.html'>Réessayer</a></h2>";
    }

}

if($password != $confirm_password){
    echo "Les deux mots de passe sont différents <br>";
    echo "Inscription Invalide, <a href='inscription.html'>Réessayer</a></h2>";
    $error = $error + 1;
}

if(!$error){ 
    add_user($nom,$prenom,$email,$password);
}
?>