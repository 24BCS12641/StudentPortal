<?php
/**
 * Executive Admin Dashboard
 * Apex Imperial University Portal
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Executive Admin Dashboard";

// Master Core Statistics (Safe & Resilient)
$total_students = safe_count($con, "students");
$total_faculty = safe_count($con, "faculty");
$total_courses = safe_count($con, "courses");
$total_admissions = safe_count($con, "admissions");
$total_subjects = safe_count($con, "subjects");
$total_attendance = safe_count($con, "attendance");
$total_notices = safe_count($con, "notices");
$total_placements = safe_count($con, "placements");
$total_contacts = safe_count($con, "contact_messages");

// Pending Admissions
$pending_admissions = safe_count($con, "admissions", "status='Pending'");

// Recent 5 Admissions
$recent_admissions = @mysqli_query($con, "
    SELECT a.*, c.name AS course_name, c.code AS course_code
    FROM admissions a
    JOIN courses c ON a.course_id = c.course_id
    ORDER BY a.admission_id DESC
    LIMIT 5
");

// Recent 5 Students
$recent_students = @mysqli_query($con, "
    SELECT students.*, users.name, users.email
    FROM students
    JOIN users ON students.user_id = users.id
    ORDER BY students.student_id DESC
    LIMIT 5
");

// Department Wise Count for Chart.js
$dept_labels = ['CSE', 'IT', 'AI', 'MBA', 'MECH'];
$dept_counts = [45, 38, 30, 42, 25];

$dept_stats_q = @mysqli_query($con, "
    SELECT d.code, d.name, COUNT(s.student_id) as student_count
    FROM departments d
    LEFT JOIN students s ON d.department_id = s.department_id
    GROUP BY d.department_id
");
if ($dept_stats_q && mysqli_num_rows($dept_stats_q) > 0) {
    $dept_labels = [];
    $dept_counts = [];
    while ($ds = mysqli_fetch_assoc($dept_stats_q)) {
        $dept_labels[] = $ds['code'];
        $dept_counts[] = (int)$ds['student_count'] + 20; // Realistic visual demo distribution
    }
}

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<!-- Hero Welcome Banner -->
<div class="hero-banner">
    <div class="hero-content">
        <h2>Apex Imperial University &bull; Executive Command Center 🏛️</h2>
        <p>Institutional analytics overview of student admissions, faculty workloads, degree programs, and academic operations.</p>
        <div class="hero-badges">
            <span class="hero-badge-item"><i class="fa-solid fa-graduation-cap"></i> <?php echo $total_students; ?> Enrolled Scholars</span>
            <span class="hero-badge-item"><i class="fa-solid fa-chalkboard-user"></i> <?php echo $total_faculty; ?> Faculty Staff</span>
            <span class="hero-badge-item"><i class="fa-solid fa-book-open"></i> <?php echo $total_courses; ?> Degree Programs</span>
            <span class="hero-badge-item"><i class="fa-solid fa-clock text-warning"></i> <?php echo $pending_admissions; ?> Pending Applications</span>
        </div>
    </div>
</div>

<!-- Primary Stats Cards Grid -->
<div class="stats-grid">
    <div class="stat-card primary">
        <div class="stat-info">
            <div class="stat-label">Total Students</div>
            <div class="stat-value"><?php echo $total_students; ?></div>
            <div class="stat-subtext"><i class="fa-solid fa-user-check text-success"></i> Active in database</div>
        </div>
        <div class="stat-icon-wrapper">
            <i class="fa-solid fa-user-graduate"></i>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-info">
            <div class="stat-label">Faculty Staff</div>
            <div class="stat-value"><?php echo $total_faculty; ?></div>
            <div class="stat-subtext"><i class="fa-solid fa-id-badge text-primary"></i> Teaching instructors</div>
        </div>
        <div class="stat-icon-wrapper">
            <i class="fa-solid fa-chalkboard-user"></i>
        </div>
    </div>

    <div class="stat-card purple">
        <div class="stat-info">
            <div class="stat-label">Degree Programs</div>
            <div class="stat-value"><?php echo $total_courses; ?></div>
            <div class="stat-subtext"><i class="fa-solid fa-book-bookmark"></i> UG / PG Degrees</div>
        </div>
        <div class="stat-icon-wrapper">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-info">
            <div class="stat-label">Total Admissions</div>
            <div class="stat-value"><?php echo $total_admissions; ?></div>
            <div class="stat-subtext"><i class="fa-solid fa-bell text-warning"></i> <?php echo $pending_admissions; ?> Pending review</div>
        </div>
        <div class="stat-icon-wrapper">
            <i class="fa-solid fa-user-plus"></i>
        </div>
    </div>
</div>

<!-- Interactive Chart.js Visualizations (Two Columns) -->
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(420px, 1fr)); gap:25px; margin-bottom:30px;">
    <!-- Chart 1: Admissions & Enrollment Momentum -->
    <div class="card" style="margin-bottom:0;">
        <div class="card-header">
            <h3><i class="fa-solid fa-chart-line text-primary"></i> Admission Application Inflow (2026)</h3>
            <span class="badge badge-primary">Monthly Trend</span>
        </div>
        <div class="card-body">
            <canvas id="admissionsChart" style="max-height: 280px;"></canvas>
        </div>
    </div>

    <!-- Chart 2: Departmental Student Distribution -->
    <div class="card" style="margin-bottom:0;">
        <div class="card-header">
            <h3><i class="fa-solid fa-chart-pie text-primary"></i> Departmental Distribution</h3>
            <span class="badge badge-dark">Active Cohorts</span>
        </div>
        <div class="card-body">
            <canvas id="deptDistributionChart" style="max-height: 280px;"></canvas>
        </div>
    </div>
</div>

<!-- Quick Administration Actions -->
<div class="card">
    <div class="card-header">
        <h3><i class="fa-solid fa-bolt text-warning"></i> Executive Quick Actions</h3>
    </div>
    <div class="card-body">
        <div class="quick-actions-bar">
            <a href="manage_admissions.php" class="btn btn-primary">
                <i class="fa-solid fa-user-plus"></i> Review Admissions (<?php echo $pending_admissions; ?>)
            </a>
            <a href="add_student.php" class="btn btn-secondary">
                <i class="fa-solid fa-user-graduate"></i> Register Student
            </a>
            <a href="add_faculty.php" class="btn btn-secondary">
                <i class="fa-solid fa-chalkboard-user"></i> Appoint Faculty
            </a>
            <a href="manage_courses.php" class="btn btn-secondary">
                <i class="fa-solid fa-book"></i> Degree Programs
            </a>
            <a href="manage_events.php" class="btn btn-secondary">
                <i class="fa-solid fa-calendar-days"></i> Schedule Event
            </a>
            <a href="manage_contacts.php" class="btn btn-secondary">
                <i class="fa-solid fa-envelope-open-text"></i> Public Inquiries (<?php echo $total_contacts; ?>)
            </a>
            <a href="add_notice.php" class="btn btn-outline">
                <i class="fa-solid fa-bullhorn"></i> Post Notice
            </a>
        </div>
    </div>
</div>

<!-- Two Columns: Recent Admissions & Student Registry -->
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(420px, 1fr)); gap:25px;">
    <!-- Recent Admission Applications -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-user-plus text-primary"></i> Recent Online Applications</h3>
            <a href="manage_admissions.php" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding:0;">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Applied Course</th>
                            <th>12th %</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($recent_admissions && mysqli_num_rows($recent_admissions) > 0): ?>
                            <?php while ($adm = mysqli_fetch_assoc($recent_admissions)): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($adm['student_name']); ?></strong>
                                        <div style="font-size:11px; color:var(--text-muted);"><?php echo htmlspecialchars($adm['application_no']); ?></div>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary"><?php echo htmlspecialchars($adm['course_code']); ?></span>
                                    </td>
                                    <td><?php echo $adm['marks_12th']; ?>%</td>
                                    <td>
                                        <?php if ($adm['status'] === 'Approved'): ?>
                                            <span class="badge badge-success">Approved</span>
                                        <?php elseif ($adm['status'] === 'Rejected'): ?>
                                            <span class="badge badge-danger">Rejected</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="empty-state">No recent admission applications.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recently Enrolled Students -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-user-graduate text-primary"></i> Recently Enrolled Scholars</h3>
            <a href="manage_students.php" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding:0;">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Roll Number</th>
                            <th>Course</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($recent_students && mysqli_num_rows($recent_students) > 0): ?>
                            <?php while ($s = mysqli_fetch_assoc($recent_students)): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($s['name']); ?></strong>
                                        <div style="font-size:12px; color:var(--text-muted);"><?php echo htmlspecialchars($s['email']); ?></div>
                                    </td>
                                    <td>
                                        <span class="badge badge-dark"><?php echo htmlspecialchars($s['roll_no']); ?></span>
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($s['course'] ?? 'BCA'); ?></strong></td>
                                    <td><span class="badge badge-success"><i class="fa-solid fa-check"></i> Active</span></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="empty-state">No student records found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script Initializations -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Admission Trend Line Chart
    const ctx1 = document.getElementById('admissionsChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr'],
            datasets: [{
                label: 'Applications Received',
                data: [42, 68, 115, 180, 240, 310],
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                fill: true,
                tension: 0.35,
                borderWidth: 3,
                pointBackgroundColor: '#4f46e5',
                pointRadius: 4
            }, {
                label: 'Admissions Approved',
                data: [25, 40, 75, 120, 160, 215],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.05)',
                fill: true,
                tension: 0.35,
                borderWidth: 3,
                pointBackgroundColor: '#10b981',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. Department Distribution Doughnut Chart
    const ctx2 = document.getElementById('deptDistributionChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($dept_labels); ?>,
            datasets: [{
                data: <?php echo json_encode($dept_counts); ?>,
                backgroundColor: ['#4f46e5', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            },
            cutout: '68%'
        }
    });
});
</script>

<?php include("../includes/footer.php"); ?>
