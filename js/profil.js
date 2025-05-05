// Faire save et enregistrer au moins une modif est fait

function detectChange() {
  let changed = false;
  document.querySelectorAll("input, select, textarea").forEach((el) => {
    if (el.value !== el.dataset.initial) {
      console.log(`Le champ ${el.name || el.id} a été modifié.`);
      changed = true;
    }
  });
  return changed;
}

function confirmChange(btn) {
  const parent = btn.closest(".profil-info");

  const champ1 = parent.querySelector(".edit-btn");
  if (champ1) {
    champ1.style.display = "inline-block";
  }

  const champ2 = parent.querySelector(".save-btn");
  if (champ2) {
    champ2.style.display = "none";
  }

  const champ3 = parent.querySelector(".reset-btn");
  if (champ3) {
    champ3.style.display = "none";
  }

  const champ = parent.querySelector("input, select, textarea");
  if (champ) {
    champ.disabled = true;
  }

  if (detectChange()) {
    document.querySelector(".send-btn").style.display = "inline-block";
  } else {
    document.querySelector(".send-btn").style.display = "none";
  }
}

function resetInput(btn, previous_value) {
  const parent = btn.closest(".profil-info");

  const champ1 = parent.querySelector(".edit-btn");
  if (champ1) {
    champ1.style.display = "inline-block";
  }

  const champ2 = parent.querySelector(".save-btn");
  if (champ2) {
    champ2.style.display = "none";
  }

  const champ3 = parent.querySelector(".reset-btn");
  if (champ3) {
    champ3.style.display = "none";
  }

  const champ = parent.querySelector("input, select, textarea");
  if (champ) {
    champ.value = previous_value;
    champ.disabled = true;
  }

  if (detectChange()) {
    document.querySelector(".send-btn").style.display = "inline-block";
  } else {
    document.querySelector(".send-btn").style.display = "none";
  }
}

function validateForm() {
  document
    .querySelectorAll("input[disabled], select[disabled], textarea[disabled]")
    .forEach((el) => {
      el.disabled = false;
    });
  let requiredFields = document.querySelectorAll(
    ".profil-info input[required], .profil-info select[required]"
  );
  for (let field of requiredFields) {
    if (field.value.trim() === "" || field.value.trim() === "Sélectionnez...") {
      alert("Tous les champs obligatoires doivent être remplis.");
      return false;
    }
  }
  return true;
}

document.querySelectorAll("input, select, textarea").forEach((el) => {
  el.dataset.initial = el.value;
});

document.querySelectorAll(".edit-btn").forEach((btn) => {
  btn.addEventListener("click", () => {
    const parent = btn.closest(".profil-info");
    btn.style.display = "none";

    const champ = parent.querySelector("input, select, textarea");
    if (champ) {
      champ.disabled = false;
      champ.focus();
    }

    const champ2 = parent.querySelector(".save-btn");
    if (champ2) {
      champ2.style.display = "inline-block";
    }

    const champ3 = parent.querySelector(".reset-btn");
    if (champ3) {
      champ3.style.display = "inline-block";
    }
  });
});
// Validation de l'email dans la page profil
const emailInput = document.getElementById("email");
const emailError = document.getElementById("email-error");

if (emailInput) {
  emailInput.addEventListener("blur", () => {
    const emailValue = emailInput.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    emailError.style.display = "none";
    emailError.innerHTML = "";

    if (!emailRegex.test(emailValue)) {
      emailError.innerHTML = `
        <p style="color: white; font-weight: bold;">Adresse e-mail invalide :</p>
        <ul>
          <li>L'adresse doit contenir un <strong>@</strong></li>
          <li>Elle doit comporter un nom de domaine valide (ex : gmail, yahoo...)</li>
          <li>Elle doit se terminer par une extension (ex : <strong>.com</strong>, <strong>.fr</strong>, ...)</li>
        </ul>
      `;
      emailError.style.display = "block";
    }
  });
}

// Validation du téléphone dans la page profil
const telInput = document.getElementById("telephone");
const telError = document.getElementById("tel-error");

if (telInput) {
  telInput.addEventListener("blur", () => {
    const telValue = telInput.value.trim();
    const telRegex = /^[0-9]{10,15}$/;

    telError.style.display = "none";
    telError.innerHTML = "";

    if (!telRegex.test(telValue)) {
      telError.innerHTML = `
        <p style="color: white; font-weight: bold;">Numéro invalide :</p>
        <ul>
          <li>Il doit contenir uniquement des chiffres (pas d'espaces ni de lettres)</li>
          <li>Il doit comporter au moins 10 chiffres</li>
          <li>En France, il commence par 0 ; sinon, utilisez l’indicatif pays (ex : +33, +212)</li>
        </ul>
      `;
      telError.style.display = "block";
    }
  });
}
