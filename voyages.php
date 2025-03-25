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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/presentation.css" />
    <title>Nos voyages - Elysia Voyage</title>
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
    <h1>Nos voyages</h1>
  </header>
  <main>

    <form name="search" method="post" action="#">
      <label for="i1"></label>
      <input class="search" id="i1" type="text" placeholder="Rechercher...">
    </form>
    
    <br />
    <br />
    
	  <div class="container">
      <!--Voyage 1-->
      <div class="card">
        <div class="card-image" style="background-image: url('Images/Maldives.jpeg');"></div>
        <div class="card-content">
          <h2>Maldives</h2>
          <p class="category">Évasion Tropicale </p>
          <p class="taille">Un coin de paradis entre lagons turquoise et bungalows flottants. Luxe et sérénité absolus.</p>
          <a href="voyage1.html" class="cta">
            Découvrir
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="10" viewBox="0 0 46 16">
              <path d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z" transform="translate(30)"></path>
            </svg>
          </a>
        </div>
      </div>

      <!--Voyage 2-->
      <div class="card">
        <div class="card-image" style="background-image: url('Images/Bora\ Bora.jpeg');"></div>
        <div class="card-content">
          <h2>Bora Bora</h2>
          <p class="category">Eaux Cristallines </p>
          <p class="taille">Plongée au cœur d’un sanctuaire marin exceptionnel. Des plages de rêve et une mer translucide.</p>
          <a href="voyage2.html" class="cta">
              Découvrir
              <svg xmlns="http://www.w3.org/2000/svg" width="30" height="10" viewBox="0 0 46 16">
                  <path d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z" transform="translate(30)"></path>
              </svg>
          </a>
        </div>
      </div>

      <!--Voyage 3-->
      <div class="card">
        <div class="card-image" style="background-image: url('Images/Tahiti.jpeg');"></div>
        <div class="card-content">
          <h2>Tahiti</h2>
          <p class="category">Nature Luxuriante </p>
          <p class="taille">Île sauvage et préservée, entre montagnes verdoyantes et plages noires mystiques.</p>
          <a href="voyage3.html" class="cta">
            Découvrir
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="10" viewBox="0 0 46 16">
              <path d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z" transform="translate(30)"></path>
            </svg>
          </a>
        </div>
      </div>
      
      <!--Voyage 4-->
      <div class="card">
        <div class="card-image" style="background-image: url('Images/Paris.jpeg');"></div>
        <div class="card-content">
          <h2>Paris</h2>
          <p class="category">Ville Lumière</p>
          <p class="taille">Un mélange envoûtant d’histoire, d’art et de romantisme. Flânez entre musées, avenues mythiques et dîners étoilés.</p>
          <a href="voyage4.html" class="cta">
            Découvrir
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="10" viewBox="0 0 46 16">
              <path d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z" transform="translate(30)"></path>
            </svg>
          </a>
        </div>
      </div>

      <!--Voyage 5-->
      <div class="card">
        <div class="card-image" style="background-image: url('Images/Rome.jpeg');"></div>
        <div class="card-content">
          <h2>Rome</h2>
          <p class="category">L'Éternelle</p>
          <p class="taille">Vestiges antiques, fontaines majestueuses et dolce vita. Une escapade où l’histoire côtoie le raffinement</p>
          <a href="voyage5.html" class="cta">
            Découvrir
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="10" viewBox="0 0 46 16">
              <path d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z" transform="translate(30)"></path>
            </svg>
          </a>
        </div>
      </div>

      <!--Voyage 6-->
      <div class="card">
        <div class="card-image" style="background-image: url('Images/Santorin.jpeg');"></div>
        <div class="card-content">
          <h2>Santorin</h2>
          <p class="category">Joyau des Cyclades</p>
          <p class="taille">Falaises blanches, dômes bleus et couchers de soleil inoubliables sur la mer Égée. Une île taillée pour le rêve.</p>
          <a href="voyage6.html" class="cta">
            Découvrir
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="10" viewBox="0 0 46 16">
              <path d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z" transform="translate(30)"></path>
            </svg>
          </a>
        </div>
      </div>

      <!-- Voyage 7 -->
      <div class="card">
        <div class="card-image" style="background-image: url('Images/Laponie.jpeg');"></div>
        <div class="card-content">
          <h2>Laponie</h2>
          <p class="category">Le Royaume des Aurores</p>
          <p class="taille">Pays des aurores boréales et des étendues enneigées, un voyage féerique au cœur du Grand Nord.</p>
          <a href="voyage7.html" class="cta">
            Découvrir
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="10" viewBox="0 0 46 16">
              <path d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z" transform="translate(30)"></path>
            </svg>
          </a>
        </div>
      </div>

      <!-- Voyage 8 -->
      <div class="card">
        <div class="card-image" style="background-image: url('Images/Courchevel.jpeg');"></div>
        <div class="card-content">
          <h2>Courchevel</h2>
          <p class="category">Le Joyau des Alpes</p>
          <p class="taille">Le summum du luxe alpin, entre chalets prestigieux, pistes d’exception et après-ski raffiné.</p>
          <a href="voyage8.html" class="cta">
            Découvrir
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="10" viewBox="0 0 46 16">
              <path d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z" transform="translate(30)"></path>
            </svg>
          </a>
        </div>
      </div>

      <!-- Voyage 9 -->
      <div class="card">
        <div class="card-image" style="background-image: url('Images/Aspen.jpeg');"></div>
        <div class="card-content">
          <h2>Aspen</h2>
          <p class="category">L’Escapade Prestige</p>
          <p class="taille">Éden des skieurs et des élites, où la poudreuse rencontre l’élégance</p>
          <a href="voyage9.html" class="cta">
            Découvrir
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="10" viewBox="0 0 46 16">
              <path d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z" transform="translate(30)"></path>
            </svg>
          </a>
        </div>
      </div>
    </div>
</main>
</body>
</html>