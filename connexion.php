<?php
session_start();
// si un utilisateur déjà connecté arrive sur connexion
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) { 
    header("Location: accueil.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Connexion - Elysia Voyage</title>
    <link rel="stylesheet" href="css/connexion.css?v=1" />
    <link id="theme-link" rel="stylesheet" href="css/themes/theme_light.css">
    <link rel="icon" type="images/logo.png" href="images/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  </head>
  <body>
    <section class="connexion">
      <h2>Heureux de vous revoir chez Elysia Voyage</h2>
      <p>
        Accédez à votre espace personnel et continuez votre voyage d'exception.
      </p>
      <form action="erreur_connexion.php" method="POST">
        <label for="email">Email :</label>
        <div class="email-wrapper">
        <input type="email" id="email" name="email" required />
        </div>

        
        <label for="password">Mot de passe :</label>
<div class="password-wrapper">
  <input type="password" id="password" name="password" required />
  <i class="fa-solid fa-eye toggle-eye" id="togglePassword"></i>
  <span id="charCount">0</span>
</div>


<div class="stay-connected">
  <label for="stay_connected">Rester connecté</label>
  <input type="checkbox" id="stay_connected" name="stay_connected" />
</div>
      <button type="submit">Se connecter</button>
      </form>

      <p><a href="#">Mot de passe oublié ?</a></p>
      <p>
        Pas encore de compte ? <a href="inscription.php">Inscrivez-vous ici</a>
      </p>
    </section>

    <?php include 'footer.php'; ?>
    
    <script src="js/connexion.js"></script>
    <script src="js/theme.js"></script>

  </body>
</html>