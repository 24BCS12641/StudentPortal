<?php
/**
 * Student Performance Report Card
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('student');

$page_title = "Student Performance Report";
$user_id = $_SESSION['user_id'];
$subj_col = get_subject_col($con);

// Fetch student profile
$st_query = mysqli_query($con, "
    SELECT u.name, u.email, s.* 
    FROM students s
    JOIN users u ON s.user_id = u.id
    WHERE s.user_id='$user_id'
    LIMIT 1
");

$student = ($st_query && mysqli_num_rows($st_query) > 0) ? mysqli_fetch_assoc($st_query) : [];
$student_id = $student['student_id'] ?? 0;

// Attendance Calculations
$total_attendance = safe_count($con, "attendance", "student_id='$student_id'");
$present_attendance = safe_count($con, "attendance", "student_id='$student_id' AND status='Present'");
$attendance_pct = ($total_attendance > 0) ? round(($present_attendance / $total_attendance) * 100, 1) : 0;

// Submissions
$total_submissions = safe_count($con, "submissions", "student_id='$student_id'");

// Grades Breakdown
$grades_q = mysqli_query($con, "
    SELECT g.*, a.title AS assignment_title, sub.`$subj_col` AS subject_name
    FROM grades g
    JOIN assignments a ON g.assignment_id = a.assignment_id
    LEFT JOIN subjects sub ON a.subject_id = sub.subject_id
    WHERE g.student_id='$student_id'
    ORDER BY g.grade_id DESC
");

$total_marks = 0;
$graded_count = 0;
$grades_list = [];
if ($grades_q) {
    while ($g = mysqli_fetch_assoc($grades_q)) {
        $grades_list[] = $g;
        $total_marks += (int)$g['marks'];
        $graded_count++;
    }
}

$average_score = ($graded_count > 0) ? round($total_marks / $graded_count, 1) : 0;

// Letter Grade & Performance Standing
if ($average_score >= 85) {
    $standing = "Outstanding Distinction ⭐";
    $standing_class = "badge-success";
    $standing_comment = "The student has demonstrated exemplary academic understanding, high analytical precision, and consistently superior performance across all coursework modules.";
} elseif ($average_score >= 70) {
    $standing = "First Class Academic Standing 👍";
    $standing_class = "badge-primary";
    $standing_comment = "The student has shown strong proficiency in course concepts with very good execution on assignments and class submissions.";
} elseif ($average_score >= 50) {
    $standing = "Satisfactory Academic Progress 📚";
    $standing_class = "badge-warning";
    $standing_comment = "The student is meeting passing criteria. Increased focus on core practical exercises is advised to achieve higher excellence.";
} else {
    $standing = "Needs Academic Attention ⚠️";
    $standing_class = "badge-danger";
    $standing_comment = "Student is encouraged to attend extra tutoring and revise fundamental coursework concepts with faculty incharge.";
}

$pic = !empty($student['profile_picture']) && file_exists("../uploads/profile_pictures/" . $student['profile_picture'])
    ? "../uploads/profile_pictures/" . $student['profile_picture']
    : "../assets/images/default-user.png";

include("../includes/header.php");
include("../includes/sidebar_student.php");
include("../includes/navbar.php");
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
    <div>
        <h2 style="font-size:24px; font-weight:800; color:var(--text-primary);">Academic Performance Card</h2>
        <p style="color:var(--text-secondary); font-size:14px;">Official institutional evaluation transcript for academic semester records.</p>
    </div>
    <button type="button" onclick="window.print()" class="btn btn-primary" style="background:#2563eb;">
        <i class="fa-solid fa-print"></i> Print Report Card
    </button>
</div>

<!-- Student Identity Card Header -->
<div class="card">
    <div class="card-body">
        <div style="display:flex; align-items:center; gap:24px; flex-wrap:wrap;">
            <img src="<?php echo $pic; ?>" alt="Student" class="table-avatar" style="width:80px; height:80px; border-radius:18px;">
            <div style="flex:1;">
                <h3 style="font-size:22px; font-weight:800; color:var(--text-primary); margin-bottom:4px;">
                    <?php echo htmlspecialchars($student['name']); ?>
                </h3>
                <div style="display:flex; gap:10px; flex-wrap:wrap; font-size:13px; color:var(--text-secondary);">
                    <span><i class="fa-solid fa-id-card"></i> Roll No: <strong><?php echo htmlspecialchars($student['roll_no']); ?></strong></span>
                    <span>&bull;</span>
                    <span><i class="fa-solid fa-graduation-cap"></i> Program: <strong><?php echo htmlspecialchars($student['course']); ?></strong></span>
                    <span>&bull;</span>
                    <span><i class="fa-solid fa-layer-group"></i> Semester: <strong><?php echo htmlspecialchars($student['semester']); ?></strong></span>
                    <span>&bull;</span>
                    <span><i class="fa-solid fa-envelope"></i> <?php echo htmlspecialchars($student['email']); ?></span>
                </div>
            </div>
            <div>
                <span class="badge <?php echo $standing_class; ?>" style="font-size:14px; padding:8px 16px;">
                    <?php echo $standing; ?>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Score Highlights Grid -->
<div class="stats-grid">
    <div class="stat-card primary">
        <div class="stat-info">
            <div class="stat-label">Cumulative Average</div>
            <div class="stat-value"><?php echo $average_score; ?>%</div>
            <div class="stat-subtext">Across evaluated work</div>
        </div>
        <div class="stat-icon-wrapper" style="background:#dbeafe; color:#2563eb;">
            <i class="fa-solid fa-award"></i>
        </div>
    </div>

    <div class="stat-card <?php echo ($attendance_pct >= 75) ? 'success' : 'danger'; ?>">
        <div class="stat-info">
            <div class="stat-label">Lecture Attendance</div>
            <div class="stat-value"><?php echo $attendance_pct; ?>%</div>
            <div class="stat-subtext"><?php echo $present_attendance; ?> of <?php echo $total_attendance; ?> lectures</div>
        </div>
        <div class="stat-icon-wrapper" style="<?php echo ($attendance_pct >= 75) ? 'background:#d1fae5; color:#10b981;' : 'background:#fee2e2; color:#ef4444;'; ?>">
            <i class="fa-solid fa-clipboard-user"></i>
        </div>
    </div>

    <div class="stat-card purple">
        <div class="stat-info">
            <div class="stat-label">Coursework Turned In</div>
            <div class="stat-value"><?php echo $total_submissions; ?></div>
            <div class="stat-subtext">Submitted assignment tasks</div>
        </div>
        <div class="stat-icon-wrapper">
            <i class="fa-solid fa-file-circle-check"></i>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-info">
            <div class="stat-label">Evaluated Items</div>
            <div class="stat-value"><?php echo $graded_count; ?></div>
            <div class="stat-subtext">Graded by instructors</div>
        </div>
        <div class="stat-icon-wrapper">
            <i class="fa-solid fa-check-double"></i>
        </div>
    </div>
</div>

<!-- Detailed Evaluation Transcript -->
<div class="card">
    <div class="card-header">
        <h3><i class="fa-solid fa-list-check"></i> Coursework Scores & Evaluation Transcript</h3>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Assignment</th>
                        <th>Subject</th>
                        <th>Marks Obtained</th>
                        <th>Letter Grade</th>
                        <th>Faculty Review Remarks</th>
                        <th>Evaluation Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($graded_count > 0): ?>
                        <?php foreach ($grades_list as $row): 
                            $m = (int)$row['marks'];
                            if ($m >= 90) { $letter = 'A+'; $badge = 'badge-success'; }
                            elseif ($m >= 80) { $letter = 'A'; $badge = 'badge-success'; }
                            elseif ($m >= 70) { $letter = 'B+'; $badge = 'badge-primary'; }
                            elseif ($m >= 60) { $letter = 'B'; $badge = 'badge-primary'; }
                            elseif ($m >= 50) { $letter = 'C'; $badge = 'badge-warning'; }
                            else { $letter = 'F'; $badge = 'badge-danger'; }
                        ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['assignment_title']); ?></strong></td>
                                <td><span class="badge badge-primary"><?php echo htmlspecialchars($row['subject_name'] ?? 'General'); ?></span></td>
                                <td><strong><?php echo $m; ?></strong> / 100</td>
                                <td><span class="badge <?php echo $badge; ?>" style="font-size:13px; font-weight:700;"><?php echo $letter; ?></span></td>
                                <td>
                                    <div style="font-size:13px; color:#475569;">
                                        "<?php echo htmlspecialchars($row['remarks'] ?? 'Good work.'); ?>"
                                    </div>
                                </td>
                                <td><?php echo isset($row['graded_at']) ? date('d M Y', strtotime($row['graded_at'])) : 'Recent'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="empty-state">No evaluations recorded yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Faculty Committee Appraisal Remark -->
<div class="card">
    <div class="card-header">
        <h3><i class="fa-solid fa-comment-dots"></i> Academic Advisory Feedback</h3>
    </div>
    <div class="card-body">
        <p style="font-size:15px; color:#334155; line-height:1.7;">
            <?php echo $standing_comment; ?>
        </p>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
