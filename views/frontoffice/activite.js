document.querySelectorAll('.activity-card').forEach(card => {
    const img = card.querySelector('img');
    const info = card.querySelector('.activity-info');
    
    img.addEventListener('click', () => {
      // Ouvre ou ferme les informations en fonction de l'état actuel
      card.classList.toggle('open');
      
      // Gestion de la disponibilité
      const available = info.querySelector('.reserve-btn');
      const unavailable = info.querySelector('.unavailable');
      
      const placesDisponibles = parseInt(info.querySelector('p:nth-child(3)').textContent.split(':')[1].trim());
      
      // Si des places sont disponibles, afficher le bouton de réservation, sinon afficher un message d'indisponibilité
      if (placesDisponibles > 0) {
        available.style.display = 'block';
        unavailable.style.display = 'none';
      } else {
        available.style.display = 'none';
        unavailable.style.display = 'block';
      }
    });
  });
// Initialisation de la carte
/*var map = L.map('map').setView([33.914, 8.131], 7); // Centré sur Tozeur (au besoin, ajustez la vue selon les villes)

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Coordonnées des activités avec photos et noms
var activities = [
  {
    name: "Soirée",
    city: "Tozeur",
    coordinates: [33.914, 8.131], // Espace spécifique pour la soirée
    image: "image/soiree.jpg"
  },
  {
    name: "Quad Aventure",
    city: "Tozeur",
    coordinates: [33.915, 8.132], // Espace spécifique pour l'activité quad
    image: "image/quad.jpg"
  },
  {
    name: "Cinéma en plein air",
    city: "Tozeur",
    coordinates: [33.916, 8.133], // Espace spécifique pour le cinéma
    image: "image/cinema.jpg"
  },
  {
    name: "Parapente",
    city: "Tabarka",
    coordinates: [37.283, 8.750],
    image: "image/parapante.jpg"
  },
  {
    name: "Plongée sous-marine",
    city: "Tabarka",
    coordinates: [37.285, 8.753],
    image: "image/plongee3.jpg"
  },
  {
    name: "Randonnée en montagne",
    city: "Tabarka",
    coordinates: [37.290, 8.760],
    image: "image/randonnee1.jpg"
  },
  {
    name: "Kayak",
    city: "Rafraf",
    coordinates: [36.930, 10.878],
    image: "image/kayak.jpg"
  },
  {
    name: "Yoga",
    city: "Rafraf",
    coordinates: [36.932, 10.879],
    image: "image/yoga1.jpg"
  },
  {
    name: "Création",
    city: "Rafraf",
    coordinates: [36.934, 10.880],
    image: "image/creation.jpg"
  }
];
*/
// Ajouter des marqueurs pour chaque activité
activities.forEach(function(activity) {
  var marker = L.marker(activity.coordinates).addTo(map);
  var popupContent = '<h3>' + activity.name + '</h3><img src="' + activity.image + '" alt="' + activity.name + '" style="width:100%; max-width:150px;">';
  marker.bindPopup(popupContent);
});
 document.querySelectorAll('.details-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const detailsContent = btn.nextElementSibling; // Le div contenant les détails
    const activityCard = btn.closest('.activity-card'); // La carte de l'activité entière
    const isVisible = detailsContent.classList.contains('show');
    
    // Cache tous les détails de l'activité avant d'afficher celui sur lequel on clique
    document.querySelectorAll('.details-content').forEach(content => {
      content.classList.remove('show');
    });

    // Cache le nom de l'activité et le bouton "Voir les détails"
    activityCard.querySelector('h3').style.display = isVisible ? 'block' : 'none';
    activityCard.querySelector('.details-btn').style.display = isVisible ? 'block' : 'none';

    // Affiche ou cache le contenu de l'activité cliquée
    if (!isVisible) {
      detailsContent.classList.add('show');
    } else {
      detailsContent.classList.remove('show');
    }
  });
});

document.querySelectorAll('.details-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const detailsContent = btn.nextElementSibling; // Le div contenant les détails
    const activityCard = btn.closest('.activity-card'); // La carte de l'activité entière
    const isVisible = detailsContent.classList.contains('show');
    
    // Cache tous les détails de l'activité avant d'afficher celui sur lequel on clique
    document.querySelectorAll('.details-content').forEach(content => {
      content.classList.remove('show');
    });

    // Cache le nom de l'activité et le bouton "Voir les détails"
    activityCard.querySelector('h3').style.display = isVisible ? 'block' : 'none';
    activityCard.querySelector('.details-btn').style.display = isVisible ? 'block' : 'none';

    // Affiche ou cache le contenu de l'activité cliquée
    if (!isVisible) {
      detailsContent.classList.add('show');
    } else {
      detailsContent.classList.remove('show');
    }
  });
});

document.querySelectorAll('.details-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const detailsContent = btn.nextElementSibling; // Le div contenant les détails
    const activityCard = btn.closest('.activity-card'); // La carte de l'activité entière
    const isVisible = detailsContent.classList.contains('show');
    
    // Cache tous les détails de l'activité avant d'afficher celui sur lequel on clique
    document.querySelectorAll('.details-content').forEach(content => {
      content.classList.remove('show');
    });

    // Cache le nom de l'activité et le bouton
    activityCard.querySelector('h3').style.display = isVisible ? 'block' : 'none';
    activityCard.querySelector('.details-btn').style.display = isVisible ? 'block' : 'none';

    // Affiche ou cache le contenu de l'activité cliquée
    if (!isVisible) {
      detailsContent.classList.add('show');
    } else {
      detailsContent.classList.remove('show');
    }
  });
});
document.querySelectorAll('.reserve-btn').forEach(button => {
  button.addEventListener('click', function() {
    const card = this.closest('.activity-card');
    const title = card.querySelector('h3').textContent;
    console.log('Titre de l\'activité:', title); // Vérification
    const activityId = title.toLowerCase().replace(/\s+/g, '-');
    console.log('Identifiant de l\'activité:', activityId); // Vérification
    window.location.href = `details.html?id=${activityId}`;
  });
});



