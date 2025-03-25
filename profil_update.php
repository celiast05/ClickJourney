<?php
session_start();
$fileJson = 'json/users.json';
$users = json_decode(file_get_contents($fileJson), true);  // Parser le contenu JSON en fait un tableau

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $users[$_SESSION['index']]['Informations']['civilite'] = $_POST['civilite'];
    $users[$_SESSION['index']]['Informations']['nom'] = $_POST['nom'];
    $users[$_SESSION['index']]['Informations']['prenom'] = $_POST['prenom'];
    $users[$_SESSION['index']]['Informations']['telephone'] = $_POST['telephone'];
    

    file_put_contents($fileJson, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
// $users[$_SESSION['index']]['voyages']['consultes'] = $_POST['nom'];
//     $users[$_SESSION['index']]['voyages']['achetes'] = $_POST['nom'];
//     $users[$_SESSION['index']]['voyages']['favoris'] = $_POST['nom'];
?>