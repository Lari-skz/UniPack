// admin.js - Admin Dashboard Logic

let allUsers = [];

document.addEventListener('DOMContentLoaded', function() {
    if (!checkAuth()) return;

    // Check if user is admin
    const user = getUserData();
    if (!user || !user.is_admin) {
        alert('Access Denied: Admin privileges required');
        window.location.href = '/userDashboard.html';
        return;
    }

    initializeAdminDashboard();
});

async function initializeAdminDashboard() {
    const user = getUserData();

    if (user) {
        document.getElementById('adminName').textContent = user.name;
    }

    // Setup logout button
    document.getElementById('logoutBtn').addEventListener('click', handleLogout);

    // Load all data
    await loadDashboardData();
}

async function loadDashboardData() {
    try {
        showLoading(true);

        await Promise.all([
            loadStats(),
            loadUsers()
        ]);

        showLoading(false);
    } catch (error) {
        console.error('Error loading dashboard data:', error);
        showError('Failed to load dashboard data. Please refresh the page.');
        showLoading(false);
    }
}

async function loadStats() {
    try {
        const response = await AdminAPI.getStats();
        const stats = response.data;

        // Update stats cards
        document.getElementById('totalUsers').textContent = stats.total_users;
        document.getElementById('totalTasks').textContent = stats.total_tasks;
        document.getElementById('totalEvents').textContent = stats.total_events;
        document.getElementById('totalCategories').textContent = stats.total_categories;
        document.getElementById('activeUsers').textContent = stats.active_users;
        document.getElementById('completedTasks').textContent = stats.completed_tasks;
        document.getElementById('pendingTasks').textContent = stats.pending_tasks;

    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

async function loadUsers() {
    try {
        const response = await AdminAPI.listUsers();
        allUsers = response.data || [];
        renderUsers();
    } catch (error) {
        console.error('Error loading users:', error);
    }
}

function renderUsers() {
    const container = document.getElementById('usersContainer');
    if (!container) return;

    if (allUsers.length === 0) {
        container.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-cyber-muted">No users found.</td></tr>';
        return;
    }

    container.innerHTML = allUsers.map(user => `
        <tr class="border-b border-cyber-cyan/20 hover:bg-cyber-darker/50">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-cyber-cyan to-cyber-pink flex items-center justify-center text-cyber-dark font-bold">
                        ${user.name.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <div class="text-cyber-text font-semibold">${user.name}</div>
                        <div class="text-cyber-muted text-sm">${user.email}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 text-center">
                ${user.is_admin ?
                    '<span class="px-3 py-1 bg-cyber-pink/20 text-cyber-pink rounded-full text-sm font-semibold">Admin</span>' :
                    '<span class="px-3 py-1 bg-cyber-cyan/20 text-cyber-cyan rounded-full text-sm">User</span>'
                }
            </td>
            <td class="px-6 py-4 text-center text-cyber-text">${user.tasks_count || 0}</td>
            <td class="px-6 py-4 text-center text-cyber-text">${user.events_count || 0}</td>
            <td class="px-6 py-4 text-center text-cyber-text">${user.categories_count || 0}</td>
            <td class="px-6 py-4 text-center text-cyber-muted text-sm">
                ${new Date(user.created_at).toLocaleDateString()}
            </td>
            <td class="px-6 py-4 text-center">
                <div class="flex items-center justify-center gap-2">
                    ${!user.is_admin ? `
                        <button onclick="toggleAdmin(${user.id})"
                                class="text-cyber-cyan hover:text-cyber-green transition-colors"
                                title="Make Admin">
                            <i class="fas fa-user-shield"></i>
                        </button>
                    ` : ''}
                    <button onclick="confirmDeleteUser(${user.id}, '${user.name.replace(/'/g, "\\'")}' )"
                            class="text-cyber-pink hover:text-red-500 transition-colors"
                            title="Delete User">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

async function toggleAdmin(userId) {
    if (!confirm('Are you sure you want to toggle admin status for this user?')) return;

    try {
        const response = await AdminAPI.toggleAdminStatus(userId);
        showSuccess(response.message || 'Admin status updated successfully');
        await loadUsers();
    } catch (error) {
        showError(error.message || 'Failed to update admin status');
    }
}

async function confirmDeleteUser(userId, userName) {
    if (!confirm(`Are you sure you want to delete user "${userName}"?\n\nThis will also delete all their tasks, events, and categories. This action cannot be undone!`)) {
        return;
    }

    try {
        await AdminAPI.deleteUser(userId);
        showSuccess('User deleted successfully');
        await loadDashboardData();
    } catch (error) {
        showError(error.message || 'Failed to delete user');
    }
}

function showLoading(show) {
    const loader = document.getElementById('loadingIndicator');
    if (loader) {
        if (show) {
            loader.classList.remove('hidden');
        } else {
            loader.classList.add('hidden');
        }
    }
}

async function handleLogout() {
    try {
        await AuthAPI.logout();
    } catch (error) {
        console.error('Logout error:', error);
    } finally {
        removeAuthToken();
        window.location.href = '/login.html';
    }
}
