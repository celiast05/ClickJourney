// Fetch voyages data
let voyagesData = [];

fetch('get_voyage.php')
  .then(response => response.json())
  .then(data => {
    voyagesData = data;
    updateResults(); // Update results after fetching data
  })
  .catch(error => console.error('Error loading voyages:', error));

// Search function
function searchVoyages(searchTerm) {
    if (!searchTerm.trim()) return voyagesData;     // Si aucun mot n'est saisi → on retourne tous les voyages

    const normalize = (text) => {
        return text.toString().toLowerCase()
            .normalize("NFD") // enlève les accents
            .replace(/[\u0300-\u036f]/g, "");
    };

    const normalizedSearch = normalize(searchTerm); // transforme le mot-clé recherché

    return voyagesData.filter(voyage => {
        // On vérifie dans le nom
        if (normalize(voyage.nom).includes(normalizedSearch)) return true;
        
        // Dans la description
        if (normalize(voyage.description).includes(normalizedSearch)) return true;
        
        // Dans les mots-clés
        if (voyage.mots_cles && voyage.mots_cles.some(mot => 
            normalize(mot).includes(normalizedSearch))) return true;

        // Dans le pays
        if (normalize(voyage.pays).includes(normalizedSearch)) return true;
        
        // Check theme
        if (normalize(voyage.theme).includes(normalizedSearch)) return true;

        return false; // si rien ne correspond, on exclut
    });
}

//La fonction sortByPrice trie dynamiquement les voyages selon le prix, soit du moins cher au plus cher, soit l’inverse. Elle est déclenchée dès que l’utilisateur sélectionne un tri dans la liste "Prix". C’est une méthode native .sort() sur tableau.
function sortByPrice(voyages, order) {
    return [...voyages].sort((a, b) => {
        return order === 'asc' ? a.prix - b.prix : b.prix - a.prix;
    });
}

// Filter by multiple criteria
function filterVoyages(voyages) {
    // On récupère tous les filtres cochés dans le HTML
    const selectedClimats = Array.from(document.querySelectorAll('input[name="climat"]:checked'))
        .map(input => input.value);
    const selectedPays = Array.from(document.querySelectorAll('input[name="pays"]:checked'))
        .map(input => input.value);
    const selectedThemes = Array.from(document.querySelectorAll('input[name="theme"]:checked'))
        .map(input => input.value);
     
        // On retourne uniquement les voyages qui passent tous les filtres cochés

    return voyages.filter(voyage => {
        // Apply climate filter
        if (selectedClimats.length && !selectedClimats.includes(voyage.climat_saison)) {
            return false;  // si le climat du voyage ne correspond pas
        }
        
        // Apply country filter
        if (selectedPays.length && !selectedPays.includes(voyage.pays)) {
            return false; // si le pays ne correspond pas
        }
        
        // Apply theme filter
        if (selectedThemes.length && !selectedThemes.includes(voyage.themes)) {
            return false;
        }
        
        return true; // le voyage passe tous les filtres → on le garde
    });
}

// Apply all filters and search
function updateResults() {
    console.log('Voyages data:', voyagesData); // Debug log

    let results = [...voyagesData];
    
    // Apply search if there's a search term
    const searchTerm = document.querySelector('input[name="keyword"]').value;
    if (searchTerm.trim()) {
        results = searchVoyages(searchTerm);
    }
    
    // Apply filters
    results = filterVoyages(results);
    
    // Apply price sorting
    const priceSort = document.getElementById('filter-prix').value;
    if (priceSort) {
        results = sortByPrice(results, priceSort);
    }
    
    displayResults(results);
}

// Display results function
function displayResults(voyages) {
    const container = document.getElementById('voyageCards');
    container.innerHTML = '';

    if (voyages.length === 0) {
        container.innerHTML = '<p>Aucun voyage trouvé</p>';
        return;
    }

    voyages.forEach(voyage => {
        const card = `
            <div class="card">
                <div class="card-image" style="background-image: url('${voyage.image}');"></div>
                <div class="card-content">
                    <h2>${voyage.nom}</h2>
                    <p class="category">${voyage.theme}</p>
                    <p class="taille">${voyage.description}</p>
                    <a href="details.php?voyage=${voyage.id}" class="cta">
                        Découvrir
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="10" viewBox="0 0 46 16">
                            <path d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z" transform="translate(30)"></path>
                        </svg>
                    </a>
                </div>
            </div>
        `;
        container.innerHTML += card;
    });
}

// Écouteur d’événement sur la liste de tri par prix
document.getElementById('filter-prix').addEventListener('change', updateResults);

// Écouteur d’événements sur **chaque case à cocher** (climat, pays, thème)
document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', updateResults);
});

// Recherche dynamique dès qu’on tape dans la barre de recherche
document.querySelector('input[name="keyword"]').addEventListener('input', updateResults);

// Mise à jour initiale à l’ouverture de la page
updateResults();