<?php
session_start();

// Durée d'inactivité autorisée
$timeout = 600;

if (!isset($_SESSION['stay_connected'])) {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
        session_unset();
        session_destroy();
        header("Location: connexion.php?timeout=1");
        exit();
    }
}

$_SESSION['last_activity'] = time();

// Fonction pour normaliser (accents + minuscule)
function normalize($string) {
    $string = mb_strtolower($string, 'UTF-8');
    $accents = array(
        'à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï',
        'ð','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ',
        'À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï',
        'Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý'
    );
    $replace = array(
        'a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i',
        'd','n','o','o','o','o','o','o','u','u','u','u','y','y',
        'a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i',
        'd','n','o','o','o','o','o','o','u','u','u','u','y'
    );
    return str_replace($accents, $replace, $string);
}

$voyages = json_decode(file_get_contents('json/voyage.json'), true);

// Traitement de la recherche
$searchResults = $voyages;
$searchTerm = '';

if (isset($_POST['keyword']) && !empty(trim($_POST['keyword']))) {
    $searchTerm = normalize(trim($_POST['keyword']));
    $searchResults = [];

    foreach ($voyages as $voyage) {
        $found = false;

        // Recherche dans le nom et la description
        if (
            strpos(normalize($voyage['nom']), $searchTerm) !== false ||
            strpos(normalize($voyage['description']), $searchTerm) !== false
        ) {
            $found = true;
        }

        // Recherche dans les mots clés
        if (!$found && isset($voyage['mots_cles'])) {
            foreach ($voyage['mots_cles'] as $mot) {
                if (strpos(normalize($mot), $searchTerm) !== false) {
                    $found = true;
                    break;
                }
            }
        }

        if ($found) {
            $searchResults[] = $voyage;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" type="text/css" href="css/voyages.css" />
    <link id="theme-link" rel="stylesheet" href="css/themes/theme_light.css">
    <title>Nos voyages - Elysia Voyage</title>
</head>
<body>
<nav>
    <img src="Images/logo.png" alt="Logo" />
    <div class="btn">
        <?php
        if (isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
            echo "<a href='admin.php'>Administrateur</a>";
        }
        ?>
        <a href="accueil.php">Accueil</a>
        <?php
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            echo "<a href='profil.php'>Mon profil</a>";
        }
        ?>
        <a href="voyages.php">Nos voyages</a>
        <?php
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            echo "<a href='script/deconnexion.php?action=run'>Déconnexion</a>";
        } else {
            echo "<a href='connexion.php'>Connexion</a>";
        }
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            $nbArticles = isset($_SESSION['panier']) ? count($_SESSION['panier']) : 0;
            echo "<a href='mon_panier.php'>Panier ($nbArticles)</a>";
        }
        ?>
    </div>
</nav>

<header>
    <h1>Nos voyages</h1>
</header>

<main>
    <form name="search" method="post" action="voyages.php">
        <input class="search" id="i1" name="keyword" type="text" placeholder="Rechercher..." value="<?= htmlspecialchars($_POST['keyword'] ?? '') ?>">
    </form>

    <br><br>

    <div style="display: flex; gap: 20px; padding: 40px; justify-content: center;">

<!-- FILTRES A GAUCHE -->
<aside style="width: 250px; background: #f8f8f8; padding: 20px; border-radius: 10px; box-shadow: 0 0 5px rgba(0,0,0,0.1);">
    <h3>Filtres</h3>

    <label><strong>Prix :</strong></label>
    <select id="filter-prix">
        <option value="">-- Aucun tri --</option>
        <option value="asc">Prix croissant</option>
        <option value="desc">Prix décroissant</option>
    </select>

    <br><br>
    <label><strong>Climat & Saison :</strong></label><br>
    <input type="checkbox" name="climat" value="Tropical & Ensoleillé"> Tropical & Ensoleillé<br>
    <input type="checkbox" name="climat" value="Chaleur d'Aventure"> Chaleur d'Aventure<br>
    <input type="checkbox" name="climat" value="Hiver Chic & Neige"> Hiver Chic & Neige<br>
    <input type="checkbox" name="climat" value="Printemps à l'Européenne"> Printemps à l'Européenne<br>
    <input type="checkbox" name="climat" value="Automne Doré"> Automne Doré<br>

    <br>
    <label><strong>Pays :</strong></label><br>
    <input type="checkbox" name="pays" value="france"> France<br>
    <input type="checkbox" name="pays" value="polynesie"> Polynésie Française<br>
    <input type="checkbox" name="pays" value="italie"> Italie<br>
    <input type="checkbox" name="pays" value="grece"> Grèce<br>
    <input type="checkbox" name="pays" value="maldives"> Maldives<br>
    <input type="checkbox" name="pays" value="tanzanie"> Tanzanie<br>
    <input type="checkbox" name="pays" value="costa rica"> Costa Rica<br>
    <input type="checkbox" name="pays" value="australie"> Australie<br>
    <input type="checkbox" name="pays" value="laponie"> Laponie<br>
    <input type="checkbox" name="pays" value="aspen"> Aspen<br>
    <input type="checkbox" name="pays" value="courchevel"> Courchevel<br>
    <input type="checkbox" name="pays" value="dubai"> Dubaï<br>
    <input type="checkbox" name="pays" value="oman"> Oman<br>
    <input type="checkbox" name="pays" value="egypte"> Égypte<br>

    <br>
    <label><strong>Thème du voyage :</strong></label><br>
    <input type="checkbox" name="theme" value="Romantique & Féerique"> Romantique & Féerique<br>
    <input type="checkbox" name="theme" value="Aventure & Exploration"> Aventure & Exploration<br>
    <input type="checkbox" name="theme" value="Désert & Splendeur d'Orient"> Désert & Splendeur d'Orient<br>
    <input type="checkbox" name="theme" value="Détente & Bien-être"> Détente & Bien-être<br>
    <input type="checkbox" name="theme" value="Flocon & Glamour"> Flocon & Glamour<br>
</aside>

    <div class="container" id="voyageCards">
        <?php if (empty($searchResults)){
            echo '<p style="text-align:center;">Aucun voyage ne correspond à votre recherche.</p>';}
         else { 
            foreach ($searchResults as $voyage) { ?>
                <div class="card">
                    <div class="card-image" style="background-image: url('<?= $voyage['image']; ?>');"></div>
                    <div class="card-content">
                        <h2><?= $voyage['nom']; ?></h2>
                        <p class="category"><?= $voyage['theme']; ?></p>
                        <p class="taille"><?= $voyage['description']; ?></p>
                        <a href="details.php?voyage=<?= $voyage['id']; ?>" class="cta">
                            Découvrir
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="10" viewBox="0 0 46 16">
                                <path d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z" transform="translate(30)"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            <?php }} ?>
    </div>
</main>
<script src="js/theme.js"></script>
<script src="js/voyages.js"></script>
</body>
</html>
