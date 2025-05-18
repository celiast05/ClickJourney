<?php
include 'session.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: connexion.php");
    exit();
}

if (!isset($_GET['voyage']) || empty($_GET['voyage'])) {
    die("Voyage non spécifié.");
}

$trips = json_decode(file_get_contents('json/voyage.json'), true);

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

$erreurs = [];

if (!isset($_POST['date_depart']) || !isset($_POST['date_retour'])) {
    $erreurs[] = "Les dates de voyage sont obligatoires.";
} else {
    $date_depart = new DateTime($_POST['date_depart']);
    $date_retour = new DateTime($_POST['date_retour']);
    $today = new DateTime();
    $today->setTime(0, 0, 0); // ignore l'heure

    if ($date_depart < $today) {
        $erreurs[] = "La date de départ ne peut pas être dans le passé.";
    }

    if ($date_retour < $date_depart) {
        $erreurs[] = "La date de retour ne peut pas être avant la date de départ.";
    }

    $interval = $date_depart->diff($date_retour);
    $nombre_nuits = $interval->days;
}


$hebergement = $_POST["hebergements"];
$nb_personnes_hebergement = (int) $_POST["nb_personnes"][$hebergement];
// Vérification : aucune activité ne doit dépasser le nombre de personnes de l'hébergement


if (isset($_POST["activites"])) {
    foreach ($_POST["activites"] as $activite) {
        $nb_pers_activite = (int) ($_POST["nb_personnes"][$activite] ?? 0);
        if ($nb_pers_activite > $nb_personnes_hebergement) {
            $erreurs[] = "$nb_pers_activite personne(s) pour l’activité « $activite », mais seulement $nb_personnes_hebergement prévues pour l’hébergement.";
        }
    }
}
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
            <a href="javascript:history.back()">⬅ Retour à la réservation</a>
        </div>
    </body>
    </html>';
    exit;
}

        



$prix_hebergement = $trip["hebergements"][$hebergement] * $nb_personnes_hebergement * $nombre_nuits;

$montant = ($trip['prix'] * $nb_personnes_hebergement) + $prix_hebergement;

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

// Vérifie si ce voyage existe déjà dans le panier
$existeDeja = false;
foreach ($_SESSION['panier'] as $item) {
    if (
        $item['id'] === $trip['id'] &&
        $item['date_depart'] === $date_depart &&
        $item['date_retour'] === $date_retour &&
        $item['hebergement'] === $hebergement
    ) {
        $existeDeja = true;
        break;
    }
}

if (!$existeDeja) {
    $_SESSION['panier'][] = [
        'id' => $trip['id'],
        'nom' => $trip['nom'],
        'hebergement' => $hebergement,
        'activites' => $_POST['activites'] ?? [],
        'nombre_personnes' => $_POST['nb_personnes'],
        'date_depart' => $date_depart->format('Y-m-d'),
        'date_retour' => $date_retour->format('Y-m-d'),
        'montant' => $montant,
        'details' => $details_options
    ];
}

header("Location: mon_panier.php");
exit();
?>