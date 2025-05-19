function popNotif(message,type=0) {
  const notif = document.getElementById('notif');
  notif.textContent = message;
  if(type){notif.classList.add(type)}; // type = good or bad
  notif.classList.remove('hidden'); // Make element visible in layout

  // Allow browser to reflow before applying fade-in
  setTimeout(() => {
    notif.classList.add('show'); // Trigger fade-in
  }, 10);

  // Auto-hide after 3 seconds
  setTimeout(() => {
    notif.classList.remove('show'); // Start fade-out
    if(type){notif.classList.remove(type)}
    setTimeout(() => {
      notif.classList.add('hidden'); // Fully hide after fade-out completes
    }, 500); // Match CSS transition duration
  }, 3000);
}

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
        popNotif("Mise à jour réussie.",'good');
      }
    } else {
      throw new Error(result.message || "Erreur inconnue");
    }
  } catch (error) {
    popNotif("Échec de la mise à jour : " + error.message, 'bad');
  }
}

async function changeRole(btn) {
  let current_role = btn.className;
  btn.classList.add("gray");

  let previousTd = btn.closest("td").previousElementSibling; // td containing the email
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
