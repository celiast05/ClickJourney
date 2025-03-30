<?php 
session_start();

$fileJson = 'json/voyage.json';

$trips = json_decode(file_get_contents($fileJson), true); 

foreach ($trips as $t) { // on récupère le bon voyage
    if($t['id'] == $_GET['voyage']){
        $trip = $t;
        break;
    }
}

include("getapikey.php");
$transaction = '154632ABCZWTC';

$montant = $trip['prix'] * $_POST["nb_personnes"][$_POST["hebergements"]];
$montant +=  $_POST["nb_personnes"][$_POST["hebergements"]] * $trip["hebergements"][$_POST["hebergements"]];
if(isset($_POST["activites"])){
    foreach($_POST["activites"] as $activite){
        $montant +=  $_POST["nb_personnes"][$activite] * $trip["activites"][$activite];
    }
}

$vendeur = "TEST";
$api_key = getAPIKey($vendeur);
$retour = "http://localhost:8888/ClickJourney/retour_paiement.php";
$control = md5( $api_key."#".$transaction."#".$montant."#".$vendeur."#".$retour."#");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panier.css?v=1.3">
    <title>Panier</title>
</head>
<body>
    <h2>Votre panier</h2>
    
        Voyage <?php echo $trip["nom"] ?></li>
        
            <?php 
                echo "<h3>Hébergement :</h3><p>".$_POST["hebergements"]. " : ". $_POST["nb_personnes"][$_POST["hebergements"]]. " personne";
                if ($_POST["nb_personnes"][$_POST["hebergements"]]>1){echo "s";}
                echo "</p>";
            if (isset($_POST["activites"])) {
                if(count($_POST["activites"])>1){
                    echo " <h3> Activités sélectionnées : </h3><ul>";
                }
                else{
                    echo "<h3> Activité sélectionnée : </h3><ul>";
                }
                $activites_selectionnes = $_POST["activites"];
                $nb_personnes = $_POST["nb_personnes"]; // Tableau associatif des nombres de personnes par hébergement
        
                foreach ($activites_selectionnes as $activite) {
                    $nombre_personnes = $nb_personnes[$activite];
                    echo "<li> " . htmlspecialchars($activite) . " : " . $nombre_personnes. " personne";
                    if($nombre_personnes>1){echo "s";};
                    echo '.</li>';
                }
                echo '</ul>';
            }
            echo "<h3>Dates :</h3><p> Du ".$_POST["date_depart"]. " au ". $_POST["date_retour"];
            ?>
    <h3> Total : <?php echo $montant; ?> €</h3>
    <form action='https://www.plateforme-smc.fr/cybank/index.php' method='POST'>
    <input type='hidden' name='transaction' value='<?php echo $transaction; ?>'>
    <input type='hidden' name='montant' value='<?php echo $montant; ?>'>
    <input type='hidden' name='vendeur' value='<?php echo $vendeur; ?>'>
    <input type='hidden' name='retour' value='<?php echo $retour; ?>'>
    <input type='hidden' name='control' value='<?php echo $control; ?>'>
    <input type='submit' value="Valider et payer">
    <a href='details.php?voyage=<?php echo $trip["id"]; ?>'> Modifier </a>
    </form>
    
</body>
</html>