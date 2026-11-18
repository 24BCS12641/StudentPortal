<?php
/**
 * Faculty Dashboard
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('faculty');

$page_title = "Faculty Dashboard";
$user_id = $_SESSION['user_id'];
$subj_col = get_subject_col($con);

// Get faculty record
$f_query = mysqli_query($con, "SELECT * FROM faculty WHERE user_id='$user_id' LIMIT 1");
$faculty = mysqli_fetch_assoc($f_query);
$faculty_id = $faculty['faculty_id'] ?? 0;

// Metrics (Safe & Resilient)
$total_assignments = safe_count($con, "assignments", "faculty_id='$faculty_id' OR faculty_id IS NULL");

$total_submissions_res = @mysqli_query($con, "
    SELECT s.submission_id FROM submissions s
    JOIN assignments a ON s.assignment_id = a.assignment_id
    WHERE a.faculty_id='$faculty_id' OR a.faculty_id IS NULL
");
$total_submissions = safe_num_rows($total_submissions_res);

$total_materials = safe_count($con, "study_materials", "faculty_id='$faculty_id' OR faculty_id IS NULL");

$total_att_res = @mysqli_query($con, "SELECT DISTINCT attendance_date, subject_id FROM attendance");
$total_attendance = safe_num_rows($total_att_res);

// Recent 5 Submissions needing grading or reviewed
$recent_submissions = @mysqli_query($con, "
    SELECT s.*, u.name AS student_name, st.roll_no, a.title AS assignment_title, sub.`$subj_col` AS subject_name
    FROM submissions s
    JOIN students st ON s.student_id = st.student_id
    JOIN users u ON st.user_id = u.id
    JOIN assignments a ON s.assignment_id = a.assignment_id
    LEFT JOIN subjects sub ON a.subject_id = sub.subject_id
    WHERE a.faculty_id='$faculty_id' OR a.faculty_id IS NULL
    ORDER BY s.submission_id DESC
    LIMIT 5
");

include("../includes/header.php");
include("../includes/sidebar_faculty.php");
include("../includes/navbar.php");
?>

<!-- Hero Banner -->
<div class="hero-banner" style="background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);">
    <div class="hero-content">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['name'] ?? 'Faculty'); ?> 👨‍🏫</h2>
        <p>Faculty Instructional Command Center &bull; <?php echo htmlspecialchars($faculty['department'] ?? 'Computer Science'); ?> Department.</p>
        <div class="hero-badges">
            <span class="hero-badge-item"><i class="fa-solid fa-user-tie"></i> <?php echo htmlspecialchars($faculty['designation'] ?? 'Faculty Member'); ?></span>
            <span class="hero-badge-item"><i class="fa-solid fa-file-pen"></i> <?php echo $total_assignments; ?> Assignments</span>
            <span class="hero-badge-item"><i class="fa-solid fa-inbox"></i> <?php echo $total_submissions; ?> Student Submissions</span>
        </div>
    </div>
</div>

<!-- Stat Cards -->
<div class="stats-grid">
    <div class="stat-card primary">
        <div class="stat-info">
            <div class="stat-label">Course Assignments</div>
            <div class="stat-value"><?php echo $total_assignments; ?></div>
            <div class="stat-subtext">Active assignments</div>
        </div>
        <div class="stat-icon-wrapper" style="background:#e0f2fe; color:#0284c7;">
            <i class="fa-solid fa-file-lines"></i>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-info">
            <div class="stat-label">Student Submissions</div>
            <div class="stat-value"><?php echo $total_submissions; ?></div>
            <div class="stat-subtext">Solutions turned in</div>
        </div>
        <div class="stat-icon-wrapper">
            <i class="fa-solid fa-inbox"></i>
        </div>
    </div>

    <div class="stat-card purple">
        <div class="stat-info">
            <div class="stat-label">Study Materials</div>
            <div class="stat-value"><?php echo $total_materials; ?></div>
            <div class="stat-subtext">Uploaded lecture notes</div>
        </div>
        <div class="stat-icon-wrapper">
            <i class="fa-solid fa-folder-open"></i>
        </div>
    </div>

    <div class="stat-card info">
        <div class="stat-info">
            <div class="stat-label">Attendance Logs</div>
            <div class="stat-value"><?php echo $total_attendance; ?></div>
            <div class="stat-subtext">Marked lecture sessions</div>
        </div>
        <div class="stat-icon-wrapper">
            <i class="fa-solid fa-calendar-check"></i>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card">
    <div class="card-header">
        <h3><i class="fa-solid fa-bolt"></i> Teaching & Evaluation Shortcuts</h3>
    </div>
    <div class="card-body">
        <div class="quick-actions-bar">
            <a href="create_assignment.php" class="btn btn-primary" style="background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);">
                <i class="fa-solid fa-plus-circle"></i> Create Assignment
            </a>
            <a href="mark_attendance.php" class="btn btn-secondary">
                <i class="fa-solid fa-clipboard-user"></i> Class Attendance
            </a>
            <a href="manage_results.php" class="btn btn-secondary">
                <i class="fa-solid fa-graduation-cap"></i> Upload Exam Marks
            </a>
            <a href="view_submissions.php" class="btn btn-secondary">
                <i class="fa-solid fa-inbox"></i> Review Submissions
            </a>
            <a href="upload_material.php" class="btn btn-secondary">
                <i class="fa-solid fa-cloud-arrow-up"></i> Upload Material
            </a>
            <a href="post_notice.php" class="btn btn-outline">
                <i class="fa-solid fa-bullhorn"></i> Post Notice
            </a>
        </div>
    </div>
</div>

<!-- Recent Student Submissions Table -->
<div class="card">
    <div class="card-header">
        <h3><i class="fa-solid fa-clock-rotate-left"></i> Recent Submissions Needing Review</h3>
        <a href="view_submissions.php" class="btn btn-outline btn-sm">View All Submissions</a>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name & Roll</th>
                        <th>Assignment</th>
                        <th>Submitted On</th>
                        <th>Status / Grade</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($recent_submissions && mysqli_num_rows($recent_submissions) > 0): ?>
                        <?php while ($subm = mysqli_fetch_assoc($recent_submissions)): 
                            $is_graded = isset($subm['marks']) && $subm['marks'] !== null;
                        ?>
                            <tr>
                                <td><strong>#<?php echo $subm['submission_id']; ?></strong></td>
                                <td>
                                    <div style="font-weight:600; color:var(--text-primary);"><?php echo htmlspecialchars($subm['student_name']); ?></div>
                                    <div style="font-size:12px; color:var(--text-muted);"><i class="fa-solid fa-id-card"></i> <?php echo htmlspecialchars($subm['roll_no']); ?></div>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($subm['assignment_title']); ?></strong>
                                    <div style="font-size:12px; color:var(--text-muted);"><?php echo htmlspecialchars($subm['subject_name'] ?? 'General'); ?></div>
                                </td>
                                <td><?php echo date('d M Y, h:i A', strtotime($subm['submitted_at'])); ?></td>
                                <td>
                                    <?php if ($is_graded): ?>
                                        <span class="badge badge-success"><i class="fa-solid fa-check"></i> Graded: <?php echo $subm['marks']; ?>/100</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning"><i class="fa-solid fa-hourglass-half"></i> Pending Grade</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:right;">
                                    <div class="table-actions">
                                        <?php if (!empty($subm['file_path']) && file_exists("../uploads/submissions/" . $subm['file_path'])): ?>
                                            <a href="../uploads/submissions/<?php echo urlencode($subm['file_path']); ?>" class="btn btn-outline btn-sm" download title="Download Solution">
                                                <i class="fa-solid fa-download"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="grade_assignment.php?id=<?php echo $subm['submission_id']; ?>" class="btn btn-primary btn-sm" style="background:#0284c7;">
                                            <i class="fa-solid fa-award"></i> Grade
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fa-solid fa-inbox"></i>
                                <h4>No student submissions received yet</h4>
                                <p>Once students submit their assignment solutions, they will appear here for grading.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
