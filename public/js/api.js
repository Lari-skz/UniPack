// API Configuration
const API_BASE_URL = 'http://localhost:8000/api';

function getAuthToken() {
    return localStorage.getItem('auth_token');
}

function setAuthToken(token) {
    localStorage.setItem('auth_token', token);
}

function removeAuthToken() {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user_data');
}

function isAuthenticated() {
    return getAuthToken() !== null;
}

function getUserData() {
    const userData = localStorage.getItem('user_data');
    return userData ? JSON.parse(userData) : null;
}

function setUserData(user) {
    localStorage.setItem('user_data', JSON.stringify(user));
}

async function apiRequest(endpoint, options = {}) {
    const token = getAuthToken();

    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...options.headers
    };

    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    const config = {
        ...options,
        headers
    };

    try {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, config);
        const data = await response.json();

        if (!response.ok) {
            throw {
                status: response.status,
                message: data.message || 'An error occurred',
                errors: data.errors || {}
            };
        }

        return data;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}

const AuthAPI = {
    async register(name, email, password, passwordConfirmation) {
        return apiRequest('/auth/register', {
            method: 'POST',
            body: JSON.stringify({
                name,
                email,
                password,
                password_confirmation: passwordConfirmation
            })
        });
    },

    async login(email, password) {
        return apiRequest('/auth/login', {
            method: 'POST',
            body: JSON.stringify({ email, password })
        });
    },

    async logout() {
        return apiRequest('/auth/logout', {
            method: 'POST'
        });
    },

    async getCurrentUser() {
        return apiRequest('/auth/user', {
            method: 'GET'
        });
    }
};

const CategoriesAPI = {
    async getAll() {
        return apiRequest('/categories', { method: 'GET' });
    },

    async create(name, description, color) {
        return apiRequest('/categories', {
            method: 'POST',
            body: JSON.stringify({ name, description, color })
        });
    },

    async update(id, data) {
        return apiRequest(`/categories/${id}`, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    },

    async delete(id) {
        return apiRequest(`/categories/${id}`, { method: 'DELETE' });
    }
};

const TasksAPI = {
    async getAll() {
        return apiRequest('/tasks', { method: 'GET' });
    },

    async create(taskData) {
        return apiRequest('/tasks', {
            method: 'POST',
            body: JSON.stringify(taskData)
        });
    },

    async update(id, taskData) {
        return apiRequest(`/tasks/${id}`, {
            method: 'PUT',
            body: JSON.stringify(taskData)
        });
    },

    async delete(id) {
        return apiRequest(`/tasks/${id}`, { method: 'DELETE' });
    }
};

const EventsAPI = {
    async getAll() {
        return apiRequest('/events', { method: 'GET' });
    },

    async create(eventData) {
        return apiRequest('/events', {
            method: 'POST',
            body: JSON.stringify(eventData)
        });
    },

    async update(id, eventData) {
        return apiRequest(`/events/${id}`, {
            method: 'PUT',
            body: JSON.stringify(eventData)
        });
    },

    async delete(id) {
        return apiRequest(`/events/${id}`, { method: 'DELETE' });
    }
};

function showError(message, elementId = 'errorMessage') {
    const errorDiv = document.getElementById(elementId);
    const errorText = document.getElementById('errorText');

    if (errorDiv && errorText) {
        errorText.textContent = message;
        errorDiv.classList.remove('hidden');
        setTimeout(() => errorDiv.classList.add('hidden'), 5000);
    } else {
        alert('Error: ' + message);
    }
}

function showSuccess(message, elementId = 'successMessage') {
    const successDiv = document.getElementById(elementId);
    const successText = document.getElementById('successText');

    if (successDiv && successText) {
        successText.textContent = message;
        successDiv.classList.remove('hidden');
        setTimeout(() => successDiv.classList.add('hidden'), 3000);
    } else {
        alert(message);
    }
}

function checkAuth() {
    if (!isAuthenticated()) {
        window.location.href = '/login.html';
        return false;
    }
    return true;
}

function redirectIfAuthenticated() {
    if (isAuthenticated()) {
        window.location.href = '/userDashboard.html';
    }
}

// Admin API calls
const AdminAPI = {
    async getStats() {
        return apiRequest('/admin/stats', {
            method: 'GET'
        });
    },

    async listUsers() {
        return apiRequest('/admin/users', {
            method: 'GET'
        });
    },

    async getUser(id) {
        return apiRequest(`/admin/users/${id}`, {
            method: 'GET'
        });
    },

    async deleteUser(id) {
        return apiRequest(`/admin/users/${id}`, {
            method: 'DELETE'
        });
    },

    async toggleAdminStatus(id) {
        return apiRequest(`/admin/users/${id}/toggle-admin`, {
            method: 'PATCH'
        });
    },

    async getRecentActivity() {
        return apiRequest('/admin/recent-activity', {
            method: 'GET'
        });
    }
};
