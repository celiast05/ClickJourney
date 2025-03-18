<?php

session_start();
$error = 0; // error count

// si un utilisateur déjà connecté arrive sur connexion
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && $_SESSION['stay_connected'] === true) { 
    header("Location: accueil.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les informations envoyées par le formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stay_connected = $_POST['stay_connected'];
}

$fileJson = 'users.json';

$users = json_decode(file_get_contents($fileJson), true);  // Parser le contenu JSON en fait un tableau

$simple_user_list = [];

if ($users !== null) {

    foreach ($users as $u) { // on récupère les mails et mot de passe associés
        $simple_user_list[$u['email']] = $u['passwordHash'];
    }

    $user_role = null;

    foreach ($users as $user) {
        if ($user['email'] === $email) {
            $user_role = $user['role'];
            break;
        }
    }

    if(isset($simple_user_list[$email]) && password_verify($password,$simple_user_list[$email])){
        $_SESSION['email'] = $email;
        $_SESSION['logged_in'] = true;
        $_SESSION['stay_connected'] = $stay_connected;
        $_SESSION['role'] = $user_role;
        header("Location: accueil.php");
        exit();
    }
    else{
        $error = $error + 1;
    }

}
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inscrition - Elysia Voyage</title>
    <link rel="stylesheet" href="css/connexion.css?v=1" />
  </head>
  <body>
    <?php 
    if ($error){
        echo "<section id='error'><p> Utilisateur invalide, mail ou mot de passe incorect.</p>";
        echo "<a href='connexion.html'>Réessayer</a>";
        echo "<a href='inscription.html'>Inscrivez vous</a></section>";
    }
    ?>
</body>
</html>