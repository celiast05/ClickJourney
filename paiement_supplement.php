<?php
include 'session.php';

if (!isset($_POST['fichier'])) die("Fichier manquant.");

$fichier = "json/achat_voyages/" . $_POST['fichier'];
$reservations = json_decode(file_get_contents($fichier), true);
$voyageData = &$reservations[0];
$ancienMontant = $voyageData['montant'];
$tripId = $voyageData['voyage']['id'];

$tripData = json_decode(file_get_contents("json/voyage.json"), true);
$trip = null;
foreach ($tripData as $t) {
    if ($t['id'] === $tripId) {
        $trip = $t;
        break;
    }
}

if (!$trip) {
    die("Données du voyage introuvables.");
}

// Dates
$date_depart = $_POST['date_depart'];
$date_retour = $_POST['date_retour'];
$start = new DateTime($date_depart);
$end = new DateTime($date_retour);
$interval = $start->diff($end);
$nbNuits = max(1, $interval->days);

// Hébergement
$hebergement = $_POST['hebergements'];
$activites = $_POST['activites'] ?? [];

$nombre_personnes = [];
foreach ($_POST['nb_personnes'] as $cle => $val) {
    $nombre_personnes[$cle] = max(1, min(10, (int)$val));
}

$nb_pers_hebergement = $nombre_personnes[$hebergement];
$prix_hebergement = $trip['hebergements'][$hebergement] * $nb_pers_hebergement * $nbNuits;

$montant = ($trip['prix'] * $nb_pers_hebergement) + $prix_hebergement;
$details = [];
$details[] = "Hébergement : $hebergement ($nb_pers_hebergement pers.) - $prix_hebergement €";

// Activités
foreach ($activites as $act) {
    if (isset($trip['activites'][$act])) {
        $nb_pers_act = $nombre_personnes[$act] ?? 1;
        $prix_act = $trip['activites'][$act] * $nb_pers_act;
        $montant += $prix_act;
        $details[] = "Activité : $act ($nb_pers_act pers.) - $prix_act €";
    }
}

// Comparaison avec montant déjà payé
$supplement = $montant - $ancienMontant;

// Mise à jour des données
$voyageData['voyage'] = [
    'id' => $trip['id'],
    'nom' => $trip['nom'],
    'hebergement' => $hebergement,
    'activites' => $activites,
    'nombre_personnes' => $nombre_personnes,
    'date_depart' => $date_depart,
    'date_retour' => $date_retour,
    'montant' => $montant,
    'frais_reservation' => $trip['prix'],
    'details' => $details
];

// Si aucun supplément : on applique directement la modification
if ($supplement <= 0) {
    $voyageData['modifie'] = true;
    file_put_contents($fichier, json_encode($reservations, JSON_PRETTY_PRINT));
    header("Location: profil.php?modification=ok");
    exit();
}

// Paiement du supplément
$transaction = uniqid();
$vendeur = "MEF-2_G";
include("getapikey.php");
$api_key = getAPIKey($vendeur);
$retour = "http://localhost:8888/ClickJourney/retour_supplement.php";
$control = md5($api_key . "#" . $transaction . "#" . $supplement . "#" . $vendeur . "#" . $retour . "#");

// Sauvegarde temporaire
file_put_contents("tmp/modif_" . $transaction . ".json", json_encode([
    'fichier' => $_POST['fichier'],
    'nouveau_montant' => $montant,
    'voyage_data' => $voyageData['voyage'],
    'transaction' => $transaction
]));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement du supplément</title>
</head>
<body>
    <h2>Un supplément de <?= $supplement ?> € est requis pour appliquer votre modification.</h2>

    <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
        <input type="hidden" name="transaction" value="<?= $transaction ?>">
        <input type="hidden" name="montant" value="<?= $supplement ?>">
        <input type="hidden" name="vendeur" value="<?= $vendeur ?>">
        <input type="hidden" name="retour" value="<?= $retour ?>">
        <input type="hidden" name="control" value="<?= $control ?>">
        <button type="submit">Payer le supplément</button>
    </form>
</body>
</html>
