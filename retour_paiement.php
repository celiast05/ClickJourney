<?php
session_start();

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/retour_paiement.css">
    <link id="theme-link" rel="stylesheet" href="css/themes/theme_light.css">
    <title>Retour de paiement</title>
</head>
<body>
<div class='container'>
    <h2>Récapitulatif du paiement</h2>

    <p><strong>Numéro de transaction :</strong> <?php echo htmlspecialchars($transaction); ?></p>
    <p><strong>Montant :</strong> <?php echo htmlspecialchars($montant); ?> €</p>
    <p><strong>Statut du paiement :</strong> <?php echo ($statut === 'accepted' ? 'Paiement accepté' : 'Paiement refusé'); ?></p>
</div>
    <?php
    if ($statut === 'accepted') {
        if (!empty($_SESSION['voyage']) && !empty($_SESSION['user'])) {
            $voyage = $_SESSION['voyage'];
            $user = $_SESSION['user'];
            $hebergement = $voyage['hebergement'] ?? "Non spécifié";
            $activites = !empty($voyage['activites']) ? $voyage['activites'] : ["Aucune activité sélectionnée"];
            $nb_personnes = $voyage['nombre_personnes'][$hebergement] ?? "Non spécifié";
            $date_depart = $voyage['date_depart'] ?? "Non spécifié";
            $date_retour = $voyage['date_retour'] ?? "Non spécifié";

            // Création du nom de fichier JSON
            //$fileName = 'json/' . strtolower(str_replace(' ', '_', $voyage['nom'])) . '_' . strtolower(str_replace(' ', '_', $user['nom'])) . '.json';


//test
$nom_voyage = $voyage['nom'] ?? "voyage_inconnu";
$nom_user = $user['nom'] ?? "utilisateur_inconnu";

$fileName = 'json/' . strtolower(str_replace(' ', '_', $nom_voyage)) . '_' . strtolower(str_replace(' ', '_', $nom_user)) . '.json';


//fin test



            // Données à enregistrer
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

            // Vérifier si un fichier existe déjà pour cet utilisateur et ce voyage
            if (file_exists($fileName)) {
                $existingData = json_decode(file_get_contents($fileName), true);
                $existingData[] = $data;
                file_put_contents($fileName, json_encode($existingData, JSON_PRETTY_PRINT));
            } else {
                file_put_contents($fileName, json_encode([$data], JSON_PRETTY_PRINT));
            }

            echo "<div class='container><p><strong>Préparer vos bagages, vous allez vous envoler vers votre destination très bientôt ...</strong></p></div>";
        }
    } 
    else {
        echo "<div class='container><h2>Paiement accepté</h2></div>";
    }
    header("refresh:5;url=profil.php");
    ?>
<script src="js/theme.js"></script>
</body>
</html>
