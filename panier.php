<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les informations envoyées par le formulaire
    echo $_POST["hebergements"];
    echo print_r($_POST["activites"]);
    echo print_r($_POST["nb_personnes"]);
} 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panier.css">
    <title>Panier</title>
</head>
<body>
    <?php
        include("getapikey.php");
        $transaction = '154632ABCZWTC';
        $montant = "1456789.99";
        $vendeur = "TEST";
        $api_key = getAPIKey($vendeur);
        $retour = "http://localhost:8888/ClickJourney/retour_paiement.php";
        $control = md5( $api_key."#".$transaction."#".$montant."#".$vendeur."#".$retour."#");

    ?>
    <h2>Votre panier</h2>
    
        Recapitulatif de panier <?php echo $montant ?>€</li>
        
            <?php 
                echo "<p>Hébergement : ".$_POST["hebergements"]. " : ". $_POST["nb_personnes"][$_POST["hebergements"]]. " personne";
                if ($_POST["nb_personnes"][$_POST["hebergements"]]>1){echo "s";}
                echo "</p>";
            if (isset($_POST["activites"])) {
                if(count($_POST["activites"])>1){
                    echo " <p> Activités sélectionnées : </p><ul>";
                }
                else{
                    echo "<p> Activité sélectionnée : </p><ul>";
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
            ?>
    <h3> Total : 1456789.99€</h3>
    <form action='https://www.plateforme-smc.fr/cybank/index.php' method='POST'>
    <input type='hidden' name='transaction' value='<?php echo $transaction; ?>'>
    <input type='hidden' name='montant' value='<?php echo $montant; ?>'>
    <input type='hidden' name='vendeur' value='<?php echo $vendeur; ?>'>
    <input type='hidden' name='retour' value='<?php echo $retour; ?>'>
    <input type='hidden' name='control' value='<?php echo $control; ?>'>
    <input type='submit' value="Valider et payer">
</body>
</html>