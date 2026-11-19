<?php
/**
 * Public Website Footer Include
 * Apex Imperial University
 */
?>
<!-- Main University Footer -->
<footer class="university-footer pt-5 pb-3">
    <div class="container">
        <div class="row g-4 pb-4 border-bottom border-secondary border-opacity-25">
            <!-- Col 1: University Crest & Overview -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="university-crest-footer">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <div>
                        <h5 class="text-white mb-0 fw-bold font-serif">Apex Imperial University</h5>
                        <small class="text-gold text-uppercase fw-semibold" style="letter-spacing: 1px;">Empowering Minds Since 1988</small>
                    </div>
                </div>
                <p class="text-light-muted mb-3" style="line-height: 1.7; font-size: 14px;">
                    Apex Imperial University is a premier multidisciplinary research university accredited with NAAC A++ Grade. We are committed to fostering global leadership, pioneering research, and transformative education.
                </p>
                <div class="footer-accreditations d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-navy-light text-gold border border-warning border-opacity-25"><i class="fa-solid fa-award me-1"></i> NAAC A++</span>
                    <span class="badge bg-navy-light text-gold border border-warning border-opacity-25"><i class="fa-solid fa-check-double me-1"></i> UGC & AICTE Approved</span>
                    <span class="badge bg-navy-light text-gold border border-warning border-opacity-25"><i class="fa-solid fa-globe me-1"></i> QS Ranked 2026</span>
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div class="col-lg-2 col-md-6 col-6">
                <h6 class="footer-heading">Quick Links</h6>
                <ul class="footer-links-list">
                    <li><a href="about.php"><i class="fa-solid fa-chevron-right"></i> About University</a></li>
                    <li><a href="academics.php"><i class="fa-solid fa-chevron-right"></i> Academic Programs</a></li>
                    <li><a href="admissions.php"><i class="fa-solid fa-chevron-right"></i> Admissions 2026</a></li>
                    <li><a href="placements.php"><i class="fa-solid fa-chevron-right"></i> Training & Placement</a></li>
                    <li><a href="contact.php"><i class="fa-solid fa-chevron-right"></i> Campus Map & Visit</a></li>
                    <li><a href="login.php"><i class="fa-solid fa-chevron-right"></i> Student Portal</a></li>
                </ul>
            </div>

            <!-- Col 3: Academic Schools -->
            <div class="col-lg-3 col-md-6 col-6">
                <h6 class="footer-heading">Schools & Faculties</h6>
                <ul class="footer-links-list">
                    <li><a href="academics.php#dept1"><i class="fa-solid fa-chevron-right"></i> Computer Science & AI</a></li>
                    <li><a href="academics.php#dept2"><i class="fa-solid fa-chevron-right"></i> Business Administration</a></li>
                    <li><a href="academics.php#dept3"><i class="fa-solid fa-chevron-right"></i> Information Technology</a></li>
                    <li><a href="academics.php#dept4"><i class="fa-solid fa-chevron-right"></i> Robotics & Mechanical</a></li>
                    <li><a href="academics.php#dept5"><i class="fa-solid fa-chevron-right"></i> Media, Arts & Humanities</a></li>
                    <li><a href="admissions.php#eligibility"><i class="fa-solid fa-chevron-right"></i> Scholarships & Aid</a></li>
                </ul>
            </div>

            <!-- Col 4: Campus Helpdesk & Contact -->
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading">Campus Helpdesk</h6>
                <div class="footer-contact-item mb-2 d-flex gap-2">
                    <i class="fa-solid fa-location-dot text-gold mt-1"></i>
                    <span class="text-light-muted fs-7">University Campus, Knowledge Boulevard, Sector 62, Institutional Area, New Delhi - 110025</span>
                </div>
                <div class="footer-contact-item mb-2 d-flex gap-2">
                    <i class="fa-solid fa-phone text-gold mt-1"></i>
                    <span class="text-light-muted fs-7">+91 (011) 2876-5400 / 5401</span>
                </div>
                <div class="footer-contact-item mb-3 d-flex gap-2">
                    <i class="fa-solid fa-envelope text-gold mt-1"></i>
                    <span class="text-light-muted fs-7">info@apexuniversity.edu.in</span>
                </div>
                <!-- Social Connect Icons -->
                <div class="footer-social-icons d-flex gap-2">
                    <a href="#" class="social-circle" title="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="#" class="social-circle" title="Twitter / X"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#" class="social-circle" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="social-circle" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="social-circle" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>
        </div>

        <!-- Sub-footer: Copyright -->
        <div class="sub-footer pt-3 d-flex flex-wrap justify-content-between align-items-center text-light-muted fs-7">
            <div>
                &copy; <?php echo date('Y'); ?> <strong>Apex Imperial University</strong>. All Rights Reserved.
            </div>
            <div class="d-flex gap-3">
                <a href="#" class="text-light-muted text-decoration-none">Privacy Policy</a>
                <span>&bull;</span>
                <a href="#" class="text-light-muted text-decoration-none">Terms of Service</a>
                <span>&bull;</span>
                <a href="login.php" class="text-gold text-decoration-none fw-semibold"><i class="fa-solid fa-lock"></i> Staff & Student Login</a>
            </div>
        </div>
    </div>
</footer>

<!-- Floating Quick Portal Access Widget for Examiner/Demo Presentation -->
<div class="quick-demo-widget" id="quickDemoWidget">
    <div class="quick-demo-toggle" onclick="toggleQuickDemo()" title="1-Click Academic Portals Demo Access">
        <i class="fa-solid fa-bolt text-warning"></i>
        <span>Portal Quick Access</span>
        <i class="fa-solid fa-chevron-up toggle-arrow" id="demoToggleArrow"></i>
    </div>
    <div class="quick-demo-popup" id="quickDemoPopup">
        <div class="popup-header">
            <strong><i class="fa-solid fa-graduation-cap text-gold me-1"></i> Fast-Fill Portal Demo</strong>
            <span class="badge bg-gold text-dark" style="font-size:10px;">EXAMINER PREVIEW</span>
        </div>
        <p class="popup-subtext">Click any role to test full portal capabilities with live data:</p>
        <div class="popup-links">
            <a href="login.php?role=admin" class="demo-link-btn admin">
                <div class="icon-wrap"><i class="fa-solid fa-shield-halved"></i></div>
                <div>
                    <div class="title">Admin Portal</div>
                    <small>admin@gmail.com / admin123</small>
                </div>
            </a>
            <a href="login.php?role=faculty" class="demo-link-btn faculty">
                <div class="icon-wrap"><i class="fa-solid fa-chalkboard-user"></i></div>
                <div>
                    <div class="title">Faculty Portal</div>
                    <small>faculty@gmail.com / faculty123</small>
                </div>
            </a>
            <a href="login.php?role=student" class="demo-link-btn student">
                <div class="icon-wrap"><i class="fa-solid fa-user-graduate"></i></div>
                <div>
                    <div class="title">Student Portal</div>
                    <small>student@gmail.com / student123</small>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
.quick-demo-widget {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.quick-demo-toggle {
    background: #0a192f;
    color: #ffffff;
    border: 2px solid #d4af37;
    border-radius: 50px;
    padding: 10px 20px;
    font-size: 13px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    box-shadow: 0 8px 25px rgba(0,0,0,0.35), 0 0 15px rgba(212,175,55,0.3);
    transition: all 0.25s ease;
}
.quick-demo-toggle:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.4), 0 0 20px rgba(212,175,55,0.5);
    background: #112240;
}
.quick-demo-popup {
    display: none;
    position: absolute;
    bottom: 50px;
    right: 0;
    width: 290px;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.25);
    border: 1px solid #e2e8f0;
    padding: 16px;
    animation: popSlideUp 0.25s ease;
}
@keyframes popSlideUp {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}
.popup-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    font-size: 13px;
    color: #0f172a;
}
.popup-subtext {
    font-size: 11.5px;
    color: #64748b;
    margin-bottom: 12px;
    line-height: 1.4;
}
.popup-links {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.demo-link-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 9px 12px;
    border-radius: 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    text-decoration: none;
    transition: all 0.2s ease;
}
.demo-link-btn:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
    transform: translateX(3px);
}
.demo-link-btn .icon-wrap {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
}
.demo-link-btn.admin .icon-wrap { background: #fee2e2; color: #dc2626; }
.demo-link-btn.faculty .icon-wrap { background: #e0e7ff; color: #4338ca; }
.demo-link-btn.student .icon-wrap { background: #dcfce7; color: #15803d; }
.demo-link-btn .title {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
}
.demo-link-btn small {
    font-size: 10.5px;
    color: #64748b;
    display: block;
}
</style>

<script>
function toggleQuickDemo() {
    const pop = document.getElementById('quickDemoPopup');
    const arr = document.getElementById('demoToggleArrow');
    if (pop.style.display === 'block') {
        pop.style.display = 'none';
        arr.style.transform = 'rotate(0deg)';
    } else {
        pop.style.display = 'block';
        arr.style.transform = 'rotate(180deg)';
    }
}
</script>

<!-- Floating Back-to-Top Button -->
<button id="backToTopBtn" class="back-to-top-btn" title="Back to Top">
    <i class="fa-solid fa-arrow-up"></i>
</button>

<!-- Virtual Admissions Assistant / FAQ Helpbot -->
<div class="helpbot-toggle" id="helpbotToggle" onclick="toggleHelpbot()" title="Chat with Admissions Assistant">
    <i class="fa-solid fa-headset"></i>
    <span>Admissions Help</span>
</div>

<div class="helpbot-dialog" id="helpbotDialog">
    <div class="helpbot-header">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-robot text-gold"></i>
            <strong style="font-size:13px;">Apex Virtual Assistant</strong>
        </div>
        <button type="button" class="btn-close btn-close-white" onclick="toggleHelpbot()" style="font-size:10px;"></button>
    </div>
    <div class="helpbot-body" id="helpbotBody">
        <div class="chat-bubble bot">
            Hello! Welcome to Apex Imperial University. How can I assist you with your admissions journey today?
        </div>
        <div class="text-muted small mb-2 fw-bold" style="font-size:11px;">Frequently asked queries:</div>
        <button type="button" class="helpbot-chip" onclick="askHelpbot('eligibility')"><i class="fa-solid fa-graduation-cap text-gold me-1"></i> B.Tech & BCA Eligibility</button>
        <button type="button" class="helpbot-chip" onclick="askHelpbot('scholarship')"><i class="fa-solid fa-award text-gold me-1"></i> Merit Scholarships (Up to 100%)</button>
        <button type="button" class="helpbot-chip" onclick="askHelpbot('placement')"><i class="fa-solid fa-briefcase text-gold me-1"></i> Placement Record (₹44.5 LPA)</button>
        <button type="button" class="helpbot-chip" onclick="askHelpbot('hostel')"><i class="fa-solid fa-bed text-gold me-1"></i> Hostels & Campus Life</button>
    </div>
</div>

<!-- Quick Search & Command Palette Modal (Ctrl + K) -->
<div class="search-modal-backdrop" id="searchModalBackdrop" onclick="if(event.target === this) closeSearchModal()">
    <div class="search-modal-content">
        <div class="search-input-header">
            <i class="fa-solid fa-magnifying-glass text-gold fs-5"></i>
            <input type="text" id="quickSearchInput" placeholder="Search degrees, admissions, faculties, portals... (Press Esc to close)" oninput="filterSearchResults(this.value)">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="closeSearchModal()">Esc</button>
        </div>
        <div class="search-results-list" id="searchResultsList">
            <a href="academics.php#undergraduate" class="search-result-item">
                <i class="fa-solid fa-graduation-cap text-gold fs-5"></i>
                <div><strong>B.Tech Computer Science & Engineering</strong> <small class="text-muted d-block">4 Years &bull; AI, Cloud & Cybersecurity specializations</small></div>
            </a>
            <a href="academics.php#undergraduate" class="search-result-item">
                <i class="fa-solid fa-laptop-code text-primary fs-5"></i>
                <div><strong>Bachelor of Computer Applications (BCA)</strong> <small class="text-muted d-block">3 Years &bull; Full-stack web, cloud computing & mobile apps</small></div>
            </a>
            <a href="academics.php#postgraduate" class="search-result-item">
                <i class="fa-solid fa-chart-line text-success fs-5"></i>
                <div><strong>Master of Business Administration (MBA)</strong> <small class="text-muted d-block">2 Years &bull; Finance, Marketing, Supply Chain & Analytics</small></div>
            </a>
            <a href="admissions.php#applyNow" class="search-result-item">
                <i class="fa-solid fa-paper-plane text-warning fs-5"></i>
                <div><strong>Apply for Admission 2026</strong> <small class="text-muted d-block">Online application form with scholarship eligibility</small></div>
            </a>
            <a href="admissions.php#trackApp" class="search-result-item">
                <i class="fa-solid fa-clock-rotate-left text-info fs-5"></i>
                <div><strong>Track Application Status</strong> <small class="text-muted d-block">Check live review and verification status with App No</small></div>
            </a>
            <a href="placements.php" class="search-result-item">
                <i class="fa-solid fa-building text-primary fs-5"></i>
                <div><strong>Placement Statistics & Recruiter Wall</strong> <small class="text-muted d-block">₹44.5 LPA highest package & 350+ recruiters</small></div>
            </a>
            <a href="login.php?role=student" class="search-result-item">
                <i class="fa-solid fa-user-graduate text-success fs-5"></i>
                <div><strong>Student Portal Access</strong> <small class="text-muted d-block">Exam results, timetable, fees & assignment submissions</small></div>
            </a>
            <a href="login.php?role=admin" class="search-result-item">
                <i class="fa-solid fa-shield-halved text-danger fs-5"></i>
                <div><strong>Admin Management Dashboard</strong> <small class="text-muted d-block">Admissions, student records, analytics & approvals</small></div>
            </a>
            <div id="noSearchResults" style="display:none; padding:20px; text-align:center; color:#64748b;">
                <i class="fa-solid fa-circle-question fs-3 text-muted mb-2"></i>
                <p class="mb-0">No matching degrees or pages found. Try searching for "B.Tech", "MBA", "Admissions", or "Portal".</p>
            </div>
        </div>
    </div>
</div>

<!-- Virtual Campus Tour Modal -->
<div class="tour-modal-backdrop" id="campusTourModal" onclick="if(event.target === this) closeCampusTourModal()">
    <div class="tour-modal-content">
        <div class="d-flex justify-content-between align-items-center p-3 border-bottom border-secondary border-opacity-25" style="background:#071326;">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-circle-play text-gold fs-5"></i>
                <h5 class="mb-0 text-white fw-bold font-serif">Apex Imperial University &bull; Virtual 360 Campus Tour</h5>
            </div>
            <button type="button" class="btn-close btn-close-white" onclick="closeCampusTourModal()"></button>
        </div>
        <div class="p-4">
            <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-lg border border-warning border-opacity-25 mb-4 position-relative">
                <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=1200&q=80" class="w-100 h-100 object-fit-cover" alt="Campus Aerial Preview">
                <div class="position-absolute top-50 start-50 translate-middle text-center w-75 p-3 rounded-4" style="background:rgba(10,25,47,0.85); backdrop-filter:blur(6px); border:1px solid rgba(212,175,55,0.4);">
                    <div class="d-inline-flex p-3 rounded-circle bg-gold text-dark mb-2" style="font-size:24px; box-shadow:0 0 20px rgba(212,175,55,0.6);">
                        <i class="fa-solid fa-play"></i>
                    </div>
                    <h5 class="text-white fw-bold mb-1">120-Acre Eco-Smart Campus Aerial Flythrough</h5>
                    <small class="text-gold">Drone footage, research facilities, sports arenas & student dormitories</small>
                </div>
            </div>
            <div class="row g-3 text-center">
                <div class="col-md-3 col-6">
                    <div class="p-3 rounded-3" style="background:#112240; border:1px solid rgba(255,255,255,0.1);">
                        <i class="fa-solid fa-book text-gold fs-4 mb-2"></i>
                        <h6 class="text-white mb-0 small fw-bold">Central Library</h6>
                        <small class="text-light-muted" style="font-size:11px;">150k+ Books & 24/7 Hub</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-3 rounded-3" style="background:#112240; border:1px solid rgba(255,255,255,0.1);">
                        <i class="fa-solid fa-microchip text-gold fs-4 mb-2"></i>
                        <h6 class="text-white mb-0 small fw-bold">AI Supercomputing</h6>
                        <small class="text-light-muted" style="font-size:11px;">NVIDIA DGX Stations</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-3 rounded-3" style="background:#112240; border:1px solid rgba(255,255,255,0.1);">
                        <i class="fa-solid fa-basketball text-gold fs-4 mb-2"></i>
                        <h6 class="text-white mb-0 small fw-bold">Olympic Complex</h6>
                        <small class="text-light-muted" style="font-size:11px;">Track, Pool & Arena</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-3 rounded-3" style="background:#112240; border:1px solid rgba(255,255,255,0.1);">
                        <i class="fa-solid fa-bed text-gold fs-4 mb-2"></i>
                        <h6 class="text-white mb-0 small fw-bold">Hostel Residences</h6>
                        <small class="text-light-muted" style="font-size:11px;">3,500+ AC Rooms</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-3 border-top border-secondary border-opacity-25 text-end" style="background:#071326;">
            <a href="contact.php" class="btn btn-gold btn-sm"><i class="fa-solid fa-calendar-check me-1"></i> Schedule In-Person Campus Visit</a>
        </div>
    </div>
</div>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- AOS Animation JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Public Website Scripts -->
<script src="assets/js/public.js"></script>
<script src="assets/js/icon-fixer.js"></script>
<script>
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            once: true,
            offset: 50
        });
    }
</script>
</body>
</html>
