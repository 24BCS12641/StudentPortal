<?php
/**
 * View Attendance (Student)
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('student');

$page_title = "My Attendance Record";
$user_id = $_SESSION['user_id'];
$subj_col = get_subject_col($con);

// Get student_id
$st_query = mysqli_query($con, "SELECT student_id, roll_no, course FROM students WHERE user_id='$user_id' LIMIT 1");
$student = ($st_query && mysqli_num_rows($st_query) > 0) ? mysqli_fetch_assoc($st_query) : [];
$student_id = $student['student_id'] ?? 0;

// Query attendance records
$query = mysqli_query($con, "
    SELECT a.*, sub.`$subj_col` AS subject_name, u.name AS faculty_name
    FROM attendance a
    JOIN subjects sub ON a.subject_id = sub.subject_id
    LEFT JOIN faculty f ON sub.faculty_id = f.faculty_id
    LEFT JOIN users u ON f.user_id = u.id
    WHERE a.student_id = '$student_id'
    ORDER BY a.attendance_date DESC, a.attendance_id DESC
");

// Calculate stats
$total_classes = safe_count($con, "attendance", "student_id='$student_id'");
$present_classes = safe_count($con, "attendance", "student_id='$student_id' AND status='Present'");
$absent_classes = $total_classes - $present_classes;
$pct = ($total_classes > 0) ? round(($present_classes / $total_classes) * 100, 1) : 0;

include("../includes/header.php");
include("../includes/sidebar_student.php");
include("../includes/navbar.php");
?>

<!-- Attendance KPI Cards -->
<div class="stats-grid" style="margin-bottom:25px;">
    <div class="stat-card <?php echo ($pct >= 75) ? 'success' : 'danger'; ?>">
        <div class="stat-info">
            <div class="stat-label">Attendance Percentage</div>
            <div class="stat-value"><?php echo $pct; ?>%</div>
            <div class="stat-subtext">
                <?php if ($pct >= 75): ?>
                    <i class="fa-solid fa-circle-check text-success"></i> Eligible for exams (&ge; 75%)
                <?php else: ?>
                    <i class="fa-solid fa-triangle-exclamation text-danger"></i> Below 75% requirement
                <?php endif; ?>
            </div>
        </div>
        <div class="stat-icon-wrapper" style="<?php echo ($pct >= 75) ? 'background:#d1fae5; color:#10b981;' : 'background:#fee2e2; color:#ef4444;'; ?>">
            <i class="fa-solid fa-percent"></i>
        </div>
    </div>

    <div class="stat-card primary">
        <div class="stat-info">
            <div class="stat-label">Total Conducted Lectures</div>
            <div class="stat-value"><?php echo $total_classes; ?></div>
            <div class="stat-subtext">Across all enrolled subjects</div>
        </div>
        <div class="stat-icon-wrapper" style="background:#dbeafe; color:#2563eb;">
            <i class="fa-solid fa-chalkboard"></i>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-info">
            <div class="stat-label">Lectures Attended</div>
            <div class="stat-value"><?php echo $present_classes; ?></div>
            <div class="stat-subtext">Marked Present</div>
        </div>
        <div class="stat-icon-wrapper">
            <i class="fa-solid fa-user-check"></i>
        </div>
    </div>

    <div class="stat-card danger">
        <div class="stat-info">
            <div class="stat-label">Lectures Missed</div>
            <div class="stat-value"><?php echo $absent_classes; ?></div>
            <div class="stat-subtext">Marked Absent</div>
        </div>
        <div class="stat-icon-wrapper">
            <i class="fa-solid fa-user-xmark"></i>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-clipboard-user"></i> Detailed Session Attendance Logs</h3>
            <span class="badge badge-primary"><?php echo $total_classes; ?> Logs</span>
        </div>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Lecture Date</th>
                        <th>Subject / Course</th>
                        <th>Faculty Incharge</th>
                        <th>Recorded Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($query && mysqli_num_rows($query) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td>
                                    <div style="font-weight:700; color:var(--text-primary); font-size:14px;">
                                        <i class="fa-solid fa-calendar-day" style="color:var(--primary); margin-right:6px;"></i>
                                        <?php echo date('d M Y (D)', strtotime($row['attendance_date'])); ?>
                                    </div>
                                </td>
                                <td><span class="badge badge-primary"><?php echo htmlspecialchars($row['subject_name']); ?></span></td>
                                <td><?php echo htmlspecialchars($row['faculty_name'] ?? 'Faculty'); ?></td>
                                <td>
                                    <?php if ($row['status'] === 'Present'): ?>
                                        <span class="badge badge-success" style="font-size:12px; font-weight:700;">
                                            <i class="fa-solid fa-circle-check"></i> Present
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-danger" style="font-size:12px; font-weight:700;">
                                            <i class="fa-solid fa-circle-xmark"></i> Absent
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="empty-state">
                                <i class="fa-solid fa-calendar-xmark"></i>
                                <h4>No attendance records logged yet</h4>
                                <p>Your attendance records will appear here as your teachers take attendance during lectures.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
