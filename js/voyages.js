// Fetch voyages data
let voyagesData = [];

fetch('../get_voyage.php')
  .then(response => response.json())
  .then(data => {
    voyagesData = data;
    updateResults(); // Update results after fetching data
  })
  .catch(error => console.error('Error loading voyages:', error));

// Search function
function searchVoyages(searchTerm) {
    if (!searchTerm.trim()) return voyagesData; // Return all if empty search

    const normalize = (text) => {
        return text.toString().toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "");
    };

    const normalizedSearch = normalize(searchTerm);

    return voyagesData.filter(voyage => {
        // Check name
        if (normalize(voyage.nom).includes(normalizedSearch)) return true;
        
        // Check description
        if (normalize(voyage.description).includes(normalizedSearch)) return true;
        
        // Check keywords
        if (voyage.mots_cles && voyage.mots_cles.some(mot => 
            normalize(mot).includes(normalizedSearch))) return true;

        // Check country
        if (normalize(voyage.pays).includes(normalizedSearch)) return true;
        
        // Check theme
        if (normalize(voyage.theme).includes(normalizedSearch)) return true;

        return false;
    });
}

// Price sorting function
function sortByPrice(voyages, order) {
    return [...voyages].sort((a, b) => {
        return order === 'asc' ? a.prix - b.prix : b.prix - a.prix;
    });
}

// Filter by multiple criteria
function filterVoyages(voyages) {
    // Get selected filters
    const selectedClimats = Array.from(document.querySelectorAll('input[name="climat"]:checked'))
        .map(input => input.value);
    const selectedPays = Array.from(document.querySelectorAll('input[name="pays"]:checked'))
        .map(input => input.value);
    const selectedThemes = Array.from(document.querySelectorAll('input[name="theme"]:checked'))
        .map(input => input.value);
    
    return voyages.filter(voyage => {
        // Apply climate filter
        if (selectedClimats.length && !selectedClimats.includes(voyage.climat_saison)) {
            return false;
        }
        
        // Apply country filter
        if (selectedPays.length && !selectedPays.includes(voyage.pays)) {
            return false;
        }
        
        // Apply theme filter
        if (selectedThemes.length && !selectedThemes.includes(voyage.themes)) {
            return false;
        }
        
        return true;
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

// Add event listeners
document.getElementById('filter-prix').addEventListener('change', updateResults);

// Add listeners for checkboxes
document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', updateResults);
});

// Update search listener to use combined filtering
document.querySelector('input[name="keyword"]').addEventListener('input', updateResults);

// Initial display
updateResults();