<?php
session_start();

if (isset($_POST['index'])) {
    unset($_SESSION['panier'][$_POST['index']]);
    $_SESSION['panier'] = array_values($_SESSION['panier']); // Réindexer
}

header("Location: mon_panier.php");
exit();
