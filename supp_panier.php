<?php
session_start();

if (!isset($_SESSION['logged_in'])) {
    header("Location: connexion.php");
    exit();
}

if (isset($_POST['index'])) {
    unset($_SESSION['panier'][$_POST['index']]);
    $_SESSION['panier'] = array_values($_SESSION['panier']); // Réindexer
}

header("Location: mon_panier.php");
exit();
?>