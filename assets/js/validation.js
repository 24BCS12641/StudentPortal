/**
 * Client-side Form Validation Utilities
 * EduPortal Management System
 */

document.addEventListener('DOMContentLoaded', () => {
    // Password match validation
    const pwdForms = document.querySelectorAll('form');
    pwdForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const newPwd = form.querySelector('input[name="new_password"]');
            const confPwd = form.querySelector('input[name="confirm_password"]');
            
            if (newPwd && confPwd) {
                if (newPwd.value !== confPwd.value) {
                    e.preventDefault();
                    alert('New Password and Confirmation Password do not match.');
                    confPwd.focus();
                    return false;
                }
                if (newPwd.value.length < 6) {
                    e.preventDefault();
                    alert('Password must be at least 6 characters long.');
                    newPwd.focus();
                    return false;
                }
            }

            // File size validation (default max 10MB)
            const fileInputs = form.querySelectorAll('input[type="file"]');
            fileInputs.forEach(fileInput => {
                if (fileInput.files && fileInput.files[0]) {
                    const maxMb = fileInput.dataset.maxMb || 10;
                    if (fileInput.files[0].size > maxMb * 1024 * 1024) {
                        e.preventDefault();
                        alert(`File size exceeds the ${maxMb}MB limit. Please select a smaller file.`);
                        return false;
                    }
                }
            });
        });
    });
});
