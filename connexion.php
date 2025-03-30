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
  </head>
  <body>
    <section class="connexion">
      <h2>Heureux de vous revoir chez Elysia Voyage</h2>
      <p>
        Accédez à votre espace personnel et continuez votre voyage d'exception.
      </p>
      <form action="erreur_connexion.php" method="POST">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required />

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required />

        <br />
        <label for="stay_connected">Rester connecté</label>
        <input type="checkbox" id="stay_connected" name="stay_connected" />
        <br />

        <button type="submit">Se connecter</button>
      </form>

      <p><a href="#">Mot de passe oublié ?</a></p>
      <p>
        Pas encore de compte ? <a href="inscription.html">Inscrivez-vous ici</a>
      </p>
    </section>

    <footer>
      <p>&copy; 2025 Elysia Voyage. Tous droits réservés.</p>
    </footer>
  </body>
</html>
