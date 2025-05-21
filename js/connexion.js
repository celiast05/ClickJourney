// Écouteur d’événement : attend que tout le HTML soit chargé avant d’exécuter le JS
document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");   // Récupère le formulaire  // Méthode JS : sélectionne le premier <form>
  const email = document.getElementById("email");   // Récupère le champ email // Méthode JS : récupère l’élément avec l’id "email"
  const password = document.getElementById("password");   // Récupère le champ mot de passe // Sert à la validation + compteur + œil
  const togglePassword = document.getElementById("togglePassword");   // Récupère l’icône œil // Sert à afficher/masquer le mot de passe
  const charCount = document.getElementById("charCount");   // Récupère l’élément qui affiche le nombre de caractères // Affiche en direct la longueur du mot de passe


  console.log("charCount:", charCount);

// Fonction personnalisée pour afficher un message d'erreur sous un champ de formulaire
function showError(input, message) { // Fonction personnalisée : affiche une erreur sous un champ
    removeError(input); // Supprime une ancienne erreur si elle existe (évite les doublons)
    const error = document.createElement("div"); // Crée une nouvelle balise <div> dans le code

    error.className = "error-message"; // Donne à cette div une classe CSS pour la styliser (ex : en rouge)
    error.innerHTML = `<ul><li>${message}</li></ul>`; // Met à l’intérieur de la div le message d’erreur sous forme de liste
    input.insertAdjacentElement("afterend", error); // Insère cette div juste après le champ concerné (sous le champ)

    input.setAttribute("data-valid", "false"); // Ajoute un attribut personnalisé au champ pour dire qu’il est invalide
  }

  // Fonction personnalisée : supprime l’erreur d’un champ
  function removeError(input) {
    const error = input.parentElement.querySelector(".error-message");// Cherche une erreur dans le parent
    if (error) error.remove(); // Si elle existe, on la supprime
    input.removeAttribute("data-valid"); // On enlève le marqueur d’erreur
  }

  // Validation du formulaire lors de la soumission
  form.addEventListener("submit", (e) => {
    let isValid = true; // Variable pour savoir si on peut envoyer le formulaire ou pas
        // Supprime les erreurs précédentes
    removeError(email);
    removeError(password);

    // Récupère les valeurs saisies sans espaces

    const emailValue = email.value.trim();
    const passwordValue = password.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;     // Expression régulière : vérifie que l’email a un format valide (exemple@domaine.fr)

    // Vérifie la validité de l’email

    if (!emailRegex.test(emailValue)) {
      isValid = false; // Marque comme invalide
    }

    // Vérifie que le mot de passe contient au moins 6 caractères

    if (passwordValue.length < 6) {
      isValid = false;
    }
    // Si une erreur est détectée, on empêche l’envoi du formulaire

    if (!isValid) {
      e.preventDefault();// Méthode JS : bloque l’envoi
    }
  });

  // Compteur de caractères du mot de passe : s’active dès qu’on tape
  password.addEventListener("input", () => {
    charCount.textContent = password.value.length; // Met à jour le compteur à chaque lettre tapée
  });

  // Afficher ou masquer le mot de passe quand on clique sur l’œil
  
  togglePassword.addEventListener("click", function () {
        // Vérifie si le mot de passe est masqué ou non

    const isPassword = password.getAttribute("type") === "password";
        // Change le type en "text" (visible) ou "password" (masqué)

    password.setAttribute("type", isPassword ? "text" : "password");
        // Change l’icône (œil ouvert / œil barré)

    this.classList.toggle("fa-eye");
    this.classList.toggle("fa-eye-slash");
  });
});
