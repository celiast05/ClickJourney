document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");
  const email = document.getElementById("email");
  const password = document.getElementById("password");
  const togglePassword = document.getElementById("togglePassword");
  const charCount = document.getElementById("charCount");

  console.log("charCount:", charCount);

  // Fonction pour afficher une erreur
  function showError(input, message) {
    removeError(input);
    const error = document.createElement("div");
    error.className = "error-message";
    error.innerHTML = `<ul><li>${message}</li></ul>`;
    input.insertAdjacentElement("afterend", error);
    input.setAttribute("data-valid", "false");
  }

  // Fonction pour retirer une erreur
  function removeError(input) {
    const error = input.parentElement.querySelector(".error-message");
    if (error) error.remove();
    input.removeAttribute("data-valid");
  }

  // Validation du formulaire
  form.addEventListener("submit", (e) => {
    let isValid = true;
    removeError(email);
    removeError(password);

    const emailValue = email.value.trim();
    const passwordValue = password.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailRegex.test(emailValue)) {
      isValid = false;
    }

    if (passwordValue.length < 6) {
      isValid = false;
    }

    if (!isValid) {
      e.preventDefault();
    }
  });

  // Compteur de caractères mot de passe
  password.addEventListener("input", () => {
    charCount.textContent = password.value.length;
  });

  // Afficher/Masquer le mot de passe
  togglePassword.addEventListener("click", function () {
    const isPassword = password.getAttribute("type") === "password";
    password.setAttribute("type", isPassword ? "text" : "password");
    this.classList.toggle("fa-eye");
    this.classList.toggle("fa-eye-slash");
  });
});
