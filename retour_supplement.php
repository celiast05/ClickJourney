<?php
include 'session.php';
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

// Appliquer la modification
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
    <title>Confirmation de modification</title>
    <link rel="stylesheet" href="css/retour_paiement.css">
    <link id="theme-link" rel="stylesheet" href="css/themes/theme_light.css">
</head>
<body>
<?php include 'nav.php'; ?>

<div class="container">
    <h2>Récapitulatif du paiement</h2>
    <p><strong>Numéro de transaction :</strong> <?= htmlspecialchars($transaction) ?></p>
    <p><strong>Montant :</strong> <?= htmlspecialchars($montant) ?> €</p>
    <p><strong>Statut du paiement :</strong> <?= ($statut === 'accepted' ? 'Paiement accepté' : 'Paiement refusé') ?></p>
</div>

<?php if ($statut === 'accepted'): ?>
    <div class="container">
        <p><strong>Supplément payé avec succès !</strong></p>
        <p>Votre réservation a été modifiée.</p>
        <a href="profil.php" class="cta">Retour à mon profil</a>
    </div>
<?php else: ?>
    <div class="container">
        <p>Le paiement du supplément a échoué. La modification n'a pas été enregistrée.</p>
        <a href="profil.php" class="cta">Retour au profil</a>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>
<script src="js/theme.js"></script>
</body>
</html>
