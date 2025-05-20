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
    <title>Modifier voyage acheté</title>
    <link rel="stylesheet" href="css/details.css">
</head>
<body>
    <h2>Modifier votre voyage payé : <?= htmlspecialchars($trip['nom']) ?></h2>

    <form action="paiement_supplement.php" method="POST">
        <input type="hidden" name="fichier" value="<?= htmlspecialchars($_GET['fichier']) ?>">

        <label>Date de départ :
            <input type="date" name="date_depart" value="<?= $voyageActuel['date_depart'] ?>" required>
        </label><br>

        <label>Date de retour :
            <input type="date" name="date_retour" value="<?= $voyageActuel['date_retour'] ?>" required>
        </label><br><br>

        <h4>Hébergements</h4>
        <?php foreach ($trip['hebergements'] as $hebergement => $prix):
            $checked = ($voyageActuel['hebergement'] === $hebergement);
            $nbPers = $voyageActuel['nombre_personnes'][$hebergement] ?? 1;
        ?>
            <label>
                <input type="radio" name="hebergements" value="<?= htmlspecialchars($hebergement) ?>" onclick="toggleInput()" <?= $checked ? 'checked' : '' ?>>
                <?= htmlspecialchars($hebergement) ?> : <?= $prix ?> € 
                <input type="number" name="nb_personnes[<?= htmlspecialchars($hebergement) ?>]" value="<?= $nbPers ?>" min="1" max="10" <?= !$checked ? 'disabled' : '' ?>>
            </label><br>
        <?php endforeach; ?>

        <?php if (!empty($trip['activites'])): ?>
            <h4>Activités</h4>
            <?php foreach ($trip['activites'] as $activite => $prix):
                $isSelected = in_array($activite, $voyageActuel['activites']);
                $nbPers = $voyageActuel['nombre_personnes'][$activite] ?? 1;
            ?>
                <label>
                    <input type="checkbox" name="activites[]" value="<?= htmlspecialchars($activite) ?>" onclick="toggleInput()" <?= $isSelected ? 'checked' : '' ?>>
                    <?= htmlspecialchars($activite) ?> : <?= $prix ?> €
                    <input type="number" name="nb_personnes[<?= htmlspecialchars($activite) ?>]" value="<?= $nbPers ?>" min="1" max="10" <?= !$isSelected ? 'disabled' : '' ?>>
                </label><br>
            <?php endforeach; ?>
        <?php endif; ?>

        <p><strong>Modification gratuite si aucune augmentation.</strong></p>
        <button type="submit">Valider la modification</button>
    </form>

    <script>
    function toggleInput() {
        const radios = document.querySelectorAll('input[type="radio"]');
        radios.forEach(radio => {
            const input = radio.parentNode.querySelector('input[type="number"]');
            if (input) {
                input.disabled = !radio.checked;
            }
        });

        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            const input = checkbox.parentNode.querySelector('input[type="number"]');
            if (input) {
                input.disabled = !checkbox.checked;
            }
        });
    }

    window.onload = toggleInput;
    </script>
</body>
</html>
