/**
 * VPNStore Admin Dashboard JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    initTooltips();
    initAutoAlerts();
    initDataTables();
});

/**
 * Bootstrap Tooltips
 */
function initTooltips() {
    const tooltipEls = document.querySelectorAll('[title]');
    tooltipEls.forEach(el => {
        if (el.closest('.admin-table') || el.closest('.btn-group')) {
            new bootstrap.Tooltip(el, { placement: 'top', trigger: 'hover' });
        }
    });
}

/**
 * Auto-dismiss alerts after 5s
 */
function initAutoAlerts() {
    const alerts = document.querySelectorAll('.admin-alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity .4s';
            setTimeout(() => alert.remove(), 400);
        }, 5000);
    });
}

/**
 * Simple row highlight on table
 */
function initDataTables() {
    const rows = document.querySelectorAll('.admin-table tbody tr');
    rows.forEach(row => {
        row.addEventListener('mouseenter', () => row.style.background = '#f0f9ff');
        row.addEventListener('mouseleave', () => row.style.background = '');
    });
}

/**
 * Confirm with styled modal (fallback to window.confirm)
 */
function adminConfirm(message, callback) {
    if (window.confirm(message)) callback();
}

/**
 * Copy text to clipboard
 */
function copyToClipboard(text, btnEl) {
    navigator.clipboard.writeText(text).then(() => {
        const original = btnEl ? btnEl.innerHTML : '';
        if (btnEl) {
            btnEl.innerHTML = '<i class="bi bi-check-lg"></i>';
            setTimeout(() => { btnEl.innerHTML = original; }, 1500);
        }
    });
}
