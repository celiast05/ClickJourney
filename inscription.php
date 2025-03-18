<?php

session_start();

function add_user($nom,$prenom, $email, $password) {
    
    $fileJson = 'users.json'; // Fichier où les utilisateurs sont stockés
    
    if (file_exists($fileJson)) {
        $users = json_decode(file_get_contents($fileJson), true); // Lire les données existantes du fichier JSON et les trasforme en tableau
    } else {
        $users = []; 
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $new_user = [
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'passwordHash' => $passwordHash,
        'role' => null,
    ];

    $users[] = $new_user;
    // Sauvegarder les utilisateurs dans le fichier JSON propre
    file_put_contents($fileJson, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    $_SESSION['email'] = $email;
    $_SESSION['logged_in'] = true;
    header("Location: accueil.php");
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
$mail_list = [];

if ($users !== null) {
    foreach ($users as $u) { // on récupère les mails du fichier json
        $mail_list[] = $u['email'];
    }

    if(in_array($email,$mail_list)){
        $error = 1;
    }

}

if($password != $confirm_password){
    $error = 2;
}

if(!$error){
    add_user($nom,$prenom,$email,$password);
}

?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inscription - Elysia Voyage</title>
    <link rel="stylesheet" href="css/connexion.css?v=1" />
  </head>
  <body>
  <section id="error">
    <?php
    if ($error == 1){
        echo "<p> Ce mail est déjà utilisé.";
        echo "<p>Inscription Invalide, </p> ";
        echo "<a href='inscription.html'>Réessayer</a>";
    }
    if ($error == 2){
        echo "<p>Les deux mots de passe sont différents.</p>";
        echo "<p>Inscription Invalide, </p> ";
        echo "<a href='inscription.html'>Réessayer</a>";
    }
    ?>
    </section>
</body>
</html>