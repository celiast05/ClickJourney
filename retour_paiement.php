<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="css/retour_paiement.css"> 
<body>
    <?php
    session_start();

    include("getapikey.php");

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        //Récupération des données envoyées par CyBank
        $transaction = $_GET["transaction"] ?? "";
        $montant = $_GET["montant"] ?? "";
        $vendeur = $_GET["vendeur"] ?? "";
        $status = $_GET["status"] ?? "";
        $control_recu = $_GET["control"] ?? "";

        //Verification des données reçues
        if(empty($transaction) || empty($montant) || empty($vendeur) || empty($status) || empty($control_recu)) {
            die("Erreur : paramètres de retour invalides.");
        }

        $api_key = getAPIKey($vendeur);
        $control_attendu = md5($api_key."#".$transaction."#".$montant."#".$vendeur."#".$status."#");

        if($control_attendu !== $control_recu){
            die("Erreur : controle d'intégrité échoué.");
        }

        echo "<div class='container'>";
        if($status == "accepted"){
            echo "<h2>Paiement accepté</h2>";
            echo "<p>Transaction : $transaction</p>";
            echo "<p>Montant : $montant</p>";
            echo "<p>Préparer vos bagages, vous allez vous envoler vers votre destination très bientôt ...</p>";

            //il faut stocker le paiement dans un json
            header("refresh:5;url=profil.php");
        }
        else{
            echo "<h2>Paiement refusé</h2>";
            echo "<p>Transaction : $transaction</p>";
            echo "<p>Montant : $montant</p>";
            echo "<p>Vérifier vos informations bancaires et réessayer</p>";
            header("refresh:5;url=panier.php");
        }
        echo "</div>";
    }
    else{
        echo "Accès interdit.";
    }

    if(!isset($_SESSION['email']) || !isset($_SESSION['commande'])){ //voir pour stocké voyage dans un json spécial
        echo "<p>Erreur : aucune commande ou utilisateur en session.";
        exit;
    }

    $email = $_SESSION['email'];
    $commande = $_SESSION['commande'];
    $commande['id'] = $commande['voyage_id'];

    $voyage_achete = [
        "id" => $commande['voyage_id'],
        "nom" => $commande['titre'],
        "date_achat" => date('Y-m-d'),
        "prix_total" => $commande['prix_total']
    ];

    $utilisateur_path = "data/utilisateurs.json";
    if(file_exists($utilisateur_path)){
        $utilisateurs = json_decode(file_get_contents($utilisateur_path), true);
        foreach ($utilisateurs as $user){
            if ($user['email'] == $email){
                if(!isset($user['voyages_achetes'])){
                $user['voyages_achetes'] = [];
                }
                $user['voyages_achetes'][] = $voyage_achete;
                break;
            }
        }
        file_put_contents($utilisateur_path, json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
    ?>
</body>
</html>