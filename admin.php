<!-- // Se connecter avec test@gmail.com le password est test -->
 <!-- pour tout les comptes le mot de passe corespond au prénom en minuscule -->
<?php
session_start();

if (isset($_SESSION['role']) && $_SESSION['role'] === 'banni') { // détection d'utilisateur banni
    header("Location: script/deconnexion.php?action=run");
    exit();
  }

// Durée d'inactivité autorisée
$timeout = 300; // 5 minutes

if (!isset($_SESSION['logged_in'])){ // utilisateur anonyme
  header("Location: connexion.php"); // Redirige vers la connexion
  exit();
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

if ( $_SESSION['role'] != 'admin'){ // si l'utilisateur n'est pas un admin
    header("Location: accueil.php"); // renvoie à la page précédente
    exit();
}

// Met à jour l'heure de la dernière activité
$_SESSION['last_activity'] = time();

$fileJson = 'json/users.json';
$users = json_decode(file_get_contents($fileJson), true);  // Parser le contenu JSON en fait un tableau

if ($users === null) {
    $users = []; // Assurer que $users est un tableau pour éviter les erreurs
}

$users_par_page = 7;
$page_actuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Récupérer la page actuelle depuis l'URL (1 par défaut)
$debut = ($page_actuelle - 1) * $users_par_page;
// Extraire les utilisateurs pour la page actuelle
$users_pagination = array_slice($users, $debut, $users_par_page);
// Nombre total de pages
$total_pages = ceil(count($users) / $users_par_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrateur - Elysia Voyage</title>
    <link rel="stylesheet" href="css/admin.css?v=1.2">
    <link id="theme-link" rel="stylesheet" href="css/themes/theme_light.css">
</head>
<body>
    <?php include 'nav.php'; ?>
    
    <div class="admin-container">
        <h1>Gestion des Utilisateurs</h1>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                if ($users !== null) {
                    foreach ($users_pagination as $user) {
                        echo "<tr>";
                        echo "<td>".$user['informations']['nom']."</td>";
                        echo "<td>".$user['informations']['prenom']."</td>";
                        echo "<td>".$user['email']."</td>";
                        if($user['role']=='admin'){
                            echo "<td> <button class='admin'>Admin</button> </td>";
                        }
                        elseif($user['role']=='vip'){
                            echo "<td> <button class='vip'>VIP</button> </td>";
                        }
                        elseif($user['role']=='banni'){
                            echo "<td> <button class='bannir'>Bloqué</button> </td>";
                        }
                        else{
                            echo "<td> <button class='normal'>Classic</button> </td>";
                        }
                        echo "</tr>";
                    }
                }
                ?>
                </tbody>
                </table>

                <div class="pagination">

    <!-- Lien vers la page précédente -->
    <?php if ($page_actuelle > 1): ?>
        <a href="?page=<?php echo $page_actuelle - 1; ?>">Précédent</a>
    <?php endif; ?>

    <!-- Lien vers la première page -->
    <?php if ($page_actuelle > 1): ?>
        <a href="?page=1">1</a>
    <?php endif; ?>

    <?php if($page_actuelle > 2){
        echo "<a>...</a>";
    }?>

    <!-- Affichage de la page actuelle avec surbrillance -->
    <a href="?page=<?php echo $page_actuelle; ?>"><?php echo $page_actuelle ?></a>

    <?php if($page_actuelle < $total_pages -1 ){
        echo "<a>...</a>";
    }?>
    <!-- Lien vers la dernière page -->
    <?php if ($page_actuelle < $total_pages): ?>
        <a href="?page=<?php echo $total_pages; ?>"><?php echo $total_pages ?></a>
    <?php endif; ?>
    <!-- Lien vers la page suivante -->
    <?php if ($page_actuelle < $total_pages): ?>
        <a href="?page=<?php echo $page_actuelle + 1; ?>">Suivant</a>
    <?php endif; ?>
    </div>
            </div>
        <div id="foot"></div>

      <script>
      fetch('/footer.html')
        .then(res => res.text())
        .then(html => {
          document.getElementById('foot').innerHTML = html;
        });
    </script>
        <script src="js/theme.js"></script>
        <script src="js/admin.js"></script>
    </body>
</html>