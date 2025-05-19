<?php
session_start();

if (!isset($_POST['transaction_id'], $_POST['montant_initial'], $_POST['nouveau_montant'])) {
    die("Données incomplètes.");
}

$transactionId = $_POST['transaction_id'];
$montantInitial = floatval($_POST['montant_initial']);
$montantNouveau = floatval($_POST['nouveau_montant']);
$supplement = $montantNouveau - $montantInitial;

if ($supplement > 0) {
    $_SESSION['modification_en_attente'] = $_POST;
    header("Location: validation_supplement.php?supplement=$supplement");
    exit();
}


// Sinon, enregistrer directement la modification
$files = glob("json/achat_voyages/*.json");
foreach ($files as $file) {
    $content = json_decode(file_get_contents($file), true);
    $trouve = false;

    foreach ($content as &$reservation) {
        if ($reservation['transaction_id'] === $transactionId) {
            $reservation['voyage']['hebergement'] = $_POST['hebergements'];
            $reservation['voyage']['date_depart'] = $_POST['date_depart'];
            $reservation['voyage']['date_retour'] = $_POST['date_retour'];
            $reservation['voyage']['activites'] = $_POST['activites'] ?? [];
            $reservation['voyage']['nombre_personnes'] = $_POST['nb_personnes'];
            $reservation['montant'] = $montantNouveau;
            $reservation['date'] = date('Y-m-d H:i:s');
            $trouve = true;
            break;
        }
    }

    if ($trouve) {
        file_put_contents($file, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        break;
    }
}

header("Location: profil.php?modification=ok");
exit();
?>
