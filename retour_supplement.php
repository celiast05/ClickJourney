<?php
include 'session.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: connexion.php");
    exit();
}

include("getapikey.php");

if (!isset($_GET['transaction'], $_GET['montant'], $_GET['vendeur'], $_GET['status'], $_GET['control'])) {
    die("Données manquantes dans la réponse de Cybank.");
}

$transaction = $_GET['transaction'];
$montant = $_GET['montant'];
$vendeur = $_GET['vendeur'];
$statut = $_GET['status'];
$control = $_GET['control'];

$api_key = getAPIKey($vendeur);
$control_calculé = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $statut . "#");

if ($control !== $control_calculé) {
    die("Erreur de validation du contrôle. Les données ont été falsifiées.");
}

$transaction = $_GET['transaction'] ?? '';
$fileTmp = "tmp/modif_" . $transaction . ".json";

if (!file_exists($fileTmp)) {
    die("Aucune donnée de modification trouvée pour cette transaction.");
}

$modif = json_decode(file_get_contents($fileTmp), true);
unlink($fileTmp); // on supprime après lecture

// OK, on applique la modification
$fichier = "json/achat_voyages/" . $modif['fichier'];

if (!file_exists($fichier)) {
    die("Erreur : le fichier de réservation est introuvable.");
}

$reservations = json_decode(file_get_contents($fichier), true);
$voyageData = &$reservations[0];

$voyageData['voyage'] = $modif['voyage_data'];
$voyageData['montant'] = $modif['nouveau_montant'];
$voyageData['modifie'] = true;
$voyageData['supplement_paye'] = true;
$voyageData['transaction_supplement'] = $transaction;

file_put_contents($fichier, json_encode($reservations, JSON_PRETTY_PRINT));

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modification enregistrée</title>
    <link rel="stylesheet" href="css/themes/theme_light.css">
</head>
<body>
    <div class="container">
        <h2>Modification enregistrée avec succès !</h2>
        <p>Le supplément a été payé et les changements sont maintenant actifs.</p>
        <a href="profil.php">Retour au profil</a>
    </div>
</body>
</html>
