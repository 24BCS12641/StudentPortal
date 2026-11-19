/**
 * EduPortal Zero-Tofu SVG Icon Engine
 * Guarantees that every icon renders as a clean, crisp vector graphic.
 * Eliminates missing font glyph squares (tofu) permanently.
 */
(function() {
    'use strict';

    const ICONS = {
        'fa-graduation-cap': '<path d="M11.7 2.8a2 2 0 0 1 1.6 0l9 4.3a1 1 0 0 1 0 1.8l-9 4.3a2 2 0 0 1-1.6 0l-9-4.3a1 1 0 0 1 0-1.8l9-4.3z"/><path d="M5 10.5v5c0 2.5 3.1 4.5 7.5 4.5s7.5-2 7.5-4.5v-5"/><path d="M22 10v6"/>',
        'fa-chart-pie': '<path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/>',
        'fa-chart-simple': '<rect width="4" height="12" x="3" y="9" rx="1"/><rect width="4" height="16" x="10" y="5" rx="1"/><rect width="4" height="8" x="17" y="13" rx="1"/>',
        'fa-chart-line': '<path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/>',
        'fa-book-bookmark': '<path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M10 2v8l3-2 3 2V2"/>',
        'fa-book-open': '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
        'fa-book-reader': '<path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><circle cx="12" cy="7" r="3"/>',
        'fa-book': '<path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>',
        'fa-calendar-days': '<rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><circle cx="8" cy="14" r="1"/><circle cx="12" cy="14" r="1"/><circle cx="16" cy="14" r="1"/><circle cx="8" cy="18" r="1"/><circle cx="12" cy="18" r="1"/>',
        'fa-calendar-check': '<rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/>',
        'fa-calendar': '<rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>',
        'fa-file-pen': '<path d="M12 22h6a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v7"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10.4 12.6a2 2 0 1 1 2.8 2.8L5 23l-3 1 1-3Z"/>',
        'fa-file-lines': '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/>',
        'fa-paper-plane': '<path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/>',
        'fa-award': '<circle cx="12" cy="8" r="6"/><path d="m15.477 12.89 2.052 7.61a1 1 0 0 1-1.42 1.08L12 19.33l-4.11 2.25a1 1 0 0 1-1.42-1.08l2.052-7.61"/>',
        'fa-trophy': '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.45 1-1 1H8v4h8v-4h-1c-.55 0-1-.45-1-1v-2.34"/><path d="M18 4H6v7a6 6 0 0 0 12 0V4Z"/>',
        'fa-clipboard-user': '<rect width="14" height="18" x="5" y="4" rx="2"/><path d="M9 2h6a1 1 0 0 1 1 1v1H8V3a1 1 0 0 1 1-1Z"/><circle cx="12" cy="11" r="2.5"/><path d="M8 18c0-2 1.8-3.5 4-3.5s4 1.5 4 3.5"/>',
        'fa-clipboard-check': '<rect width="14" height="18" x="5" y="4" rx="2"/><path d="M9 2h6a1 1 0 0 1 1 1v1H8V3a1 1 0 0 1 1-1Z"/><path d="m9 14 2 2 4-4"/>',
        'fa-receipt': '<path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M8 7h8"/><path d="M8 11h8"/><path d="M8 15h5"/>',
        'fa-folder-open': '<path d="m6 14 1.5-2.9A2 2 0 0 1 9.3 10H20a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.7.9L11 6h7a2 2 0 0 1 2 2v2"/>',
        'fa-bullhorn': '<path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>',
        'fa-id-card': '<rect width="18" height="14" x="3" y="5" rx="2"/><circle cx="9" cy="11" r="2"/><path d="M6 16c0-1.5 1.3-2.5 3-2.5s3 1 3 2.5"/><line x1="14" x2="18" y1="10" y2="10"/><line x1="14" x2="18" y1="13" y2="13"/>',
        'fa-bolt': '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
        'fa-right-from-bracket': '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>',
        'fa-magnifying-glass': '<circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/>',
        'fa-search': '<circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/>',
        'fa-bell': '<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>',
        'fa-user': '<circle cx="12" cy="7" r="4"/><path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/>',
        'fa-users': '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'fa-laptop-code': '<rect width="18" height="12" x="3" y="4" rx="2"/><path d="M2 20h20"/><path d="m10 9-2 2 2 2"/><path d="m14 9 2 2-2 2"/>',
        'fa-gears': '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>',
        'fa-sliders': '<line x1="4" x2="4" y1="21" y2="14"/><line x1="4" x2="4" y1="10" y2="3"/><line x1="12" x2="12" y1="21" y2="12"/><line x1="12" x2="12" y1="8" y2="3"/><line x1="20" x2="20" y1="21" y2="16"/><line x1="20" x2="20" y1="12" y2="3"/><line x1="1" x2="7" y1="14" y2="14"/><line x1="9" x2="15" y1="8" y2="8"/><line x1="17" x2="23" y1="16" y2="16"/>',
        'fa-chalkboard-user': '<rect width="18" height="12" x="3" y="3" rx="2"/><circle cx="12" cy="18" r="2.5"/><path d="M8 22c0-1.8 1.8-3 4-3s4 1.2 4 3"/>',
        'fa-user-graduate': '<circle cx="12" cy="10" r="4"/><path d="M5 21v-2a5 5 0 0 1 10 0v2"/><path d="M12 2 4 6l8 4 8-4-8-4Z"/><path d="M19 8v4"/>',
        'fa-user-gear': '<circle cx="10" cy="7" r="4"/><path d="M3 21v-2a4 4 0 0 1 4-4h6a4 4 0 0 1 4 4v2"/><circle cx="19" cy="11" r="2"/>',
        'fa-check': '<polyline points="20 6 9 17 4 12"/>',
        'fa-check-double': '<path d="m18 6-7 11-4-4"/><path d="m22 10-7 11-2-2"/>',
        'fa-circle-check': '<circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>',
        'fa-circle-exclamation': '<circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>',
        'fa-circle-info': '<circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="16" y2="12"/><line x1="12" x2="12.01" y1="8" y2="8"/>',
        'fa-circle-xmark': '<circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/>',
        'fa-xmark': '<line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/>',
        'fa-plus': '<line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/>',
        'fa-plus-circle': '<circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="16"/><line x1="8" x2="16" y1="12" y2="12"/>',
        'fa-trash': '<polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/>',
        'fa-pen-to-square': '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>',
        'fa-print': '<polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/>',
        'fa-download': '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/>',
        'fa-upload': '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/>',
        'fa-filter': '<polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>',
        'fa-arrow-left': '<line x1="19" x2="5" y1="12" y2="12"/><polyline points="12 19 5 12 12 5"/>',
        'fa-arrow-right': '<line x1="5" x2="19" y1="12" y2="12"/><polyline points="12 5 19 12 12 19"/>',
        'fa-arrows-rotate': '<path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/>',
        'fa-eye': '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>',
        'fa-eye-slash': '<path d="m2 2 20 20"/><path d="M10.4 4.4C10.9 4.3 11.4 4.2 12 4.2c7 0 10 7 10 7s-1.4 3.2-4.4 5.3"/><path d="M6.7 6.7C3.7 8.8 2 11.2 2 11.2s3 7 10 7c1.7 0 3.3-.4 4.7-1.1"/><circle cx="12" cy="12" r="3"/>',
        'fa-lock': '<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
        'fa-shield-halved': '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M12 2v20"/>',
        'fa-shield': '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        'fa-envelope': '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
        'fa-phone': '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
        'fa-location-dot': '<path d="M12 2a8 8 0 0 0-8 8c0 5.25 8 12 8 12s8-6.75 8-12a8 8 0 0 0-8-8z"/><circle cx="12" cy="10" r="3"/>',
        'fa-clock': '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
        'fa-bars': '<line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/>',
        'fa-briefcase': '<rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
        'fa-database': '<ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>',
        'fa-building-columns': '<line x1="2" x2="22" y1="21" y2="21"/><line x1="4" x2="20" y1="18" y2="18"/><polygon points="12 2 2 7 22 7 12 2"/><line x1="6" x2="6" y1="18" y2="7"/><line x1="10" x2="10" y1="18" y2="7"/><line x1="14" x2="14" y1="18" y2="7"/><line x1="18" x2="18" y1="18" y2="7"/>',
        'fa-feather-pointed': '<path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L3 11.5V21h9.5l7.74-7.76a6 6 0 0 0 0-1z"/><line x1="16" x2="2" y1="8" y2="22"/><line x1="17.5" x2="9" y1="15" y2="15"/>',
        'fa-sparkles': '<path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3L12 3Z"/>'
    };

    function createSvgIcon(pathsHtml, extraClass) {
        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.setAttribute('viewBox', '0 0 24 24');
        svg.setAttribute('fill', 'none');
        svg.setAttribute('stroke', 'currentColor');
        svg.setAttribute('stroke-width', '2');
        svg.setAttribute('stroke-linecap', 'round');
        svg.setAttribute('stroke-linejoin', 'round');
        svg.setAttribute('class', 'portal-svg-icon ' + (extraClass || ''));
        svg.style.display = 'inline-block';
        svg.style.width = '1em';
        svg.style.height = '1em';
        svg.style.verticalAlign = '-0.125em';
        svg.style.overflow = 'visible';
        svg.innerHTML = pathsHtml;
        return svg;
    }

    function scanAndReplaceIcons() {
        // If Font Awesome official SVG JS is active and transformed elements, leave it
        if (window.FontAwesome && window.FontAwesome.dom && window.FontAwesome.dom.i2svg) {
            try {
                window.FontAwesome.dom.i2svg();
            } catch(e) {}
        }

        // Target any <i> element that has fa- classes and hasn't been turned into an SVG
        const iconNodes = document.querySelectorAll('i[class*="fa-"]');
        iconNodes.forEach(function(el) {
            // Check if already replaced or has children
            if (el.dataset.iconReplaced || el.querySelector('svg')) return;

            const classList = Array.from(el.classList);
            let matchedPaths = null;
            let matchedClass = '';

            for (const cls of classList) {
                if (ICONS[cls]) {
                    matchedPaths = ICONS[cls];
                    matchedClass = cls;
                    break;
                }
            }

            // Also check partial matches
            if (!matchedPaths) {
                for (const cls of classList) {
                    if (cls.startsWith('fa-')) {
                        // Map aliases
                        if (cls.includes('chart')) matchedPaths = ICONS['fa-chart-pie'];
                        else if (cls.includes('book')) matchedPaths = ICONS['fa-book-open'];
                        else if (cls.includes('calendar')) matchedPaths = ICONS['fa-calendar-days'];
                        else if (cls.includes('user') || cls.includes('grad')) matchedPaths = ICONS['fa-graduation-cap'];
                        else if (cls.includes('pen') || cls.includes('edit')) matchedPaths = ICONS['fa-file-pen'];
                        else if (cls.includes('check')) matchedPaths = ICONS['fa-check'];
                        else if (cls.includes('plane') || cls.includes('send')) matchedPaths = ICONS['fa-paper-plane'];
                        else if (cls.includes('award') || cls.includes('trophy')) matchedPaths = ICONS['fa-award'];
                        else if (cls.includes('folder') || cls.includes('note')) matchedPaths = ICONS['fa-folder-open'];
                        else if (cls.includes('bell') || cls.includes('notice') || cls.includes('horn')) matchedPaths = ICONS['fa-bullhorn'];
                        else if (cls.includes('card') || cls.includes('id')) matchedPaths = ICONS['fa-id-card'];
                        else if (cls.includes('search')) matchedPaths = ICONS['fa-magnifying-glass'];
                        else if (cls.includes('out') || cls.includes('logout') || cls.includes('bracket')) matchedPaths = ICONS['fa-right-from-bracket'];
                        else if (cls.includes('file')) matchedPaths = ICONS['fa-file-lines'];
                        else if (cls.includes('bolt')) matchedPaths = ICONS['fa-bolt'];
                        
                        if (matchedPaths) {
                            matchedClass = cls;
                            break;
                        }
                    }
                }
            }

            if (matchedPaths) {
                const svg = createSvgIcon(matchedPaths, classList.join(' '));
                // Copy computed dimensions if set on i
                svg.style.color = window.getComputedStyle(el).color || 'currentColor';
                el.dataset.iconReplaced = 'true';
                el.textContent = '';
                el.style.display = 'inline-flex';
                el.style.alignItems = 'center';
                el.style.justifyContent = 'center';
                el.appendChild(svg);
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', scanAndReplaceIcons);
    } else {
        scanAndReplaceIcons();
    }

    // Run again after window load and on any dynamic modal/AJAX updates
    window.addEventListener('load', scanAndReplaceIcons);
    setTimeout(scanAndReplaceIcons, 300);
    setTimeout(scanAndReplaceIcons, 1000);
    setTimeout(scanAndReplaceIcons, 2500);

    // Export globally
    window.EduPortalIconFixer = { scan: scanAndReplaceIcons };
})();
