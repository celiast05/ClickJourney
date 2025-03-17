<?php
session_start();

// Durée d'inactivité autorisée
$timeout = 300; // 5 minutes

if (!isset($_SESSION['logged_in'])){ // utilisateur anonyme
  header("Location: connexion.html"); // Redirige vers la connexion
  exit();
} 

if ( !isset($_SESSION['stay_connected'])){ // si "Rester connecté" n'est pas clické
  // Vérifier si l'utilisateur est inactif depuis trop longtemps
  if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
      session_unset(); // Supprime toutes les variables de session
      session_destroy(); // Détruit la session
      header("Location: connexion.html?timeout=1"); // Redirige vers la connexion
      exit();
  }
}

// Met à jour l'heure de la dernière activité
$_SESSION['last_activity'] = time();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrateur - Elysia Voyage</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Gestion des Utilisateurs</h1>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Email</th>
                    <th>VIP</th>
                    <th>Bloqué</th>
                    <th>Profil</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Alice</td>
                    <td>Dupont</td>
                    <td>alice.dupont@example.com</td>
                    <td>
                        <button class="vip">Oui</button>
                    </td>
                    <td>
                        <button class="bannir">Non</button>
                    </td>
                    <td>
                        <button class="normal">Voir</button>
                    </td>
                </tr>
                <tr>
                    <td>Jean</td>
                    <td>Martin</td>
                    <td>jean.martin@example.com</td>
                    <td>
                        <button class="vip">Non</button>
                    </td>
                    <td>
                        <button class="bannir">Non</button>
                    </td>
                    <td>
                        <button class="normal">Voir</button>
                    </td>
                </tr>
                <tr>
                    <td>Emma</td>
                    <td>Lefevre</td>
                    <td>emma.lefevre@example.com</td>
                    <td>
                        <button class="vip">Non</button>
                    </td>
                    <td>
                        <button class="bannir">Oui</button>
                    </td>
                    <td>
                        <button class="normal">Voir</button>
                    </td>
                </tr>
                <tr>
                    <td>Lucas </td>
                    <td>Morel</td>
                    <td>lucas.morel@example.com</td>
                    <td>
                        <button class="vip">Non</button>
                    </td>
                    <td>
                        <button class="bannir">Non</button>
                    </td>
                    <td>
                        <button class="normal">Voir</button>
                    </td>
                </tr>
                <tr>
                    <td>Emma </td>
                    <td>Dubois</td>
                    <td>emma.dubois@example.com</td>
                    <td>
                        <button class="vip">Non</button>
                    </td>
                    <td>
                        <button class="bannir">Non</button>
                    </td>
                    <td>
                        <button class="normal">Voir</button>
                    </td>
                </tr>
                <tr>
                    <td>Chloé </td>
                    <td>Marchand</td>
                    <td>chloé.marchand@example.com</td>
                    <td>
                        <button class="vip">Oui</button>
                    </td>
                    <td>
                        <button class="bannir">Non</button>
                    </td>
                    <td>
                        <button class="normal">Voir</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>