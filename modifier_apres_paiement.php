<?php
include 'session.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: connexion.php");
    exit();
}

if (!isset($_GET['fichier']) || !file_exists("json/achat_voyages/" . $_GET['fichier'])) {
    die("Réservation introuvable.");
}

$fichier = "json/achat_voyages/" . $_GET['fichier'];
$reservations = json_decode(file_get_contents($fichier), true);
$voyageData = &$reservations[0];

$tripId = $voyageData['voyage']['id'];
$tripData = json_decode(file_get_contents("json/voyage.json"), true);

$trip = null;
foreach ($tripData as $t) {
    if ($t['id'] === $tripId) {
        $trip = $t;
        break;
    }
}

if (!$trip) {
    die("Données du voyage non trouvées.");
}

$voyageActuel = $voyageData['voyage'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier <?= htmlspecialchars($trip['nom']) ?></title>
    <link rel="stylesheet" href="css/details.css?v=1.7">
    <link id="theme-link" rel="stylesheet" href="css/themes/theme_light.css">
</head>
<body>
<?php include 'nav.php'; ?>

<h2>Modifier votre voyage : <?= htmlspecialchars($trip['nom']) ?></h2>
<div class="container">
    <div class="image"><img src="<?= $trip['image'] ?>" alt="Image de <?= $trip['nom'] ?>"></div>
    <section>
    <form id="reservation-form" action="paiement_supplement.php" method="POST">
        <input type="hidden" name="fichier" value="<?= htmlspecialchars($_GET['fichier']) ?>">

        <label for="date_depart" class="header"> Date de départ : &emsp;
            <input type="date" id="date_depart" name="date_depart" value="<?= $voyageActuel['date_depart'] ?>" required>
        </label>
        <br>
        <label for="date_retour" class="header"> Date de retour : &emsp;
            <input type="date" id="date_retour" name="date_retour" value="<?= $voyageActuel['date_retour'] ?>" required>
        </label>
        <br><br>

        <?php if (!empty($trip["hebergements"])): ?>
            <h4>Hébergements</h4>
            <div class="radio-group">
            <?php foreach ($trip['hebergements'] as $hebergement => $prix):
                $checked = ($voyageActuel['hebergement'] === $hebergement);
                $nbPers = $voyageActuel['nombre_personnes'][$hebergement] ?? 1;
            ?>
                <label>
                    <input type="radio" name="hebergements" value="<?= htmlspecialchars($hebergement) ?>" onclick="toggleInput()" <?= $checked ? 'checked' : '' ?>>
                    <p><?= $hebergement ?> : <?= $prix ?> €</p>
                    &emsp;
                    <input type="number" name="nb_personnes[<?= htmlspecialchars($hebergement) ?>]" value="<?= $nbPers ?>" min="1" max="10" <?= !$checked ? 'disabled' : '' ?>> <span style="font-size: 20px"> personne(s)</span>
                </label>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($trip["activites"])): ?>
            <h4>Activités</h4>
            <div class="checkbox-group">
            <?php foreach ($trip['activites'] as $activite => $prix):
                $isSelected = in_array($activite, $voyageActuel['activites']);
                $nbPers = $voyageActuel['nombre_personnes'][$activite] ?? 1;
            ?>
                <label>
                    <input type="checkbox" name="activites[]" value="<?= htmlspecialchars($activite) ?>" onclick="toggleInput()" <?= $isSelected ? 'checked' : '' ?>>
                    <p><?= $activite ?> : <?= $prix ?> €</p>
                    &emsp;
                    <input type="number" name="nb_personnes[<?= htmlspecialchars($activite) ?>]" value="<?= $nbPers ?>" min="1" max="10" <?= !$isSelected ? 'disabled' : '' ?>> <span style="font-size: 20px"> personne(s)</span>
                </label>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <br>
        <p><strong>Modification gratuite si aucune augmentation.</strong></p>
        <p><strong>Prix estimé : </strong><span id="estimation">0</span> € 
        <?php if(isset($_SESSION['role']) && $_SESSION['role']=='vip'){echo 'avec la réduction <span style="color: #a58a60; font-weight: bold;">VIP (-20%)</span>'; } ?></p>
        <button type="submit">Valider la modification</button>
    </form>
    </section>
</div>

<script>
function toggleInput() {
    const radios = document.querySelectorAll('input[type="radio"]');
    radios.forEach(radio => {
        const input = radio.parentNode.querySelector('input[type="number"]');
        if (input) input.disabled = !radio.checked;
    });

    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        const input = checkbox.parentNode.querySelector('input[type="number"]');
        if (input) input.disabled = !checkbox.checked;
    });
}

window.onload = toggleInput;

// Nettoyage des champs non sélectionnés
document.getElementById("reservation-form").addEventListener("submit", function () {
    document.querySelectorAll('input[type="number"]').forEach(input => {
        const label = input.closest("label");
        const checkbox = label.querySelector('input[type="checkbox"]');
        const radio = label.querySelector('input[type="radio"]');

        if (radio && !radio.checked) {
            input.removeAttribute("name");
        }

        if (checkbox && !checkbox.checked) {
            input.removeAttribute("name");
        }

        if ((checkbox && checkbox.checked) || (radio && radio.checked)) {
            const labelText = label.querySelector("p")?.innerText?.split(":")[0]?.trim();
            if (labelText) {
                input.name = `nb_personnes[${labelText}]`;
            }
        }
    });
});
</script>

<script id="prix-data" type="application/json">
{
  "basePrix": <?= $trip['prix'] ?>,
  "hebergements": <?= json_encode($trip['hebergements']) ?>,
  "activites": <?= json_encode($trip['activites']) ?>,
  "role": <?= json_encode($_SESSION['role']) ?>
}
</script>

<?php include 'footer.php'; ?>

<script src="js/theme.js"></script>

</body>
</html>
