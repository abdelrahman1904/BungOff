//la page index 
document.getElementById('toggleSidebar').addEventListener('click', function () {
    document.querySelector('.sidebar').classList.toggle('collapsed');
    document.querySelector('.content').classList.toggle('collapsed-content');
});
// de la pge ajout 
document.getElementById('vehicleForm').addEventListener("submit", function(event) {
    event.preventDefault(); // Empêcher la soumission immédiate du formulaire
    
    // Récupération des valeurs des champs
    const type = document.getElementById("type").value;
    const matricule = document.getElementById("matricule").value.trim();
    const model = document.getElementById("model").value.trim();
    const capacite = document.getElementById("capacite").value;
    const dispo = document.getElementById("dispo").value;

    // Variable pour vérifier s'il y a des erreurs
    let hasError = false;

    // Validation de chaque champ
    if (type === "") {
        alert("Veuillez choisir un type de véhicule.");
        hasError = true;
    }

    if (matricule === "") {
        alert("Le matricule est requis.");
        hasError = true;
    } else if (!/^[A-Za-z0-9-]+$/.test(matricule)) {
        alert("Le matricule ne doit contenir que des lettres, chiffres ou tirets.");
        hasError = true;
    }

    if (model === "") {
        alert("Le modèle est requis.");
        hasError = true;
    }

    if (capacite === "" || parseInt(capacite) <= 0) {
        alert("La capacité doit être un nombre positif.");
        hasError = true;
    }

    if (dispo === "") {
        alert("Veuillez sélectionner la disponibilité.");
        hasError = true;
    }

    // Si une erreur est présente, ne pas soumettre le formulaire
    if (hasError) {
        return false; // Empêche la soumission
    } else {
        // Si tout est correct, soumettre le formulaire
        alert("Le formulaire est valide, soumission en cours...");
        document.getElementById('vehicleForm').submit(); // Soumettre le formulaire
    }
});