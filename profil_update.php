<?php
session_start();
$fileJson = 'json/users.json';
$users = json_decode(file_get_contents($fileJson), true);  // Parser le contenu JSON en fait un tableau

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $users[$_SESSION['index']]['informations']['civilite'] = $_POST['civilite'];
    $users[$_SESSION['index']]['informations']['nom'] = $_POST['nom'];
    $users[$_SESSION['index']]['informations']['prenom'] = $_POST['prenom'];
    $users[$_SESSION['index']]['informations']['telephone'] = $_POST['telephone'];
    $users[$_SESSION['index']]['informations']['photo'] = $_POST['photo'];
    $users[$_SESSION['index']]['informations']['preferences'] = $_POST['preferences'];
    $users[$_SESSION['index']]['informations']['passeport'] = $_POST['passeport'];

    file_put_contents($fileJson, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    $users[$_SESSION['index']]['civilite'] = $_POST['civilite'];
    $users[$_SESSION['index']]['nom'] = $_POST['nom'];
    $users[$_SESSION['index']]['prenom'] = $_POST['prenom'];
    $users[$_SESSION['index']]['telephone'] = $_POST['telephone'];
    $users[$_SESSION['index']]['photo'] = $_POST['photo'];
    $users[$_SESSION['index']]['preferences'] = $_POST['preferences'];
    $users[$_SESSION['index']]['passeport'] = $_POST['passeport'];
    $_SESSION['user']=$users[$_SESSION['index']];
    
    header("Location: profil.php");
    exit();
    }
?>