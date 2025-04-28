<?php

    session_start();

    // Durée d'inactivité autorisée
    $timeout = 300; // 5 minutes

    if (!isset($_SESSION['logged_in'])){ 
    header("Location: connexion.php");
    exit();
    } 
    if (!isset($_SESSION['user'])) {
        $fileJson = 'json/users.json';
        $users = json_decode(file_get_contents($fileJson), true);
        foreach ($users as $user) {
            if ($user['email'] == $_SESSION['email']) {
                $_SESSION['user'] = $user;
                break;
            }
        }
    }

    if ( !isset($_SESSION['stay_connected'])){ // si "Rester connecté" n'est pas clické
    // Vérifier si l'utilisateur est inactif depuis trop longtemps
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
        session_unset(); // Supprime toutes les variables de session
        session_destroy(); // Détruit la session
        header("Location: connexion.php?timeout=1"); // Redirige vers la connexion
        exit();
    }
    }

    // Met à jour l'heure de la dernière activité
    $_SESSION['last_activity'] = time();

    function print_trip($list, $name) {
        if(!empty($list)){
            $fileJson = 'json/voyage.json';
            $trips = json_decode(file_get_contents($fileJson), true); 
            foreach ($trips as $t) { // on fais la liste des correspondances
                $trip_to_link[$t['nom']] = $t['id'];
            }
            echo "<h2>". $name;
            if(count($list)>1){
                echo 's';
            }
            echo " :</h2>";
            foreach($list as $trip){
                echo '<p class="voyage-nom"><a href="details.php?voyage='.$trip_to_link[$trip].'">'.htmlspecialchars($trip).'</a></p>';
            }
        }
    }
    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Elysia Voyage</title>
    <link rel="stylesheet" href="css/profil.css?v=1">
    <link id="theme-link" rel="stylesheet" href="css/themes/theme_light.css">

</head>
<body>
<nav>
      <img src="Images/logo.png" alt="Logo" />
      <div class="btn">
        <?php
        if(isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
            echo "<a href='admin.php'>Administrateur</a>";
        }
        ?>
        <a href="accueil.php">Accueil</a>
        <?php
        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            echo "<a href='profil.php'>Mon profil</a>";
        }
        ?>
        <a href="voyages.php">Nos voyages</a>
        <a href="filtrage.php">Filtrer</a>
        <?php
        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            echo "<a href='script/deconnexion.php?action=run'>Déconnexion</a>";
        }
        else{
            echo "<a href='connexion.php'>Connexion</a>";
        }
        ?>
      </div>
    </nav>
    </nav>
    
    <div class="container">
    <section class="profil">
        <form action="profil_update.php" method="POST" onsubmit="return validateForm()">
            <h2>Mon Profil</h2>
            <div class="profil-info">
                <label for="civilite obligatoire">Civilité :</label>
                <select id="civilite" name="civilite" disabled> 
                <option value="" <?php echo empty($_SESSION['user']['civilite']) ? "selected" : ""; ?>>Sélectionnez...</option>
                    <option value="Monsieur" <?php echo isset($_SESSION['user']['civilite']) && $_SESSION['user']['civilite'] == "Monsieur" ? "selected" : ""; ?>>Monsieur</option>
                    <option value="Madame" <?php echo isset($_SESSION['user']['civilite']) && $_SESSION['user']['civilite'] == "Madame" ? "selected" : ""; ?>>Madame</option>
                    <option value="Autre" <?php echo isset($_SESSION['user']['civilite']) && $_SESSION['user']['civilite'] == "Autre" ? "selected" : ""; ?>>Autre</option>
                </select>
            </div>
        
            <div class="profil-info obligatoire">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" disabled value="<?php echo isset($_SESSION['user']['nom']) ? $_SESSION['user']['nom'] : ''; ?>" required>
            </div>
            
            <div class="profil-info obligatoire">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" disabled value="<?php echo isset($_SESSION['user']['prenom']) ? $_SESSION['user']['prenom'] : ''; ?>" required>
            </div>
            
            <div class="profil-info obligatoire">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" disabled value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" required>
            </div>
            
            <div class="profil-info obligatoire">
                <label for="telephone">Téléphone :</label>
                <input type="tel" id="telephone" name="telephone" disabled value="<?php echo isset($_SESSION['user']['telephone']) ? $_SESSION['user']['telephone'] : ''; ?>" required>
            </div>
            
            <h3>Informations Facultatives</h3>
            
            <div class="profil-info">
                <label for="photo">Photo de Profil :</label>
                <input type="file" id="photo" name="photo" accept="image/*" disabled>
            </div>
        
            
            <div class="profil-info">
                <label for="preferences">Préférences de Voyage :</label>
                <textarea id="preferences" name="preferences" placeholder="Indiquez vos préférences (ex: plages, montagnes, road trip...)" disabled ><?php echo isset($_SESSION['user']['preferences']) ? $_SESSION['user']['informations']['preferences'] : ''; ?></textarea>
            </div>
            
            <div class="profil-info">
                <label for="passeport">Type de Passeport :</label>
                <select id="passeport" name="passeport" disabled>
                <option value="">Sélectionnez...</option>
                    <option value="Ordinaire" <?php echo isset($_SESSION['user']['informations']['passeport']) && $_SESSION['user']['informations']['passeport'] == "Ordinaire" ? "selected" : ""; ?>>Ordinaire</option>
                    <option value="Diplomatique" <?php echo isset($_SESSION['user']['informations']['passeport']) && $_SESSION['user']['informations']['passeport'] == "Diplomatique" ? "selected" : ""; ?>>Diplomatique</option>
                    <option value="Officiel" <?php echo isset($_SESSION['user']['informations']['passeport']) && $_SESSION['user']['informations']['passeport'] == "Officiel" ? "selected" : ""; ?>>Officiel</option>
                </select>
            </div>

            <br />
            
            <button type="button" class="edit-btn" onclick="enableEdit()">✎ Modifier</button>
            <button type="submit" class="save-btn" style="display:none;">Enregistrer</button>
        </form>
    </section>
    <?php
    
if (!empty($_SESSION['user']['voyages']['consultes']) || !empty($_SESSION['user']['voyages']['favoris']) || !empty($_SESSION['user']['voyages']['achetes'])) {
    echo "<section class='historique'>";
    
        echo "<div class='historique-section consultes'>";
            echo "<div class='voyage-container'>";
            print_trip($_SESSION['user']['voyages']['consultes'], "Consulté");
            echo "</div>";
        echo "</div>";

        echo "<div class='historique-section favoris'>";
            echo "<div class='voyage-container'>";
            print_trip($_SESSION['user']['voyages']['favoris'], "Favori");
            echo "</div>";
        echo "</div>";

        echo "<div class='historique-section achetes'>";
            echo "<div class='voyage-container'>";
            print_trip($_SESSION['user']['voyages']['achetes'], "Acheté");
            echo "</div>";
        echo "</div>";

    echo "</section>";
}
?>

    </div>
    
    <footer>
        <p>&copy; 2025 Elysia Voyage. Tous droits réservés.</p>

    </footer>
    
    <script>
        function enableEdit() {
            let inputs = document.querySelectorAll('.profil-info input, .profil-info select, .profil-info textarea');
            inputs.forEach(input => input.removeAttribute('disabled'));
            document.querySelector('.edit-btn').style.display = 'none';
            document.querySelector('.save-btn').style.display = 'inline-block';
        }

        function validateForm() {
            let requiredFields = document.querySelectorAll('.profil-info .obligatoire input');
            for (let field of requiredFields) {
                if (field.value.trim() === "") {
                    alert("Tous les champs obligatoires doivent être remplis.");
                    return false;
                }
            }
            return true;
        }
    </script>
<script src="js/theme.js"></script>
</body>
</html>

