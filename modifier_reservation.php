<?php
include 'session.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: connexion.php");
    exit();
}

if (!isset($_GET['index']) || !isset($_SESSION['panier'][$_GET['index']])) {
    die("Voyage introuvable dans le panier.");
}

$index = (int)$_GET['index'];
$voyage = $_SESSION['panier'][$index];

$trips = json_decode(file_get_contents('json/voyage.json'), true);
$trip = null;
foreach ($trips as $t) {
    if ($t['id'] === $voyage['id']) {
        $trip = $t;
        break;
    }
}

if (!$trip) {
    die("Voyage non trouvé dans la base.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier <?= htmlspecialchars($trip['nom']) ?></title>
    <link rel="stylesheet" href="css/details.css">
    <link id="theme-link" rel="stylesheet" href="css/themes/theme_light.css">
</head>
<body>
<?php include 'nav.php'; ?>

<h2>Modifier votre voyage : <?= htmlspecialchars($trip['nom']) ?></h2>
<div class="container">
<div class="image"><img src="<?= $trip['image'] ?>" alt="Image de <?= $trip['nom'] ?>"></div>
<section>
<form id="reservation-form" action="modifier_traitement.php" method="POST">
    <input type="hidden" name="index" value="<?= $index ?>">

    <h4>Hébergements</h4>
    <div class="radio-group">
    <?php foreach ($trip['hebergements'] as $hebergement => $prix):
        $checked = ($voyage['hebergement'] === $hebergement);
        $nbPers = $voyage['nombre_personnes'][$hebergement] ?? 1;
        ?>
        <label>
            <input type="radio" name="hebergements" value="<?= htmlspecialchars($hebergement) ?>" onclick="toggleInput()" <?= $checked ? 'checked' : '' ?>>
            <p><?= $hebergement ?> : <?= $prix ?> €</p>
            &emsp;
            <input type="number" name="nb_personnes[<?= htmlspecialchars($hebergement) ?>]" value="<?= $nbPers ?>" min="1" max="10" <?= !$checked ? 'disabled' : '' ?>>
        </label>
    <?php endforeach; ?>
    </div>

    <?php if (!empty($trip['activites'])): ?>
    <h4>Activités</h4>
    <div class="checkbox-group">
        <?php foreach ($trip['activites'] as $activite => $prix):
            $isSelected = in_array($activite, $voyage['activites']);
            $nbPers = $voyage['nombre_personnes'][$activite] ?? 1;
            ?>
            <label>
                <input type="checkbox" name="activites[]" value="<?= htmlspecialchars($activite) ?>" onclick="toggleInput()" <?= $isSelected ? 'checked' : '' ?>>
                <p><?= $activite ?> : <?= $prix ?> €</p>
                &emsp;
                <input type="number" name="nb_personnes[<?= htmlspecialchars($activite) ?>]" value="<?= $nbPers ?>" min="1" max="10" <?= !$isSelected ? 'disabled' : '' ?>>
            </label>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <label for="date_depart">Date de départ :
        <input type="date" id="date_depart" name="date_depart" value="<?= $voyage['date_depart'] ?>" required>
    </label>
    <label for="date_retour">Date de retour :
        <input type="date" id="date_retour" name="date_retour" value="<?= $voyage['date_retour'] ?>" required>
    </label>

    <p><strong>Prix estimé : </strong><span id="estimation">0</span> €</p>
    <input type="hidden" id="nouveau_montant" name="nouveau_montant" value="0">
    <button type="submit">Valider les modifications</button>
</form>
</section>
</div>

<script id="prix-data" type="application/json">
{
  "basePrix": <?= $trip['prix'] ?>,
  "hebergements": <?= json_encode($trip['hebergements']) ?>,
  "activites": <?= json_encode($trip['activites']) ?>
}
</script>
<script src="js/theme.js"></script>
<script src="js/panier.js"></script>
<script>
function toggleInput() {
    const selectedInputs = document.querySelectorAll('input[type="radio"]:checked, input[type="checkbox"]:checked');
    const uncheckedInputs = document.querySelectorAll('input[type="radio"]:not(:checked), input[type="checkbox"]:not(:checked)');

    const allNumberInputs = document.querySelectorAll('input[type="number"]');
    allNumberInputs.forEach(input => input.disabled = false);

    uncheckedInputs.forEach(input => {
        const numberInput = input.parentNode.querySelector('input[type="number"]');
        if (numberInput) {
            numberInput.disabled = true;
            numberInput.value = "1";
        }
    });
}
</script>
</body>
</html>
