<?php
session_start();

if (!isset($_GET['transaction'])) {
    die("Transaction inconnue.");
}

$transactionId = $_GET['transaction'];
$reservation = null;

// Parcourt tous les fichiers dans achat_voyages/
$files = glob("json/achat_voyages/*.json");

foreach ($files as $file) {
    $content = json_decode(file_get_contents($file), true);
    if (!is_array($content)) continue;

    foreach ($content as $entry) {
        if (isset($entry['transaction_id']) && $entry['transaction_id'] === $transactionId) {
            $reservation = $entry;
            $nomFichier = basename($file);
            break 2;
        }
    }
}

if (!$reservation) {
    die("Réservation introuvable.");
}

$voyage = $reservation['voyage'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail de la réservation</title>
    <link rel="stylesheet" href="css/details.css?v=1.7">
    <link id="theme-link" rel="stylesheet" href="css/themes/theme_light.css">
</head>
<body>
<?php include 'nav.php'; ?>

<h2><?= htmlspecialchars($voyage['nom']) ?> — Détail de votre réservation</h2>

<div class="container">

    <section>
        <h4>Destination</h4>
        <p><?= htmlspecialchars($voyage['nom']) ?></p>

        <h4>Dates</h4>
        <p><?= htmlspecialchars($voyage['date_depart']) ?> → <?= htmlspecialchars($voyage['date_retour']) ?></p>

        <h4>Hébergement</h4>
        <p><?= htmlspecialchars($voyage['hebergement']) ?></p>

        <h4>Activités</h4>
        <?php if (!empty($voyage['activites'])): ?>
            <ul>
                <?php foreach ($voyage['activites'] as $a): ?>
                    <li><?= htmlspecialchars($a) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucune activité sélectionnée</p>
        <?php endif; ?>

        <h4>Montant payé</h4>
        <p><?= htmlspecialchars($reservation['montant']) ?> €</p>

        <br><br>
        <a href="modifier_apres_paiement.php?fichier=<?= urlencode($nomFichier) ?>" class="envoi">Modifier cette réservation</a>
    </section>
</div>

<?php include 'footer.php'; ?>
<script src="js/theme.js"></script>
</body>
</html>
