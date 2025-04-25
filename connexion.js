// Fichier : connexion.js

document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
  
    function removeError(input) {
      const error = input.parentElement.querySelector(".error-message");
      if (error) error.remove();
      input.removeAttribute("data-valid");
    }
  
    function showError(input, message) {
      removeError(input);
      const error = document.createElement("div");
      error.className = "error-message";
      error.innerHTML = <ul><li>${message}</li></ul>;
      input.insertAdjacentElement("afterend", error);
      input.setAttribute("data-valid", "false");
    }
  
    form.addEventListener("submit", (e) => {
      let isValid = true;
      removeError(email);
      removeError(password);
  
      const emailValue = email.value.trim();
      const passwordValue = password.value.trim();
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  
      if (!emailRegex.test(emailValue)) {
        showError(email, "Adresse e-mail invalide.");
        isValid = false;
      }
  
      if (passwordValue.length < 6) {
        showError(password, "Le mot de passe doit contenir au moins 6 caractères.");
        isValid = false;
      }
  
      if (!isValid) {
        e.preventDefault();
      }
    });
  });
  
  
  document.addEventListener("DOMContentLoaded", function () {
    const togglePassword = document.getElementById("togglePassword");
    const password = document.getElementById("password");
  
    togglePassword.addEventListener("click", function () {
      const isPassword = password.getAttribute("type") === "password";
      password.setAttribute("type", isPassword ? "text" : "password");
  
      this.classList.toggle("fa-eye");
      this.classList.toggle("fa-eye-slash");
    });
  });