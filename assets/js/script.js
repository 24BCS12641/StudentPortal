/**
 * General Frontend Scripts
 * EduPortal Management System
 */

document.addEventListener('DOMContentLoaded', () => {
    // Auto-focus first input field in modal/forms
    const firstInput = document.querySelector('form input:not([type="hidden"]):not([disabled])');
    if (firstInput && !firstInput.value) {
        firstInput.focus();
    }

    // Tooltip or helper popups if any
    const deleteButtons = document.querySelectorAll('.btn-danger, [data-confirm]');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const msg = btn.getAttribute('data-confirm') || 'Are you sure you want to perform this action? This cannot be undone.';
            if (!confirm(msg)) {
                e.preventDefault();
                return false;
            }
        });
    });
});
