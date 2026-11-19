<?php
/**
 * Student Dashboard
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('student');

$page_title = "Student Dashboard";
$user_id = $_SESSION['user_id'];
$subj_col = get_subject_col($con);

// Get student details (resilient: try JOIN first, fallback to users-only)
$st_query = @mysqli_query($con, "
    SELECT s.*, u.name, u.email 
    FROM students s
    JOIN users u ON s.user_id = u.id
    WHERE s.user_id='$user_id'
    LIMIT 1
");

if (!$st_query || mysqli_num_rows($st_query) === 0) {
    // Fallback: get user info directly
    $st_query = @mysqli_query($con, "SELECT id, name, email, role FROM users WHERE id='$user_id' LIMIT 1");
}

$student = ($st_query && mysqli_num_rows($st_query) > 0) ? mysqli_fetch_assoc($st_query) : [];
$student_id = $student['student_id'] ?? $student['id'] ?? 0;

// Metrics (Safe & Resilient)
$total_subjects = safe_count($con, "subjects");
$total_assignments = safe_count($con, "assignments");
$total_materials = safe_count($con, "study_materials");

// Attendance
$total_att = safe_count($con, "attendance", "student_id='$student_id'");
$present_att = safe_count($con, "attendance", "student_id='$student_id' AND status='Present'");
$att_pct = ($total_att > 0) ? round(($present_att / $total_att) * 100) : 0;

// Submissions count
$total_submissions = safe_count($con, "submissions", "student_id='$student_id'");

// Average Grade
$grade_q = @mysqli_query($con, "SELECT marks FROM grades WHERE student_id='$student_id'");
$grade_sum = 0;
$grade_count = 0;
if ($grade_q && mysqli_num_rows($grade_q) > 0) {
    while ($g = mysqli_fetch_assoc($grade_q)) {
        $grade_sum += $g['marks'];
        $grade_count++;
    }
}
$avg_marks = ($grade_count > 0) ? round($grade_sum / $grade_count) : 0;

// Recent 4 Assignments
$recent_assignments = mysqli_query($con, "
    SELECT a.*, s.`$subj_col` AS subject_name,
           (SELECT submission_id FROM submissions sub WHERE sub.assignment_id = a.assignment_id AND sub.student_id = '$student_id' LIMIT 1) as submitted_id
    FROM assignments a
    LEFT JOIN subjects s ON a.subject_id = s.subject_id
    ORDER BY a.assignment_id DESC
    LIMIT 4
");

// Recent Notices
$recent_notices = mysqli_query($con, "SELECT * FROM notices ORDER BY notice_id DESC LIMIT 3");

include("../includes/header.php");
include("../includes/sidebar_student.php");
include("../includes/navbar.php");
?>

<!-- Hero Banner -->
<div class="hero-banner" style="background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 50%, #60a5fa 100%);">
    <div class="hero-content">
        <h2>Welcome back, <?php echo htmlspecialchars($student['name'] ?? 'Student'); ?> 🎓</h2>
        <p>Keep track of your coursework deadlines, attendance records, study materials, and academic evaluation.</p>
        <div class="hero-badges">
            <span class="hero-badge-item"><i class="fa-solid fa-id-card"></i> Roll No: <?php echo htmlspecialchars($student['roll_no'] ?? 'N/A'); ?></span>
            <span class="hero-badge-item"><i class="fa-solid fa-graduation-cap"></i> <?php echo htmlspecialchars($student['course'] ?? 'BCA'); ?> (Semester <?php echo htmlspecialchars($student['semester'] ?? 1); ?>)</span>
            <span class="hero-badge-item"><i class="fa-solid fa-trophy"></i> GPA/Avg: <?php echo $avg_marks > 0 ? $avg_marks . '%' : 'Pending'; ?></span>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card primary">
        <div class="stat-info">
            <div class="stat-label">Enrolled Subjects</div>
            <div class="stat-value"><?php echo $total_subjects; ?></div>
            <div class="stat-subtext">Curriculum modules</div>
        </div>
        <div class="stat-icon-wrapper" style="background:#dbeafe; color:#2563eb;">
            <i class="fa-solid fa-book-open"></i>
        </div>
    </div>

    <div class="stat-card purple">
        <div class="stat-info">
            <div class="stat-label">Course Assignments</div>
            <div class="stat-value"><?php echo $total_assignments; ?></div>
            <div class="stat-subtext"><?php echo $total_submissions; ?> Completed & Submitted</div>
        </div>
        <div class="stat-icon-wrapper">
            <i class="fa-solid fa-file-pen"></i>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-info">
            <div class="stat-label">Attendance Rate</div>
            <div class="stat-value"><?php echo $att_pct; ?>%</div>
            <div class="stat-subtext"><?php echo $present_att; ?> of <?php echo $total_att; ?> Lectures Attended</div>
        </div>
        <div class="stat-icon-wrapper">
            <i class="fa-solid fa-calendar-check"></i>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-info">
            <div class="stat-label">Average Score</div>
            <div class="stat-value"><?php echo ($avg_marks > 0) ? $avg_marks . '%' : 'N/A'; ?></div>
            <div class="stat-subtext"><?php echo $grade_count; ?> Graded Submissions</div>
        </div>
        <div class="stat-icon-wrapper">
            <i class="fa-solid fa-award"></i>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card">
    <div class="card-header">
        <h3><i class="fa-solid fa-bolt"></i> Student Quick Shortcuts</h3>
    </div>
    <div class="card-body">
        <div class="quick-actions-bar">
            <a href="view_assignments.php" class="btn btn-primary" style="background:#2563eb;">
                <i class="fa-solid fa-file-lines"></i> View Assignments
            </a>
            <a href="my_submissions.php" class="btn btn-secondary">
                <i class="fa-solid fa-paper-plane"></i> My Submissions
            </a>
            <a href="view_grades.php" class="btn btn-secondary">
                <i class="fa-solid fa-award"></i> View Grades
            </a>
            <a href="view_attendance.php" class="btn btn-secondary">
                <i class="fa-solid fa-clipboard-user"></i> Attendance Record
            </a>
            <a href="study_materials.php" class="btn btn-secondary">
                <i class="fa-solid fa-folder-open"></i> Study Notes
            </a>
            <a href="performance_report.php" class="btn btn-outline">
                <i class="fa-solid fa-chart-simple"></i> Performance Card
            </a>
        </div>
    </div>
</div>

<!-- Two Columns: Assignments & Notices -->
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(360px, 1fr)); gap:25px;">
    <!-- Active Course Assignments -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-file-pen"></i> Available Coursework</h3>
            <a href="view_assignments.php" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding:0;">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Assignment</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th style="text-align:right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($recent_assignments && mysqli_num_rows($recent_assignments) > 0): ?>
                            <?php while ($a = mysqli_fetch_assoc($recent_assignments)): 
                                $is_submitted = !empty($a['submitted_id']);
                                $is_overdue = (strtotime($a['deadline']) < strtotime(date('Y-m-d')));
                            ?>
                                <tr>
                                    <td>
                                        <div style="font-weight:700; color:var(--text-primary);"><?php echo htmlspecialchars($a['title']); ?></div>
                                        <div style="font-size:12px; color:var(--text-muted);"><?php echo htmlspecialchars($a['subject_name'] ?? 'General'); ?></div>
                                    </td>
                                    <td>
                                        <span style="font-size:13px; font-weight:600; color:<?php echo $is_overdue ? '#ef4444' : '#1e293b'; ?>;">
                                            <?php echo date('d M Y', strtotime($a['deadline'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($is_submitted): ?>
                                            <span class="badge badge-success"><i class="fa-solid fa-check"></i> Submitted</span>
                                        <?php elseif ($is_overdue): ?>
                                            <span class="badge badge-danger"><i class="fa-solid fa-triangle-exclamation"></i> Overdue</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning"><i class="fa-solid fa-clock"></i> Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align:right;">
                                        <?php if ($is_submitted): ?>
                                            <a href="my_submissions.php" class="btn btn-outline btn-sm">View</a>
                                        <?php else: ?>
                                            <a href="submit_assignment.php?id=<?php echo $a['assignment_id']; ?>" class="btn btn-primary btn-sm" style="background:#2563eb;">Submit</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="empty-state">No coursework currently available.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Campus Announcements -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-bullhorn"></i> Campus Announcements</h3>
            <a href="view_notices.php" class="btn btn-outline btn-sm">All Notices</a>
        </div>
        <div class="card-body">
            <div class="notice-feed">
                <?php if ($recent_notices && mysqli_num_rows($recent_notices) > 0): ?>
                    <?php while ($n = mysqli_fetch_assoc($recent_notices)): ?>
                        <div class="notice-card">
                            <div class="notice-header">
                                <div class="notice-title"><?php echo htmlspecialchars($n['title']); ?></div>
                                <span class="badge badge-primary"><i class="fa-solid fa-calendar"></i> <?php echo isset($n['posted_on']) ? date('d M', strtotime($n['posted_on'])) : 'New'; ?></span>
                            </div>
                            <div class="notice-body">
                                <?php echo nl2br(htmlspecialchars($n['description'])); ?>
                            </div>
                            <div class="notice-meta" style="margin-top:10px;">
                                <i class="fa-solid fa-user"></i> By: <?php echo htmlspecialchars($n['posted_by'] ?? 'Administration'); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state">No recent notices.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
