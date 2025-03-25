<?php
session_start();

// Durée d'inactivité autorisée
$timeout = 600; // 10 minutes

if ( !isset($_SESSION['stay_connected'])){ // si Rester connecté n'est pas cliqué
  // Vérifier si l'utilisateur est inactif depuis trop longtemps
  if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
      session_unset(); // Supprime toutes les variables de session
      session_destroy(); // Détruit la session
      header("Location: connexion.html?timeout=1"); // Redirige vers la connexion
      exit();
  }
}

// Met à jour l'heure de la dernière activité
$_SESSION['last_activity'] = time();

$voyages = json_decode(file_get_contents('json/voyage.json'), true);

$voyageId = $_GET['id'];
$voyage = null;

foreach ($voyages as $v) {
    if ($v['id'] == $voyageId) {
        $voyage = $v;
        break;
    }
}

if ($voyage === null) {
  die("Voyage introuvable");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/presentation.css" />
    <title>Nos voyages - Elysia Voyage</title>
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
            echo "<a href='profil.html'>Mon profil</a>";
        }
        ?>
        <a href="voyages.php">Nos voyages</a>
        <a href="filtrage.php">Filtrer</a>
        <?php
        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            echo "<a href='deconnexion.php?action=run'>Déconnexion</a>";
        }
        else{
            echo "<a href='connexion.html'>Connexion</a>";
        }
        ?>
      </div>
</nav>
<header>
    <h1>Détails du voyage </h1>
</header>
<main>
    <div class="details">
    <div class="voyage-image" style="background-image: url('<?php echo $voyage['image']; ?>');"></div>
    <div class="voyage-info">
      <h2><?php echo htmlspecialchars($voyage['nom']); ?></h2>
      <p><strong>Sous-Titre :</strong> <?php echo htmlspecialchars($voyage['theme']); ?></p>
      <p><strong>Description :</strong> <?php echo htmlspecialchars($voyage['description']); ?></p>
      <p><strong>Détails :</strong> <?php echo nl2br(htmlspecialchars($voyage['details'])); ?></p>
    </div>
  </div>
</main>
</body>
</html>