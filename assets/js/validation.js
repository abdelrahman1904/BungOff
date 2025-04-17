document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('campaignForm');
    
    if (!form) {
        console.error('Form with ID "campaignForm" not found');
        return;
    }

    form.addEventListener('submit', function (e) {
        let isValid = true;
        const titreC = document.getElementById('titreC').value.trim();
        const descriptionC = document.getElementById('descriptionC').value.trim();
        const dateDebut = document.getElementById('date_debutC').value;
        const dateFin = document.getElementById('date_finC').value;
        const id = document.getElementById('id').value.trim();

        // Clear previous error messages
        document.querySelectorAll('.error-message').forEach(el => el.remove());

        // Title validation
        if (titreC.length < 5 || titreC.length > 10) {
            showError('titreC', 'Title must be between 5 and 10 characters.');
            isValid = false;
        }

        // Description validation
        if (descriptionC.length > 255) {
            showError('descriptionC', 'Description must not exceed 255 characters.');
            isValid = false;
        }

        // Date validation
        if (!dateDebut || !dateFin) {
            showError('date_debutC', 'Both start and end dates are required.');
            isValid = false;
        } else if (dateFin < dateDebut) {
            showError('date_finC', 'End date must be after the start date.');
            isValid = false;
        }

        // ID validation
        const idNum = parseInt(id);
        if (isNaN(idNum) || idNum <= 0) {
            showError('id', 'ID must be a positive number.');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    function showError(fieldId, message) {
        const field = document.getElementById(fieldId);
        const errorElement = document.createElement('div');
        errorElement.className = 'error-message text-danger mt-1';
        errorElement.textContent = message;
        field.parentNode.appendChild(errorElement);
        field.classList.add('is-invalid');
    }
});