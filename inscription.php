<?php
include 'session.php'; // Fonction PHP : inclut le fichier qui lance session_start()

$fileJson = 'json/users.json'; // Variable : chemin vers le fichier contenant les utilisateurs
$error = 0; // Variable qui stockera les codes d'erreur (0 = pas d'erreur)

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Vérifie si le formulaire a été soumis (POST)
    $email = $_POST['email']; // Superglobale PHP : récupère l’email saisi
    $password = $_POST['password'];  // Superglobale : mot de passe
    $confirm_password = $_POST['confirm-password']; // Superglobale : confirmation
    
    // Vérifie si le fichier JSON existe
    if (file_exists($fileJson)) { // Fonction PHP : teste si un fichier existe

        $users = json_decode(file_get_contents($fileJson), true); // Lit le fichier JSON et convertit en tableau PHP
        
        // Vérifie si l'email existe déjà dans le tableau
        if ($users !== null) {
            foreach ($users as $u) {
                if($email == $u['email']){
                    $error = 1;  // Vérifie si l'email existe déjà dans le tableau

                    break;
                }
            }
        }
    }

    // Vérifie que les deux mots de passe sont identiques
    if($password != $confirm_password){
        $error = 2; // Code erreur 2 : mot de passe ≠ confirmation
    }

    // Si pas d'erreur, créer l'utilisateur     // Si aucune erreur, on ajoute l'utilisateur

    if(!$error){
        add_user($email, $password);  // Appel de la fonction définie plus bas
    }
}

function add_user($email, $password) {
    
    $fileJson = 'json/users.json'; // chemin vers le fichier JSON
    if (file_exists($fileJson)) {     // Vérifie si le fichier existe, le lit, ou initialise un tableau vide

        $users = json_decode(file_get_contents($fileJson), true); // Lire les données existantes du fichier JSON et les trasforme en tableau
    } else {
        $users = [];
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT); // Fonction PHP : hache le mot de passe pour la sécurité
    // Création du nouvel utilisateur (tableau associatif)

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
            "achetes" => []
        ]
    ];

    $users[] = $new_user; // Ajoute le nouvel utilisateur au tableau
    // Sauvegarder les utilisateurs dans le fichier JSON propre
    file_put_contents($fileJson, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Initialise la session utilisateur

    $_SESSION['email'] = $_POST['email'];
    $_SESSION['logged_in'] = true;
    $_SESSION['role'] = "normal";
    $_SESSION['user'] = $new_user;
    $_SESSION['index'] = count($users)-1;
    $_SESSION["panier"] = [];
    header("Location: accueil.php");  // Redirige vers l’accueil
    exit(); // Stoppe le script
}
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inscription - Elysia Voyage</title>
    <link rel="stylesheet" href="css/inscription.css" />
    <link id="theme-link" rel="stylesheet" href="css/themes/theme_light.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  </head>
  <body>
    <section class="inscription">
      <h2>Rejoignez l'élite du voyage avec Elysia Voyage</h2>
      <p>
        Faites partie d'une communauté exclusive de voyageurs privilégiés et
        profitez d'expériences uniques à travers le monde.
      </p>

      <form action="inscription.php" method="POST">
        <?php if ($error == 1): ?>
            <div class="error-message">Cet email est déjà utilisé.</div>
        <?php endif; ?>
        <?php if ($error == 2): ?>
            <div class="error-message">Les mots de passe ne correspondent pas.</div>
        <?php endif; ?>

        <label for="nom">Nom :</label>
        <input
          type="text"
          id="nom"
          name="nom"
          required
          placeholder="Entrez votre nom"
        />

        <label for="prenom">Prénom :</label>
        <input
          type="text"
          id="prenom"
          name="prenom"
          required
          placeholder="Entrez votre prénom"
        />

        <label for="email">Email :</label>
        <input
          type="email"
          id="email"
          name="email"
          required
          placeholder="Entrez votre email"
        />

        <label for="password">Mot de passe :</label>
        <div class="password-wrapper">
          <input
            type="password"
            id="password"
            name="password"
            required
            placeholder="Créez un mot de passe"
          />
          <i class="fa-solid fa-eye toggle-eye" id="togglePassword"></i>
        </div>

        <label for="confirm-password">Confirmer le mot de passe :</label>
        <div class="password-wrapper">
          <input
            type="password"
            id="confirm-password"
            name="confirm-password"
            required
            placeholder="Confirmez votre mot de passe"
          />
          <i class="fa-solid fa-eye toggle-eye" id="toggleConfirm"></i>
        </div>

        <button type="submit">Rejoindre l'expérience</button>
      </form>

      <p>Déjà un compte ? <a href="connexion.php">Connectez-vous ici</a></p>
    </section>

    <?php include 'footer.php'; ?>
    
    <script src="js/inscription.js"></script> <!-- Script JS : validation côté client -->
    <script src="js/theme.js"></script>
  </body>
</html>