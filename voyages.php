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
        <a href="filtrage.php">Filtrer</a>
        <?php
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            echo "<a href='script/deconnexion.php?action=run'>Déconnexion</a>";
        } else {
            echo "<a href='connexion.php'>Connexion</a>";
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

    <div class="container">
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
</body>
</html>
