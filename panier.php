<?php 
session_start();

if (!isset($_GET['voyage']) || empty($_GET['voyage'])) {
    die("Voyage non spécifié.");
}

$fileJson = 'json/voyage.json';
$trips = json_decode(file_get_contents($fileJson), true);

$trip = null;
foreach ($trips as $t) {
    if ($t['id'] == $_GET['voyage']) {
        $trip = $t;
        break;
    }
}

if (!$trip) {
    die("Voyage introuvable.");
}

if (!isset($_POST['date_depart']) || !isset($_POST['date_retour'])) {
    die("Les dates de voyage sont obligatoires.");
}

$date_depart = htmlspecialchars($_POST['date_depart']);
$date_retour = htmlspecialchars($_POST['date_retour']);

$montant = $trip['prix'] * $_POST["nb_personnes"][$_POST["hebergements"]];

$hebergement = $_POST["hebergements"];
$nb_personnes_hebergement = (int) $_POST["nb_personnes"][$hebergement];
$prix_hebergement = $trip["hebergements"][$hebergement] * $nb_personnes_hebergement;
$montant += $prix_hebergement;

$details_options = [];
$details_options[] = "Hébergement : " . htmlspecialchars($hebergement) . " ($nb_personnes_hebergement pers.) - $prix_hebergement €";

if (isset($_POST["activites"])) {
    foreach ($_POST["activites"] as $activite) {
        if (isset($_POST["nb_personnes"][$activite]) && isset($trip["activites"][$activite])) {
            $nb_pers = (int)$_POST["nb_personnes"][$activite];
            $prix_activite = $trip["activites"][$activite] * $nb_pers;
            $montant += $prix_activite;
            $details_options[] = "Activité : " . htmlspecialchars($activite) . " ($nb_pers pers.) - $prix_activite €";
        }
    }
}

$_SESSION['voyage'] = [
    'id' => $trip['id'],
    'nom' => $trip['nom'],
    'hebergement' => $_POST['hebergements'],
    'activites' => isset($_POST['activites']) ? $_POST['activites'] : [],
    'nombre_personnes' => $_POST['nb_personnes'],
    'date_depart' => $_POST['date_depart'],
    'date_retour' => $_POST['date_retour']
];

require_once("getapikey.php");

$transaction = uniqid();
$vendeur = "MEF-2_G";
$api_key = getAPIKey($vendeur);
$retour = "http://localhost:8888/ClickJourney/retour_paiement.php";
$control = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $retour . "#");

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/panier.css?v=1.3">
    <title>Panier</title>
</head>
<body>

    <h2>Votre panier</h2>

    <p><strong>Voyage :</strong> <?php echo htmlspecialchars($trip["nom"]); ?></p>
    <p><strong>Dates :</strong> Du <?php echo $date_depart; ?> au <?php echo $date_retour; ?></p>

    <h3>Options sélectionnées :</h3>
    <ul>
        <?php
        if (!empty($details_options)) {
            foreach ($details_options as $option) {
                echo "<li>$option</li>";
            }
        } else {
            echo "<li>Aucune option supplémentaire.</li>";
        }
        ?>
    </ul>

    <h3>Total : <?php echo $montant; ?> €</h3>

    <form action='https://www.plateforme-smc.fr/cybank/index.php' method='POST'>
        <input type='hidden' name='transaction' value='<?php echo $transaction; ?>'>
        <input type='hidden' name='montant' value='<?php echo $montant; ?>'>
        <input type='hidden' name='vendeur' value='<?php echo $vendeur; ?>'>
        <input type='hidden' name='retour' value='<?php echo $retour; ?>'>
        <input type='hidden' name='control' value='<?php echo $control; ?>'>
        <input type='submit' value="Valider et payer">
    </form>

    <p><a href='details.php?voyage=<?php echo $trip["id"]; ?>'>Modifier</a></p>

</body>
</html>
