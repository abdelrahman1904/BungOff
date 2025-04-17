// Coordonnées des villes (latitude, longitude)
const villes = {
    'Tunis': [36.8065, 10.1815],
    'Tozeur': [33.9197, 8.1335],
    'Tabarka': [36.9544, 8.7582],
    'Rafraf': [37.1902, 10.1835]
};

// Couleurs des itinéraires
const colors = {
    'Tunis-Tozeur': '#126cb6',
    'Tunis-Tabarka': '#00b609', 
    'Tunis-Rafraf': '#b49786'
};

// Initialisation des cartes avec itinéraires
function initMaps() {
    const trajets = [
        { id: 'map1', depart: 'Tunis', arrivee: 'Tozeur' },
        { id: 'map2', depart: 'Tunis', arrivee: 'Tabarka' },
        { id: 'map3', depart: 'Tunis', arrivee: 'Rafraf' },
        { id: 'map4', depart: 'Tozeur', arrivee: 'Tunis' },
        { id: 'map5', depart: 'Tabarka', arrivee: 'Tunis' },
        { id: 'map6', depart: 'Rafraf', arrivee: 'Tunis' }
    ];

    trajets.forEach(trajet => {
        const mapElement = document.getElementById(trajet.id);
        if (mapElement) {
            // Création de la carte
            const map = L.map(trajet.id).setView([
                (villes[trajet.depart][0] + villes[trajet.arrivee][0]) / 2,
                (villes[trajet.depart][1] + villes[trajet.arrivee][1]) / 2
            ], 7);

            // Ajout du fond de carte
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // Ajout des marqueurs
            L.marker(villes[trajet.depart]).addTo(map)
                .bindPopup(`<b>Départ:</b> ${trajet.depart}`)
                .openPopup();

            L.marker(villes[trajet.arrivee]).addTo(map)
                .bindPopup(`<b>Arrivée:</b> ${trajet.arrivee}`);

            // Détermination de la couleur
            const trajetKey = trajet.depart + '-' + trajet.arrivee;
            const color = colors[trajetKey] || '#666';

            // Ajout de l'itinéraire
            L.Routing.control({
                waypoints: [
                    L.latLng(villes[trajet.depart]),
                    L.latLng(villes[trajet.arrivee])
                ],
                routeWhileDragging: false,
                show: false,
                addWaypoints: false,
                draggableWaypoints: false,
                fitSelectedRoutes: true,
                lineOptions: {
                    styles: [{ color, opacity: 0.7, weight: 5 }]
                }
            }).addTo(map);
        }
    });
}

// Initialisation au chargement
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.carte-container')) {
        initMaps();
    }
    
    // Empêche le zoom avec la molette sur les cartes
    document.querySelectorAll('.carte-container').forEach(map => {
        map.addEventListener('wheel', e => e.preventDefault());
    });
});


  function reserverTrajet(id) {
    alert("Trajet " + id + " réservé !");
  }

  function annulerTrajet(id) {
    alert("Trajet " + id + " annulé !");
  }
// partie reserver 
document.getElementById("reservationForm").addEventListener("submit", function(event) {
    let nom = document.getElementById("nom").value.trim();
    let email = document.getElementById("email").value.trim();
    let trajet = document.getElementById("trajet").value;
    let date = document.getElementById("date").value;
    let nombre = parseInt(document.getElementById("nombre").value);
    let places = parseInt(document.getElementById("places").value);

    // Vérification du nom
    if (nom.length < 3) {
        alert("Veuillez entrer un nom complet valide (au moins 3 caractères).");
        event.preventDefault();
        return;
    }

    // Vérification de l'email simple
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert("Veuillez entrer une adresse email valide.");
        event.preventDefault();
        return;
    }

    // Vérification du trajet
    if (!trajet) {
        alert("Veuillez choisir un trajet.");
        event.preventDefault();
        return;
    }

    // Vérification de la date (future ou aujourd'hui)
    let today = new Date().toISOString().split('T')[0];
    if (date < today) {
        alert("Veuillez choisir une date de départ valide (aujourd'hui ou plus tard).");
        event.preventDefault();
        return;
    }

    // Vérification du nombre de passagers
    if (isNaN(nombre) || nombre < 1) {
        alert("Veuillez entrer un nombre de passagers valide (au moins 1).");
        event.preventDefault();
        return;
    }


    // Vérifie que le nombre de places <= nombre de passagers
    if (places > nombre) {
        alert("Le nombre de places ne peut pas dépasser le nombre de passagers.");
        event.preventDefault();
        return;
    }
});
// partie annuler
document.getElementById("annulationForm").addEventListener("submit", function(event) {
    const nom = document.getElementById("nom").value.trim();
    const email = document.getElementById("email").value.trim();
    const numero = document.getElementById("numero").value.trim();
    const raison = document.getElementById("raison").value.trim();

    // Vérification du nom
    if (nom.length < 3) {
        alert("Veuillez entrer un nom complet valide (au moins 3 caractères).");
        event.preventDefault();
        return;
    }

    // Vérification de l'email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert("Veuillez entrer une adresse email valide.");
        event.preventDefault();
        return;
    }

    // Vérification du numéro de réservation
    if (numero.length < 5) {
        alert("Veuillez entrer un numéro de réservation valide (au moins 5 caractères).");
        event.preventDefault();
        return;
    }

    // Vérification de la raison
    if (raison.length < 10) {
        alert("Veuillez préciser une raison valable pour l'annulation (au moins 10 caractères).");
        event.preventDefault();
        return;
    }
    
});






