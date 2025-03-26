<?php
session_start();

// Durée d'inactivité autorisée
$timeout = 300; // 5 minutes

if (!isset($_SESSION['logged_in'])){ // utilisateur anonyme
  header("Location: connexion.html"); // Redirige vers la connexion
  exit();
} 

if ( !isset($_SESSION['stay_connected'])){ // si "Rester connecté" n'est pas clické
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


$trip_to_link = ["Paris" => "paris", "Santorin" => "santorin", "Courchevel" => "courchevel", "Bora Bora" => "bora_bora", "Rome" => "rome", "Maldives"=>"maldives",
            "Laponie"=>"laponie", "Tahiti"=>"tahiti", "Aspen" => "aspen", "Costa Rica"=>"costarica", "Tanzanie" => "tanzanie", "Australie" => "australie","Oman" => "oman",
            "Égypte" => "egypte","Dubaï"=>"budai"];
$link_to_trip = array_flip($trip_to_link);

$fileJson = 'json/voyage.json';

$trips = json_decode(file_get_contents($fileJson), true); 

foreach ($trips as $t) { // on récupère le bon voyage
    if($t['id'] == $_GET['voyage']){
        $trip = $t;
        break;
    }
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $trip["nom"] ?></title>
    <link rel="stylesheet" href="css/details.css?v=1">
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
            echo "<a href='deconnexion.php?action=run'>Déconnexion</a>";
        }
        else{
            echo "<a href='connexion.html'>Connexion</a>";
        }
        ?>
      </div>
    </nav>

    <h2><?php echo $trip['nom']. ', '. $trip["theme"]; ?></h2>
    <div class="container">
    <div class="image"><img src="<?php echo $trip["image"]; ?>" alt="<?php echo "Image de ".$trip["nom"]; ?>"/></div>
    <section>
    <?php if (!empty($trip["description"])){
        echo '<p>'. $trip["description"] ."</p>";
    }?>
    <?php if (!empty($trip["climat_saison"])){
        echo "<h4>Climats et Saisons </h4>";
        echo '<p>'. $trip["climat_saison"] ."</p>";
    }?>
    <?php if (!empty($trip["hebergements"])){
        echo "<h4>Hébergements </h4>";
        echo '<p>'.implode(', ',$trip["hebergements"]) ."</p>";
    }?>
    <?php if (!empty($trip["activites"])){
        echo "<h4>Activités </h4>";
        echo '<p>'.implode(', ',$trip["activites"]) ."</p>";
    }?>
    <?php if (!empty($trip["experiences"])){
        echo "<h4>Experiences </h4>";
        echo '<p>'.implode(', ',$trip["experiences"]) ."</p>";
    }?>
    <?php if (!empty($trip["intimite"])){
        echo "<h4>Intimité </h4>";
        echo '<p>'. $trip["intimite"] ."</p>";
    }?>
    </section>
    </div>
</body>
</html>