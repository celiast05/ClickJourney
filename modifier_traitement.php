<?php
include 'session.php';
require_once 'erreur_voyage.php';

if (!isset($_SESSION['logged_in']) || !isset($_POST['index'])) {
    header("Location: mon_panier.php");
    exit();
}

$index = (int)$_POST['index'];

if (!isset($_SESSION['panier'][$index])) {
    die("Voyage introuvable dans le panier.");
}

$voyageActuel = $_SESSION['panier'][$index];

// Charger les données du voyage
$tripData = json_decode(file_get_contents("json/voyage.json"), true);
$trip = null;
foreach ($tripData as $t) {
    if ($t['id'] === $voyageActuel['id']) {
        $trip = $t;
        break;
    }
}

if (!$trip) {
    die("Données du voyage introuvables.");
}

$erreurs = validerFormulaireReservation($trip, $_POST);

// Validation des données
if (!empty($erreurs)) {
    echo '<!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Erreur de réservation</title>
        <link rel="stylesheet" href="css/themes/theme_light.css">
        <link rel="stylesheet" href="css/erreur.css">
    </head>
    <body>
        <div class="error-box">
            <h2>Erreur(s) de réservation :</h2>
            <ul>';
                foreach ($erreurs as $err) {
                    echo '<li>' . htmlspecialchars($err) . '</li>';
                }
    echo '</ul>
            <a href="javascript:history.back()" class="continuer-recherches">Retour à la réservation</a>
        </div>
    </body>
    </html>';
    exit;
}

// Traitement des données
$date_depart = new DateTime($_POST['date_depart']);
$date_retour = new DateTime($_POST['date_retour']);
$interval = $date_depart->diff($date_retour);
$nombre_nuits = $interval->days;

$hebergement = $_POST['hebergements'];
$activites = $_POST['activites'] ?? [];

$nombre_personnes = [];
foreach ($_POST['nb_personnes'] as $cle => $val) {
    $nombre_personnes[$cle] = max(1, min(10, (int)$val));
}

$nb_pers_hebergement = $nombre_personnes[$hebergement];
$prix_hebergement = $trip['hebergements'][$hebergement] * $nb_pers_hebergement * $nombre_nuits;

$montant = ($trip['prix'] * $nb_pers_hebergement) + $prix_hebergement;

// Détails des options
$details = [];
$details[] = "Hébergement : $hebergement ($nb_pers_hebergement pers.) - $prix_hebergement €";

foreach ($activites as $act) {
    if (isset($trip['activites'][$act])) {
        $nb_pers_act = $nombre_personnes[$act] ?? 1;
        $prix_act = $trip['activites'][$act] * $nb_pers_act;
        $montant += $prix_act;
        $details[] = "Activité : $act ($nb_pers_act pers.) - $prix_act €";
    }
}

// Réduction VIP
if (isset($_SESSION['role']) && $_SESSION['role'] === 'vip') {
    $montant *= 0.8;
    $details[] = "Réduction VIP -20%";
}

// Mise à jour du panier
$_SESSION['panier'][$index] = [
    'id' => $trip['id'],
    'nom' => $trip['nom'],
    'hebergement' => $hebergement,
    'activites' => $activites,
    'nombre_personnes' => $nombre_personnes,
    'date_depart' => $date_depart->format('Y-m-d'),
    'date_retour' => $date_retour->format('Y-m-d'),
    'montant' => $montant,
    'frais_reservation' => $trip['prix'],
    'details' => $details
];

header("Location: mon_panier.php?modifie=1");
exit();
?>