<?php
include 'session.php'; // charge la session (avec session_start())
// si un utilisateur déjà connecté arrive sur connexion
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) { 
    header("Location: accueil.php"); // redirige vers la page d'accueil
    exit(); // stoppe immédiatement le script
}
?>
<!-- Début HTML (tête du document) -->
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" /> <!-- définit le jeu de caractères en UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />  <!-- adapte l’affichage aux écrans mobiles -->
    <title>Connexion - Elysia Voyage</title> <!-- titre de l'onglet -->
    <link rel="stylesheet" href="css/connexion.css?v=1" /> <!-- feuille de style propre à cette page -->
    <link id="theme-link" rel="stylesheet" href="css/themes/theme_light.css"> <!-- thème clair par défaut -->
    <link rel="icon" type="images/logo.png" href="images/favicon.png"> <!-- favicon du site -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> <!-- bibliothèque d’icônes pour l’œil -->
  </head>
  <body>
    <!-- Corps de page + formulaire -->
    <?php include 'notif.php'; ?> <!-- insère le bloc pour afficher une notification s’il y en a -->
    <section class="connexion">
      <h2>Heureux de vous revoir chez Elysia Voyage</h2>
      <p>
        Accédez à votre espace personnel et continuez votre voyage d'exception.
      </p>
      <form action="erreur_connexion.php" method="POST"> <!-- envoie les infos à erreur_connexion.php via POST -->
        <label for="email">Email :</label>
        <div class="email-wrapper">
        <input type="email" id="email" name="email" required <?php if (isset($_GET['email'])){echo "value='".$_GET['email']."'";} ?> />
        </div>
<!-- connexion.js utilise l’ID email pour la validation JS. -->
        
 <label for="password">Mot de passe :</label>
<div class="password-wrapper">
  <input type="password" id="password" name="password" required /> <!-- champ obligatoire -->
  <span id="charCount">0</span> <!-- compteur de caractères tapés-->
  <i class="fa-solid fa-eye toggle-eye" id="togglePassword"></i> <!-- icône œil pour voir le mot de passe -->
</div>

<div class="stay-connected">
  <label for="stay_connected">Rester connecté</label>
  <input type="checkbox" id="stay_connected" name="stay_connected" /> <!-- itype="checkbox" est un attribut HTML standard. Il sert à créer une case à cocher dans un formulaire. S’il est coché, sa valeur est envoyée en POST, sinon il n’apparaît pas. En PHP, on vérifie avec isset() si elle a été cochée.-->
</div>
      <button type="submit">Se connecter</button>  <!-- type="submit" est un attribut HTML standard utilisé pour envoyer un formulaire. Il déclenche la validation automatique du formulaire, puis envoie les données au script PHP défini dans action.-->
      </form>

      <p><a href="#">Mot de passe oublié ?</a></p>
      <p>
        Pas encore de compte ? <a href="inscription.php">Inscrivez-vous ici</a>
      </p>
    </section>

    <?php include 'footer.php'; ?> <!-- iinsère le pied de page commun -->

    <?php
    if (isset($_GET['timeout'])):?>
      <script>
        const notif = document.getElementById("notif");
        notif.textContent = "Timeout, veuillez vous reconnecter";
        notif.classList.remove("hidden"); // Make element visible in layout

        // Allow browser to reflow before applying fade-in
        setTimeout(() => {
          notif.classList.add("show"); // Trigger fade-in
        }, 10);

        // Auto-hide after 3 seconds
        setTimeout(() => {
          notif.classList.remove("show"); // Start fade-out
          setTimeout(() => {
            notif.classList.add("hidden"); // Fully hide after fade-out completes
          }, 500); // Match CSS transition duration
        }, 3000);
      </script>
    <?php endif; ?>
    
    <script src="js/connexion.js"></script>  <!-- connexion.js contient : validation JS (email, mot de passe) compteur caractères affichage/mot de passe visible -->
    <script src="js/theme.js"></script>

  </body>
</html>