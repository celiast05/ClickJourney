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
            "civilite" => null,
            "telephone" => null,
            "photo" => null,
            "preferences" => null,
            "passeport" => null
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
  <?php include 'nav.php'; ?>
    
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
    <div id="foot"></div>

      <script>
      fetch('/footer.html')
        .then(res => res.text())
        .then(html => {
          document.getElementById('foot').innerHTML = html;
        });
    </script>
    <script src="js/theme.js"></script>
</body>
</html>