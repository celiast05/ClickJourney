<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mon Panier</title>
  <link rel="stylesheet" href="css/panier.css">
  <link id="theme-link" rel="stylesheet" href="css/themes/theme-light.css">
</head>
<body>

<?php if (isset($_GET['added'])): ?>
  <div id="confirmation" class="alert">Voyage ajouté au panier</div>
<?php endif; ?>

  <h1>Contenu de votre panier</h1>

  <?php
  if (empty($_SESSION['panier'])) {
      echo "<p>Votre panier est vide.</p>";
  } else {
      $total = 0;

      foreach ($_SESSION['panier'] as $index => $voyage) {
          echo "<div style='border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;'>";

          echo "<h3>" . htmlspecialchars($voyage['nom']) . "</h3>";
          echo "<p><strong>Dates :</strong> " . htmlspecialchars($voyage['date_depart']) . " → " . htmlspecialchars($voyage['date_retour']) . "</p>";

          echo "<ul>";
          foreach ($voyage['details'] as $detail) {
              echo "<li>$detail</li>";
          }
          echo "</ul>";

          echo "<p><strong>Montant :</strong> " . $voyage['montant'] . " €</p>";

          // Formulaire de suppression
          echo "<form action='supp_panier.php' method='POST'>";
          echo "<input type='hidden' name='index' value='$index'>";
          echo "<button type='submit'>Supprimer ce voyage</button>";
          echo "</form>";

          echo "</div>";

          $total += $voyage['montant'];
      }

      echo "<hr><h2>Total à payer : $total €</h2>";
  }
  ?>
    <form action="validation_paiement.php" method="POST">
        <button type="submit">Valider et payer</button>
    </form>

  <a href="voyages.php">Continuer vos recherches</a>

  <script src="js/theme.js"></script>
  <script>
  const confirmation = document.getElementById('confirmation');
  if (confirmation) {
    setTimeout(() => {
      confirmation.style.display = 'none';
    }, 3000); // 3 secondes
  }
</script>
</body>
</html>
