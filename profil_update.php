<?php
session_start();
$fileJson = 'json/users.json';
$users = json_decode(file_get_contents($fileJson), true);  // Parser le contenu JSON en fait un tableau

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $users[$_SESSION['index']]['informations']['civilite'] = $_POST['civilite'];
    $users[$_SESSION['index']]['informations']['nom'] = $_POST['nom'];
    $users[$_SESSION['index']]['informations']['prenom'] = $_POST['prenom'];
    $users[$_SESSION['index']]['informations']['telephone'] = $_POST['telephone'];

    $_SESSION['user']=$users[$_SESSION['index']];
    file_put_contents($fileJson, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header("Location: profil.php");
    exit();
    }
?>