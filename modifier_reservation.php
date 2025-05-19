<?php
include 'session.php';

if (!isset($_GET['transaction'])) {
    die("Référence de réservation manquante.");
}

$transactionId = $_GET['transaction'];
$reservation = null;

// Recherche de la réservation dans les fichiers JSON
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

$voyageReserve = $reservation['voyage'];

// On récupère les détails complets depuis voyage.json pour les prix
$trips = json_decode(file_get_contents('json/voyage.json'), true);
$trip = null;
foreach ($trips as $t) {
    if ($t['id'] === $voyageReserve['id']) {
        $trip = $t;
        break;
    }
}

if (!$trip) {
    die("Voyage non trouvé dans la base de données.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier <?= htmlspecialchars($trip["nom"]) ?></title>
    <link rel="stylesheet" href="css/details.css">
    <link id="theme-link" rel="stylesheet" href="css/themes/theme_light.css">
</head>
<body>
<?php include 'nav.php'; ?>

<h2>Modifier votre réservation pour <?= $trip['nom'] ?></h2>
<div class="container">
<div class="image"><img src="<?= $trip['image'] ?>" alt="Image de <?= $trip['nom'] ?>"/></div>
<section>
<form id="reservation-form" action="modifier_traitement.php" method="POST">

    <?php if (!empty($trip['description'])) echo '<p>' . $trip['description'] . '</p>'; ?>

    <h4>Hébergements</h4>
    <div class="radio-group">
    <?php foreach ($trip['hebergements'] as $hebergement => $prix):
        $checked = ($voyageReserve['hebergement'] === $hebergement);
        ?>
        <label>
            <input type="radio" name="hebergements" value="<?= htmlspecialchars($hebergement) ?>" onclick="toggleInput()" <?= $checked ? 'checked' : '' ?>>
            <p><?= $hebergement ?> : <?= $prix ?> €</p>
            &emsp; <input type="number" name="nb_personnes[<?= htmlspecialchars($hebergement) ?>]" value="<?= $voyageReserve['nombre_personnes'][$hebergement] ?? 1 ?>" min="1" max="10" <?= !$checked ? 'disabled' : '' ?>>
        </label>
    <?php endforeach; ?>
    </div>

    <?php if (!empty($trip['activites'])): ?>
        <h4>Activités</h4>
        <div class="checkbox-group">
        <?php foreach ($trip['activites'] as $activity => $prix):
            $isSelected = in_array($activity, $voyageReserve['activites']);
            ?>
            <label>
                <input type="checkbox" name="activites[]" value="<?= htmlspecialchars($activity) ?>" onclick="toggleInput()" <?= $isSelected ? 'checked' : '' ?>>
                <p><?= $activity ?> : <?= $prix ?> €</p>
                &emsp; <input type="number" name="nb_personnes[<?= htmlspecialchars($activity) ?>]" value="<?= $voyageReserve['nombre_personnes'][$activity] ?? 1 ?>" min="1" max="10" <?= !$isSelected ? 'disabled' : '' ?>>
            </label>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <br>
    <label for="date_depart" class="header">Date de départ :
        <input type="date" id="date_depart" name="date_depart" value="<?= $voyageReserve['date_depart'] ?>" required>
    </label>
    <br>
    <label for="date_retour" class="header">Date de retour :
        <input type="date" id="date_retour" name="date_retour" value="<?= $voyageReserve['date_retour'] ?>" required>
    </label>

    <br><br>
    <p><strong>Prix estimé : </strong><span id="estimation">0</span> €</p>
    <input type="hidden" name="montant_initial" value="<?= htmlspecialchars($reservation['montant']) ?>">
    <input type="hidden" name="voyage_id" value="<?= htmlspecialchars($trip['id']) ?>">
    <input type="hidden" name="transaction_id" value="<?= htmlspecialchars($transactionId) ?>">
    <input type="hidden" id="nouveau_montant" name="nouveau_montant" value="0">
    <button type="submit">Valider les modifications</button>
    <pre><?php print_r($trip); ?></pre>
</form>
</section>
</div>

<?php include 'footer.php'; ?>

<script>
function toggleInput() {
        const selectedInputs = document.querySelectorAll('input[type="radio"]:checked, input[type="checkbox"]:checked');
        const uncheckedInputs = document.querySelectorAll('input[type="radio"]:not(:checked), input[type="checkbox"]:not(:checked)');

        const allNumberInputs = document.querySelectorAll('input[type="number"]');

        allNumberInputs.forEach(input => {
            input.disabled = false;
        });

        uncheckedInputs.forEach(input => {
            const numberInput = input.parentNode.querySelector('input[type="number"]');
            if (numberInput) {
                numberInput.disabled = true;
                numberInput.value = "1";
            }
        });
    }
    document.getElementById("reservation-form").addEventListener("submit", function() {
        document.querySelectorAll('input[type="number"]:disabled').forEach(input => {
            input.removeAttribute("name");
        });
    });
</script>
<script id="prix-data" type="application/json">
{
  "basePrix": <?= $trip['prix'] ?>,
  "hebergements": <?= json_encode($trip['hebergements']) ?>,
  "activites": <?= json_encode($trip['activites']) ?>
}
</script>
<script src="js/theme.js"></script>
<script src="js/panier.js"></script>
</body>
</html>
