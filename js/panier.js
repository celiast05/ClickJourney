document.addEventListener("DOMContentLoaded", () => {
    const dataTag = document.getElementById("prix-data");
    if (!dataTag) return;
  
    const { basePrix, hebergements, activites } = JSON.parse(dataTag.textContent);
    const inputs = document.querySelectorAll('input[type="number"], input[type="radio"], input[type="checkbox"]');
    const estimation = document.getElementById('estimation');
  
    function calculerPrix() {
      let total = 0;
  
      const checkedHebergement = document.querySelector('input[name="hebergements"]:checked');
      if (checkedHebergement) {
        const nb = parseInt(document.querySelector(`input[name="nb_personnes[${checkedHebergement.value}]"]`)?.value || 0);
        total += basePrix * nb;
        total += hebergements[checkedHebergement.value] * nb;
      }
  
      const checkedActivites = document.querySelectorAll('input[type="checkbox"]:checked');
      checkedActivites.forEach(act => {
        const nb = parseInt(document.querySelector(`input[name="nb_personnes[${act.value}]"]`)?.value || 0);
        total += activites[act.value] * nb;
      });
  
      if (estimation) {
        estimation.textContent = total;
      }
    }
  
    inputs.forEach(input => {
      input.addEventListener('input', calculerPrix);
      input.addEventListener('change', calculerPrix);
    });
  
    calculerPrix();
  });
  