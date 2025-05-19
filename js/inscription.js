document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
  
    const nom = document.getElementById("nom");
    const prenom = document.getElementById("prenom");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirm-password");
  
    // Fonction pour afficher une erreur
    function showError(input, message) {
      removeError(input);
      const error = document.createElement("p");
      error.classList.add("error-message");
      error.style.color = "red";
      error.textContent = message;
      input.insertAdjacentElement("afterend", error);
      input.setAttribute("data-valid", "false");
    }
  
    // Fonction pour retirer une erreur
    function removeError(input) {
      const parent = input.closest('.password-wrapper') || input;
      const container = parent.parentElement;
    
      // Supprime tous les messages d’erreur qui sont directement après le champ ou son wrapper
      container.querySelectorAll('.error-message').forEach(error => {
        error.remove();
      });
    
      input.setAttribute("data-valid", "true");
    }
    
  
    // Vérifie si un champ est valide
    function isValid(input) {
      return input.getAttribute("data-valid") === "true";
    }
  
    // Règle le verrouillage progressif des champs
    function updateFieldAccess() {
      // Nom → Prénom
      prenom.disabled = !isValid(nom);
      // Prénom → Email
      email.disabled = !isValid(prenom);
      // Email → Mot de passe
      password.disabled = !isValid(email);
      // Mot de passe → Confirmation
      confirmPassword.disabled = !isValid(password);
    }
  
    
    // VALIDATION DU NOM
    nom.addEventListener("blur", () => {
      const regexNom = /^[A-Za-zÀ-ÿ\s'-]+$/;
      if (nom.value.trim().length < 2 || !regexNom.test(nom.value)) {
        removeError(nom);
      
        const errorContainer = document.createElement("div");
        errorContainer.classList.add("error-message");
        errorContainer.innerHTML = `
          <p style="color: white; font-weight: 600;">Nom invalide :</p>
          <ul>
            <li>Le nom ne doit contenir que des lettres (pas de chiffres, de symboles ou de caractères spéciaux)</li>
            <li>Le nom ne doit pas contenir d’accents</li>
            <li>Le nom ne doit pas commencer ou finir par un espace</li>
            <li>Le nom doit commencer par une majuscule</li>
          </ul>
        `;
        nom.insertAdjacentElement("afterend", errorContainer);
        nom.setAttribute("data-valid", "false");
      } else {
        removeError(nom);
      }
      
      updateFieldAccess();
    });
  
    // VALIDATION DU PRÉNOM
    prenom.addEventListener("blur", () => {
      const regexPrenom = /^[A-Za-zÀ-ÿ\s'-]+$/;
      if (prenom.value.trim().length < 2 || !regexPrenom.test(prenom.value)) {
        removeError(prenom);
      
        const errorContainer = document.createElement("div");
        errorContainer.classList.add("error-message");
        errorContainer.innerHTML = `
          <p style="color: white; font-weight: 600;">Prénom invalide :</p>
          <ul>
            <li>Le prénom ne doit contenir que des lettres (pas de chiffres, de symboles ou de caractères spéciaux)</li>
            <li>Le prénom ne doit pas contenir d’accents</li>
            <li>Le prénom ne doit pas commencer ou finir par un espace</li>
            <li>Le prénom doit commencer par une majuscule</li>
          </ul>
        `;
        prenom.insertAdjacentElement("afterend", errorContainer);
        prenom.setAttribute("data-valid", "false");
      } else {
        removeError(prenom);
      }
      
      updateFieldAccess();
    }); 
    

    // VALIDATION DE L'EMAIL
  email.addEventListener("blur", () => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    removeError(email);
  
    if (!emailRegex.test(email.value.trim())) {
      const errorContainer = document.createElement("div");
      errorContainer.classList.add("error-message");
      
      errorContainer.innerHTML = `
        <p style="color: white; font-weight: bold;">Adresse e-mail invalide :</p>
        <ul>
          <li>L'adresse doit contenir un <strong>@</strong></li>
          <li>Elle doit comporter un nom de domaine valide (ex : gmail, yahoo...)</li>
          <li>Elle doit se terminer par une extension (ex : <strong>.com</strong>, <strong>.fr</strong>, ...)</li>
        </ul>
      `;
  
      email.insertAdjacentElement("afterend", errorContainer);
      email.setAttribute("data-valid", "false");
    } else {
      email.setAttribute("data-valid", "true");
    }
  
    updateFieldAccess();
  });
  
    
  
    // VALIDATION DU MOT DE PASSE
   
    let isClickingEye = false;
    let isClickingConfirmEye = false;
  
  
    togglePassword.addEventListener("mousedown", () => {
      isClickingEye = true;
    });
    
    togglePassword.addEventListener("mouseup", () => {
      setTimeout(() => {
        isClickingEye = false;
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
        removeError(password);
      }
    
      updateFieldAccess();
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
      e.preventDefault();
  
      if (
        isValid(nom) &&
        isValid(prenom) &&
        isValid(email) &&
        isValid(password) &&
        password.value === confirmPassword.value
      ) {
        form.submit();
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
  
  
  
  const togglePassword = document.getElementById("togglePassword");
  const toggleConfirm = document.getElementById("toggleConfirm");
  const password = document.getElementById("password");
  const confirmPassword = document.getElementById("confirm-password");
  
  let isClickingEye = false;
  
  togglePassword.addEventListener("mousedown", () => {
    isClickingEye = true;
  });
  togglePassword.addEventListener("mouseup", () => {
    setTimeout(() => {
      isClickingEye = false;
    }, 100);
  });
  
  togglePassword.addEventListener("click", function (e) {
    e.preventDefault();
    const isPassword = password.type === "password";
    password.type = isPassword ? "text" : "password";
    this.classList.toggle("fa-eye");
    this.classList.toggle("fa-eye-slash");
  });
  
  toggleConfirm.addEventListener("click", function (e) {
    e.preventDefault();
    const isPassword = confirmPassword.type === "password";
    confirmPassword.type = isPassword ? "text" : "password";
    this.classList.toggle("fa-eye");
    this.classList.toggle("fa-eye-slash");
  });