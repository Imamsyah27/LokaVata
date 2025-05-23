document.getElementById('login').addEventListener('click', function() {
    document.getElementById('container').classList.remove('active');
});

document.getElementById('register').addEventListener('click', function() {
    document.getElementById('container').classList.add('active');
});

document.addEventListener("DOMContentLoaded", function () {
    const toggleSignupPassword = document.getElementById('toggleSignupPassword');
    if (toggleSignupPassword) {
        toggleSignupPassword.addEventListener('click', function () {
            const passwordInput = document.getElementById('signupPassword');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    // Toggle password for sign-up
    const toggleSignupPassword = document.getElementById('toggleSignupPassword');
    if (toggleSignupPassword) {
        toggleSignupPassword.addEventListener('click', function () {
            const passwordInput = document.getElementById('signupPassword');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    }

    // Toggle password for sign-in
    const toggleLoginPassword = document.getElementById('toggleLoginPassword');
    if (toggleLoginPassword) {
        toggleLoginPassword.addEventListener('click', function () {
            const passwordInput = document.getElementById('loginPassword');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    }
});

// Toggle for sign-up password
const toggleSignupPassword = document.getElementById('toggleSignupPassword');

// Toggle for sign-up confirm password
const toggleSignupConfirmPassword = document.getElementById('toggleSignupConfirmPassword');
if (toggleSignupConfirmPassword) {
    toggleSignupConfirmPassword.addEventListener('click', function () {
        const confirmInput = document.getElementById('signupConfirmPassword');
        const type = confirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmInput.setAttribute('type', type);
        this.textContent = type === 'password' ? '👁️' : '🙈';
    });
};

