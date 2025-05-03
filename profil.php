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
    <link rel="stylesheet" href="css/profil.css?v=2.2">
    <link id="theme-link" rel="stylesheet" href="css/themes/theme_light.css">
    <script src="profil.js" defer></script>
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
                <label for="civilite">Civilité :</label>
                <div class="inclined" >
                    <select id="civilite" name="civilite" disabled required> 
                        <option value="" <?php echo empty($_SESSION['user']['informations']['civilite']) ? "selected" : ""; ?>>Sélectionnez...</option>
                        <option value="Monsieur" <?php echo (isset($_SESSION['user']['informations']['civilite']) && $_SESSION['user']['informations']['civilite'] == "Monsieur") ? "selected" : ""; ?>>Monsieur</option>
                        <option value="Madame" <?php echo (isset($_SESSION['user']['informations']['civilite']) && $_SESSION['user']['informations']['civilite'] == "Madame") ? "selected" : ""; ?>>Madame</option>
                        <option value="Autre" <?php echo (isset($_SESSION['user']['informations']['civilite']) && $_SESSION['user']['informations']['civilite'] == "Autre") ? "selected" : ""; ?>>Autre</option>
                    </select>
                    <button type="button" class="edit-btn">✎</button>
                    <button type="button" class="save-btn" style="display:none;" onclick="confirmChange(this)">✔</button>
                    <button type="button" class="reset-btn" style="display:none;"  onclick="resetInput(this, '<?php echo $_SESSION['user']['informations']['civilite']; ?>')">⟳</button>
                </div>
            </div>

            <div class="profil-info">
                <label for="nom">Nom :</label>
                <div class="inclined" >
                <input type="text" id="nom" name="nom" disabled value="<?php echo isset($_SESSION['user']['informations']['nom']) ? $_SESSION['user']['informations']['nom'] : ''; ?>" required>
                    <button type="button" class="edit-btn">✎</button>
                    <button type="button" class="save-btn" style="display:none;" onclick="confirmChange(this)">✔</button>
                    <button type="button" class="reset-btn" style="display:none;" onclick="resetInput(this, '<?php echo $_SESSION['user']['informations']['nom']; ?>')">⟳</button>
                </div>
            </div>

            <div class="profil-info">
                <label for="prenom">Prénom :</label>
                <div class="inclined" >
                    <input type="text" id="prenom" name="prenom" disabled value="<?php echo isset($_SESSION['user']['informations']['prenom']) ? $_SESSION['user']['informations']['prenom'] : ''; ?>" required>
                    <button type="button" class="edit-btn">✎</button>
                    <button type="button" class="save-btn" style="display:none;" onclick="confirmChange(this)">✔</button>
                    <button type="button" class="reset-btn" style="display:none;"  onclick="resetInput(this, '<?php echo $_SESSION['user']['informations']['prenom']; ?>')">⟳</button>
                </div>
            </div>

            <div class="profil-info">
                <label for="email">Email :</label>
                <div class="inclined" >
                    <input type="email" id="email" name="email" disabled value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" required>
                    <button type="button" class="edit-btn">✎</button>
                    <button type="button" class="save-btn" style="display:none;" onclick="confirmChange(this)">✔</button>
                    <button type="button" class="reset-btn" style="display:none;"  onclick="resetInput(this, '<?php echo $_SESSION['email']; ?>')">⟳</button>
                </div>
            </div>

            <div class="profil-info">
            <label for="telephone">Téléphone :</label>
                <div class="inclined" >
                    <input type="tel" id="telephone" name="telephone" disabled value="<?php echo isset($_SESSION['user']['informations']['telephone']) ? $_SESSION['user']['informations']['telephone'] : ''; ?>" required>
                    <button type="button" class="edit-btn">✎</button>
                    <button type="button" class="save-btn" style="display:none;" onclick="confirmChange(this)">✔</button>
                    <button type="button" class="reset-btn" style="display:none;"  onclick="resetInput(this, '<?php echo $_SESSION['user']['informations']['telephone']; ?>')">⟳</button>
                </div>
                
                
            </div>
            
            <h3>Informations Facultatives</h3>
            
            <div class="profil-info">
                <label for="photo">Photo de Profil :</label>
                <div class="inclined" >
                <input type="file" id="photo" name="photo" accept="image/*" disabled>
                    <button type="button" class="edit-btn">✎</button>
                    <button type="button" class="save-btn" style="display:none;" onclick="confirmChange(this)">✔</button>
                    <button type="button" class="reset-btn" style="display:none;"  onclick="resetInput(this, '<?php echo 'None' ?>')">⟳</button>
                </div>
            </div>
        
            
            <div class="profil-info">
                <label for="preferences">Préférences de Voyage :</label>
                <div class="inclined" >
                    <textarea id="preferences" name="preferences" placeholder="Indiquez vos préférences (ex: plages, montagnes, road trip...)" disabled ><?php echo isset($_SESSION['user']['preferences']) ? $_SESSION['user']['informations']['preferences'] : ''; ?></textarea>
                    <button type="button" class="edit-btn">✎</button>
                    <button type="button" class="save-btn" style="display:none;" onclick="confirmChange(this)">✔</button>
                    <button type="button" class="reset-btn" style="display:none;"  onclick="resetInput(this, '<?php echo $_SESSION['user']['informations']['preferences']; ?>')">⟳</button>
                </div>
            </div>
            
            <div class="profil-info">
                <label for="passeport">Type de Passeport :</label>
                <div class="inclined" >
                    <select id="passeport" name="passeport" disabled>
                        <option value="">Sélectionnez...</option>
                        <option value="Ordinaire" <?php echo isset($_SESSION['user']['informations']['passeport']) && $_SESSION['user']['informations']['passeport'] == "Ordinaire" ? "selected" : ""; ?>>Ordinaire</option>
                        <option value="Diplomatique" <?php echo isset($_SESSION['user']['informations']['passeport']) && $_SESSION['user']['informations']['passeport'] == "Diplomatique" ? "selected" : ""; ?>>Diplomatique</option>
                        <option value="Officiel" <?php echo isset($_SESSION['user']['informations']['passeport']) && $_SESSION['user']['informations']['passeport'] == "Officiel" ? "selected" : ""; ?>>Officiel</option>
                    </select>
                    <button type="button" class="edit-btn">✎</button>
                    <button type="button" class="save-btn" style="display:none;" onclick="confirmChange(this)">✔</button>
                    <button type="button" class="reset-btn" style="display:none;"  onclick="resetInput(this, '<?php echo $_SESSION['user']['informations']['passeport']; ?>')">⟳</button>
                </div>
                
            </div>

            <br />
            <button type="submit" class="send-btn" style="display:none;">Enregistrer</button>
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
    <script src="js/theme.js"></script>
    <!-- <script src="profil.js"></script> -->
</body>
</html>

