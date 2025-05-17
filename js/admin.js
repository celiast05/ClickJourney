async function send_request(email, new_role) {
  const data = {
    user_email: email,
    role: new_role,
  }; // transform data into dictionary

  try {
    const response = await fetch("role_update.php", {
      method: "POST",
      body: JSON.stringify(data), // make the dictonary into json
      headers: { "Content-Type": "application/json" },
    });

    const result = await response.json();

    if (response.ok && result.success) {
      if (result.redirect) {
        // user isn't an admin anymore
        window.location.href = result.redirect;
      } else {
        alert("Mise à jour réussie.");
      }
    } else {
      throw new Error(result.message || "Erreur inconnue");
    }
  } catch (error) {
    alert("Échec de la mise à jour : " + error.message);
  }
}

async function changeRole(btn) {
  let current_role = btn.className;
  btn.classList.add("gray");

  let previousTd = btn.closest("td").previousElementSibling; // td conataining the email
  let user_email = previousTd.textContent;

  btn.classList.remove(current_role);

  const roles = ["admin", "vip", "bannir", "normal"];
  const roles_printed = ["Admin", "VIP", "Bloqué", "Classic"]; // Text to print in PHP

  let new_role;

  roles.forEach(function (item, index) {
    if (item === current_role) {
      new_role = roles[(index + 1) % roles.length];
      send_request(user_email, new_role);
      btn.classList.remove("gray");
      if (new_role) btn.classList.add(new_role);
      btn.textContent = roles_printed[(index + 1) % roles.length];
      return 0;
    }
  });
}

document.querySelectorAll("button").forEach((btn) => {
  btn.addEventListener("click", () => {
    changeRole(btn);
  });
});
