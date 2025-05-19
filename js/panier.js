document.addEventListener("DOMContentLoaded", function () {
  // Récupération des données de prix
  const prixData = JSON.parse(document.getElementById("prix-data").textContent);
  const form = document.getElementById("reservation-form");
  const estimationElement = document.getElementById("estimation");

  // Fonction de calcul du prix
  function calculerPrix() {
    // Calcul de la durée
    let duree = 1;
    const dateDepart = document.getElementById("date_depart").value;
    const dateRetour = document.getElementById("date_retour").value;

    if (dateDepart && dateRetour) {
      const diffTime = new Date(dateRetour) - new Date(dateDepart);
      duree = Math.max(1, diffTime / (1000 * 60 * 60 * 24));
    }

    // Calcul hébergement
    let prixHebergement = 0;
    const hebergementRadio = form.querySelector(
      'input[name="hebergements"]:checked'
    );
    if (hebergementRadio) {
      const hebergement = hebergementRadio.value;
      const nbPersonnes =
        hebergementRadio.closest("label").querySelector('input[type="number"]')
          .value || 1;
      prixHebergement =
        prixData.hebergements[hebergement] * duree * nbPersonnes;
    }

    // Calcul activités
    let prixActivites = 0;
    form
      .querySelectorAll('input[name="activites[]"]:checked')
      .forEach((activite) => {
        const nbPersonnes =
          activite.closest("label").querySelector('input[type="number"]')
            .value || 1;
        prixActivites += prixData.activites[activite.value] * nbPersonnes;
      });

    // Calcul total
    const nbPersonnesHebergement = hebergementRadio
      ? hebergementRadio.closest("label").querySelector('input[type="number"]')
          .value || 1
      : 1;

    let total =
      prixData.basePrix * nbPersonnesHebergement +
      prixHebergement +
      prixActivites;
    if (prixData.role == "vip") {
      total = total * 0.8;
    }

    // Affichage
    estimationElement.textContent = total.toLocaleString("fr-FR");
  }

  // Écouteurs d'événements
  form.addEventListener("change", function (e) {
    if (e.target.matches('input[type="radio"], input[type="checkbox"]')) {
      toggleInput();
    }
    if (
      e.target.matches(
        'input[type="number"], input[type="date"], input[type="radio"], input[type="checkbox"]'
      )
    ) {
      calculerPrix();
    }
  });

  // Initialisation
  calculerPrix();
});
