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
function synchroniserNombrePersonnes() {
  const hebergementRadio = document.querySelector('input[type="radio"]:checked');
  if (!hebergementRadio) return;

  const hebergementNbInput = hebergementRadio.parentNode.querySelector('input[type="number"]');
  if (!hebergementNbInput) return;

  const nbPersonnes = parseInt(hebergementNbInput.value) || 1;

  const activiteCheckboxes = document.querySelectorAll('input[type="checkbox"]');
  activiteCheckboxes.forEach(cb => {
    const numberInput = cb.parentNode.querySelector('input[type="number"]');
    if (numberInput) {
      numberInput.max = nbPersonnes; // Limite maximale
      if (parseInt(numberInput.value) > nbPersonnes) {
        numberInput.value = nbPersonnes; // Si trop, on corrige
      }
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

  // Quand on change le nombre de personnes dans l'hébergement
document.querySelectorAll('input[type="radio"]').forEach(radio => {
  const numberInput = radio.parentNode.querySelector('input[type="number"]');
  if (numberInput) {
    numberInput.addEventListener("input", synchroniserNombrePersonnes);
  }

  radio.addEventListener("change", () => {
    toggleInput();
    synchroniserNombrePersonnes();
  });
});

// Appeler dès le début
toggleInput();
synchroniserNombrePersonnes();

document.getElementById("reservation-form").addEventListener("submit", function (e) {
  const depart = new Date(document.getElementById("date_depart").value);
  const retour = new Date(document.getElementById("date_retour").value);
  const today = new Date();
  today.setHours(0, 0, 0, 0); // Ignore l'heure

  if (depart < today) {
    alert("La date de départ ne peut pas être dans le passé.");
    e.preventDefault();
    return;
  }

  if (retour < depart) {
    alert("La date de retour ne peut pas être avant la date de départ.");
    e.preventDefault();
    return;
  }
});

