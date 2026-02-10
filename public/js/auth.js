// auth.js - Login and Registration Logic

document.addEventListener('DOMContentLoaded', function() {
    redirectIfAuthenticated();

    const loginForm = document.getElementById('loginForm');
    const signUpForm = document.getElementById('signUpForm');

    if (loginForm) {
        initializeLogin();
    }

    if (signUpForm) {
        initializeSignUp();
    }
});

function initializeLogin() {
    const loginForm = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');

    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;

        if (!email || !password) {
            showError('Please fill in all fields');
            return;
        }

        loginButton.disabled = true;
        loginButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Logging in...';

        try {
            const response = await AuthAPI.login(email, password);

            setAuthToken(response.data.token);
            setUserData(response.data.user);

            showSuccess('Login successful! Redirecting...');

            setTimeout(() => {
                window.location.href = '/userDashboard.html';
            }, 1000);

        } catch (error) {
            let errorMessage = 'Login failed. Please try again.';

            if (error.status === 401) {
                errorMessage = 'Invalid email or password.';
            } else if (error.message) {
                errorMessage = error.message;
            }

            showError(errorMessage);
            loginButton.disabled = false;
            loginButton.innerHTML = '<i class="fas fa-sign-in-alt mr-2"></i>Log In';
        }
    });
}

function initializeSignUp() {
    const signUpForm = document.getElementById('signUpForm');
    const signUpButton = document.getElementById('signUpButton');

    signUpForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;

        if (!name || !email || !password || !passwordConfirmation) {
            showError('Please fill in all fields');
            return;
        }

        if (password.length < 8) {
            showError('Password must be at least 8 characters long');
            return;
        }

        if (password !== passwordConfirmation) {
            showError('Passwords do not match');
            return;
        }

        signUpButton.disabled = true;
        signUpButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Account...';

        try {
            const response = await AuthAPI.register(name, email, password, passwordConfirmation);

            // Nu mai salvăm token-ul - utilizatorul trebuie să se logheze manual
            // setAuthToken(response.data.token);
            // setUserData(response.data.user);

            showSuccess('Account created successfully! Please login.');

            setTimeout(() => {
                window.location.href = '/login.html';
            }, 1500);

        } catch (error) {
            let errorMessage = 'Registration failed. Please try again.';

            if (error.errors && error.errors.email) {
                errorMessage = error.errors.email[0];
            } else if (error.errors && error.errors.password) {
                errorMessage = error.errors.password[0];
            } else if (error.message) {
                errorMessage = error.message;
            }

            showError(errorMessage);
            signUpButton.disabled = false;
            signUpButton.innerHTML = '<i class="fas fa-user-plus mr-2"></i>Create Account';
        }
    });
}

function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');

    if (input && icon) {
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
}

function togglePasswordConfirmation() {
    const input = document.getElementById('password_confirmation');
    const icon = document.getElementById('toggleConfirmIcon');

    if (input && icon) {
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
}
