<?php
/**
 * Manage Attendance (Admin) - University Master Register
 * Apex Imperial University Portal
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Manage Student Attendance";
$subj_col = get_subject_col($con);

// Delete Handler
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    mysqli_query($con, "DELETE FROM attendance WHERE attendance_id='$del_id'");
    set_flash("success", "Attendance record deleted successfully.");
    header("Location: manage_attendance.php");
    exit();
}

$where_clauses = ["1=1"];
$dept_filter = isset($_GET['dept']) && is_numeric($_GET['dept']) ? (int)$_GET['dept'] : 0;
$date_filter = isset($_GET['date']) && !empty($_GET['date']) ? escape($con, $_GET['date']) : '';
$status_filter = isset($_GET['status']) ? escape($con, $_GET['status']) : '';
$search = isset($_GET['search']) ? escape($con, trim($_GET['search'])) : '';

if ($dept_filter > 0) {
    $where_clauses[] = "students.department_id = '$dept_filter'";
}
if (!empty($date_filter)) {
    $where_clauses[] = "(attendance.attendance_date = '$date_filter' OR attendance.date = '$date_filter')";
}
if (!empty($status_filter)) {
    $where_clauses[] = "attendance.status = '$status_filter'";
}
if (!empty($search)) {
    $where_clauses[] = "(users.name LIKE '%$search%' OR students.roll_no LIKE '%$search%' OR subjects.`$subj_col` LIKE '%$search%')";
}

$where_sql = implode(" AND ", $where_clauses);

$query = mysqli_query($con, "
    SELECT attendance.*, users.name AS student_name, users.email AS student_email,
           students.roll_no, students.course, students.semester, students.profile_picture,
           subjects.`$subj_col` AS subject_name, subjects.subject_code,
           d.name AS department_name, d.code AS department_code
    FROM attendance
    LEFT JOIN students ON attendance.student_id = students.student_id
    LEFT JOIN users ON students.user_id = users.id
    LEFT JOIN subjects ON attendance.subject_id = subjects.subject_id
    LEFT JOIN departments d ON students.department_id = d.department_id
    WHERE $where_sql
    ORDER BY attendance.attendance_date DESC, attendance.attendance_id DESC
");

// Fetch Departments
$depts_list = mysqli_query($con, "SELECT department_id, name, code FROM departments ORDER BY department_id ASC");

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header" style="flex-wrap:wrap; gap:12px;">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-calendar-check text-primary"></i> University Student Attendance Register</h3>
            <span class="badge badge-primary"><?php echo safe_num_rows($query); ?> Logs Filtered</span>
        </div>
        <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
            <form method="GET" class="search-toolbar" style="display:flex; gap:8px; flex-wrap:wrap;">
                <select name="dept" class="form-control" style="width:160px; font-size:13px;" onchange="this.form.submit()">
                    <option value="">All Departments</option>
                    <?php 
                    mysqli_data_seek($depts_list, 0);
                    while ($dl = safe_fetch_assoc($depts_list)): 
                    ?>
                        <option value="<?php echo $dl['department_id']; ?>" <?php if ($dept_filter == $dl['department_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($dl['code']); ?> - <?php echo htmlspecialchars($dl['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <input type="date" name="date" class="form-control" style="width:145px; font-size:13px;" value="<?php echo htmlspecialchars($date_filter); ?>" onchange="this.form.submit()">

                <select name="status" class="form-control" style="width:120px; font-size:13px;" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="Present" <?php if ($status_filter === 'Present') echo 'selected'; ?>>Present</option>
                    <option value="Absent" <?php if ($status_filter === 'Absent') echo 'selected'; ?>>Absent</option>
                    <option value="Late" <?php if ($status_filter === 'Late') echo 'selected'; ?>>Late</option>
                    <option value="Excused" <?php if ($status_filter === 'Excused') echo 'selected'; ?>>OD/Leave</option>
                </select>

                <div class="search-input-group" style="min-width:180px;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" class="form-control" placeholder="Search student..." value="<?php echo htmlspecialchars($search); ?>">
                </div>

                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-filter"></i></button>

                <?php if ($dept_filter > 0 || !empty($date_filter) || !empty($status_filter) || !empty($search)): ?>
                    <a href="manage_attendance.php" class="btn btn-outline" title="Clear Filters"><i class="fa-solid fa-rotate-left"></i></a>
                <?php endif; ?>
            </form>
            <button type="button" class="btn btn-outline" onclick="window.print()">
                <i class="fa-solid fa-print"></i> Print
            </button>
        </div>
    </div>

    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Student Information</th>
                        <th>Roll Number</th>
                        <th>Department & Program</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($query && safe_num_rows($query) > 0): ?>
                        <?php while ($row = safe_fetch_assoc($query)): 
                            $pic = !empty($row['profile_picture']) && file_exists("../uploads/profile_pictures/" . $row['profile_picture'])
                                ? "../uploads/profile_pictures/" . $row['profile_picture']
                                : "../assets/images/default-user.png";
                            $status_val = $row['status'];
                        ?>
                            <tr>
                                <td>
                                    <strong><?php echo date('d M Y', strtotime($row['attendance_date'] ?: $row['date'])); ?></strong>
                                </td>
                                <td>
                                    <div class="table-user-cell">
                                        <img src="<?php echo $pic; ?>" alt="Avatar" class="table-avatar">
                                        <div>
                                            <div class="user-name"><?php echo htmlspecialchars($row['student_name']); ?></div>
                                            <div class="user-subtext"><?php echo htmlspecialchars($row['student_email'] ?? ''); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-dark" style="font-family:monospace; font-weight:700;">
                                        <?php echo htmlspecialchars($row['roll_no']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-primary" style="font-size:11px;"><?php echo htmlspecialchars($row['department_code'] ?? 'CSE'); ?></span>
                                    <strong><?php echo htmlspecialchars($row['course']); ?></strong>
                                    <span style="font-size:11px; color:var(--text-muted);">Sem <?php echo $row['semester']; ?></span>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['subject_name']); ?></strong>
                                </td>
                                <td>
                                    <?php if ($status_val === 'Present'): ?>
                                        <span class="badge badge-success"><i class="fa-solid fa-check"></i> Present</span>
                                    <?php elseif ($status_val === 'Absent'): ?>
                                        <span class="badge badge-danger"><i class="fa-solid fa-xmark"></i> Absent</span>
                                    <?php elseif ($status_val === 'Late'): ?>
                                        <span class="badge badge-warning" style="background:#fef3c7; color:#b45309;"><i class="fa-solid fa-clock"></i> Late</span>
                                    <?php else: ?>
                                        <span class="badge badge-info"><i class="fa-solid fa-shield"></i> <?php echo htmlspecialchars($status_val); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span style="font-size:12px; color:var(--text-muted);">
                                        <?php echo !empty($row['remarks']) ? htmlspecialchars($row['remarks']) : '—'; ?>
                                    </span>
                                </td>
                                <td style="text-align:right;">
                                    <a href="manage_attendance.php?delete=<?php echo $row['attendance_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this attendance entry?')" title="Delete Log">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="empty-state">
                                <i class="fa-solid fa-calendar-xmark"></i>
                                <h4>No attendance records match your criteria</h4>
                                <p>Try clearing filters or search criteria.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
