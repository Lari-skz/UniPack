// dashboard.js - User Dashboard Logic

let tasks = [];
let events = [];
let categories = [];

document.addEventListener('DOMContentLoaded', function() {
    if (!checkAuth()) return;

    initializeDashboard();
});

async function initializeDashboard() {
    const user = getUserData();

    if (user) {
        document.getElementById('userName').textContent = user.name;
    }

    // Setup logout button
    document.getElementById('logoutBtn').addEventListener('click', handleLogout);

    // Setup modals
    setupModals();

    // Load data
    await loadAllData();
}

async function loadAllData() {
    try {
        await Promise.all([
            loadCategories(),
            loadTasks(),
            loadEvents()
        ]);
    } catch (error) {
        console.error('Error loading data:', error);
        showError('Failed to load data. Please refresh the page.');
    }
}

async function loadCategories() {
    try {
        const response = await CategoriesAPI.getAll();
        categories = response.data || [];
        renderCategories();
        updateCategorySelect();
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

async function loadTasks() {
    try {
        const response = await TasksAPI.getAll();
        tasks = response.data || [];
        renderTasks();
        updateStats();
    } catch (error) {
        console.error('Error loading tasks:', error);
    }
}

async function loadEvents() {
    try {
        const response = await EventsAPI.getAll();
        events = response.data || [];
        renderEvents();
    } catch (error) {
        console.error('Error loading events:', error);
    }
}

function renderCategories() {
    const container = document.getElementById('categoriesContainer');
    if (!container) return;

    if (categories.length === 0) {
        container.innerHTML = '<p class="text-cyber-muted">No categories yet. Create one!</p>';
        return;
    }

    container.innerHTML = categories.map(cat => `
        <div class="bg-cyber-darker border border-cyber-cyan/30 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-4 h-4 rounded-full" style="background-color: ${cat.color || '#00F0FF'}"></div>
                    <div>
                        <h4 class="text-cyber-text font-semibold">${cat.name}</h4>
                        ${cat.description ? `<p class="text-cyber-muted text-sm">${cat.description}</p>` : ''}
                    </div>
                </div>
                <button onclick="deleteCategory(${cat.id})" class="text-cyber-pink hover:text-red-500">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `).join('');
}

function renderTasks() {
    const container = document.getElementById('tasksContainer');
    if (!container) return;

    if (tasks.length === 0) {
        container.innerHTML = '<p class="text-cyber-muted">No tasks yet. Create one!</p>';
        return;
    }

    const getPriorityColor = (priority) => {
        switch(priority) {
            case 'high': return 'text-red-400';
            case 'medium': return 'text-yellow-400';
            case 'low': return 'text-green-400';
            default: return 'text-cyber-muted';
        }
    };

    const getStatusBadge = (status) => {
        switch(status) {
            case 'completed': return 'bg-green-500/20 text-green-400';
            case 'in_progress': return 'bg-yellow-500/20 text-yellow-400';
            default: return 'bg-cyber-cyan/20 text-cyber-cyan';
        }
    };

    container.innerHTML = tasks.map(task => `
        <div class="bg-cyber-darker border border-cyber-cyan/30 rounded-lg p-4">
            <div class="flex items-start justify-between mb-2">
                <h4 class="text-cyber-text font-semibold">${task.title}</h4>
                <div class="flex gap-2">
                    ${task.status !== 'completed' ? `
                        <button onclick="updateTaskStatus(${task.id}, 'completed')"
                                class="text-green-400 hover:text-green-300" title="Mark Complete">
                            <i class="fas fa-check-circle"></i>
                        </button>
                    ` : ''}
                    <button onclick="deleteTask(${task.id})" class="text-cyber-pink hover:text-red-500">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            ${task.description ? `<p class="text-cyber-muted text-sm mb-2">${task.description}</p>` : ''}
            <div class="flex items-center gap-4 text-sm">
                <span class="px-2 py-1 rounded ${getStatusBadge(task.status)}">${task.status.replace('_', ' ')}</span>
                <span class="${getPriorityColor(task.priority)}">
                    <i class="fas fa-flag mr-1"></i>${task.priority}
                </span>
                <span class="text-cyber-muted">
                    <i class="fas fa-calendar mr-1"></i>${task.due_date}
                </span>
            </div>
        </div>
    `).join('');
}

function renderEvents() {
    const container = document.getElementById('eventsContainer');
    if (!container) return;

    if (events.length === 0) {
        container.innerHTML = '<p class="text-cyber-muted">No events yet. Create one!</p>';
        return;
    }

    container.innerHTML = events.map(event => `
        <div class="bg-cyber-darker border border-cyber-pink/30 rounded-lg p-4">
            <div class="flex items-start justify-between mb-2">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full" style="background-color: ${event.color || '#FF006E'}"></div>
                    <h4 class="text-cyber-text font-semibold">${event.title}</h4>
                </div>
                <button onclick="deleteEvent(${event.id})" class="text-cyber-pink hover:text-red-500">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            ${event.description ? `<p class="text-cyber-muted text-sm mb-2">${event.description}</p>` : ''}
            <div class="flex items-center gap-4 text-sm text-cyber-muted">
                <span><i class="fas fa-calendar mr-1"></i>${event.event_date}</span>
                <span><i class="fas fa-clock mr-1"></i>${event.event_time}</span>
            </div>
        </div>
    `).join('');
}

function updateStats() {
    const totalTasks = tasks.length;
    const completedTasks = tasks.filter(t => t.status === 'completed').length;
    const pendingTasks = tasks.filter(t => t.status === 'pending').length;

    document.getElementById('totalTasks').textContent = totalTasks;
    document.getElementById('completedTasks').textContent = completedTasks;
    document.getElementById('pendingTasks').textContent = pendingTasks;
}

function updateCategorySelect() {
    const select = document.getElementById('taskCategory');
    if (!select) return;

    select.innerHTML = '<option value="">No Category</option>' +
        categories.map(cat => `<option value="${cat.id}">${cat.name}</option>`).join('');
}

// Modal Functions
function setupModals() {
    // Category Modal
    document.getElementById('openCategoryModal')?.addEventListener('click', () => {
        document.getElementById('categoryModal').classList.remove('hidden');
    });

    document.getElementById('closeCategoryModal')?.addEventListener('click', () => {
        document.getElementById('categoryModal').classList.add('hidden');
        document.getElementById('categoryForm').reset();
    });

    document.getElementById('categoryForm')?.addEventListener('submit', handleCategorySubmit);

    // Task Modal
    document.getElementById('openTaskModal')?.addEventListener('click', () => {
        document.getElementById('taskModal').classList.remove('hidden');
    });

    document.getElementById('closeTaskModal')?.addEventListener('click', () => {
        document.getElementById('taskModal').classList.add('hidden');
        document.getElementById('taskForm').reset();
    });

    document.getElementById('taskForm')?.addEventListener('submit', handleTaskSubmit);

    // Event Modal
    document.getElementById('openEventModal')?.addEventListener('click', () => {
        document.getElementById('eventModal').classList.remove('hidden');
    });

    document.getElementById('closeEventModal')?.addEventListener('click', () => {
        document.getElementById('eventModal').classList.add('hidden');
        document.getElementById('eventForm').reset();
    });

    document.getElementById('eventForm')?.addEventListener('submit', handleEventSubmit);
}

async function handleCategorySubmit(e) {
    e.preventDefault();

    const name = document.getElementById('categoryName').value;
    const description = document.getElementById('categoryDescription').value;
    const color = document.getElementById('categoryColor').value;

    try {
        await CategoriesAPI.create(name, description, color);
        showSuccess('Category created successfully!');
        document.getElementById('categoryModal').classList.add('hidden');
        document.getElementById('categoryForm').reset();
        await loadCategories();
    } catch (error) {
        showError(error.message || 'Failed to create category');
    }
}

async function handleTaskSubmit(e) {
    e.preventDefault();

    const taskData = {
        title: document.getElementById('taskTitle').value,
        description: document.getElementById('taskDescription').value,
        due_date: document.getElementById('taskDueDate').value,
        priority: document.getElementById('taskPriority').value,
        category_id: document.getElementById('taskCategory').value || null
    };

    try {
        await TasksAPI.create(taskData);
        showSuccess('Task created successfully!');
        document.getElementById('taskModal').classList.add('hidden');
        document.getElementById('taskForm').reset();
        await loadTasks();
    } catch (error) {
        showError(error.message || 'Failed to create task');
    }
}

async function handleEventSubmit(e) {
    e.preventDefault();

    const eventData = {
        title: document.getElementById('eventTitle').value,
        description: document.getElementById('eventDescription').value,
        event_date: document.getElementById('eventDate').value,
        event_time: document.getElementById('eventTime').value,
        color: document.getElementById('eventColor').value
    };

    try {
        await EventsAPI.create(eventData);
        showSuccess('Event created successfully!');
        document.getElementById('eventModal').classList.add('hidden');
        document.getElementById('eventForm').reset();
        await loadEvents();
    } catch (error) {
        showError(error.message || 'Failed to create event');
    }
}

async function deleteCategory(id) {
    if (!confirm('Are you sure you want to delete this category?')) return;

    try {
        await CategoriesAPI.delete(id);
        showSuccess('Category deleted successfully!');
        await loadCategories();
    } catch (error) {
        showError(error.message || 'Failed to delete category');
    }
}

async function deleteTask(id) {
    if (!confirm('Are you sure you want to delete this task?')) return;

    try {
        await TasksAPI.delete(id);
        showSuccess('Task deleted successfully!');
        await loadTasks();
    } catch (error) {
        showError(error.message || 'Failed to delete task');
    }
}

async function deleteEvent(id) {
    if (!confirm('Are you sure you want to delete this event?')) return;

    try {
        await EventsAPI.delete(id);
        showSuccess('Event deleted successfully!');
        await loadEvents();
    } catch (error) {
        showError(error.message || 'Failed to delete event');
    }
}

async function updateTaskStatus(id, status) {
    try {
        await TasksAPI.update(id, { status });
        showSuccess('Task updated successfully!');
        await loadTasks();
    } catch (error) {
        showError(error.message || 'Failed to update task');
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
