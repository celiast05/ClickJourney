<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
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

if (!isset($_POST['date_depart']) || !isset($_POST['date_retour'])) {
    die("Les dates de voyage sont obligatoires.");
}

$date_depart = htmlspecialchars($_POST['date_depart']);
$date_retour = htmlspecialchars($_POST['date_retour']);

$hebergement = $_POST["hebergements"];
$nb_personnes_hebergement = (int) $_POST["nb_personnes"][$hebergement];
$prix_hebergement = $trip["hebergements"][$hebergement] * $nb_personnes_hebergement;

$montant = $trip['prix'] * $nb_personnes_hebergement + $prix_hebergement;

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
        'date_depart' => $date_depart,
        'date_retour' => $date_retour,
        'montant' => $montant,
        'details' => $details_options
    ];
}

header("Location: mon_panier.php");
exit();