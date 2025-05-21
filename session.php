<?php
session_start();

// Durée d'inactivité autorisée
$timeout = 600; // 10 minutes

if (isset($_SESSION['index'])) {
    $usersData = json_decode(file_get_contents('json/users.json'), true);
    if (isset($usersData[$_SESSION['index']]) && $usersData[$_SESSION['index']]['email'] == $_SESSION['email']){ // if user got deleted or wrong index
      $_SESSION['role'] = $usersData[$_SESSION['index']]['role'];
    }
    else{
      header("Location: script/deconnexion.php?action=run");
      exit();
    }
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

if(isset($_SESSION['logged_in'])){
  if (isset($_SESSION['stay_connected']) && !($_SESSION['stay_connected'])){ // if stay_connected isn't clicked
    if (isset($_SESSION['last_activity'])) {
          $inactivity = time() - $_SESSION['last_activity'];

    if ($inactivity > $timeout) {
        session_unset();
        session_destroy();
        header("Location: connexion.php?timeout"); // Redirect to connexion
        exit();
      }
    }
  }
$_SESSION['last_activity'] = time(); // Met à jour l'heure de la dernière activité si connecté
}

?>