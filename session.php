<?php
session_start();

// Durée d'inactivité autorisée
$timeout = 600; // 10 minutes

if (isset($_SESSION['index'])) {
    $usersData = json_decode(file_get_contents('json/users.json'), true);
    $_SESSION['role'] = $usersData[$_SESSION['index']]['role'];
}

if (isset($_SESSION['role']) && $_SESSION['role'] === 'bannir'){// dection of banned user
  header("Location: script/deconnexion.php?action=run");
  exit();
}

if (isset($_GET['banni'])): // after banned user got redirected to accueil.php?banni ?>
  <script>
      alert("Vous avez été banni. Vous avez été automatiquement déconnecté.");
  </script>
<?php endif;

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