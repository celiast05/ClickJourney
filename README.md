
# 🌍 Click-journeY

**Click-journeY** est un site de voyages de luxe personnalisés, réalisé dans le cadre du module d'informatique du semestre 4 (préING2 - 2024/2025).  
Le site propose aux utilisateurs des circuits de voyages fixes, modifiables à chaque étape (hébergement, activités, transport, etc.).

## 👥 Membres de l’équipe

- **Célia Astier**
- **Bouchra Zamoum**
- **Raphaëlle Grimaldi**

---

## 🧾 Fonctionnalités

### Utilisateur
- 🔐 Inscription / Connexion avec validation client & serveur
- 🧳 Consultation et personnalisation des voyages
- 🛒 Ajout automatique au panier + résumé du voyage
- 💳 Paiement sécurisé simulé
- 🧾 Historique des réservations
- 👤 Modification du profil en AJAX

### Administrateur
- 👥 Gestion des utilisateurs : statut, bannissement, rôle
- 🔄 Modification des rôles en AJAX avec indicateur de chargement

### Frontend dynamique
- 🎨 Changement de thème (clair, sombre, contrasté, large)
- 🧠 Tri dynamique des voyages (prix, durée, étapes)
- 🧮 Calcul asynchrone du prix en temps réel
- 📋 Formulaires validés côté client sans rechargement

---

## 🛠️ Technologies utilisées

- **HTML / CSS** : mise en page, thèmes
- **JavaScript** : DOM, interactions utilisateur, validation
- **AJAX** : mises à jour sans rechargement (profil, admin, prix, options…)
- **PHP** : gestion serveur, sessions, traitement des données
- **JSON** : structure des données utilisateurs & voyages

---

## 📁 Structure du projet


---

## 🚀 Lancement local

1. Installer un serveur local (XAMPP, MAMP, WAMP…).
2. Copier le dossier du projet dans `htdocs` (ou équivalent).
3. Lancer Apache.
4. Accéder via : `http://localhost/click-journeY/script/accueil.php`

---

## 🐞 Points forts techniques

- Architecture modulaire (footer, header, sessions séparés)
- Requêtes AJAX avec retour JSON pour éviter le rechargement complet
- Modularisation CSS par page
- JSON dynamique pour étapes & options de voyage
- Interface responsive et compatible multi-navigateurs

---

## 🧪 Tests & Sécurité

- Validation dynamique des formulaires
- Gestion des erreurs (champs vides, incohérences, navigation interdite sans session)
- Tests sur différents navigateurs et tailles d’écran
- Protection contre comportements inattendus lors de la soutenance

---

## 📎 Annexes

- 📄 [Charte graphique](./rapport%20+%20charte/Charte%20graphique%20v1.4.pdf)
- 📄 [Rapport de projet final](./rapport%20+%20charte/Rapport%20de%20projet%20final.pdf)

---

## 🎓 Projet universitaire

> Ce projet a été réalisé dans le cadre du module informatique 4 (HTML/CSS, PHP, JS, DOM, AJAX) – préING2 – CY Tech – 2024/2025  
> Enseignants : **R. Grignon** & **C. Le Breton**


| **Adresse mail**  | **Mot de passe** | **Rôle**       |
|-------------------|------------------|----------------|
| test@gmail.com    | Test_admin1      | Administrateur |
| zoe@gmail.com     | Zoe_admin2       | Administrateur |
| charles@gmail.com | Charles_vip1     | VIP            |
| peter@gmail.com   | PeterParker1     | VIP            |
| luc@gmail.com     | Luc_ban1         | Banni          |
| thomas@gmail.com  | Thomas_norm3     | Banni          |
| justice@gmail.com | justice          | Normal         |
| marc@gmail.com    | Marc_norm1       | Normal         |
| paul@gmail.com    | Paul_norm2       | Normal         |
