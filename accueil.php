<?php
session_start();

// Durée d'inactivité autorisée
$timeout = 600; // 10 minutes

if ( !isset($_SESSION['stay_connected'])){ // si Rester connecté n'est pas clické
  // Vérifier si l'utilisateur est inactif depuis trop longtemps
  if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
      session_unset(); // Supprime toutes les variables de session
      session_destroy(); // Détruit la session
      header("Location: connexion.php?timeout=1"); // Redirige vers la connexion
      exit();
  }
}

// Met à jour l'heure de la dernière activité
$_SESSION['last_activity'] = time();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="css/accueil.css" />
    <link id="theme-link" rel="stylesheet" href="css/themes/theme_light.css">
    <title>Elysia Voyage</title>
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
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
          $nbArticles = isset($_SESSION['panier']) ? count($_SESSION['panier']) : 0;
          echo "<a href='mon_panier.php'>Panier ($nbArticles)</a>";
        }
        ?>
        <button id="change-theme">Changer de thème</button>
      </div>
    </nav>

    <header>
      <h1>Elysia Voyage</h1>
      <h4>L’élégance du voyage, la magie de l’amour</h4>
    </header>
    <main>
      <p>
        Plongez dans un univers où luxe, romantisme et évasion se rencontrent
        pour créer la lune de miel de vos rêves. Chez Elysia Voyage, nous
        concevons des expériences uniques pour les couples en quête d’exception.
        Des plages privées aux villas somptueuses, des îles paradisiaques aux
        destinations les plus exclusives, chaque voyage est une promesse de
        moments inoubliables.
        <br />
        <br />
        Notre expertise et notre attention aux moindres détails nous permettent
        d’offrir des séjours sur-mesure, où chaque instant est pensé pour vous
        émerveiller. Laissez-nous transformer votre voyage de noces en une
        parenthèse enchantée, où amour et raffinement ne font qu’un.
      </p>

      <br />

      <a href="voyages.php" class="cta">
        Decouvrir
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="30"
          height="10"
          viewBox="0 0 46 16"
        >
          <path
            d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z"
            transform="translate(30)"
          ></path>
        </svg>
      </a>
    </main>
    <script src="js/theme.js"></script>
  </body>
</html>
