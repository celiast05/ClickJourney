<?php
include 'session.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: connexion.php");
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
    if (isset($_SESSION['panier'], $_SESSION['logged_in'])) {
        $voyage = $_SESSION['panier'][0];
        $userId = isset($_SESSION['logged_in']) ? $_SESSION['logged_in'] : null;
        
        $data = [
            'user_id' => $userId,
            'transaction_id' => $transaction,
            'montant' => $montant,
            'voyage' => $voyage,
            'date' => date('Y-m-d H:i:s')
        ];

        $pays = isset($voyage['nom']) ? $voyage['nom'] : 'destination_inconnue';
        $prenom = isset($_SESSION['prenom']) ? $_SESSION['prenom'] : '';
        $nom = isset($_SESSION['nom']) ? $_SESSION['nom'] : '';
        $identite = trim($prenom . '_' . $nom);
        $identite = strtolower($identite);
        $identite = preg_replace('/[^a-z0-9_]/', '', $identite);
        $nomFichier = strtolower($identite . '_' . $pays);
        $jsonFile = "json/{$nomFichier}.json";

        // Charger les données existantes ou créer un tableau vide
        $existingData = [];
        if (file_exists($jsonFile)) {
            $jsonContent = file_get_contents($jsonFile);
            $existingData = json_decode($jsonContent, true);
            if (!is_array($existingData)) {
                $existingData = [];
            }
        }

        // Ajouter le nouveau voyage
        $existingData[] = $data;

        // Enregistrer les données mises à jour
        file_put_contents($jsonFile, json_encode($existingData, JSON_PRETTY_PRINT));
    }

    // On vide le panier quoi qu’il arrive
    unset($_SESSION['panier']);
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
