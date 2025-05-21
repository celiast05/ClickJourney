function initInitialData() {
  document.querySelectorAll("input, select, textarea").forEach((el) => {
    el.dataset.initial = el.value;
  });
}

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

// Manage buttons if edited
function confirmChange(btn) {
  const parent = btn.closest(".profil-info");

  parent
    .querySelector(".edit-btn")
    ?.style.setProperty("display", "inline-block");
  parent.querySelector(".save-btn")?.style.setProperty("display", "none");
  parent.querySelector(".reset-btn")?.style.setProperty("display", "none");

  const champ = parent.querySelector("input, select, textarea");
  if (champ) {
    champ.disabled = true;
  }

  document.querySelector(".send-btn").style.display = detectChange()
    ? "inline-block"
    : "none";
}

// Reset inputs
function resetInput(btn, previous_value) {
  const parent = btn.closest(".profil-info");

  parent
    .querySelector(".edit-btn")
    ?.style.setProperty("display", "inline-block");
  parent.querySelector(".save-btn")?.style.setProperty("display", "none");
  parent.querySelector(".reset-btn")?.style.setProperty("display", "none");

  const champ = parent.querySelector("input, select, textarea");
  if (champ) {
    champ.value = previous_value;
    champ.disabled = true;
  }

  document.querySelector(".send-btn").style.display = detectChange()
    ? "inline-block"
    : "none";
}

function popNotif(message, type = 0) {
  const notif = document.getElementById("notif");
  notif.textContent = message;
  if (type) {
    notif.classList.add(type);
  } // type = good or bad
  notif.classList.remove("hidden"); // Make element visible in layout

  // Allow browser to reflow before applying fade-in
  setTimeout(() => {
    notif.classList.add("show"); // Trigger fade-in
  }, 10);

  // Auto-hide after 3 seconds
  setTimeout(() => {
    notif.classList.remove("show"); // Start fade-out
    if (type) {
      notif.classList.remove(type);
    }
    setTimeout(() => {
      notif.classList.add("hidden"); // Fully hide after fade-out completes
    }, 500); // Match CSS transition duration
  }, 3000);
}

// Form validation
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
      popNotif("Tous les champs obligatoires doivent être remplis.", "bad");
      return false;
    }
  }
  return true;
}

function resetForm() {
  // put back the form in disabled for some unselected inputs
  document.querySelectorAll("input, select, textarea").forEach((input) => {
    const parent = input.closest(".profil-info");
    const editBtn = parent.querySelector(".edit-btn");
    if (editBtn && window.getComputedStyle(editBtn).display != "none") {
      // if button exist and is visible
      input.disabled = true;
    } else {
      input.value = input.dataset.initial;
      popNotif(
        "Tous les changements non sauvegardés n'ont pas été pris en compte."
      );
      parent.querySelector(".save-btn")?.style.setProperty("display", "none");
      parent.querySelector(".reset-btn")?.style.setProperty("display", "none");
      editBtn?.style.setProperty("display", "inline-block");
      input.disabled = true;
    }
  });
}

// Enable inputs for edit
document.querySelectorAll(".edit-btn").forEach((btn) => {
  btn.addEventListener("click", () => {
    const parent = btn.closest(".profil-info");
    btn.style.display = "none";

    const champ = parent.querySelector("input, select, textarea");
    if (champ) {
      champ.disabled = false;
      champ.focus();
    }

    parent
      .querySelector(".save-btn")
      ?.style.setProperty("display", "inline-block");
    parent
      .querySelector(".reset-btn")
      ?.style.setProperty("display", "inline-block");
  });
});

// Email validation
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
            <li>Elle doit comporter un nom de domaine valide</li>
            <li>Elle doit se terminer par une extension (.com, .fr...)</li>
          </ul>`;
      emailError.style.display = "block";
    }
  });
}

// Phone validation
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
            <li>Uniquement des chiffres (sans espace ni lettre)</li>
            <li>10 chiffres minimum</li>
            <li>Commence par 0 ou un indicatif international (ex : +33)</li>
          </ul>`;
      telError.style.display = "block";
    }
  });
}

// Sending the form
const form = document.getElementById("profilForm");
if (form) {
  form.addEventListener("submit", async (e) => {
    e.preventDefault(); // prevent normal sending of the form

    if (!validateForm()) return;

    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries()); // transform data into dictionary

    try {
      const response = await fetch("profil_update.php", {
        method: "POST",
        body: JSON.stringify(data), // make the dictonary into json
        headers: { "Content-Type": "application/json" },
      });

      const result = await response.json();

      if (response.ok && result.success) {
        popup_message = "Mise à jour réussie.";
        popNotif(popup_message, "good");
        resetForm();
        initInitialData(); // Reset reference dataset after each update
        document.querySelector(".send-btn").style.display = "none";
      } else {
        throw new Error(result.message || "Erreur inconnue");
      }
    } catch (error) {
      popup_message = "Échec de la mise à jour : " + error.message;
      popNotif(popup_message, "bad");
    }
  });
}

// Init at first loading
initInitialData();
