<?php
include 'session.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="css/accueil.css" />
    <link id="theme-link" rel="stylesheet" href="css/themes/theme_light.css">
    <title>Elysia Voyage</title>
  </head>
  <body> 
    <?php include 'nav.php'; ?>

    <header>
      <h1>Elysia Voyage</h1>
      <h4>L’élégance du voyage, la magie de l’amour</h4>
    </header>
    <main>
      <p>
        Plongez dans un univers où luxe, romantisme et évasion se rencontrent
        pour créer la lune de miel de vos rêves. Chez Elysia Voyage, nous
        concevons des expériences uniques pour les couples en quête d’exception.
        Des plages privées aux villas somptueuses, des îles paradisiaques aux
        destinations les plus exclusives, chaque voyage est une promesse de
        moments inoubliables.
        <br />
        <br />
        Notre expertise et notre attention aux moindres détails nous permettent
        d’offrir des séjours sur-mesure, où chaque instant est pensé pour vous
        émerveiller. Laissez-nous transformer votre voyage de noces en une
        parenthèse enchantée, où amour et raffinement ne font qu’un.
      </p>

      <br />

      <a href="voyages.php" class="cta">
        Decouvrir
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
      </a>
    </main>
    
    <?php include 'footer.php'; ?>

    <script src="js/theme.js"></script>
  </body>
</html>
