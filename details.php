<?php
session_start();

// Durée d'inactivité autorisée
$timeout = 300; // 5 minutes

if (!isset($_SESSION['logged_in'])){ // utilisateur anonyme
  header("Location: connexion.php"); // Redirige vers la connexion
  exit();
} 

if ( !isset($_SESSION['stay_connected'])){ // si "Rester connecté" n'est pas clické
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
    <link rel="stylesheet" href="css/details.css?v=1.7">
    <link id="theme-link" rel="stylesheet" href="css/themes/theme_light.css">
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
        ?>
      </div>
    </nav>

    <h2><?php echo $trip['nom']. ', '. $trip["theme"]; ?></h2>
    <div class="container">
    <div class="image"><img src="<?php echo $trip["image"]; ?>" alt="<?php echo "Image de ".$trip["nom"]; ?>"/></div>
    <section>
    <form id="reservation-form" action="ajout_panier.php?voyage=<?php echo $_GET['voyage']; ?>" method="POST">
    <?php if (!empty($trip["description"])){
            echo '<p>'. $trip["description"] ."</p>";
        }?>
        <?php if (!empty($trip["climat_saison"])){
            echo "<h4>Climats et Saisons </h4>";
            echo '<p>'. $trip["climat_saison"] ."</p>";
        }?>
        <?php if (!empty($trip["hebergements"])){
            echo '<h4>Hébergements </h4> <div class="radio-group">';
        $bool = 1;
        foreach($trip["hebergements"] as $hebergement => $prix){
                echo '<label>
                    <input type="radio" name="hebergements" value="'.htmlspecialchars($hebergement). '" onclick="toggleInput()"';
                if ($bool){
                    echo ' checked';
                } 
                echo '/>
                    <p>'.$hebergement.' : '.$prix. '€</p>
                &emsp; <input type="number" id="nb_personnes" name="nb_personnes[' . htmlspecialchars($hebergement) . ']" value="1" min="1" max="10" required';
                if (!$bool){
                    echo ' disabled';
                }
                echo '> <span style="font-size: 20px"> personne(s)</span></label>';
                $bool = 0;
            }
            echo '</div>';
        }
        ?>
        <?php if (!empty($trip["activites"])){
            echo '<h4>Activités </h4> <div class="checkbox-group">';
        foreach($trip["activites"] as $activity => $prix){
                echo '<label>
                    <input type="checkbox" name="activites[]" value="'.htmlspecialchars($activity).'" onclick="toggleInput()" />
                    <p>'.$activity.' : '.$prix. '€</p>
                &emsp; <input type="number" id="nb_personnes" name="nb_personnes[' . htmlspecialchars($activity) . ']" value="1" min="1" max="10" required disabled> <span style="font-size: 20px"> personne(s)</span></label>';
            }
        echo '</div>';
        }
        ?>
        <?php if (!empty($trip["experiences"])){
            echo "<h4>Experiences </h4> ";
            echo '<p>'.implode(', ',$trip["experiences"]) ."</p>";
        }?>
        <?php if (!empty($trip["intimite"])){
            echo "<h4>Intimité </h4>";
            echo '<p>'. $trip["intimite"] ."</p>";
        }?>
        <?php if (!empty($trip["prix"])){
            echo "<h4>Prix par personne : ". $trip["prix"] ." € </h4>";
        }?>
    <br>
        <label for="date_depart" class="header"> Date de départ : &emsp;
        <input type="date" id="date_depart" name="date_depart" required/>
        </label>
        
        <br>
        <label for="date_retour" class="header"> Date de retour : &emsp;
        <input type="date" id="date_retour" name="date_retour" required />
        </label>
        
        <br><br>
        <p><strong>Prix estimé : </strong><span id="estimation">0</span> €</p>
        <button type="submit">Ajouter au panier</button>
        </form>
    </section>
    </div>
    <script>


    function toggleInput() {
        // Sélectionner toutes les cases cochées (radio et checkbox)
        const selectedInputs = document.querySelectorAll('input[type="radio"]:checked, input[type="checkbox"]:checked');
        const uncheckedInputs = document.querySelectorAll('input[type="radio"]:not(:checked), input[type="checkbox"]:not(:checked)');

        // Sélectionner tous les champs de nombre de personnes
        const allNumberInputs = document.querySelectorAll('input[type="number"]');

        // Désactiver tous les champs et supprimer leur "name"
        allNumberInputs.forEach(input => {
            input.disabled = false;
        });

        uncheckedInputs.forEach(input => {
            const numberInput = input.parentNode.querySelector('input[type="number"]');
            if (numberInput) {
                numberInput.disabled = true;
                numberInput.value = "1"; // Remettre à 1
            }
        });
    }
    // Avant l'envoi du formulaire, on supprime les inputs désactivés pour qu'ils ne soient pas envoyés
    document.getElementById("reservation-form").addEventListener("submit", function() {
        document.querySelectorAll('input[type="number"]:disabled').forEach(input => {
            input.removeAttribute("name"); // Supprimer le name pour qu'il ne soit pas envoyé
        });
    });
</script>
<script id="prix-data" type="application/json">
{
  "basePrix": <?= $trip['prix'] ?>,
  "hebergements": <?= json_encode($trip['hebergements']) ?>,
  "activites": <?= json_encode($trip['activites']) ?>
}
<script src="js/theme.js"></script>
<script src="js/prix.js"></script>

</body>
</html>