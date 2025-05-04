<?php

session_start();

if (isset($_GET['action']) && $_GET['action'] == "run") {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'banni') { //Si l'utilisateur est banni
        session_unset();// Supprimer toutes les variables de session
        session_destroy();// Détruire la session
        header("Location: ..\accueil.php?banni"); 
    }
    else{
        session_unset();// Supprimer toutes les variables de session
        session_destroy();// Détruire la session
        header("Location: ..\accueil.php"); // Rediriger vers la page de connexion
    }
    exit();
}
?>
