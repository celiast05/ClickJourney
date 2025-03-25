<?php
session_start();

// Durée d'inactivité autorisée
$timeout = 600; // 10 minutes

if ( !isset($_SESSION['stay_connected'])){ // si Rester connecté n'est pas clické
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
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="css/filtrage.css" />
    <title>Personalisation - Elysia Voyage</title>
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

    <header>
      <h1>Elysia Voyage</h1>
    </header>

    <main>
      <div class="container">
        <form action="recherche_voyage.php" method="POST">
          <fieldset>
            <legend>Personaliser votre voyage</legend>
            <!-- Destination -->
            <label for="destination" class="header">Destination </label>
            <input
              type="text"
              id="destination"
              name="destination"
              placeholder="Ex Paris, Rome, New York"
            />

            <!-- Dates -->
            <label for="date_depart" class="header">Date de départ </label>
            <input type="date" id="date_depart" name="date_depart" />

            <label for="date_retour" class="header">Date de retour </label>
            <input type="date" id="date_retour" name="date_retour" />

            <!-- Options supplémentaires -->
            <label class="header">Options supplémentaires</label>
            <div class="checkbox-group">
              <!-- Climat & Saison -->
              <label for="climat_saison">Climat & Saison </label>
              <select id="climat_saison" name="climat_saison">
                <option value="">-- Sélectionner --</option>
                <option value="tropical_ensoleille">
                  Tropical & Ensoleillé
                </option>
                <option value="douceur_mediterraneenne">
                  Douceur Méditerranéenne
                </option>
                <option value="hiver_chic_neige">Hiver Chic & Neige</option>
                <option value="printemps_fleuri">Printemps Fleuri</option>
                <option value="automne_dore">Automne Doré</option>
              </select>

              <!-- Type d'Hébergement -->
              <label for="hebergement">Type d'Hébergement </label>
              <select id="hebergement" name="hebergement">
                <option value="">-- Sélectionner --</option>
                <option value="villas_privees">
                  Villas privées sur pilotis
                </option>
                <option value="hotels_resorts_5">
                  Hôtels & Resorts 5 étoiles
                </option>
                <option value="yachts_croisieres">
                  Yachts & Croisières privées
                </option>
                <option value="chateaux_demeures">
                  Châteaux & Demeures historiques
                </option>
                <option value="lodges_safari">Lodges & Safari Lodges</option>
              </select>
            </div>

            <!-- Expériences & Activités -->
            <label class="header">Expériences & Activités </label>
            <div class="checkbox-group">
              <label>
                <input type="checkbox" name="activites[]" value="diner_prive" />
                <p>Dîner privé sous les étoiles</p>
              </label>
              <label>
                <input
                  type="checkbox"
                  name="activites[]"
                  value="plongee_tortues"
                />
                <p>Plongée & Snorkeling avec tortues</p></label
              >
              <label>
                <input type="checkbox" name="activites[]" value="safari_vip" />
                <p>Safari VIP avec lodge 5 étoiles</p></label
              >
              <label>
                <input
                  type="checkbox"
                  name="activites[]"
                  value="survol_helicoptere"
                />
                <p>Survol en hélicoptère ou montgolfière</p></label
              >
              <label>
                <input
                  type="checkbox"
                  name="activites[]"
                  value="spa_bien_etre"
                />
                <p>Spa & Bien-être ultra-luxe</p></label
              >
              <label>
                <input
                  type="checkbox"
                  name="activites[]"
                  value="shopping_haut_couture"
                />
                <p>Shopping haute couture & joaillerie</p></label
              >
            </div>

            <!-- Intimité & Exclusivité -->
            <label class="header">Intimité & Exclusivité </label>
            <div class="checkbox-group">
              <label>
                <input
                  type="checkbox"
                  name="exclusivite[]"
                  value="ile_privee"
                />
                <p>Île privée</p>
              </label>
              <label>
                <input
                  type="checkbox"
                  name="exclusivite[]"
                  value="destination_confidentielle"
                />
                <p>Destination confidentielle (peu de touristes)</p></label
              >
              <label>
                <input
                  type="checkbox"
                  name="exclusivite[]"
                  value="experiences_sur_mesure"
                />
                <p>Expériences uniques sur-mesure</p></label
              >
              <label>
                <input
                  type="checkbox"
                  name="exclusivite[]"
                  value="conciergerie_personnalisee"
                />
                <p>Conciergerie & services personnalisés</p></label
              >
            </div>

            <!-- Thème du Voyage -->
            <label for="theme_voyage" class="header">Thème du Voyage </label>
            <div>
              <select id="theme_voyage" name="theme_voyage">
                <option value="">-- Sélectionner --</option>
                <option value="romantique_feerique">
                  Romantique & Féérique
                </option>
                <option value="aventure_exploration">
                  Aventure & Exploration
                </option>
                <option value="culture_histoire">Culture & Histoire</option>
                <option value="detente_bien_etre">Détente & Bien-être</option>
                <option value="gastronomie_enologie">
                  Gastronomie & Enologie
                </option>
              </select>
            </div>
            <br />

            <!-- Bouton de soumission -->
            <button class="cta" type="submit">
              Rechercher
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="30"
                height="10"
                viewBox="0 0 46 16"
              >
                <path
                  d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z"
                  transform="translate(30)"
                ></path>
              </svg>
            </button>
          </fieldset>
        </form>
      </div>
    </main>
  </body>
</html>