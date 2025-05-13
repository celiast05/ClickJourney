<?php
session_start();

if (!isset($_SESSION['logged_in']) || empty($_SESSION['panier'])) {
    header("Location: mon_panier.php");
    exit();
}

if (isset($_SESSION['role']) && $_SESSION['role'] === 'banni') { // détection d'utilisateur banni
  header("Location: script/deconnexion.php?action=run");
  exit();
}

// Calcul du total
$total = 0;
foreach ($_SESSION['panier'] as $voyage) {
    $total += $voyage['montant'];
}

// Préparation des données pour un paiement unique
$transaction = uniqid();
$vendeur = "MEF-2_G";
require_once("getapikey.php");
$api_key = getAPIKey($vendeur);
$retour = "http://localhost:8888/ClickJourney/retour_paiement.php"; // À adapter selon ton environnement
$control = md5($api_key . "#" . $transaction . "#" . $total . "#" . $vendeur . "#" . $retour . "#");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Validation du paiement</title>
  <link rel="stylesheet" href="css/panier.css">
  <link id="theme-link" rel="stylesheet" href="css/themes/theme-light.css">
</head>
<body>
  <h1>Récapitulatif avant paiement</h1>

  <?php foreach ($_SESSION['panier'] as $voyage): ?>
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:15px;">
      <h3><?= htmlspecialchars($voyage['nom']) ?></h3>
      <p>Dates : <?= $voyage['date_depart'] ?> → <?= $voyage['date_retour'] ?></p>
      <ul>
        <?php foreach ($voyage['details'] as $detail): ?>
          <li><?= $detail ?></li>
        <?php endforeach; ?>
      </ul>
      <strong>Total : <?= $voyage['montant'] ?> €</strong>
    </div>
  <?php endforeach; ?>

  <h2>Total global à payer : <?= $total ?> €</h2>

  <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
    <input type="hidden" name="transaction" value="<?= $transaction ?>">
    <input type="hidden" name="montant" value="<?= $total ?>">
    <input type="hidden" name="vendeur" value="<?= $vendeur ?>">
    <input type="hidden" name="retour" value="<?= $retour ?>">
    <input type="hidden" name="control" value="<?= $control ?>">
    <button type="submit">Payer maintenant</button>
  </form>

  <a href="mon_panier.php" class="continuer-recherches">Retour au panier</a>
<div id="foot"></div>

      <script>
      fetch('/footer.html')
        .then(res => res.text())
        .then(html => {
          document.getElementById('foot').innerHTML = html;
        });
    </script>
  <script src="js/theme.js"></script>
</body>
</html>
