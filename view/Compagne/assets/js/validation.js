// validation.js

// Fonction utilitaire pour afficher les messages d'erreur
function afficherErreur(inputElement, message) {
    const parent = inputElement.parentElement;
    let erreur = parent.querySelector('.error-message');
    if (!erreur) {
        erreur = document.createElement('div');
        erreur.className = 'error-message';
        erreur.style.color = 'red';
        erreur.style.fontSize = '0.9em';
        erreur.style.marginTop = '5px';
        parent.appendChild(erreur);
    }
    erreur.textContent = message;
}

// Fonction utilitaire pour supprimer les messages d'erreur
function supprimerErreur(inputElement) {
    const parent = inputElement.parentElement;
    const erreur = parent.querySelector('.error-message');
    if (erreur) {
        erreur.remove();
    }
}

// Validation du formulaire des campagnes
function validerFormulaireCampagne(formulaire) {
    let estValide = true;

    // Récupérer les champs
    const nom = formulaire.querySelector('#nom');
    const description = formulaire.querySelector('#description');
    const dateDebut = formulaire.querySelector('#date_debut');
    const dateFin = formulaire.querySelector('#date_fin');

    // Supprimer les messages d'erreur précédents
    [nom, description, dateDebut, dateFin].forEach(champ => supprimerErreur(champ));

    // Valider le nom (max 255 caractères)
    if (!nom.value.trim()) {
        afficherErreur(nom, 'Le nom est obligatoire.');
        estValide = false;
    } else if (nom.value.length > 255) {
        afficherErreur(nom, 'Le nom ne doit pas dépasser 255 caractères.');
        estValide = false;
    }

    // Valider la description (non vide)
    if (!description.value.trim()) {
        afficherErreur(description, 'La description est obligatoire.');
        estValide = false;
    }

    // Valider la date de début
    const aujourdhui = new Date();
    aujourdhui.setHours(0, 0, 0, 0); // Réinitialiser l'heure pour comparaison
    const debut = new Date(dateDebut.value);
    if (!dateDebut.value) {
        afficherErreur(dateDebut, 'La date de début est obligatoire.');
        estValide = false;
    } else if (isNaN(debut.getTime())) {
        afficherErreur(dateDebut, 'La date de début est invalide.');
        estValide = false;
    } else if (debut < aujourdhui) {
        afficherErreur(dateDebut, 'La date de début ne peut pas être dans le passé.');
        estValide = false;
    }

    // Valider la date de fin
    const fin = new Date(dateFin.value);
    if (!dateFin.value) {
        afficherErreur(dateFin, 'La date de fin est obligatoire.');
        estValide = false;
    } else if (isNaN(fin.getTime())) {
        afficherErreur(dateFin, 'La date de fin est invalide.');
        estValide = false;
    } else if (fin <= debut) {
        afficherErreur(dateFin, 'La date de fin doit être postérieure à la date de début.');
        estValide = false;
    }

    return estValide;
}

// Validation du formulaire des promotions
function validerFormulairePromotion(formulaire) {
    let estValide = true;

    // Récupérer les champs
    const titre = formulaire.querySelector('#titreP');
    const description = formulaire.querySelector('#descriptionP');
    const pourcentage = formulaire.querySelector('#pourcentage');
    const codePromo = formulaire.querySelector('#codePromo');
    const dateDebut = formulaire.querySelector('#date_debutP');
    const dateFin = formulaire.querySelector('#date_finP');
    const campagne = formulaire.querySelector('#idC');

    // Supprimer les messages d'erreur précédents
    [titre, description, pourcentage, codePromo, dateDebut, dateFin, campagne].forEach(champ => supprimerErreur(champ));

    // Valider le titre (max 255 caractères)
    if (!titre.value.trim()) {
        afficherErreur(titre, 'Le titre est obligatoire.');
        estValide = false;
    } else if (titre.value.length > 255) {
        afficherErreur(titre, 'Le titre ne doit pas dépasser 255 caractères.');
        estValide = false;
    }

    // Valider la description (non vide)
    if (!description.value.trim()) {
        afficherErreur(description, 'La description est obligatoire.');
        estValide = false;
    }

    // Valider le pourcentage (entre 0 et 100)
    const pourcentageValeur = parseFloat(pourcentage.value);
    if (!pourcentage.value) {
        afficherErreur(pourcentage, 'Le pourcentage est obligatoire.');
        estValide = false;
    } else if (isNaN(pourcentageValeur) || pourcentageValeur < 0 || pourcentageValeur > 100) {
        afficherErreur(pourcentage, 'Le pourcentage doit être un nombre entre 0 et 100.');
        estValide = false;
    }

    // Valider le code promo (max 50 caractères si fourni)
    if (codePromo.value && codePromo.value.length > 50) {
        afficherErreur(codePromo, 'Le code promo ne doit pas dépasser 50 caractères.');
        estValide = false;
    }

    // Valider la date de début
    const aujourdhui = new Date();
    aujourdhui.setHours(0, 0, 0, 0);
    const debut = new Date(dateDebut.value);
    if (!dateDebut.value) {
        afficherErreur(dateDebut, 'La date de début est obligatoire.');
        estValide = false;
    } else if (isNaN(debut.getTime())) {
        afficherErreur(dateDebut, 'La date de début est invalide.');
        estValide = false;
    } else if (debut < aujourdhui) {
        afficherErreur(dateDebut, 'La date de début ne peut pas être dans le passé.');
        estValide = false;
    }

    // Valider la date de fin
    const fin = new Date(dateFin.value);
    if (!dateFin.value) {
        afficherErreur(dateFin, 'La date de fin est obligatoire.');
        estValide = false;
    } else if (isNaN(fin.getTime())) {
        afficherErreur(dateFin, 'La date de fin est invalide.');
        estValide = false;
    } else if (fin <= debut) {
        afficherErreur(dateFin, 'La date de fin doit être postérieure à la date de début.');
        estValide = false;
    }

    // Valider la campagne associée
    if (!campagne.value) {
        afficherErreur(campagne, 'Vous devez sélectionner une campagne.');
        estValide = false;
    }

    return estValide;
}

// Ajouter les écouteurs d'événements lors du chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Formulaire des campagnes
    const formulaireCampagne = document.querySelector('form[action*="manage_campaign.php"]');
    if (formulaireCampagne) {
        formulaireCampagne.addEventListener('submit', function(e) {
            if (!validerFormulaireCampagne(this)) {
                e.preventDefault();
            }
        });
    }

    // Formulaire des promotions
    const formulairePromotion = document.querySelector('form[action*="manage_promotion.php"]');
    if (formulairePromotion) {
        formulairePromotion.addEventListener('submit', function(e) {
            if (!validerFormulairePromotion(this)) {
                e.preventDefault();
            }
        });
    }
});