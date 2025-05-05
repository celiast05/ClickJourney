let voyages = [];

// Charger le JSON
fetch('json/voyage.json')
    .then(res => res.json())
    .then(data => {
        voyages = data;
        filtrer(); // Afficher dès le chargement
    })
    .catch(error => console.error("Erreur JSON :", error));

// Affiche les cartes
function afficherVoyages(liste) {
    const container = document.getElementById("voyageCards");
    container.innerHTML = "";

    if (liste.length === 0) {
        container.innerHTML = '<p style="text-align:center;">Aucun voyage ne correspond aux filtres.</p>';
        return;
    }

    liste.forEach(v => {
        const card = document.createElement("div");
        card.className = "card";
        card.innerHTML = `
            <div class="card-image" style="background-image: url('${v.image}');"></div>
            <div class="card-content">
                <h2>${v.nom}</h2>
                <p class="category">${v.theme}</p>
                <p class="taille">${v.description}</p>
                <a href="details.php?voyage=${v.id}" class="cta">
                    Découvrir
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="10" viewBox="0 0 46 16">
                        <path d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z" transform="translate(30)"></path>
                    </svg>
                </a>
            </div>`;
        container.appendChild(card);
    });
}

// Appliquer les filtres
function filtrer() {
    console.log("Filtrer function called"); // Vérifier que la fonction est appelée
    
    const triPrix = document.getElementById("filter-prix").value;
    const climats = Array.from(document.querySelectorAll('input[name="climat"]:checked')).map(cb => cb.value.trim());
    const pays = Array.from(document.querySelectorAll('input[name="pays"]:checked')).map(cb => cb.value.trim().toLowerCase());
    const themes = Array.from(document.querySelectorAll('input[name="theme"]:checked')).map(cb => cb.value.trim());

    console.log("Filtres actifs:", { climats, pays, themes}); // Voir ce qui est sélectionné

    let results = voyages.filter(v => {
        if (climats.length > 0 && !climats.includes(v.climat_saison)) {
            return false;
        }
        
        if (pays.length > 0 && !pays.includes(v.pays)) {
            return false;
        }
        
        if (themes.length > 0 && !themes.includes(v.themes)) {
            return false;
        }
        
        return true;
    });

    // Tri
    if (triPrix === "asc") results.sort((a, b) => a.moyenne - b.moyenne);
    if (triPrix === "desc") results.sort((a, b) => b.moyenne - a.moyenne);

    console.log("Résultats après filtrage:", results); // Voir le résultat
    afficherVoyages(results);
}

// Événements
document.getElementById("filter-prix").addEventListener("change", filtrer);
document.querySelectorAll('input[name="climat"]').forEach(cb => cb.addEventListener("change", filtrer));
document.querySelectorAll('input[name="activite"]').forEach(cb => cb.addEventListener("change", filtrer));
document.querySelectorAll('input[name="pays"]').forEach(cb => cb.addEventListener("change", filtrer));
document.querySelectorAll('input[name="theme"]').forEach(cb => cb.addEventListener("change", filtrer));
