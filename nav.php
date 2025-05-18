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
    if(isset($_SESSION['logged_in'])) {
        echo "<a href='profil.php'>Mon profil</a>";
    }
    ?>
    <a href="voyages.php">Nos voyages</a>
    <?php
    if(isset($_SESSION['logged_in'])) {
        echo "<a href='script/deconnexion.php?action=run'>Déconnexion</a>";
    }
    else{
        echo "<a href='connexion.php'>Connexion</a>";
    }
    if (isset($_SESSION['logged_in'])) {
      $nbArticles = isset($_SESSION['panier']) ? count($_SESSION['panier']) : 0;
      echo "<a href='mon_panier.php'>Panier ($nbArticles)</a>";
    }
    ?>
    <a href="javascript:void(0)" id="change-theme">Changer de thème</a>
  </div>
</nav>