function toggleInput() {
  // Sélectionner toutes les cases cochées (radio et checkbox)
  const selectedInputs = document.querySelectorAll(
    'input[type="radio"]:checked, input[type="checkbox"]:checked'
  );
  const uncheckedInputs = document.querySelectorAll(
    'input[type="radio"]:not(:checked), input[type="checkbox"]:not(:checked)'
  );

  // Sélectionner tous les champs de nombre de personnes
  const allNumberInputs = document.querySelectorAll('input[type="number"]');

  // Désactiver tous les champs
  allNumberInputs.forEach((input) => {
    input.disabled = false;
  });

  uncheckedInputs.forEach((input) => {
    const numberInput = input.parentNode.querySelector('input[type="number"]');
    if (numberInput) {
      numberInput.disabled = true;
      numberInput.value = "1"; // Remettre à 1
    }
  });
}

document
  .getElementById("reservation-form")
  .addEventListener("submit", function () {
    document
      .querySelectorAll('input[type="number"]:disabled')
      .forEach((input) => {
        input.removeAttribute("name"); // Supprimer le name pour qu'il ne soit pas envoyé
      });
  });
