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
    <link rel="stylesheet" href="css/details.css">
    <link id="theme-link" rel="stylesheet" href="css/themes/theme_light.css">
</head>
<body>
<?php include 'nav.php'; ?>

<div class="container">
    <h2>Détail de votre réservation</h2>
    <p><strong>Destination :</strong> <?= htmlspecialchars($voyage['nom']) ?></p>
    <p><strong>Dates :</strong> <?= htmlspecialchars($voyage['date_depart']) ?> → <?= htmlspecialchars($voyage['date_retour']) ?></p>
    <p><strong>Hébergement :</strong> <?= htmlspecialchars($voyage['hebergement']) ?></p>

    <?php if (!empty($voyage['activites'])): ?>
        <p><strong>Activités :</strong></p>
        <ul>
            <?php foreach ($voyage['activites'] as $a): ?>
                <li><?= htmlspecialchars($a) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p><strong>Activités :</strong> Aucune activité sélectionnée</p>
    <?php endif; ?>

    <p><strong>Montant payé :</strong> <?= htmlspecialchars($reservation['montant']) ?> €</p>

    <br>
    <a href="modifier_reservation.php?transaction=<?= urlencode($transactionId) ?>" class="cta">Modifier cette réservation</a>
</div>

<?php include 'footer.php'; ?>
<script src="js/theme.js"></script>
</body>
</html>
