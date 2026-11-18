<?php
/**
 * Manage Attendance (Admin)
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Manage Student Attendance";
$search = "";
$subj_col = get_subject_col($con);

if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    mysqli_query($con, "DELETE FROM attendance WHERE attendance_id='$del_id'");
    set_flash("success", "Attendance record deleted successfully.");
    header("Location: manage_attendance.php");
    exit();
}

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = escape($con, $_GET['search']);
    $query = mysqli_query($con, "
        SELECT attendance.*, users.name AS student_name, students.roll_no, subjects.`$subj_col` AS subject_name
        FROM attendance
        LEFT JOIN students ON attendance.student_id = students.student_id
        LEFT JOIN users ON students.user_id = users.id
        LEFT JOIN subjects ON attendance.subject_id = subjects.subject_id
        WHERE users.name LIKE '%$search%'
           OR students.roll_no LIKE '%$search%'
           OR subjects.`$subj_col` LIKE '%$search%'
        ORDER BY attendance.attendance_date DESC, attendance.attendance_id DESC
    ");
} else {
    $query = mysqli_query($con, "
        SELECT attendance.*, users.name AS student_name, students.roll_no, subjects.`$subj_col` AS subject_name
        FROM attendance
        LEFT JOIN students ON attendance.student_id = students.student_id
        LEFT JOIN users ON students.user_id = users.id
        LEFT JOIN subjects ON attendance.subject_id = subjects.subject_id
        ORDER BY attendance.attendance_date DESC, attendance.attendance_id DESC
    ");
}

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-calendar-check"></i> Student Attendance Logs</h3>
            <span class="badge badge-primary"><?php echo mysqli_num_rows($query); ?> Records</span>
        </div>
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <form method="GET" class="search-toolbar">
                <div class="search-input-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" class="form-control" placeholder="Search student, roll no, subject..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
                <?php if (!empty($search)): ?>
                    <a href="manage_attendance.php" class="btn btn-outline" title="Clear Search"><i class="fa-solid fa-rotate-left"></i></a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name & Roll No</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($query && mysqli_num_rows($query) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><strong>#<?php echo $row['attendance_id']; ?></strong></td>
                                <td>
                                    <div style="font-weight:600; color:var(--text-primary);"><?php echo htmlspecialchars($row['student_name'] ?? 'Student'); ?></div>
                                    <div style="font-size:12px; color:var(--text-muted);"><i class="fa-solid fa-id-card"></i> <?php echo htmlspecialchars($row['roll_no'] ?? 'N/A'); ?></div>
                                </td>
                                <td><span class="badge badge-primary"><?php echo htmlspecialchars($row['subject_name'] ?? 'General'); ?></span></td>
                                <td><?php echo date('d M Y', strtotime($row['attendance_date'])); ?></td>
                                <td>
                                    <?php if ($row['status'] === 'Present'): ?>
                                        <span class="badge badge-success"><i class="fa-solid fa-check"></i> Present</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger"><i class="fa-solid fa-xmark"></i> Absent</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:right;">
                                    <a href="?delete=<?php echo $row['attendance_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this attendance record?')" title="Delete Attendance">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fa-solid fa-calendar-xmark"></i>
                                <h4>No attendance records found</h4>
                                <p><?php echo !empty($search) ? 'No records matched your search query.' : 'There are currently no attendance logs recorded.'; ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
