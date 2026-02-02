import '@fortawesome/fontawesome-free/css/all.css';
// Tenant Specific JavaScript

// Tenant Configuration will be set by Blade template
let TenantConfig = window.TenantConfig || {
    schoolName: "Smart Pathshala",
    schoolId: "",
    primaryColor: "#2563EB",
    isLocked: false,
    domain: ""
};

// Initialize Tenant Features
document.addEventListener('DOMContentLoaded', function() {
    initializeTenantFeatures();
    setupThemeManager();
    setupNotifications();
    setupAutoSave();
    setupRealTimeUpdates();
});

// Initialize Tenant Specific Features
function initializeTenantFeatures() {
    // Set dynamic school name
    updateSchoolBranding();
    
    // Apply custom colors
    applySchoolColors();
    
    // Setup sidebar toggle for mobile
    setupMobileSidebar();
    
    // Initialize tooltips
    initializeTooltips();
    
    // Setup form validation
    setupFormValidation();
}

// Update School Branding
function updateSchoolBranding() {
    const schoolNameElements = document.querySelectorAll('.school-name');
    schoolNameElements.forEach(element => {
        element.textContent = TenantConfig.schoolName;
    });
    
    // Update page title
    document.title = `${TenantConfig.schoolName} - Smart Pathshala`;
}

// Apply School Colors
function applySchoolColors() {
    const root = document.documentElement;
    root.style.setProperty('--school-primary', TenantConfig.primaryColor);
    
    // Update logo if available
    const logoElements = document.querySelectorAll('.school-logo');
    logoElements.forEach(element => {
        const logoUrl = "{{ tenant('data')['logo_url'] ?? '' }}";
        if (logoUrl) {
            element.src = logoUrl;
        }
    });
}

// Mobile Sidebar Toggle
function setupMobileSidebar() {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.querySelector('.tenant-sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
        
        // Close sidebar when clicking outside
        document.addEventListener('click', function(e) {
            if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    }
}

// Theme Manager
function setupThemeManager() {
    const themeToggle = document.getElementById('theme-toggle');
    const savedTheme = localStorage.getItem('tenant-theme') || 'light';
    
    // Apply saved theme
    document.documentElement.setAttribute('data-theme', savedTheme);
    
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('tenant-theme', newTheme);
            
            // Update icon
            const icon = this.querySelector('i');
            if (icon) {
                icon.className = newTheme === 'light' ? 'fas fa-moon' : 'fas fa-sun';
            }
        });
    }
}

// Notification System
function setupNotifications() {
    // Check for lock status
    if (TenantConfig.isLocked) {
        showNotification('warning', 'স্কুল বর্তমানে লক করা আছে। কিছু কার্যক্রম সীমিত থাকতে পারে।');
    }
    
    // Setup notification bell
    const notificationBell = document.getElementById('notification-bell');
    if (notificationBell) {
        notificationBell.addEventListener('click', showNotificationPanel);
    }
}

// Show Notification
function showNotification(type, message, duration = 5000) {
    const notification = document.createElement('div');
    notification.className = `tenant-notification tenant-notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
        </div>
    `;
    
    // Add to container
    const container = document.getElementById('notifications-container') || createNotificationContainer();
    container.appendChild(notification);
    
    // Auto remove
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, duration);
}

// Create Notification Container
function createNotificationContainer() {
    const container = document.createElement('div');
    container.id = 'notifications-container';
    container.className = 'notifications-container';
    document.body.appendChild(container);
    return container;
}

// Auto Save Functionality
function setupAutoSave() {
    const forms = document.querySelectorAll('form[data-auto-save]');
    
    forms.forEach(form => {
        let saveTimeout;
        
        form.addEventListener('input', function() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                autoSaveForm(form);
            }, 2000);
        });
    });
}

// Auto Save Form
function autoSaveForm(form) {
    const formData = new FormData(form);
    const saveUrl = form.dataset.autoSave;
    
    fetch(saveUrl, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'সয়ংক্রিয়ভাবে সংরক্ষিত হয়েছে');
        }
    })
    .catch(error => {
        console.error('Auto save failed:', error);
    });
}

// Real Time Updates
function setupRealTimeUpdates() {
    // WebSocket connection for real-time updates
    if (typeof io !== 'undefined') {
        const socket = io(`ws://${window.location.host}`);
        
        socket.on('tenant-update', function(data) {
            if (data.tenant_id === TenantConfig.schoolId) {
                handleTenantUpdate(data);
            }
        });
    }
}

// Handle Tenant Updates
function handleTenantUpdate(data) {
    switch (data.type) {
        case 'lock_status':
            TenantConfig.isLocked = data.is_locked;
            if (data.is_locked) {
                showNotification('warning', 'স্কুল লক করা হয়েছে');
            } else {
                showNotification('success', 'স্কুল আনলক করা হয়েছে');
            }
            break;
            
        case 'announcement':
            showNotification('info', data.message);
            break;
            
        case 'maintenance':
            showNotification('warning', data.message, 10000);
            break;
    }
}

// Form Validation
function setupFormValidation() {
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(form)) {
                e.preventDefault();
            }
        });
    });
}

// Validate Form
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            showError(input, 'এই ফিল্ডটি আবশ্যক');
            isValid = false;
        } else {
            clearError(input);
        }
    });
    
    return isValid;
}

// Show Field Error
function showError(input, message) {
    clearError(input);
    
    const errorElement = document.createElement('div');
    errorElement.className = 'field-error';
    errorElement.textContent = message;
    
    input.parentElement.appendChild(errorElement);
    input.classList.add('error');
}

// Clear Field Error
function clearError(input) {
    const errorElement = input.parentElement.querySelector('.field-error');
    if (errorElement) {
        errorElement.remove();
    }
    input.classList.remove('error');
}

// Initialize Tooltips
function initializeTooltips() {
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            showTooltip(this, this.dataset.tooltip);
        });
        
        element.addEventListener('mouseleave', function() {
            hideTooltip();
        });
    });
}

// Show Tooltip
function showTooltip(element, text) {
    hideTooltip();
    
    const tooltip = document.createElement('div');
    tooltip.className = 'tenant-tooltip';
    tooltip.textContent = text;
    
    document.body.appendChild(tooltip);
    
    const rect = element.getBoundingClientRect();
    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
}

// Hide Tooltip
function hideTooltip() {
    const tooltip = document.querySelector('.tenant-tooltip');
    if (tooltip) {
        tooltip.remove();
    }
}

// Export for global use
window.showNotification = showNotification;
window.autoSaveForm = autoSaveForm;
