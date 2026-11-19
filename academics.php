<?php
/**
 * Modern University Portal - Academics & Programs
 * Apex Imperial University
 */

include("config/db.php");

$page_title = "Academics & Degree Programs | Apex Imperial University";

// Fetch all departments
$depts_q = mysqli_query($con, "SELECT * FROM departments ORDER BY department_id ASC");

// Fetch Undergraduate Programs
$ug_courses_q = mysqli_query($con, "
    SELECT c.*, d.name AS dept_name, d.code AS dept_code
    FROM courses c
    JOIN departments d ON c.department_id = d.department_id
    WHERE c.degree_level='Undergraduate'
    ORDER BY c.course_id ASC
");

// Fetch Postgraduate Programs
$pg_courses_q = mysqli_query($con, "
    SELECT c.*, d.name AS dept_name, d.code AS dept_code
    FROM courses c
    JOIN departments d ON c.department_id = d.department_id
    WHERE c.degree_level='Postgraduate'
    ORDER BY c.course_id ASC
");

include("includes/public_header.php");
include("includes/public_navbar.php");
?>

<!-- Breadcrumb Strip -->
<div class="bg-navy py-5 text-white position-relative" style="border-bottom: 2px solid var(--gold);">
    <div class="container py-3">
        <span class="badge bg-gold text-dark text-uppercase fw-bold px-3 py-2 mb-2">Curriculum & Disciplines</span>
        <h1 class="display-4 fw-bold font-serif mb-2">Academic Programs & Departments</h1>
        <p class="text-light-muted fs-6 mb-0">World-class undergraduate, postgraduate, and doctoral curricula tailored for 21st-century global careers.</p>
    </div>
</div>

<!-- 1. Academic Departments Overview -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Schools of Study</span>
            <h2 class="section-title">Academic Departments & Research Centers</h2>
            <p class="section-subtitle">Our academic ecosystem is structured into <?php echo safe_num_rows($depts_q); ?> autonomous academic schools & departments promoting cross-disciplinary investigation.</p>
        </div>

        <div class="row g-4">
            <?php if ($depts_q && safe_num_rows($depts_q) > 0): ?>
                <?php while ($d = safe_fetch_assoc($depts_q)): ?>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" id="dept<?php echo $d['department_id']; ?>">
                        <div class="univ-card p-4">
                            <div class="dept-icon-box">
                                <i class="fa-solid <?php echo htmlspecialchars($d['icon'] ?? 'fa-graduation-cap'); ?>"></i>
                            </div>
                            <span class="badge bg-navy text-gold mb-2"><?php echo htmlspecialchars($d['code']); ?></span>
                            <h5 class="fw-bold text-navy mb-2"><?php echo htmlspecialchars($d['name']); ?></h5>
                            <p class="text-secondary small mb-3" style="line-height: 1.7;">
                                <?php echo htmlspecialchars($d['description']); ?>
                            </p>
                            <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                                <small class="text-muted"><i class="fa-solid fa-user-tie text-gold me-1"></i> Head of Dept:</small>
                                <strong class="small text-navy"><?php echo htmlspecialchars($d['head_of_dept']); ?></strong>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Interactive Degree Quick Search & Filter Bar -->
<div class="bg-light py-3 border-top border-bottom">
    <div class="container">
        <div class="p-3 bg-white rounded-4 border shadow-sm d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2 flex-grow-1" style="max-width: 480px;">
                <i class="fa-solid fa-magnifying-glass text-gold fs-5 ms-2"></i>
                <input type="text" id="courseFilterInput" class="form-control border-0 shadow-none" placeholder="Search degrees by name (e.g. B.Tech, BCA, MBA, AI)..." oninput="filterCourseCards(this.value)">
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="#undergraduate" class="btn btn-outline-primary btn-sm px-3" style="border-radius:20px; font-weight:700;"><i class="fa-solid fa-graduation-cap me-1"></i> Undergraduate (UG)</a>
                <a href="#postgraduate" class="btn btn-outline-primary btn-sm px-3" style="border-radius:20px; font-weight:700;"><i class="fa-solid fa-user-graduate me-1"></i> Postgraduate (PG)</a>
                <a href="admissions.php#applyNow" class="btn btn-gold btn-sm px-3" style="border-radius:20px; font-weight:700;"><i class="fa-solid fa-paper-plane me-1"></i> Apply Online</a>
            </div>
        </div>
    </div>
</div>

<!-- 2. Undergraduate Programs (UG) -->
<section class="py-5 bg-light" id="undergraduate">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3" data-aos="fade-up">
            <div>
                <span class="section-tag">Undergraduate Degrees</span>
                <h2 class="section-title mb-0">Bachelor Degree Programs (UG)</h2>
            </div>
            <a href="admissions.php#applyNow" class="btn btn-gold">
                <i class="fa-solid fa-paper-plane me-1"></i> Apply for UG Admission
            </a>
        </div>

        <div class="row g-4">
            <?php if ($ug_courses_q && safe_num_rows($ug_courses_q) > 0): ?>
                <?php while ($ug = safe_fetch_assoc($ug_courses_q)): ?>
                    <div class="col-lg-6" data-aos="fade-up" id="course<?php echo $ug['course_id']; ?>">
                        <div class="univ-card p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-navy text-gold"><?php echo htmlspecialchars($ug['code']); ?></span>
                                <span class="badge bg-warning bg-opacity-25 text-dark fw-bold"><?php echo htmlspecialchars($ug['duration']); ?> &bull; <?php echo $ug['semester_count']; ?> Semesters</span>
                            </div>
                            <h4 class="fw-bold text-navy mb-2"><?php echo htmlspecialchars($ug['name']); ?></h4>
                            <p class="text-secondary small mb-3" style="line-height: 1.7;">
                                <?php echo htmlspecialchars($ug['description']); ?>
                            </p>
                            <div class="p-3 bg-light rounded-3 mb-3 small">
                                <div class="mb-1"><strong><i class="fa-solid fa-check-circle text-gold me-1"></i> Eligibility:</strong> <?php echo htmlspecialchars($ug['eligibility']); ?></div>
                                <div><strong><i class="fa-solid fa-wallet text-gold me-1"></i> Annual Tuition:</strong> ₹<?php echo number_format($ug['fees_per_year']); ?> / Year</div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="admissions.php?course_id=<?php echo $ug['course_id']; ?>#applyNow" class="btn btn-gold btn-sm flex-grow-1">
                                    <i class="fa-solid fa-file-pen me-1"></i> Apply Now
                                </a>
                                <a href="contact.php" class="btn btn-outline-secondary btn-sm">
                                    <i class="fa-solid fa-info-circle me-1"></i> Syllabus Enquiry
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 3. Postgraduate Programs (PG) -->
<section class="py-5 bg-white" id="postgraduate">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3" data-aos="fade-up">
            <div>
                <span class="section-tag">Postgraduate Degrees</span>
                <h2 class="section-title mb-0">Master Degree Programs (PG)</h2>
            </div>
            <a href="admissions.php#applyNow" class="btn btn-gold">
                <i class="fa-solid fa-paper-plane me-1"></i> Apply for PG Admission
            </a>
        </div>

        <div class="row g-4">
            <?php if ($pg_courses_q && safe_num_rows($pg_courses_q) > 0): ?>
                <?php while ($pg = safe_fetch_assoc($pg_courses_q)): ?>
                    <div class="col-lg-6" data-aos="fade-up" id="course<?php echo $pg['course_id']; ?>">
                        <div class="univ-card p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-navy text-gold"><?php echo htmlspecialchars($pg['code']); ?></span>
                                <span class="badge bg-warning bg-opacity-25 text-dark fw-bold"><?php echo htmlspecialchars($pg['duration']); ?> &bull; <?php echo $pg['semester_count']; ?> Semesters</span>
                            </div>
                            <h4 class="fw-bold text-navy mb-2"><?php echo htmlspecialchars($pg['name']); ?></h4>
                            <p class="text-secondary small mb-3" style="line-height: 1.7;">
                                <?php echo htmlspecialchars($pg['description']); ?>
                            </p>
                            <div class="p-3 bg-light rounded-3 mb-3 small">
                                <div class="mb-1"><strong><i class="fa-solid fa-check-circle text-gold me-1"></i> Eligibility:</strong> <?php echo htmlspecialchars($pg['eligibility']); ?></div>
                                <div><strong><i class="fa-solid fa-wallet text-gold me-1"></i> Annual Tuition:</strong> ₹<?php echo number_format($pg['fees_per_year']); ?> / Year</div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="admissions.php?course_id=<?php echo $pg['course_id']; ?>#applyNow" class="btn btn-gold btn-sm flex-grow-1">
                                    <i class="fa-solid fa-file-pen me-1"></i> Apply Now
                                </a>
                                <a href="contact.php" class="btn btn-outline-secondary btn-sm">
                                    <i class="fa-solid fa-info-circle me-1"></i> Syllabus Enquiry
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 4. Academic Pedagogy Highlights -->
<section class="py-5 bg-navy text-white">
    <div class="container py-4 text-center" data-aos="fade-up">
        <span class="badge bg-gold text-dark fw-bold px-3 py-2 text-uppercase mb-3">Distinguished Pedagogy</span>
        <h2 class="display-5 fw-bold font-serif mb-4">Why Academics at Apex Imperial Stand Apart</h2>
        <div class="row g-4 mt-2 text-start">
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-navy-light border border-secondary border-opacity-25 h-100">
                    <i class="fa-solid fa-book-bookmark text-gold fs-2 mb-3"></i>
                    <h5 class="fw-bold text-white">Continuous Choice-Based Credit System (CBCS)</h5>
                    <p class="text-light-muted small mb-0">Flexibility to pick open electives from artificial intelligence, economics, international relations, and fine arts.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-navy-light border border-secondary border-opacity-25 h-100">
                    <i class="fa-solid fa-chalkboard-user text-gold fs-2 mb-3"></i>
                    <h5 class="fw-bold text-white">1:15 Faculty-to-Student Mentorship Ratio</h5>
                    <p class="text-light-muted small mb-0">Every student is assigned a dedicated faculty advisor for individual academic guidance and research coaching.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-navy-light border border-secondary border-opacity-25 h-100">
                    <i class="fa-solid fa-flask-vial text-gold fs-2 mb-3"></i>
                    <h5 class="fw-bold text-white">Capstone Industrial Dissertations</h5>
                    <p class="text-light-muted small mb-0">Mandatory 6-month industry semester or patent-oriented research thesis before graduation.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function filterCourseCards(query) {
    const q = query.toLowerCase().trim();
    const cards = document.querySelectorAll('#undergraduate .col-lg-6, #postgraduate .col-lg-6');
    cards.forEach(col => {
        const text = col.textContent.toLowerCase();
        if (q === '' || text.includes(q)) {
            col.style.display = 'block';
        } else {
            col.style.display = 'none';
        }
    });
}
</script>

<?php include("includes/public_footer.php"); ?>
