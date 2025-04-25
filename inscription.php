<?php

session_start();
$fileJson = 'json\users.json';
$index = -1;

function add_user($email, $password) {
    
    $fileJson = 'json/users.json';
    if (file_exists($fileJson)) {
        $users = json_decode(file_get_contents($fileJson), true); // Lire les données existantes du fichier JSON et les trasforme en tableau
    } else {
        $users = [];
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $new_user = [
        "email" => $email,
        "passwordHash" => $passwordHash,
        "role" => "normal",
        "informations" => [
            "nom" => $_POST['nom'],
            "prenom" => $_POST['prenom'],
            "civilite" => "",
            "telephone" => null
        ],
        "dates" => [
            "inscription" => date("Y-m-d"), // Date d'inscription actuelle
            "derniere_connexion" => null
        ],
        "voyages" => [
            "consultes" => [],
            "achetes" => [],
            "favoris" => []
        ]
    ];

    $users[] = $new_user;
    // Sauvegarder les utilisateurs dans le fichier JSON propre
    file_put_contents($fileJson, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    $_SESSION['email'] = $_POST['email'];
    $_SESSION['logged_in'] = true;
    $_SESSION['role'] = "normal";
    $_SESSION['user'] = $new_user;
    $_SESSION['index'] = count($users)-1;
    $_SESSION["panier"] = [];
    header("Location: accueil.php");
    exit();
}

$error = 0; // error count

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Récupérer les informations du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
}


$users = json_decode(file_get_contents($fileJson), true); // Parser le contenu JSON en fait un tableau

if ($users !== null) {
    foreach ($users as $u) { // on récupère les mails du fichier json
        if($email==$u['email']){
            $error = 1; // Eamil déjà utilisé dans la base utilisateur
            break;
        }
    }
}

if($password != $confirm_password){
    $error = 2;
}

if(!$error){
    add_user($email,$password);
}

?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inscription - Elysia Voyage</title>
    <link rel="stylesheet" href="css/inscription.css?v=1" />
    <link id="theme-link" rel="stylesheet" href="css/themes/theme_light.css">
  </head>
  <body>
  <nav>
      <img src="Images/logo.png" alt="Logo" />
      <div class="btn">
        <?php
        if(isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
            echo "<a href='admin.php'>Administrateur</a>";
        }
        ?>
        <a href="accueil.php">Accueil</a>
        <?php
        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            echo "<a href='profil.php'>Mon profil</a>";
        }
        ?>
        <a href="voyages.php">Nos voyages</a>
        <a href="filtrage.php">Filtrer</a>
        <?php
        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            echo "<a href='script/deconnexion.php?action=run'>Déconnexion</a>";
        }
        else{
            echo "<a href='connexion.php'>Connexion</a>";
        }
        ?>
      </div>
    </nav>
    
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
    <script src="js/theme.js"></script>
</body>
</html>