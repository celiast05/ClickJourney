document.addEventListener("DOMContentLoaded", () => {
    // Quand tout le HTML est chargé, on lance le code JS

    const form = document.querySelector("form"); // Sélectionne le formulaire HTML
    // Récupération des champs du formulaire via leur ID (attribut HTML)

    const nom = document.getElementById("nom");   // Récupère le champ "nom" (balise avec id="nom")
    const prenom = document.getElementById("prenom");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirm-password");
  
    // Fonction pour afficher une erreur
    function showError(input, message) {
      removeError(input); // Supprime les anciennes erreurs du champ
      const error = document.createElement("p"); // Crée une nouvelle balise <p> (JS natif)
      error.classList.add("error-message");  // Ajoute une classe CSS pour le style
      error.style.color = "red"; // Donne une couleur rouge au message
      error.textContent = message; // Ajoute le texte de l'erreur (sans HTML)
      input.insertAdjacentElement("afterend", error); // Place la balise juste après le champ
      input.setAttribute("data-valid", "false"); // Attribut personnalisé : indique que le champ est invalide
    }
  
    // Fonction pour retirer une erreur
    function removeError(input) {
      const parent = input.closest('.password-wrapper') || input; // Cherche un parent wrapper (utile pour les champs mot de passe)
      const container = parent.parentElement; // Récupère l’élément parent du champ
    
      // Supprime tous les messages d’erreur qui sont directement après le champ ou son wrapper
      container.querySelectorAll('.error-message').forEach(error => {
        error.remove();  // Supprime chaque message d’erreur
      });
    
      input.setAttribute("data-valid", "true"); // Marque le champ comme valide
    } 
    
  
    // Vérifie si un champ est valide
    function isValid(input) {
      return input.getAttribute("data-valid") === "true";
    }
  
    // Règle le verrouillage progressif des champs
    function updateFieldAccess() {
      // Nom → Prénom
      prenom.disabled = !isValid(nom); // Active le champ prénom seulement si nom est valide
      // Prénom → Email
      email.disabled = !isValid(prenom);
      // Email → Mot de passe
      password.disabled = !isValid(email);
      // Mot de passe → Confirmation
      confirmPassword.disabled = !isValid(password);
    }
  
    
    // VALIDATION DU NOM
    nom.addEventListener("blur", () => {
          // regex : accepte lettres majuscules/minuscules, espaces, tirets, accents

      const regexNom = /^[A-Za-zÀ-ÿ\s'-]+$/;
      if (nom.value.trim().length < 2 || !regexNom.test(nom.value)) {
        removeError(nom);  // Supprime les erreurs précédentes
      
        const errorContainer = document.createElement("div"); // Crée une div pour afficher les erreurs
        errorContainer.classList.add("error-message"); // Ajoute une classe CSS
        errorContainer.innerHTML = `
          <p style="color: white; font-weight: 600;">Nom invalide :</p>
          <ul>
            <li>Le nom ne doit contenir que des lettres (pas de chiffres, de symboles ou de caractères spéciaux)</li>
            <li>Le nom ne doit pas contenir d’accents</li>
            <li>Le nom ne doit pas commencer ou finir par un espace</li>
            <li>Le nom doit commencer par une majuscule</li>
          </ul>
        `;
        nom.insertAdjacentElement("afterend", errorContainer); // Ajoute le message sous le champ
        nom.setAttribute("data-valid", "false");  // Marque le champ comme invalide
      } else {
        removeError(nom); // Pas d’erreur → supprime les message
      }
      
      updateFieldAccess(); // Active ou désactive les champs suivants
    });
  
    // VALIDATION DU PRÉNOM
    prenom.addEventListener("blur", () => {  // blur est un événement JavaScript qui se déclenche quand on quitte un champ.
      const regexPrenom = /^[A-Za-zÀ-ÿ\s'-]+$/;   // Expression régulière (regex) : autorise lettres, espaces, tirets, apostrophes et lettres accentuées

      if (prenom.value.trim().length < 2 || !regexPrenom.test(prenom.value)) {     // Vérifie que le prénom fait au moins 2 caractères ET qu’il respecte la regex

        removeError(prenom); // Supprime les anciens messages d’erreur s’il y en avait
      
        const errorContainer = document.createElement("div"); // Crée un bloc <div> pour afficher l’erreur
        errorContainer.classList.add("error-message"); // Ajoute une classe CSS pour le style

        errorContainer.innerHTML = `
          <p style="color: white; font-weight: 600;">Prénom invalide :</p>
          <ul>
            <li>Le prénom ne doit contenir que des lettres (pas de chiffres, de symboles ou de caractères spéciaux)</li>
            <li>Le prénom ne doit pas contenir d’accents</li>
            <li>Le prénom ne doit pas commencer ou finir par un espace</li>
            <li>Le prénom doit commencer par une majuscule</li>
          </ul>
        `;
        prenom.insertAdjacentElement("afterend", errorContainer); // Ajoute la div juste après le champ <input>
        prenom.setAttribute("data-valid", "false");  // Marque le champ comme invalide
      } else {
        removeError(prenom); // Si tout est bon, on supprime les erreurs éventuelles
      }
      
      updateFieldAccess(); // Met à jour l’activation des champs suivants (email, mot de passe, etc.)
    }); 
    

    // VALIDATION DE L'EMAIL
  email.addEventListener("blur", () => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    removeError(email);
  
    if (!emailRegex.test(email.value.trim())) {
      const errorContainer = document.createElement("div");     // Si le champ ne respecte pas la structure de l’email...  // Crée un bloc d'erreur

      errorContainer.classList.add("error-message");  // Ajoute une classe CSS pour le style
      
      errorContainer.innerHTML = `
        <p style="color: white; font-weight: bold;">Adresse e-mail invalide :</p>
        <ul>
          <li>L'adresse doit contenir un <strong>@</strong></li>
          <li>Elle doit comporter un nom de domaine valide (ex : gmail, yahoo...)</li>
          <li>Elle doit se terminer par une extension (ex : <strong>.com</strong>, <strong>.fr</strong>, ...)</li>
        </ul>
      `;
  
      email.insertAdjacentElement("afterend", errorContainer);  // Affiche le message sous le champ
      email.setAttribute("data-valid", "false");  // Marque le champ comme invalide
    } else {
      email.setAttribute("data-valid", "true"); // Si tout est bon → on le marque comme valide
    }
  
    updateFieldAccess(); // Active ou désactive le champ mot de passe selon validité de l'email
  });
  
    
  
    // VALIDATION DU MOT DE PASSE
   
    let isClickingEye = false; 
    let isClickingConfirmEye = false;
  
  
    togglePassword.addEventListener("mousedown", () => {
      isClickingEye = true; // Si l’utilisateur clique sur l’œil, on ne valide pas
    });
    
    togglePassword.addEventListener("mouseup", () => {
      setTimeout(() => {
        isClickingEye = false; // Une fois relâché, on peut revalider
      }, 100);
    });
  
    toggleConfirm.addEventListener("mousedown", () => {
      isClickingConfirmEye = true;
    });
    toggleConfirm.addEventListener("mouseup", () => {
      setTimeout(() => {
        isClickingConfirmEye = false;
      }, 100);
    });
    
    
    password.addEventListener("blur", () => {
      if (isClickingEye) return; // ne pas valider si clic sur l’œil
    
      const passwordValue = password.value;
      const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d?!$£€@&()\-\+_=*%\/<>,.;:])[A-Za-z\d?!$£€@&()\-\+_=*%\/<>,.;:]{6,50}$/;
    
      if (!passwordRegex.test(passwordValue)) {
        removeError(password);
    
        const errorContainer = document.createElement("div");
        errorContainer.classList.add("error-message");
    
        errorContainer.innerHTML = `
          <p style="margin-bottom: 8px; font-weight: bold;">Votre mot de passe est incorrect :</p>
          <ul>
            <li>Le mot de passe doit comporter entre <strong>6 et 50 caractères</strong></li>
            <li>Il doit contenir des lettres <strong>latines uniquement</strong>, sans accent</li>
            <li>Il doit inclure au moins <strong>une majuscule</strong>, <strong>une minuscule</strong> et <strong>un chiffre ou caractère spécial</strong></li>
            <li>Ne doit contenir <strong>aucun caractère non autorisé</strong></li>
          </ul>
        `;
    
        password.closest('.password-wrapper')?.insertAdjacentElement("afterend", errorContainer);
        password.setAttribute("data-valid", "false");
    
      } else {
        removeError(password); // Si valide, on nettoie
      }
    
      updateFieldAccess(); // Active confirmation si mot de passe est bon
    });
    
    // VALIDATION DE LA CONFIRMATION DU MOT DE PASSE
    confirmPassword.addEventListener("blur", () => {
      if (isClickingConfirmEye) return; // Ne valide pas si l’utilisateur clique sur l’œil
    
      removeError(confirmPassword);
    
      if (password.value !== confirmPassword.value) {
        const errorContainer = document.createElement("div");
        errorContainer.classList.add("error-message");
    
        errorContainer.innerHTML = `
          <p style="color: white; font-weight: bold;">Confirmation incorrecte :</p>
          <ul>
            <li>Les deux mots de passe doivent être <strong>identiques</strong></li>
            <li>Vérifiez les <strong>majuscules et minuscules</strong></li>
            <li>Assurez-vous de ne pas avoir ajouté d’espace par erreur</li>
          </ul>
        `;
        
        confirmPassword.closest('.password-wrapper')?.insertAdjacentElement("afterend", errorContainer);
        confirmPassword.setAttribute("data-valid", "false");
      } else {
        confirmPassword.setAttribute("data-valid", "true");
      }
    });
    
    // VALIDATION AU MOMENT DE SOUMETTRE
    form.addEventListener("submit", (e) => {
      e.preventDefault();  // Empêche l’envoi si tout n’est pas valide
  
      if (
        isValid(nom) &&
        isValid(prenom) &&
        isValid(email) &&
        isValid(password) &&
        password.value === confirmPassword.value
      ) {
        form.submit(); // Envoie le formulaire si tout est bon
      } else {
        alert("Veuillez corriger les champs avant de soumettre le formulaire.");
      }
    });
  
    // Initialisation : désactiver tous les champs sauf nom
    prenom.disabled = true;
    email.disabled = true;
    password.disabled = true;
    confirmPassword.disabled = true;
  });
  
  // Afficher / masquer les mots de passe
  
  
  // Récupération des éléments HTML : champs mot de passe et icônes œil

  const togglePassword = document.getElementById("togglePassword"); // icône œil principal
  const toggleConfirm = document.getElementById("toggleConfirm"); // œil du champ de confirmation
  const password = document.getElementById("password");
  const confirmPassword = document.getElementById("confirm-password");
  
  let isClickingEye = false; // Variable utilisée pour éviter de déclencher la validation pendant un clic sur l'œil

  
  togglePassword.addEventListener("mousedown", () => {
    isClickingEye = true; // Quand on clique sur l’icône œil
  });
  togglePassword.addEventListener("mouseup", () => {
    setTimeout(() => {
      isClickingEye = false; // On réactive la validation juste après
    }, 100);
  });
  
  togglePassword.addEventListener("click", function (e) {
    e.preventDefault(); // Empêche le comportement par défaut si c'est dans un formulaire

    const isPassword = password.type === "password"; // Vérifie si le champ est actuellement masqué
    password.type = isPassword ? "text" : "password"; // Bascule entre texte (visible) et mot de passe (masqué)
    this.classList.toggle("fa-eye");  // Alterne l’icône entre œil normal
    this.classList.toggle("fa-eye-slash"); // ...et œil barré
  });
  
  toggleConfirm.addEventListener("click", function (e) {
    e.preventDefault();
    const isPassword = confirmPassword.type === "password";
    confirmPassword.type = isPassword ? "text" : "password";
    this.classList.toggle("fa-eye");
    this.classList.toggle("fa-eye-slash");
  });