<?php
session_start();

if (isset($_SESSION['role']) && $_SESSION['role'] === 'banni') { // détection d'utilisateur banni
    header("Location: script/deconnexion.php?action=run");
    exit();
  }

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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Retour de paiement</title>
    <link rel="stylesheet" href="css/retour_paiement.css">
    <link id="theme-link" rel="stylesheet" href="css/themes/theme_light.css">
</head>
<body>
<div class='container'>
    <h2>Récapitulatif du paiement</h2>

    <p><strong>Numéro de transaction :</strong> <?= htmlspecialchars($transaction); ?></p>
    <p><strong>Montant :</strong> <?= htmlspecialchars($montant); ?> €</p>
    <p><strong>Statut du paiement :</strong> <?= ($statut === 'accepted' ? 'Paiement accepté' : 'Paiement refusé'); ?></p>
</div>

<?php
if ($statut === 'accepted') {
    echo "<div class='container'><p><strong>Paiement accepté ! Préparez vos bagages...</strong></p></div>";

    // On vide le panier quoi qu’il arrive
    unset($_SESSION['panier']);

    // On tente d’enregistrer les infos si disponibles
    if (!empty($_SESSION['voyage']) && !empty($_SESSION['user'])) {
        $voyage = $_SESSION['voyage'];
        $user = $_SESSION['user'];
        $hebergement = $voyage['hebergement'] ?? "Non spécifié";
        $activites = !empty($voyage['activites']) ? $voyage['activites'] : ["Aucune activité sélectionnée"];
        $nb_personnes = $voyage['nombre_personnes'][$hebergement] ?? "Non spécifié";
        $date_depart = $voyage['date_depart'] ?? "Non spécifié";
        $date_retour = $voyage['date_retour'] ?? "Non spécifié";

        $nom_voyage = $voyage['nom'] ?? "voyage_inconnu";
        $nom_user = $user['nom'] ?? "utilisateur_inconnu";
        $fileName = 'json/' . strtolower(str_replace(' ', '_', $nom_voyage)) . '_' . strtolower(str_replace(' ', '_', $nom_user)) . '.json';

        $data = [
            'transaction' => $transaction,
            'destination' => $voyage['nom'],
            'hebergement' => $hebergement,
            'activites' => $activites,
            'nombre_personnes' => $nb_personnes,
            'montant' => $montant,
            'statut' => $statut,
            'date_depart' => $date_depart,
            'date_retour' => $date_retour
        ];

        if (file_exists($fileName)) {
            $existingData = json_decode(file_get_contents($fileName), true);
            $existingData[] = $data;
            file_put_contents($fileName, json_encode($existingData, JSON_PRETTY_PRINT));
        } else {
            file_put_contents($fileName, json_encode([$data], JSON_PRETTY_PRINT));
        }

        
        unset($_SESSION['voyage']);
    }
} else {
    echo "<div class='container'><p>Paiement refusé. Veuillez réessayer.</p></div>";
}
?>

<a href="accueil.php">Retour à l'accueil</a>

<!-- Redirection JS vers le profil -->
<script>
  setTimeout(() => {
    window.location.href = "accueil.php";
  }, 5000); // 5 secondes
</script>

<?php include 'footer.php'; ?>

<script src="js/theme.js"></script>
</body>
</html>
