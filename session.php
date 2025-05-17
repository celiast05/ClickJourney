<?php
session_start();

// Durée d'inactivité autorisée
$timeout = 600; // 10 minutes

if (isset($_SESSION['role']) && $_SESSION['role'] === 'banni') { // détection d'utilisateur banni
  header("Location: script/deconnexion.php?action=run");
  exit();
}

if (isset($_GET['banni'])): ?>
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

if (isset($_SESSION['logged_in']) && (!isset($_SESSION['prenom']) || !isset($_SESSION['nom']))) {
    $userId = $_SESSION['logged_in'];
    $usersData = json_decode(file_get_contents('json/users.json'), true);

    foreach ($usersData as $user) {
        if ($user['email'] === $userId) {
            $_SESSION['prenom'] = $user['informations']['prenom'];
            $_SESSION['nom'] = $user['informations']['nom'];
            $_SESSION['role'] = $user['role'];
            break;
        }
    }
}
?>