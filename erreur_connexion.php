<?php

session_start();
$error = 0; // error count
$index = 0;

// si un utilisateur déjà connecté arrive sur connexion
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) { 
    header("Location: accueil.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les informations envoyées par le formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];
}

$fileJson = 'json/users.json';

$users = json_decode(file_get_contents($fileJson), true);  // Parser le contenu JSON en fait un tableau

if ($users !== null) {
    foreach ($users as $u) { // on récupère les mails et mot de passe associés
        if($u['email'] == $email){
            $password_check = $u['passwordHash'];
            $user_role = $u['role'];
            break;
        }
        else{
            $index += 1;
        }
    }

    if(isset($password_check) && password_verify($password,$password_check)){
        $_SESSION['email'] = $email;
        $_SESSION['logged_in'] = true;
        $_SESSION['stay_connected'] = $_POST['stay_connected'];
        $_SESSION['role'] = $user_role;
        $_SESSION['user'] = $users[$index];
        $_SESSION['index'] = $index;
        $_SESSION["panier"] = [];
        $users[$index]['dates']['derniere_connexion'] = date("Y-m-d"); // Ex: 2025-03-25
        file_put_contents($fileJson, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
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
    <title>Inscription - Elysia Voyage</title>
    <link rel="stylesheet" href="css/connexion.css?v=1.1" />
  </head>
  <body>
    <nav>
      <img src="Images/logo.png" alt="Logo" />
      <div class="btn">
        <a href="accueil.php">Accueil</a>
        <?php
        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            echo "<a href='profil.php'>Mon profil</a>";
        }
        ?>
        <a href="voyages.php">Nos voyages</a>
        <a href="filtrage.php">Filtrer</a>
      </div>
    </nav>

    <?php 
    if ($error){
        echo "<section id='error'><p> Utilisateur invalide, mail ou mot de passe incorect.</p>";
        echo "<a href='connexion.php'>Réessayer</a>";
        echo "<a href='inscription.html'>Inscrivez vous</a></section>";
    }
    ?>
</body>
</html>