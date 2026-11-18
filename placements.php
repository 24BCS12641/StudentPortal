<?php
/**
 * Modern University Portal - Placements & Corporate Relations
 * Apex Imperial University
 */

include("config/db.php");

$page_title = "Training & Placements | Career Development Cell - Apex Imperial University";

// Fetch placed students
$placements_q = mysqli_query($con, "SELECT * FROM placements ORDER BY package_lpa DESC");

include("includes/public_header.php");
include("includes/public_navbar.php");
?>

<!-- Breadcrumb Strip -->
<div class="bg-navy py-5 text-white position-relative" style="border-bottom: 2px solid var(--gold);">
    <div class="container py-3">
        <span class="badge bg-gold text-dark text-uppercase fw-bold px-3 py-2 mb-2">Corporate Relations & Career Cell</span>
        <h1 class="display-4 fw-bold font-serif mb-2">Training, Internships & Placements</h1>
        <p class="text-light-muted fs-6 mb-0">Unlocking high-impact career pathways with Fortune 500 multinationals, hyper-growth unicorns, and premier research labs.</p>
    </div>
</div>

<!-- 1. Placement Key Statistics Highlights -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row g-4 text-center">
            <div class="col-lg-3 col-sm-6" data-aos="fade-up">
                <div class="p-4 rounded-4 bg-light border border-2 border-warning border-opacity-25 h-100">
                    <h2 class="display-5 fw-bold text-navy mb-0">₹44.5 LPA</h2>
                    <span class="badge bg-gold text-dark my-2">Highest International CTC</span>
                    <p class="text-secondary small mb-0">Secured at Microsoft Redmond HQ by Computer Science Engineering scholars.</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                <div class="p-4 rounded-4 bg-light border border-2 border-warning border-opacity-25 h-100">
                    <h2 class="display-5 fw-bold text-navy mb-0">₹12.8 LPA</h2>
                    <span class="badge bg-gold text-dark my-2">Average Batch CTC</span>
                    <p class="text-secondary small mb-0">Across all undergraduate engineering and business administration cohorts.</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                <div class="p-4 rounded-4 bg-light border border-2 border-warning border-opacity-25 h-100">
                    <h2 class="display-5 fw-bold text-navy mb-0">350+</h2>
                    <span class="badge bg-gold text-dark my-2">Corporate Recruiters</span>
                    <p class="text-secondary small mb-0">Visiting campus annually for recruitment, hackathons, and corporate internships.</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6" data-aos="fade-up" data-aos-delay="300">
                <div class="p-4 rounded-4 bg-light border border-2 border-warning border-opacity-25 h-100">
                    <h2 class="display-5 fw-bold text-navy mb-0">98.4%</h2>
                    <span class="badge bg-gold text-dark my-2">Overall Placement Rate</span>
                    <p class="text-secondary small mb-0">Consistently maintained over the last five graduating batches.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Interactive Placement Analytics & Trend Charts -->
<section class="py-5 bg-white border-top border-bottom">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Empirical Excellence</span>
            <h2 class="section-title">Campus Placement Analytics & Trends</h2>
            <p class="section-subtitle">Transparent tracking of our 5-year compensation growth and department-wise hiring conversion rates.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="p-4 rounded-4 bg-light border h-100 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-navy mb-0"><i class="fa-solid fa-chart-line text-gold me-2"></i> Average Package Growth (2022–2026)</h5>
                        <span class="badge bg-gold text-dark font-monospace">+70.6% Growth</span>
                    </div>
                    <p class="text-muted small mb-4">Steady rise in average compensation secured across domestic and international placements.</p>
                    <div style="height: 280px; position: relative;">
                        <canvas id="salaryTrendChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-left">
                <div class="p-4 rounded-4 bg-light border h-100 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-navy mb-0"><i class="fa-solid fa-chart-simple text-gold me-2"></i> Department-wise Placement %</h5>
                        <span class="badge bg-navy text-white font-monospace">Batch 2025–26</span>
                    </div>
                    <p class="text-muted small mb-4">Percentage of eligible graduates successfully placed in core corporate careers.</p>
                    <div style="height: 280px; position: relative;">
                        <canvas id="deptPlacementChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Top Tier-1 Corporate Recruiters Wall -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Industry Connections</span>
            <h2 class="section-title">Our Premier Corporate Recruitment Partners</h2>
            <p class="section-subtitle">Over 350+ multinational technology giants, consulting leaders, and global banks recruit annually from our campus.</p>
        </div>

        <div class="row g-3 justify-content-center text-center" data-aos="fade-up">
            <div class="col-6 col-md-3 col-lg-2">
                <div class="p-3 rounded-3 bg-white border shadow-xs h-100 d-flex flex-column justify-content-center align-items-center">
                    <i class="fa-brands fa-google text-danger fs-2 mb-2"></i>
                    <strong class="text-navy small">Google</strong>
                    <span class="badge bg-light text-muted mt-1" style="font-size:10px;">Cloud & AI</span>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="p-3 rounded-3 bg-white border shadow-xs h-100 d-flex flex-column justify-content-center align-items-center">
                    <i class="fa-brands fa-microsoft text-primary fs-2 mb-2"></i>
                    <strong class="text-navy small">Microsoft</strong>
                    <span class="badge bg-light text-muted mt-1" style="font-size:10px;">Enterprise Tech</span>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="p-3 rounded-3 bg-white border shadow-xs h-100 d-flex flex-column justify-content-center align-items-center">
                    <i class="fa-brands fa-amazon text-warning fs-2 mb-2"></i>
                    <strong class="text-navy small">Amazon</strong>
                    <span class="badge bg-light text-muted mt-1" style="font-size:10px;">AWS & Retail</span>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="p-3 rounded-3 bg-white border shadow-xs h-100 d-flex flex-column justify-content-center align-items-center">
                    <i class="fa-solid fa-briefcase text-success fs-2 mb-2"></i>
                    <strong class="text-navy small">Deloitte</strong>
                    <span class="badge bg-light text-muted mt-1" style="font-size:10px;">Consulting</span>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="p-3 rounded-3 bg-white border shadow-xs h-100 d-flex flex-column justify-content-center align-items-center">
                    <i class="fa-brands fa-apple text-dark fs-2 mb-2"></i>
                    <strong class="text-navy small">Apple</strong>
                    <span class="badge bg-light text-muted mt-1" style="font-size:10px;">Hardware & OS</span>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="p-3 rounded-3 bg-white border shadow-xs h-100 d-flex flex-column justify-content-center align-items-center">
                    <i class="fa-solid fa-building-columns text-info fs-2 mb-2"></i>
                    <strong class="text-navy small">Goldman Sachs</strong>
                    <span class="badge bg-light text-muted mt-1" style="font-size:10px;">Fintech</span>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="p-3 rounded-3 bg-white border shadow-xs h-100 d-flex flex-column justify-content-center align-items-center">
                    <i class="fa-solid fa-network-wired text-primary fs-2 mb-2"></i>
                    <strong class="text-navy small">Cisco</strong>
                    <span class="badge bg-light text-muted mt-1" style="font-size:10px;">Networking</span>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="p-3 rounded-3 bg-white border shadow-xs h-100 d-flex flex-column justify-content-center align-items-center">
                    <i class="fa-solid fa-database text-danger fs-2 mb-2"></i>
                    <strong class="text-navy small">Oracle</strong>
                    <span class="badge bg-light text-muted mt-1" style="font-size:10px;">Cloud DB</span>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="p-3 rounded-3 bg-white border shadow-xs h-100 d-flex flex-column justify-content-center align-items-center">
                    <i class="fa-solid fa-gears text-secondary fs-2 mb-2"></i>
                    <strong class="text-navy small">TCS Digital</strong>
                    <span class="badge bg-light text-muted mt-1" style="font-size:10px;">R&D & Services</span>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="p-3 rounded-3 bg-white border shadow-xs h-100 d-flex flex-column justify-content-center align-items-center">
                    <i class="fa-solid fa-microchip text-primary fs-2 mb-2"></i>
                    <strong class="text-navy small">Infosys</strong>
                    <span class="badge bg-light text-muted mt-1" style="font-size:10px;">Global Delivery</span>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="p-3 rounded-3 bg-white border shadow-xs h-100 d-flex flex-column justify-content-center align-items-center">
                    <i class="fa-solid fa-laptop-code text-success fs-2 mb-2"></i>
                    <strong class="text-navy small">Adobe</strong>
                    <span class="badge bg-light text-muted mt-1" style="font-size:10px;">Creative Cloud</span>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <div class="p-3 rounded-3 bg-white border shadow-xs h-100 d-flex flex-column justify-content-center align-items-center">
                    <i class="fa-solid fa-shield-halved text-warning fs-2 mb-2"></i>
                    <strong class="text-navy small">PwC Advisory</strong>
                    <span class="badge bg-light text-muted mt-1" style="font-size:10px;">Risk & Tax</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Star Achievers & Placements Ledger -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Student Hall of Fame</span>
            <h2 class="section-title">Class of 2024–2025 Placement Achievers</h2>
            <p class="section-subtitle">Heartiest congratulations to our brilliant minds placed in global enterprise leaders.</p>
        </div>

        <div class="row g-4">
            <?php if ($placements_q && mysqli_num_rows($placements_q) > 0): ?>
                <?php while ($p = mysqli_fetch_assoc($placements_q)): ?>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up">
                        <div class="univ-card p-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div style="width:60px; height:60px; border-radius:50%; overflow:hidden; border:2px solid var(--gold); flex-shrink:0;">
                                    <img src="assets/images/default-user.png" alt="Student Photo" class="w-100 h-100 object-fit-cover">
                                </div>
                                <div>
                                    <h5 class="fw-bold text-navy mb-0"><?php echo htmlspecialchars($p['student_name']); ?></h5>
                                    <small class="text-muted"><i class="fa-solid fa-id-badge text-gold me-1"></i> <?php echo htmlspecialchars($p['roll_no']); ?></small>
                                </div>
                            </div>

                            <div class="p-3 bg-light rounded-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-muted small">Recruiting Company:</span>
                                    <strong class="text-navy"><?php echo htmlspecialchars($p['company_name']); ?></strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-muted small">Designation / Role:</span>
                                    <span class="badge bg-navy text-white"><?php echo htmlspecialchars($p['role']); ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                    <span class="text-muted small">Annual Compensation:</span>
                                    <strong class="text-gold fs-5">₹<?php echo $p['package_lpa']; ?> LPA</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 3. Prominent Recruiting Partners -->
<section class="py-5 bg-white">
    <div class="container py-4 text-center">
        <span class="section-tag" data-aos="fade-up">Industry Giants</span>
        <h2 class="section-title mb-5" data-aos="fade-up">Top Corporate Recruiting Partners</h2>

        <div class="row g-4 justify-content-center align-items-center">
            <div class="col-lg-2 col-md-3 col-6" data-aos="zoom-in">
                <div class="p-3 border rounded-3 bg-light shadow-sm">
                    <h5 class="fw-bold text-navy mb-0"><i class="fa-brands fa-microsoft text-primary me-1"></i> Microsoft</h5>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6" data-aos="zoom-in" data-aos-delay="100">
                <div class="p-3 border rounded-3 bg-light shadow-sm">
                    <h5 class="fw-bold text-navy mb-0"><i class="fa-brands fa-amazon text-warning me-1"></i> Amazon</h5>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6" data-aos="zoom-in" data-aos-delay="200">
                <div class="p-3 border rounded-3 bg-light shadow-sm">
                    <h5 class="fw-bold text-navy mb-0"><i class="fa-brands fa-google text-danger me-1"></i> Google</h5>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6" data-aos="zoom-in" data-aos-delay="300">
                <div class="p-3 border rounded-3 bg-light shadow-sm">
                    <h5 class="fw-bold text-navy mb-0"><i class="fa-solid fa-cube text-info me-1"></i> Deloitte</h5>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6" data-aos="zoom-in" data-aos-delay="400">
                <div class="p-3 border rounded-3 bg-light shadow-sm">
                    <h5 class="fw-bold text-navy mb-0"><i class="fa-solid fa-database text-danger me-1"></i> Oracle</h5>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6" data-aos="zoom-in" data-aos-delay="500">
                <div class="p-3 border rounded-3 bg-light shadow-sm">
                    <h5 class="fw-bold text-navy mb-0"><i class="fa-solid fa-laptop-code text-success me-1"></i> Infosys</h5>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. Career Development & Internship Training Program -->
<section class="py-5 bg-navy text-white">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="badge bg-gold text-dark fw-bold px-3 py-2 text-uppercase mb-3">Structured Career Grooming</span>
                <h2 class="display-5 fw-bold font-serif mb-3">From Classroom to Corporate Boardroom</h2>
                <p class="text-light-muted mb-4" style="line-height: 1.8;">
                    Our Career Development Cell (CDC) conducts continuous 360-degree training beginning from the 2nd year. Students undergo rigorous algorithmic problem solving, full-stack software development, corporate etiquette, and mock HR interviews with industry mentors.
                </p>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fa-solid fa-circle-check text-gold fs-4"></i>
                        <span>Mandatory 8-week paid summer internship program in Fortune 500 firms.</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <i class="fa-solid fa-circle-check text-gold fs-4"></i>
                        <span>Dedicated incubation seed funding for student entrepreneurial ventures.</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <i class="fa-solid fa-circle-check text-gold fs-4"></i>
                        <span>Over 1,200 on-campus interview drives conducted every academic season.</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="p-4 rounded-4 bg-navy-light border border-secondary border-opacity-25 text-center">
                    <i class="fa-solid fa-award text-gold display-3 mb-3"></i>
                    <h3 class="fw-bold text-white mb-2">Want to Recruit From Apex?</h3>
                    <p class="text-light-muted small mb-4">Partner with our placement office to conduct campus drives and hire exceptional engineering and management talent.</p>
                    <a href="contact.php" class="btn btn-gold btn-lg">
                        <i class="fa-solid fa-handshake me-1"></i> Connect with Placement Cell
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. 5-Year Salary Growth Trend Line Chart
    const ctxSalary = document.getElementById('salaryTrendChart');
    if (ctxSalary && typeof Chart !== 'undefined') {
        new Chart(ctxSalary, {
            type: 'line',
            data: {
                labels: ['2022', '2023', '2024', '2025', '2026 (Est.)'],
                datasets: [
                    {
                        label: 'Average Package (LPA)',
                        data: [7.5, 8.8, 10.2, 12.8, 14.2],
                        borderColor: '#d4af37',
                        backgroundColor: 'rgba(212, 175, 55, 0.15)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#0a192f',
                        pointBorderColor: '#d4af37',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    },
                    {
                        label: 'Highest Package (LPA)',
                        data: [28.0, 34.5, 38.0, 44.5, 48.0],
                        borderColor: '#0a192f',
                        backgroundColor: 'transparent',
                        borderDash: [5, 5],
                        tension: 0.3,
                        borderWidth: 2,
                        pointBackgroundColor: '#d4af37',
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { font: { family: 'Plus Jakarta Sans', weight: '600' } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return ctx.dataset.label + ': ₹' + ctx.raw + ' LPA';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (val) { return '₹' + val + ' LPA'; }
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 2. Department-wise Placement Percentage Bar Chart
    const ctxDept = document.getElementById('deptPlacementChart');
    if (ctxDept && typeof Chart !== 'undefined') {
        new Chart(ctxDept, {
            type: 'bar',
            data: {
                labels: ['CSE', 'IT', 'AI & ML', 'MBA', 'Mech/Robo', 'BCA'],
                datasets: [{
                    label: 'Placement Conversion Rate (%)',
                    data: [99.2, 98.4, 97.5, 96.8, 91.2, 95.0],
                    backgroundColor: [
                        '#0a192f',
                        '#d4af37',
                        '#1e3a8a',
                        '#10b981',
                        '#f59e0b',
                        '#8b5cf6'
                    ],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return 'Placed: ' + ctx.raw + '%';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        min: 80,
                        max: 100,
                        ticks: {
                            callback: function (val) { return val + '%'; }
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }
});
</script>

<?php include("includes/public_footer.php"); ?>
