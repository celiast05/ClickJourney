<?php

    session_start();

    // Durée d'inactivité autorisée
    $timeout = 300; // 5 minutes

    if (!isset($_SESSION['logged_in'])){ // utilisateur anonyme
    header("Location: connexion.html"); // Redirige vers la connexion
    exit();
    } 

    if ( !isset($_SESSION['stay_connected'])){ // si "Rester connecté" n'est pas clické
    // Vérifier si l'utilisateur est inactif depuis trop longtemps
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
        session_unset(); // Supprime toutes les variables de session
        session_destroy(); // Détruit la session
        header("Location: connexion.html?timeout=1"); // Redirige vers la connexion
        exit();
    }
    }

    // Met à jour l'heure de la dernière activité
    $_SESSION['last_activity'] = time();


    // Vérifier si la clé 'voyages' et 'consultes' existent
    if (isset($data['voyages']['consultes'])) {
        $consultes = $data['voyages']['consultes'];
    
        // Afficher les voyages consultés
        echo "Voyages consultés : " . implode(", ", $consultes);
    } else {
        echo "Aucune donnée de voyages consultés trouvée.";
    }
    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Elysia Voyage</title>
    <link rel="stylesheet" href="css/profil.css?v=1">

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
            echo "<a href='deconnexion.php?action=run'>Déconnexion</a>";
        }
        else{
            echo "<a href='connexion.html'>Connexion</a>";
        }
        ?>
      </div>
    </nav>
    </nav>
    
    <div class="container">
    <section class="profil">
        <h2>Mon Profil</h2>
        <form action="profil_update.php" method="POST" onsubmit="return validateForm()">
            <div class="profil-info">
                <label for="civilite">Civilité :</label>
                <select id="civilite" name="civilite" <?php if(isset($_SESSION['user']['informations']['civilite'])){
                    echo "value=".$_SESSION['user']['informations']['civilite'];} ?>>
                    <option value="">Sélectionnez...</option>
                    <option value="Monsieur">Monsieur</option>
                    <option value="Madame">Madame</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>
        
            <div class="profil-info obligatoire">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" <?php echo "value=".$_SESSION['user']['informations']['nom'] ?> required>
            </div>
            
            <div class="profil-info obligatoire">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" <?php echo "value=".$_SESSION['user']['informations']['prenom'] ?> required>
            </div>
            
            <div class="profil-info obligatoire">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" <?php echo "value=".$_SESSION['email'] ?> required>
            </div>
            
            <div class="profil-info obligatoire">
                <label for="telephone">Téléphone :</label>
                <input type="tel" id="telephone" name="telephone" <?php if(isset($_SESSION['user']['informations']['telephone'])){
                    echo "value=".$_SESSION['user']['informations']['telephone'];}
                    else{echo "placeholder='Votre téléphone'";} ?>>
            </div>
            
            <h3>Informations Facultatives</h3>
            
            <div class="profil-info">
                <label for="photo">Photo de Profil :</label>
                <input type="file" id="photo" name="photo" accept="image/*">
            </div>
        
            
            <div class="profil-info">
                <label for="preferences">Préférences de Voyage :</label>
                <textarea id="preferences" name="preferences" placeholder="Indiquez vos préférences (ex: plages, montagnes, road trip...)"></textarea>
            </div>
            
            <div class="profil-info">
                <label for="passeport">Type de Passeport :</label>
                <select id="passeport" name="passeport">
                    <option value="">Sélectionnez...</option>
                    <option value="Ordinaire">Ordinaire</option>
                    <option value="Diplomatique">Diplomatique</option>
                    <option value="Officiel">Officiel</option>
                </select>
            </div>

            <br />
            
            <button type="button" class="edit-btn" onclick="enableEdit()">✎ Modifier</button>
            <button type="submit" class="save-btn" style="display:none;">Enregistrer</button>
        </form>
    </section>
    <?php
    echo "<section class='historique'>";
 
        if(!empty($_SESSION['user']['voyages']['consultes'])){
            echo "<h2> Voyage(s) consulté(s) :</h2> <p>". implode(', ',$_SESSION['user']['voyages']['consultes']) . "</p>" ;
        }
        
    echo "</section>";
    ?>
    </div>
    
    <footer>
        <p>&copy; 2025 Elysia Voyage. Tous droits réservés.</p>

    </footer>
    
    <script>
        function enableEdit() {
            let inputs = document.querySelectorAll('.profil-info input, .profil-info select, .profil-info textarea');
            inputs.forEach(input => input.removeAttribute('readonly'));
            document.querySelector('.edit-btn').style.display = 'none';
            document.querySelector('.save-btn').style.display = 'inline-block';
        }

        function validateForm() {
            let requiredFields = document.querySelectorAll('.profil-info.obligatoire input');
            for (let field of requiredFields) {
                if (field.value.trim() === "") {
                    alert("Tous les champs obligatoires doivent être remplis.");
                    return false;
                }
            }
            return true;
        }
    </script>

</body>

</html>
