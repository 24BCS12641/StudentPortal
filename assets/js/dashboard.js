/**
 * EduPortal Dashboard Interactive Features & Animations
 * High-performance, modern client-side utilities
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Mobile Sidebar Drawer Toggle
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('show');
            if (overlay) overlay.classList.toggle('show');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function () {
            if (sidebar) sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }

    // 2. Auto-dismiss alerts after 4.5 seconds with smooth fade
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(function () {
                if (alert.parentElement) alert.remove();
            }, 400);
        }, 4500);
    });

    // 3. Global & In-page Table Live Search
    function filterTableRows(query) {
        const q = query.toLowerCase().trim();
        const tables = document.querySelectorAll('.custom-table');

        tables.forEach(function (table) {
            const rows = table.querySelectorAll('tbody tr');
            let visibleCount = 0;

            rows.forEach(function (row) {
                // Skip 'no-results' row
                if (row.classList.contains('no-results-row')) return;

                const text = row.textContent.toLowerCase();
                if (q === '' || text.indexOf(q) !== -1) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Handle empty state row if no match
            let noMatchRow = table.querySelector('.no-results-row');
            if (visibleCount === 0 && q !== '') {
                if (!noMatchRow) {
                    const colCount = table.querySelectorAll('thead th').length || 4;
                    noMatchRow = document.createElement('tr');
                    noMatchRow.className = 'no-results-row';
                    noMatchRow.innerHTML = `<td colspan="${colCount}" style="text-align:center; padding:30px; color:#94a3b8;">
                        <i class="fa-solid fa-magnifying-glass" style="font-size:24px; margin-bottom:8px; display:block;"></i>
                        No matching records found for "<strong>${query}</strong>"
                    </td>`;
                    table.querySelector('tbody').appendChild(noMatchRow);
                } else {
                    noMatchRow.style.display = '';
                }
            } else if (noMatchRow) {
                noMatchRow.style.display = 'none';
            }
        });
    }

    const navbarSearch = document.getElementById('navbarGlobalSearch');
    if (navbarSearch) {
        navbarSearch.addEventListener('input', function () {
            filterTableRows(this.value);
        });
    }

    const tableQuickSearch = document.getElementById('tableQuickSearch');
    if (tableQuickSearch) {
        tableQuickSearch.addEventListener('input', function () {
            filterTableRows(this.value);
        });
    }

    // 4. Keyboard Shortcut: Press '/' to quickly focus search
    document.addEventListener('keydown', function (e) {
        if (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
            e.preventDefault();
            if (navbarSearch) {
                navbarSearch.focus();
                navbarSearch.select();
            } else if (tableQuickSearch) {
                tableQuickSearch.focus();
                tableQuickSearch.select();
            }
        }
    });

    // 5. Stat Counter Up Animation
    const statValues = document.querySelectorAll('.stat-value');
    statValues.forEach(function (el) {
        const text = el.textContent.trim();
        const hasPercent = text.includes('%');
        const num = parseFloat(text.replace(/[^0-9.]/g, ''));

        if (!isNaN(num) && num > 0) {
            let start = 0;
            const duration = 750; // ms
            const startTime = performance.now();

            function updateCounter(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                // Ease out quad
                const easeOut = 1 - (1 - progress) * (1 - progress);
                const currentVal = Math.round(start + (num - start) * easeOut);
                
                el.textContent = currentVal + (hasPercent ? '%' : '');

                if (progress < 1) {
                    requestAnimationFrame(updateCounter);
                } else {
                    el.textContent = text; // Restore original formatting
                }
            }

            requestAnimationFrame(updateCounter);
        }
    });
});
