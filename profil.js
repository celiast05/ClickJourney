// Faire save et enregistrer au moins une modif est fait

document.querySelectorAll(".edit-btn").forEach((btn) => {
  btn.addEventListener("click", () => {
    const parent = btn.closest(".profil-info");
    btn.style.display = "none";
    document.querySelector(".send-btn").style.display = "inline-block";

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
}

// function resetInput(btn) {
//   const parent = btn.closest(".profil-info");

//   const champ1 = parent.querySelector(".edit-btn");
//   if (champ1) {
//     champ1.style.display = "inline-block";
//   }

//   const champ2 = parent.querySelector(".save-btn");
//   if (champ2) {
//     champ2.style.display = "none";
//   }

//   const champ3 = parent.querySelector(".reset-btn");
//   if (champ3) {
//     champ3.style.display = "none";
//   }
// }

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
