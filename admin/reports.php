<?php
/**
 * System Reports (Admin)
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Academic & System Reports";

$total_students = mysqli_num_rows(mysqli_query($con, "SELECT * FROM students"));
$total_faculty = mysqli_num_rows(mysqli_query($con, "SELECT * FROM faculty"));
$total_subjects = mysqli_num_rows(mysqli_query($con, "SELECT * FROM subjects"));
$total_assignments = mysqli_num_rows(mysqli_query($con, "SELECT * FROM assignments"));
$total_submissions = mysqli_num_rows(mysqli_query($con, "SELECT * FROM submissions"));
$total_attendance = mysqli_num_rows(mysqli_query($con, "SELECT * FROM attendance"));
$total_notices = mysqli_num_rows(mysqli_query($con, "SELECT * FROM notices"));

// Student breakdown by course
$course_stats = mysqli_query($con, "
    SELECT course, COUNT(*) as count 
    FROM students 
    GROUP BY course
");

// Attendance present vs absent
$present_count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM attendance WHERE status='Present'"));
$absent_count = mysqli_num_rows(mysqli_query($con, "SELECT * FROM attendance WHERE status='Absent'"));
$overall_att_pct = ($total_attendance > 0) ? round(($present_count / $total_attendance) * 100, 1) : 0;

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <div>
        <h2 style="font-size:24px; font-weight:800; color:var(--text-primary);">Institutional Analytics & Reports</h2>
        <p style="color:var(--text-secondary); font-size:14px;">Real-time metrics, enrollment distribution, and attendance performance.</p>
    </div>
    <button type="button" onclick="window.print()" class="btn btn-primary">
        <i class="fa-solid fa-print"></i> Print Official Report
    </button>
</div>

<!-- Metric Overview Cards -->
<div class="stats-grid">
    <div class="stat-card primary">
        <div class="stat-info">
            <div class="stat-label">Enrolled Students</div>
            <div class="stat-value"><?php echo $total_students; ?></div>
            <div class="stat-subtext">Active student accounts</div>
        </div>
        <div class="stat-icon-wrapper"><i class="fa-solid fa-user-graduate"></i></div>
    </div>

    <div class="stat-card success">
        <div class="stat-info">
            <div class="stat-label">Faculty Staff</div>
            <div class="stat-value"><?php echo $total_faculty; ?></div>
            <div class="stat-subtext">Instructional faculty</div>
        </div>
        <div class="stat-icon-wrapper"><i class="fa-solid fa-chalkboard-user"></i></div>
    </div>

    <div class="stat-card warning">
        <div class="stat-info">
            <div class="stat-label">Subjects</div>
            <div class="stat-value"><?php echo $total_subjects; ?></div>
            <div class="stat-subtext">Curriculum modules</div>
        </div>
        <div class="stat-icon-wrapper"><i class="fa-solid fa-book-open"></i></div>
    </div>

    <div class="stat-card purple">
        <div class="stat-info">
            <div class="stat-label">Avg Attendance</div>
            <div class="stat-value"><?php echo $overall_att_pct; ?>%</div>
            <div class="stat-subtext"><?php echo $present_count; ?> Present / <?php echo $total_attendance; ?> Total</div>
        </div>
        <div class="stat-icon-wrapper"><i class="fa-solid fa-chart-line"></i></div>
    </div>
</div>

<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(360px, 1fr)); gap:25px;">
    <!-- Course Distribution Breakdown -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-layer-group"></i> Enrollment by Course / Degree</h3>
        </div>
        <div class="card-body" style="padding:0;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Course / Program</th>
                        <th>Student Count</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($course_stats && mysqli_num_rows($course_stats) > 0): ?>
                        <?php while ($c = mysqli_fetch_assoc($course_stats)): 
                            $pct = ($total_students > 0) ? round(($c['count'] / $total_students) * 100, 1) : 0;
                        ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($c['course']); ?></strong></td>
                                <td><?php echo $c['count']; ?> Students</td>
                                <td>
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <div style="flex:1; background:#e2e8f0; height:8px; border-radius:4px; overflow:hidden;">
                                            <div style="width:<?php echo $pct; ?>%; background:var(--primary); height:100%;"></div>
                                        </div>
                                        <span style="font-size:12px; font-weight:700;"><?php echo $pct; ?>%</span>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="empty-state">No student data available.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Academic Activity Summary -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-clipboard-check"></i> Academic Operations Summary</h3>
        </div>
        <div class="card-body">
            <table class="profile-details-table">
                <tr>
                    <td>Total Course Assignments</td>
                    <td><strong><?php echo $total_assignments; ?></strong> published assignments</td>
                </tr>
                <tr>
                    <td>Student Submissions</td>
                    <td><strong><?php echo $total_submissions; ?></strong> solutions uploaded</td>
                </tr>
                <tr>
                    <td>Total Attendance Logs</td>
                    <td><strong><?php echo $total_attendance; ?></strong> session attendances recorded</td>
                </tr>
                <tr>
                    <td>Present Sessions</td>
                    <td><span class="badge badge-success"><?php echo $present_count; ?> Present</span></td>
                </tr>
                <tr>
                    <td>Absent Sessions</td>
                    <td><span class="badge badge-danger"><?php echo $absent_count; ?> Absent</span></td>
                </tr>
                <tr>
                    <td>Campus Circulars</td>
                    <td><strong><?php echo $total_notices; ?></strong> announcements posted</td>
                </tr>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
