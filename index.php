<?php
/**
 * Modern University Portal - Home Page
 * Apex Imperial University
 */

include("config/db.php");

$page_title = "Apex Imperial University | Excellence in Higher Education & Research";

// Fetch Latest 4 Announcements
$notices_q = mysqli_query($con, "SELECT * FROM notices ORDER BY notice_id DESC LIMIT 4");

// Fetch 3 Upcoming Events
$events_q = mysqli_query($con, "SELECT * FROM events ORDER BY event_date ASC LIMIT 3");

// Fetch 6 Popular Academic Programs
$courses_q = mysqli_query($con, "
    SELECT c.*, d.name AS dept_name, d.icon AS dept_icon
    FROM courses c
    JOIN departments d ON c.department_id = d.department_id
    ORDER BY c.course_id ASC
    LIMIT 6
");

// Fetch Top 4 Placed Students
$placements_q = mysqli_query($con, "SELECT * FROM placements ORDER BY package_lpa DESC LIMIT 4");

// Fetch 6 Gallery Photos
$gallery_q = mysqli_query($con, "SELECT * FROM gallery ORDER BY gallery_id ASC LIMIT 6");

include("includes/public_header.php");
include("includes/public_navbar.php");
?>

<!-- Announcement Notice Ticker -->
<div class="notice-ticker-bar">
    <div class="container d-flex align-items-center gap-3">
        <span class="ticker-badge"><i class="fa-solid fa-bolt me-1"></i> Announcements</span>
        <div class="overflow-hidden flex-grow-1" style="white-space: nowrap;">
            <marquee behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();" class="text-white small mb-0">
                🎓 Admissions Open for Session 2026–2027 in B.Tech, BCA, MBA & MCA Programs &bull;
                🏆 Apex Imperial University ranked in Top 25 NIRF Universities 2026 &bull;
                🚀 InnovateTech 36-Hour Hackathon Registrations live now &bull;
                📚 Mid-Semester Examination Schedules released for all departments.
            </marquee>
        </div>
    </div>
</div>

<!-- 1. Hero Section -->
<header class="hero-section">
    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-up">
                <div class="hero-tag">
                    <i class="fa-solid fa-award"></i> NAAC A++ Accredited Autonomous University
                </div>
                <h1 class="hero-title">
                    Empowering Next-Gen Leaders with <span>World-Class Knowledge</span> & Innovation.
                </h1>
                <p class="hero-subtitle">
                    At Apex Imperial University, we blend academic rigor with real-world research, industry mentorship, and cutting-edge labs to prepare scholars for global impact.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="admissions.php#applyNow" class="btn btn-gold btn-lg">
                        <i class="fa-solid fa-paper-plane me-1"></i> Apply for Admission 2026
                    </a>
                    <a href="academics.php" class="btn btn-outline-gold btn-lg">
                        <i class="fa-solid fa-graduation-cap me-1"></i> Explore Programs
                    </a>
                    <button type="button" class="btn btn-outline-light btn-lg" onclick="openCampusTourModal()">
                        <i class="fa-solid fa-circle-play text-gold me-1"></i> Campus Tour
                    </button>
                    <a href="login.php" class="btn btn-navy btn-lg">
                        <i class="fa-solid fa-lock me-1"></i> Portal Access
                    </a>
                </div>

                <!-- Key Metrics Counter Strip -->
                <div class="row g-4 hero-stats-row">
                    <div class="col-6 col-md-3 hero-stat-item">
                        <h3 class="counter-val" data-target="15000" data-suffix="+">15,000+</h3>
                        <p>Active Scholars</p>
                    </div>
                    <div class="col-6 col-md-3 hero-stat-item">
                        <h3 class="counter-val" data-target="98" data-suffix="%">98%</h3>
                        <p>Placement Rate</p>
                    </div>
                    <div class="col-6 col-md-3 hero-stat-item">
                        <h3 class="counter-val" data-target="44.5" data-suffix=" LPA">44.5 LPA</h3>
                        <p>Highest Package</p>
                    </div>
                    <div class="col-6 col-md-3 hero-stat-item">
                        <h3 class="counter-val" data-target="350" data-suffix="+">350+</h3>
                        <p>Recruiting Giants</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- 2. About University Overview -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="section-tag">Discover Our Legacy</span>
                <h2 class="section-title">An Institution Built on Three Decades of Academic Distinction</h2>
                <p class="text-secondary mb-4" style="line-height: 1.8;">
                    Established in 1988 under the visionary leadership of distinguished academicians, <strong>Apex Imperial University</strong> has grown into a world-renowned seat of higher learning. Across our 120-acre modern eco-campus, over 15,000 students pursue research-driven undergraduate and postgraduate programs.
                </p>
                
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="p-3 rounded-3 bg-light border-start border-4 border-warning">
                            <h6 class="fw-bold mb-1 text-navy"><i class="fa-solid fa-microchip text-gold me-2"></i> Industry 4.0 Labs</h6>
                            <small class="text-muted">NVIDIA GPU, AI, IoT and Robotics specialized research centers.</small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 rounded-3 bg-light border-start border-4 border-warning">
                            <h6 class="fw-bold mb-1 text-navy"><i class="fa-solid fa-globe text-gold me-2"></i> Global Partnerships</h6>
                            <small class="text-muted">Student exchange tie-ups with 45+ premier universities worldwide.</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <a href="about.php" class="btn btn-navy">
                        <span>Read Full Story</span> <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a href="admissions.php" class="btn btn-outline-secondary">
                        <span>Admission Eligibility</span>
                    </a>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-left">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1000&q=80" alt="Students on Campus" class="img-fluid rounded-4 shadow-lg w-100" style="min-height: 400px; object-fit: cover;">
                    <div class="position-absolute bottom-0 start-0 m-4 p-4 rounded-4 bg-navy text-white shadow-lg d-none d-sm-block border border-warning border-opacity-25" style="max-width: 320px;">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-trophy fs-1 text-gold"></i>
                            <div>
                                <h5 class="fw-bold mb-0 text-white">NIRF Rank 24</h5>
                                <small class="text-gold">Top 25 National University Ranking 2026</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Featured Academic Programs -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center mb-4" data-aos="fade-up">
            <span class="section-tag">Academic Excellence</span>
            <h2 class="section-title">Undergraduate & Postgraduate Programs</h2>
            <p class="section-subtitle">Choose from internationally accredited degrees formulated in consultation with corporate industry experts.</p>
        </div>

        <!-- Interactive Discipline Filter Pills -->
        <div class="d-flex justify-content-center gap-2 mb-5 flex-wrap" data-aos="fade-up">
            <button type="button" class="btn btn-primary program-filter-btn px-4 py-2" onclick="filterPrograms('all', this)" style="border-radius:25px; font-size:13px; font-weight:700;">
                <i class="fa-solid fa-layer-group me-1"></i> All Disciplines
            </button>
            <button type="button" class="btn btn-outline-secondary program-filter-btn px-4 py-2" onclick="filterPrograms('Engineering', this)" style="border-radius:25px; font-size:13px; font-weight:700;">
                <i class="fa-solid fa-microchip me-1"></i> Engineering & AI
            </button>
            <button type="button" class="btn btn-outline-secondary program-filter-btn px-4 py-2" onclick="filterPrograms('Computer Applications', this)" style="border-radius:25px; font-size:13px; font-weight:700;">
                <i class="fa-solid fa-code me-1"></i> Computer Applications
            </button>
            <button type="button" class="btn btn-outline-secondary program-filter-btn px-4 py-2" onclick="filterPrograms('Management', this)" style="border-radius:25px; font-size:13px; font-weight:700;">
                <i class="fa-solid fa-chart-pie me-1"></i> Business & MBA
            </button>
        </div>

        <div class="row g-4">
            <?php if ($courses_q && safe_num_rows($courses_q) > 0): ?>
                <?php while ($c = safe_fetch_assoc($courses_q)): 
                    $cat = 'General';
                    $cname = $c['name'];
                    if (stripos($cname, 'Computer Science') !== false || stripos($cname, 'B.Tech') !== false || stripos($cname, 'M.Tech') !== false) {
                        $cat = 'Engineering';
                    } elseif (stripos($cname, 'BCA') !== false || stripos($cname, 'MCA') !== false || stripos($cname, 'Computer Applications') !== false || stripos($cname, 'Information Tech') !== false) {
                        $cat = 'Computer Applications';
                    } elseif (stripos($cname, 'BBA') !== false || stripos($cname, 'MBA') !== false || stripos($cname, 'Business') !== false) {
                        $cat = 'Management';
                    }
                ?>
                    <div class="col-lg-4 col-md-6 program-filter-item" data-category="<?php echo $cat; ?>" data-aos="fade-up">
                        <div class="univ-card p-4 position-relative d-flex flex-column justify-content-between">
                            <div>
                                <span class="course-badge bg-navy text-gold">
                                    <?php echo htmlspecialchars($c['degree_level']); ?>
                                </span>
                                <div class="dept-icon-box">
                                    <i class="fa-solid <?php echo htmlspecialchars($c['dept_icon'] ?? 'fa-graduation-cap'); ?>"></i>
                                </div>
                                <small class="text-muted fw-bold text-uppercase d-block mb-1">
                                    <?php echo htmlspecialchars($c['dept_name']); ?>
                                </small>
                                <h5 class="fw-bold mb-2 text-navy">
                                    <?php echo htmlspecialchars($c['name']); ?>
                                </h5>
                                <p class="text-secondary small mb-3" style="line-height: 1.6;">
                                    <?php echo htmlspecialchars(substr($c['description'], 0, 120)) . '...'; ?>
                                </p>
                            </div>

                            <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted d-block">Duration</small>
                                    <strong class="text-navy"><?php echo htmlspecialchars($c['duration']); ?></strong>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Annual Fee</small>
                                    <strong class="text-gold">₹<?php echo number_format($c['fees_per_year']); ?></strong>
                                </div>
                                <a href="academics.php#course<?php echo $c['course_id']; ?>" class="btn btn-outline-gold btn-sm">
                                    Details <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

        <div class="text-center mt-5" data-aos="fade-up">
            <a href="academics.php" class="btn btn-navy btn-lg">
                <i class="fa-solid fa-book-open me-2"></i> View All Degrees & Syllabi
            </a>
        </div>
    </div>
</section>

<!-- 4. Announcements & Upcoming Events (Two Columns) -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row g-5">
            <!-- Left: Latest Announcements -->
            <div class="col-lg-6" data-aos="fade-right">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="section-tag">Campus News</span>
                        <h3 class="fw-bold text-navy mb-0">Official Bulletin & Notices</h3>
                    </div>
                    <a href="admissions.php" class="text-gold fw-bold text-decoration-none small">View All <i class="fa-solid fa-chevron-right"></i></a>
                </div>

                <div class="d-flex flex-column gap-3">
                    <?php if ($notices_q && safe_num_rows($notices_q) > 0): ?>
                        <?php while ($n = safe_fetch_assoc($notices_q)): ?>
                            <div class="p-3 rounded-3 bg-light border-start border-4 border-primary">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge bg-navy text-gold font-monospace"><?php echo htmlspecialchars($n['category']); ?></span>
                                    <small class="text-muted"><i class="fa-regular fa-clock me-1"></i> <?php echo date('d M Y', strtotime($n['posted_on'])); ?></small>
                                </div>
                                <h6 class="fw-bold mb-1 text-navy"><?php echo htmlspecialchars($n['title']); ?></h6>
                                <p class="text-secondary small mb-1"><?php echo htmlspecialchars(substr($n['content'], 0, 140)) . '...'; ?></p>
                                <small class="text-muted fw-semibold">Issued by: <?php echo htmlspecialchars($n['posted_by']); ?></small>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right: Upcoming Events -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="section-tag">Campus Life</span>
                        <h3 class="fw-bold text-navy mb-0">Upcoming Events & Summits</h3>
                    </div>
                    <a href="contact.php" class="text-gold fw-bold text-decoration-none small">Event Calendar <i class="fa-solid fa-chevron-right"></i></a>
                </div>

                <div class="d-flex flex-column gap-3">
                    <?php if ($events_q && safe_num_rows($events_q) > 0): ?>
                        <?php while ($e = safe_fetch_assoc($events_q)): ?>
                            <div class="event-card">
                                <div class="event-date-box">
                                    <span class="event-day"><?php echo date('d', strtotime($e['event_date'])); ?></span>
                                    <span class="event-month"><?php echo date('M', strtotime($e['event_date'])); ?></span>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1 text-navy"><?php echo htmlspecialchars($e['title']); ?></h5>
                                    <p class="text-secondary small mb-2"><?php echo htmlspecialchars(substr($e['description'], 0, 110)) . '...'; ?></p>
                                    <div class="d-flex gap-3 text-muted small">
                                        <span><i class="fa-solid fa-clock text-gold me-1"></i> <?php echo htmlspecialchars($e['event_time']); ?></span>
                                        <span><i class="fa-solid fa-location-dot text-gold me-1"></i> <?php echo htmlspecialchars($e['location']); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. Placement & Corporate Achievements Banner -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="placement-banner" data-aos="zoom-in">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="badge bg-gold text-dark fw-bold px-3 py-2 text-uppercase mb-3">Corporate Placements & Careers</span>
                    <h2 class="text-white fw-bold mb-3 font-serif">Bridging Academic Talent with Global Tech Giants</h2>
                    <p class="text-light-muted mb-4">
                        Our centralized Career Development Cell conducts intensive coding bootcamps, mock corporate interviews, and industry hackathons ensuring consistent placement records year after year.
                    </p>
                    <div class="d-flex flex-wrap gap-4">
                        <div>
                            <h3 class="fw-bold text-gold mb-0">₹44.5 LPA</h3>
                            <small class="text-light-muted">Highest International Package</small>
                        </div>
                        <div>
                            <h3 class="fw-bold text-gold mb-0">₹12.8 LPA</h3>
                            <small class="text-light-muted">Average Campus Package</small>
                        </div>
                        <div>
                            <h3 class="fw-bold text-gold mb-0">350+</h3>
                            <small class="text-light-muted">Corporate Recruiters</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 text-lg-end">
                    <a href="placements.php" class="btn btn-gold btn-lg me-2 mb-2">
                        <i class="fa-solid fa-chart-line me-1"></i> View Placement Report
                    </a>
                    <a href="admissions.php" class="btn btn-outline-light btn-lg mb-2">
                        <span>Admission Process</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Student Star Achievers -->
        <div class="row g-4 mt-4">
            <?php if ($placements_q && safe_num_rows($placements_q) > 0): ?>
                <?php while ($p = safe_fetch_assoc($placements_q)): ?>
                    <div class="col-md-3 col-sm-6" data-aos="fade-up">
                        <div class="univ-card p-3 text-center">
                            <div class="badge bg-warning bg-opacity-25 text-warning fw-bold mb-2">
                                Package: ₹<?php echo $p['package_lpa']; ?> LPA
                            </div>
                            <h6 class="fw-bold text-navy mb-0"><?php echo htmlspecialchars($p['student_name']); ?></h6>
                            <small class="text-muted d-block mb-2"><?php echo htmlspecialchars($p['role']); ?></small>
                            <span class="badge bg-navy text-white px-3 py-2">
                                <i class="fa-solid fa-building me-1"></i> <?php echo htmlspecialchars($p['company_name']); ?>
                            </span>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 6. World-Class Campus Facilities -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Campus Infrastructure</span>
            <h2 class="section-title">Designed for Holistic Learning & Living</h2>
            <p class="section-subtitle">Explore a vibrant ecosystem equipped with world-class academic, residential, and recreational facilities.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="facility-card">
                    <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=800&q=80" alt="Central Library">
                    <div class="facility-overlay">
                        <h5>Central Automated Library</h5>
                        <p class="small text-light-muted mb-0">150,000+ volumes, IEEE e-journals & 24/7 digital study pods.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="facility-card">
                    <img src="https://images.unsplash.com/photo-1581092921461-eab62e97a780?auto=format&fit=crop&w=800&q=80" alt="AI Computing Lab">
                    <div class="facility-overlay">
                        <h5>AI & High-Performance Lab</h5>
                        <p class="small text-light-muted mb-0">NVIDIA DGX GPU workstations for deep learning and robotics simulation.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="facility-card">
                    <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=800&q=80" alt="University Auditorium">
                    <div class="facility-overlay">
                        <h5>Grand Convention Hall</h5>
                        <p class="small text-light-muted mb-0">2,500-seat acoustically engineered convention center for international meets.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 7. Campus Photo Gallery -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Campus Snapshot</span>
            <h2 class="section-title">Life at Apex Imperial University</h2>
            <p class="section-subtitle">Moments from our vibrant academic calendar, athletic tournaments, and technological symposia.</p>
        </div>

        <div class="row g-4">
            <?php if ($gallery_q && safe_num_rows($gallery_q) > 0): ?>
                <?php while ($g = safe_fetch_assoc($gallery_q)): ?>
                    <div class="col-lg-4 col-md-6" data-aos="zoom-in">
                        <div class="facility-card" style="height: 240px;">
                            <img src="<?php echo htmlspecialchars($g['image_url']); ?>" alt="<?php echo htmlspecialchars($g['title']); ?>">
                            <div class="facility-overlay">
                                <span class="badge bg-gold text-dark mb-1 align-self-start font-monospace" style="font-size:10px;">
                                    <?php echo htmlspecialchars($g['category']); ?>
                                </span>
                                <h6 class="text-white fw-bold mb-0"><?php echo htmlspecialchars($g['title']); ?></h6>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 8. Student Voices & Success Testimonials -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Alumni & Scholar Stories</span>
            <h2 class="section-title">What Our Scholars Say About Apex Imperial</h2>
            <p class="section-subtitle">Real experiences from our alumni working at top international tech enterprises and research institutes.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="testimonial-card">
                    <div>
                        <div class="rating-stars">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <p class="testimonial-quote">
                            "The specialized NVIDIA GPU computing labs and research mentorship at Apex gave me the skills to publish two IEEE papers and clear Google's rigorous hiring rounds. The professors here push you to innovate, not just pass exams."
                        </p>
                    </div>
                    <div class="testimonial-author">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80" alt="Ananya Sharma" class="testimonial-avatar">
                        <div>
                            <h6 class="fw-bold mb-0 text-navy">Ananya Sharma</h6>
                            <small class="text-muted d-block">B.Tech CSE &bull; Class of 2024</small>
                            <span class="badge bg-gold text-dark mt-1" style="font-size:10px;"><i class="fa-brands fa-google me-1"></i> SDE at Google (₹44.5 LPA)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="testimonial-card">
                    <div>
                        <div class="rating-stars">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <p class="testimonial-quote">
                            "The international exchange semester in Singapore and hands-on consulting case studies transformed my career trajectory. The Career Development Cell conducted mock GDs and executive interviews that helped me secure a high-impact consulting role."
                        </p>
                    </div>
                    <div class="testimonial-author">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80" alt="Rohan Mehta" class="testimonial-avatar">
                        <div>
                            <h6 class="fw-bold mb-0 text-navy">Rohan Mehta</h6>
                            <small class="text-muted d-block">MBA &bull; Class of 2023</small>
                            <span class="badge bg-navy text-white mt-1" style="font-size:10px;"><i class="fa-solid fa-briefcase me-1"></i> Strategy Lead at Deloitte (₹28 LPA)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="testimonial-card">
                    <div>
                        <div class="rating-stars">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <p class="testimonial-quote">
                            "The continuous 36-hour hackathons and live capstone projects made full-stack development second nature. The integrated student portal made keeping track of attendance, results, and faculty feedback effortless."
                        </p>
                    </div>
                    <div class="testimonial-author">
                        <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=200&q=80" alt="Priya Singh" class="testimonial-avatar">
                        <div>
                            <h6 class="fw-bold mb-0 text-navy">Priya Singh</h6>
                            <small class="text-muted d-block">BCA &bull; Class of 2024</small>
                            <span class="badge bg-gold text-dark mt-1" style="font-size:10px;"><i class="fa-brands fa-microsoft me-1"></i> Cloud Engineer at Microsoft (₹32 LPA)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 9. Ready to Apply CTA Section -->
<section class="py-5 bg-navy text-white text-center position-relative" style="border-top: 3px solid var(--gold);">
    <div class="container py-4 position-relative" style="z-index: 2;" data-aos="fade-up">
        <span class="badge bg-gold text-dark px-3 py-2 text-uppercase fw-bold mb-3">Admissions Open 2026–2027</span>
        <h2 class="display-5 fw-bold font-serif mb-3">Begin Your Journey Towards Academic Excellence</h2>
        <p class="text-light-muted fs-5 mb-4 mx-auto" style="max-width: 650px;">
            Join over 15,000 scholars shaping the future in Science, Technology, Business, and Humanities. Apply online in just 5 minutes.
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="admissions.php#applyNow" class="btn btn-gold btn-lg px-4">
                <i class="fa-solid fa-paper-plane me-1"></i> Submit Online Application
            </a>
            <a href="contact.php" class="btn btn-outline-light btn-lg px-4">
                <i class="fa-solid fa-phone me-1"></i> Talk to Admissions Counselor
            </a>
        </div>
    </div>
</section>

<?php include("includes/public_footer.php"); ?>
