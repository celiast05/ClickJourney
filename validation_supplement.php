<?php
include 'session.php';

if (!isset($_SESSION['modification_en_attente']) || !isset($_GET['supplement'])) {
    header("Location: profil.php");
    exit();
}

include("getapikey.php");

$supplement = floatval($_GET['supplement']);
$formData = $_SESSION['modification_en_attente'];

$transaction = uniqid("modif_");
$vendeur = "MEF-2_G";
$api_key = getAPIKey($vendeur);
$retour = "http://localhost:8888/ClickJourney/retour_modification.php";

$control = md5($api_key . "#" . $transaction . "#" . $supplement . "#" . $vendeur . "#" . $retour . "#");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement du supplément</title>
    <link rel="stylesheet" href="css/panier.css">
    <link id="theme-link" rel="stylesheet" href="css/themes/theme-light.css">
</head>
<body>
    <h1>Paiement du supplément</h1>

    <div style="border:1px solid #ccc; padding:10px; margin-bottom:15px;">
        <p>Suite à votre modification, un supplément est à régler.</p>
        <strong>Montant du supplément : <?= number_format($supplement, 2, ',', ' ') ?> €</strong>
    </div>

    <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
        <input type="hidden" name="transaction" value="<?= $transaction ?>">
        <input type="hidden" name="montant" value="<?= $supplement ?>">
        <input type="hidden" name="vendeur" value="<?= $vendeur ?>">
        <input type="hidden" name="retour" value="<?= $retour ?>">
        <input type="hidden" name="control" value="<?= $control ?>">
        <button type="submit">Payer le supplément</button>
    </form>

    <a href="profil.php" class="continuer-recherches">Annuler la modification</a>

    <?php include 'footer.php'; ?>
    <script src="js/theme.js"></script>
</body>
</html>
