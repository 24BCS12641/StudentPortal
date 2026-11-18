/**
 * Public Website JavaScript Utilities
 * Apex Imperial University - Modern Interactive Engine
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Counter-up animation for Public Stats
    const counters = document.querySelectorAll('.counter-val');
    counters.forEach(function (counter) {
        const target = parseFloat(counter.getAttribute('data-target') || counter.textContent.replace(/[^0-9.]/g, ''));
        const suffix = counter.getAttribute('data-suffix') || '';
        const prefix = counter.getAttribute('data-prefix') || '';
        
        if (!isNaN(target)) {
            let start = 0;
            const duration = 1200;
            const startTime = performance.now();

            function updateCounter(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const easeOut = 1 - Math.pow(1 - progress, 3);
                const current = Math.round(start + (target - start) * easeOut);
                
                counter.textContent = prefix + current.toLocaleString() + suffix;

                if (progress < 1) {
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = prefix + target.toLocaleString() + suffix;
                }
            }

            requestAnimationFrame(updateCounter);
        }
    });

    // 2. Smooth scrolling for internal anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId && targetId !== '#') {
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });

    // 3. Navbar scroll effect
    const nav = document.querySelector('.main-university-nav');
    const backToTop = document.getElementById('backToTopBtn');

    window.addEventListener('scroll', function () {
        if (window.scrollY > 50) {
            if (nav) nav.classList.add('scrolled');
            if (backToTop) backToTop.style.display = 'flex';
        } else {
            if (nav) nav.classList.remove('scrolled');
            if (backToTop) backToTop.style.display = 'none';
        }
    });

    // 4. Back to top button click
    if (backToTop) {
        backToTop.addEventListener('click', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // 5. Global Keyboard Shortcut: Ctrl + K or Cmd + K for Quick Search
    window.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            openSearchModal();
        }
        if (e.key === 'Escape') {
            closeSearchModal();
            closeCampusTourModal();
        }
    });
});

// Quick Search / Command Palette Logic
function openSearchModal() {
    const modal = document.getElementById('searchModalBackdrop');
    if (modal) {
        modal.style.display = 'flex';
        const input = document.getElementById('quickSearchInput');
        if (input) {
            input.value = '';
            input.focus();
            filterSearchResults('');
        }
    }
}

function closeSearchModal() {
    const modal = document.getElementById('searchModalBackdrop');
    if (modal) modal.style.display = 'none';
}

function filterSearchResults(query) {
    const q = query.toLowerCase().trim();
    const items = document.querySelectorAll('.search-result-item');
    let matchedCount = 0;

    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (q === '' || text.includes(q)) {
            item.style.display = 'flex';
            matchedCount++;
        } else {
            item.style.display = 'none';
        }
    });

    const noRes = document.getElementById('noSearchResults');
    if (noRes) {
        noRes.style.display = matchedCount === 0 ? 'block' : 'none';
    }
}

// Virtual Campus Tour Modal
function openCampusTourModal() {
    const modal = document.getElementById('campusTourModal');
    if (modal) modal.style.display = 'flex';
}

function closeCampusTourModal() {
    const modal = document.getElementById('campusTourModal');
    if (modal) modal.style.display = 'none';
}

// Virtual Admissions Assistant / FAQ Helpbot
function toggleHelpbot() {
    const dialog = document.getElementById('helpbotDialog');
    if (dialog) {
        dialog.style.display = (dialog.style.display === 'block') ? 'none' : 'block';
    }
}

function askHelpbot(topic) {
    const body = document.getElementById('helpbotBody');
    if (!body) return;

    let question = "";
    let answer = "";

    if (topic === 'eligibility') {
        question = "What is the eligibility for B.Tech CSE?";
        answer = "For B.Tech CSE: 10+2 with Physics & Mathematics (min 60% aggregate). Direct admission for JEE Main / State CET rank holders or via Apex Scholastic Test (AST).";
    } else if (topic === 'scholarship') {
        question = "What scholarships are offered?";
        answer = "Apex Merit Scholarships offer up to 100% tuition waivers for scholars with 90%+ in qualifying examinations, national sports achievers, and single girl child candidates.";
    } else if (topic === 'placement') {
        question = "How are campus placements?";
        answer = "Our 2025–26 batch recorded a 98.4% placement rate with ₹44.5 LPA highest package and ₹12.8 LPA average across 350+ corporate recruiters!";
    } else if (topic === 'hostel') {
        question = "Are hostel & transport facilities available?";
        answer = "Yes! Modern air-conditioned residential hostels for 3,500+ students with 24/7 high-speed Wi-Fi, dining commons, gym, and AC bus routes across the capital.";
    }

    // Append user question
    const userMsg = document.createElement('div');
    userMsg.className = 'chat-bubble user';
    userMsg.textContent = question;
    body.appendChild(userMsg);

    // Append bot response after realistic brief delay
    setTimeout(() => {
        const botMsg = document.createElement('div');
        botMsg.className = 'chat-bubble bot';
        botMsg.innerHTML = '<strong class="text-gold"><i class="fa-solid fa-graduation-cap me-1"></i> Apex Assistant:</strong><br>' + answer + '<div class="mt-2"><a href="admissions.php#applyNow" class="btn btn-gold btn-sm py-1 px-2" style="font-size:11px;">Apply Online Now &rarr;</a></div>';
        body.appendChild(botMsg);
        body.scrollTop = body.scrollHeight;
    }, 300);
}

// Merit Scholarship Estimator Slider
function updateScholarship(pct) {
    const pctVal = parseFloat(pct);
    document.getElementById('scholarshipPctDisplay').textContent = pctVal + '%';

    let tier = "Standard Admission";
    let discount = 0;
    let badgeClass = "bg-secondary";

    if (pctVal >= 95) {
        tier = "Presidential Full Scholar (100% Tuition Waiver)";
        discount = 100;
        badgeClass = "bg-success";
    } else if (pctVal >= 90) {
        tier = "Dean's Gold Merit Scholarship (50% Tuition Waiver)";
        discount = 50;
        badgeClass = "bg-gold text-dark";
    } else if (pctVal >= 80) {
        tier = "Silver Academic Excellence (25% Tuition Waiver)";
        discount = 25;
        badgeClass = "bg-primary";
    } else if (pctVal >= 75) {
        tier = "Merit Encouragement Grant (10% Tuition Waiver)";
        discount = 10;
        badgeClass = "bg-info text-dark";
    }

    const tierEl = document.getElementById('scholarshipTier');
    const discountEl = document.getElementById('scholarshipDiscount');
    const netFeeEl = document.getElementById('scholarshipNetFee');

    if (tierEl) tierEl.textContent = tier;
    if (discountEl) discountEl.textContent = discount + '% Tuition Discount';

    // Base fee ₹1,60,000/yr
    const baseFee = 160000;
    const netFee = baseFee - (baseFee * (discount / 100));
    if (netFeeEl) netFeeEl.textContent = '₹' + netFee.toLocaleString() + ' / Year';
}

// Program Filter Pills on Academics / Home
function filterPrograms(category, btn) {
    const cards = document.querySelectorAll('.program-filter-item');
    cards.forEach(card => {
        const cat = card.getAttribute('data-category');
        if (category === 'all' || cat === category) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });

    // Update active button
    if (btn) {
        document.querySelectorAll('.program-filter-btn').forEach(b => {
            b.classList.remove('btn-primary');
            b.classList.add('btn-outline-secondary');
        });
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-primary');
    }
}
