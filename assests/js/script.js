// ================= ERRORS =================
const errorContainer = document.getElementById('error-container');
const errorList = document.getElementById('error-list');

function showErrors(errors) {
    if (!errors || errors.length === 0) return;

    errorList.innerHTML = '';
    errors.forEach(error => {
        const li = document.createElement('li');
        li.textContent = error;
        errorList.appendChild(li);
    });

    errorContainer.classList.remove('hidden');
}

// ================= SUCCESS =================
function showSuccess(message) {
    if (!message) return;

    const successContainer = document.getElementById('success-container');
    const successMessage = document.getElementById('success-message');

    successMessage.textContent = message;
    successContainer.classList.remove('hidden');
}

// ================= MOBILE MENU =================
const mobileMenuBtn = document.getElementById('mobile-menu-btn');
const mobileMenu = document.getElementById('mobile-menu');

if (mobileMenuBtn && mobileMenu) {
    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
}

// ================= FORM VALIDATION =================
const form = document.querySelector('form');

if (form) {
    form.addEventListener('submit', function (e) {
        let erreurs = [];

        const nom = document.getElementById('nom').value.trim();
        const prenom = document.getElementById('prenom').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('mot_de_passe').value;
        const role = document.querySelector('input[name="role"]:checked');

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const passRegex = /^(?=.*[A-Z])(?=.*\d).{8,}$/;

        if (nom.length < 2) erreurs.push("Le nom est trop court.");
        if (prenom.length < 2) erreurs.push("Le prénom est trop court.");
        if (!emailRegex.test(email)) erreurs.push("L'adresse email n'est pas valide.");
        if (!passRegex.test(password)) {
            erreurs.push("Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.");
        }
        if (!role) erreurs.push("Veuillez choisir si vous êtes Sportif ou Coach.");
        console.log("Erreurs détectées:", erreurs); 

        if (erreurs.length > 0) {
            e.preventDefault();
            showErrors(erreurs);
        }
    });
}


