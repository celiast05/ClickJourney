<?php
include 'session.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: connexion.php");
    exit();
}

/*if (!isset($_SESSION['user'])) {
    $fileJson = 'json/users.json';
    $users = json_decode(file_get_contents($fileJson), true);
    foreach ($users as $user) {
        if ($user['email'] == $_SESSION['email']) {
            $_SESSION['user'] = $user;
            break;
        }
    }
}*/

if (isset($_SESSION['logged_in'])) {
    $email = $_SESSION['logged_in'];
    $users = json_decode(file_get_contents('json/users.json'), true);
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            $_SESSION['user'] = $user;
            break;
        }
    }
}

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
</head>
<body>
<?php include 'nav.php'; ?>
    
    <div class="container">
    <section class="profil">
        <form action="profil_update.php" method="POST" onsubmit="return validateForm()" id="profilForm">
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
                <div class="error-message" id="email-error" style="display: none;"></div>
            </div>

            <div class="profil-info">
            <label for="telephone">Téléphone :</label>
                <div class="inclined" >
                    <input type="tel" id="telephone" name="telephone" disabled value="<?php echo isset($_SESSION['user']['informations']['telephone']) ? $_SESSION['user']['informations']['telephone'] : ''; ?>" required>
                    <button type="button" class="edit-btn">✎</button>
                    <button type="button" class="save-btn" style="display:none;" onclick="confirmChange(this)">✔</button>
                    <button type="button" class="reset-btn" style="display:none;"  onclick="resetInput(this, '<?php echo $_SESSION['user']['informations']['telephone']; ?>')">⟳</button>
                </div>
                <div class="error-message" id="tel-error" style="display: none;"></div>
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
            echo "<h2>Voyages Achetés :</h2>";
            if (!empty($_SESSION['user']['voyages']['achetes'])) {
                foreach ($_SESSION['user']['voyages']['achetes'] as $voyage) {
                    if (is_array($voyage) && isset($voyage['nom'], $voyage['transaction_id'])) {
                        echo '<p class="voyage-nom">';
                        echo '<a href="reservation_detail.php?transaction=' . htmlspecialchars($voyage['transaction_id']) . '">';
                        echo htmlspecialchars($voyage['nom']);
                        echo '</a></p>';
                    }
                }
            } else {
                echo "<p>Aucun voyage acheté pour le moment.</p>";
            }
            echo "</div>";
        echo "</div>";

    echo "</section>";
}
?>

    </div>
    
    <?php include 'footer.php'; ?>
    
    <script src="js/theme.js"></script>
    <script src="js/profil.js"></script>
</body>
</html>


