function sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

async function changeRole(btn) {
  let current_role = btn.className;
  btn.classList.add("gray");

  await sleep(2000); // Pause de 2 secondes ici

  btn.classList.remove(current_role);

  const roles = ["admin", "vip", "bannir", "normal"];
  const roles_printed = ["Admin", "VIP", "Bloqué", "Classic"]; // Text to print in PHP

  let new_role;

  roles.forEach(function (item, index) {
    if (item === current_role) {
      new_role = roles[(index + 1) % roles.length];
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
