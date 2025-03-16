<?php

session_start();

if (isset($_GET['action']) && $_GET['action'] == "run") {
    session_unset();// Supprimer toutes les variables de session
    session_destroy();// Détruire la session
    header("Location: accueil.php"); // Rediriger vers la page de connexion
    exit();
}
?>
