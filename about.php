<?php
/**
 * Modern University Portal - About Us
 * Apex Imperial University
 */

include("config/db.php");

$page_title = "About Us | History, Vision & Leadership - Apex Imperial University";

// Fetch faculty leadership
$faculty_q = mysqli_query($con, "
    SELECT f.*, u.name, u.email, u.profile_picture, d.name AS dept_name
    FROM faculty f
    JOIN users u ON f.user_id = u.id
    LEFT JOIN departments d ON f.department_id = d.department_id
    ORDER BY f.faculty_id ASC
");

include("includes/public_header.php");
include("includes/public_navbar.php");
?>

<!-- Breadcrumb / Header Strip -->
<div class="bg-navy py-5 text-white position-relative" style="border-bottom: 2px solid var(--gold);">
    <div class="container py-3">
        <span class="badge bg-gold text-dark text-uppercase fw-bold px-3 py-2 mb-2">Heritage & Academic Standing</span>
        <h1 class="display-4 fw-bold font-serif mb-2">About Apex Imperial University</h1>
        <p class="text-light-muted fs-6 mb-0">Three and a half decades of scholarly pursuit, disruptive innovation, and societal transformation.</p>
    </div>
</div>

<!-- 1. History & Legacy Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="section-tag">Historical Legacy</span>
                <h2 class="section-title">Founded in 1988 on the Tenets of Truth, Integrity & Innovation</h2>
                <p class="text-secondary mb-3" style="line-height: 1.8;">
                    Founded under the State University Act of 1988, <strong>Apex Imperial University</strong> began with a modest cohort of 120 engineering scholars and 12 faculty fellows. Today, the university has evolved into a premier national hub spanning <?php echo safe_count($con, 'departments'); ?> specialized academic schools and <?php echo safe_count($con, 'courses'); ?>+ degree programs.
                </p>
                <p class="text-secondary mb-4" style="line-height: 1.8;">
                    Recognized by the University Grants Commission (UGC) and approved by AICTE, our institution holds the prestigious <strong>NAAC A++ accreditation</strong> with a cumulative grade point average of 3.82.
                </p>
                <div class="d-flex gap-4">
                    <div class="border-start border-4 border-warning ps-3">
                        <h4 class="fw-bold text-navy mb-0">38+ Years</h4>
                        <small class="text-muted">Of Academic Provenance</small>
                    </div>
                    <div class="border-start border-4 border-warning ps-3">
                        <h4 class="fw-bold text-navy mb-0">75,000+</h4>
                        <small class="text-muted">Global Alumni Network</small>
                    </div>
                    <div class="border-start border-4 border-warning ps-3">
                        <h4 class="fw-bold text-navy mb-0">120 Acres</h4>
                        <small class="text-muted">Smart Green Campus</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=1000&q=80" alt="University Clock Tower" class="img-fluid rounded-4 shadow-lg w-100">
            </div>
        </div>
    </div>
</section>

<!-- 2. Vision, Mission & Core Values -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Institutional Purpose</span>
            <h2 class="section-title">Our Vision, Mission & Educational Creed</h2>
        </div>

        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up">
                <div class="univ-card p-4">
                    <div class="dept-icon-box">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                    <h4 class="fw-bold text-navy mb-3">Our Vision</h4>
                    <p class="text-secondary" style="line-height: 1.7;">
                        To be a globally recognized center of higher learning and disruptive technological research, cultivating enlightened professionals who champion ethical practices, sustainable enterprise, and societal advancement.
                    </p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="univ-card p-4">
                    <div class="dept-icon-box">
                        <i class="fa-solid fa-bullseye"></i>
                    </div>
                    <h4 class="fw-bold text-navy mb-3">Our Mission</h4>
                    <p class="text-secondary" style="line-height: 1.7;">
                        Deliver experiential, multi-disciplinary pedagogy powered by world-class infrastructure; nurture independent research solving real-world challenges; and forge unbreakable industry-academia partnerships.
                    </p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="univ-card p-4">
                    <div class="dept-icon-box">
                        <i class="fa-solid fa-gem"></i>
                    </div>
                    <h4 class="fw-bold text-navy mb-3">Core Values</h4>
                    <ul class="list-unstyled text-secondary" style="line-height: 1.9;">
                        <li><i class="fa-solid fa-check text-gold me-2"></i> Academic Integrity & Transparency</li>
                        <li><i class="fa-solid fa-check text-gold me-2"></i> Inclusivity & Diversity in Thought</li>
                        <li><i class="fa-solid fa-check text-gold me-2"></i> Relentless Pursuit of Innovation</li>
                        <li><i class="fa-solid fa-check text-gold me-2"></i> Environmental & Social Responsibility</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Principal / Vice-Chancellor Message -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-5" data-aos="fade-right">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=800&q=80" alt="Principal" class="img-fluid rounded-4 shadow-lg w-100" style="max-height: 480px; object-fit: cover;">
                    <div class="p-3 bg-navy text-white rounded-3 shadow mt-3 border-start border-4 border-warning">
                        <h6 class="fw-bold mb-0 text-white">Dr. Vikramaditya Sen, Ph.D. (Oxon)</h6>
                        <small class="text-gold">Vice-Chancellor & Former Chairman, National Research Council</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-7" data-aos="fade-left">
                <span class="section-tag">Leadership Perspective</span>
                <h2 class="section-title">Message from the Vice-Chancellor</h2>
                <div class="text-secondary fst-italic fs-5 mb-4 border-start border-3 border-warning ps-3" style="line-height: 1.8;">
                    "Education is not merely the accumulation of facts; it is the ignition of analytical curiosity, the cultivation of resilient character, and the courage to engineer a better tomorrow."
                </div>
                <p class="text-secondary" style="line-height: 1.8;">
                    Welcome to Apex Imperial University. Here, classrooms are dynamic think-tanks where professors and students collaborate on equal footing. Whether developing quantum machine learning models, formulating fiscal strategies for emerging markets, or crafting sustainable architectural marvels, our students are empowered to transcend traditional boundaries.
                </p>
                <p class="text-secondary mb-4" style="line-height: 1.8;">
                    I invite you to explore our campus, engage with our distinguished faculty, and become a part of an intellectual fraternity that refuses to settle for anything less than extraordinary.
                </p>
                <div class="d-flex align-items-center gap-3">
                    <img src="assets/images/default-user.png" alt="Signature" style="height: 45px; opacity: 0.7;">
                    <div>
                        <h6 class="fw-bold text-navy mb-0">Office of the Vice-Chancellor</h6>
                        <small class="text-muted">Apex Imperial University, New Delhi</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. Distinguished Faculty Directory Preview -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Academic Mentors</span>
            <h2 class="section-title">Eminent Faculty & Research Directors</h2>
            <p class="section-subtitle">Learn under thought leaders holding doctoral distinctions from leading global universities.</p>
        </div>

        <div class="row g-4">
            <?php if ($faculty_q && safe_num_rows($faculty_q) > 0): ?>
                <?php while ($f = safe_fetch_assoc($faculty_q)): ?>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up">
                        <div class="univ-card p-4 text-center">
                            <div class="mx-auto mb-3" style="width: 90px; height: 90px; border-radius: 50%; overflow: hidden; border: 3px solid var(--gold);">
                                <img src="assets/images/default-user.png" alt="<?php echo htmlspecialchars($f['name']); ?>" class="w-100 h-100 object-fit-cover">
                            </div>
                            <h5 class="fw-bold text-navy mb-1"><?php echo htmlspecialchars($f['name']); ?></h5>
                            <span class="badge bg-gold text-dark mb-2"><?php echo htmlspecialchars($f['designation']); ?></span>
                            <p class="text-muted small mb-2"><?php echo htmlspecialchars($f['qualification']); ?></p>
                            <p class="text-secondary small mb-3"><strong>Specialization:</strong> <?php echo htmlspecialchars($f['specialization']); ?></p>
                            <div class="pt-2 border-top small text-muted">
                                <i class="fa-solid fa-envelope text-gold me-1"></i> <?php echo htmlspecialchars($f['email']); ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 5. Infrastructure & Facilities Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Campus Living</span>
            <h2 class="section-title">Infrastructure Designed for Distinction</h2>
        </div>

        <div class="row g-4">
            <div class="col-md-3 col-6 text-center" data-aos="zoom-in">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <i class="fa-solid fa-wifi fs-1 text-gold mb-3"></i>
                    <h5 class="fw-bold text-navy">10 Gbps Fiber</h5>
                    <p class="text-muted small mb-0">High-speed seamless Wi-Fi connectivity throughout campus.</p>
                </div>
            </div>
            <div class="col-md-3 col-6 text-center" data-aos="zoom-in" data-aos-delay="100">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <i class="fa-solid fa-bed fs-1 text-gold mb-3"></i>
                    <h5 class="fw-bold text-navy">Modern Hostels</h5>
                    <p class="text-muted small mb-0">Separate secure AC/Non-AC residential hostels with dining halls.</p>
                </div>
            </div>
            <div class="col-md-3 col-6 text-center" data-aos="zoom-in" data-aos-delay="200">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <i class="fa-solid fa-bus fs-1 text-gold mb-3"></i>
                    <h5 class="fw-bold text-navy">Campus Fleet</h5>
                    <p class="text-muted small mb-0">Air-conditioned luxury buses plying across NCR and city routes.</p>
                </div>
            </div>
            <div class="col-md-3 col-6 text-center" data-aos="zoom-in" data-aos-delay="300">
                <div class="p-4 rounded-4 bg-light border h-100">
                    <i class="fa-solid fa-heart-pulse fs-1 text-gold mb-3"></i>
                    <h5 class="fw-bold text-navy">Health Center</h5>
                    <p class="text-muted small mb-0">24/7 medical infirmary with resident doctors and emergency ambulance.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include("includes/public_footer.php"); ?>
