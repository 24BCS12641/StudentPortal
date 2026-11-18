<?php
/**
 * Faculty Attendance Report
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('faculty');

$page_title = "Attendance Register & Reports";
$subj_col = get_subject_col($con);

$search = "";
$where_clauses = ["1=1"];

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = escape($con, $_GET['search']);
    $where_clauses[] = "(u.name LIKE '%$search%' OR st.roll_no LIKE '%$search%' OR sub.`$subj_col` LIKE '%$search%')";
}

if (isset($_GET['date']) && !empty($_GET['date'])) {
    $filter_date = escape($con, $_GET['date']);
    $where_clauses[] = "a.attendance_date = '$filter_date'";
}

$where_sql = implode(" AND ", $where_clauses);

$query = mysqli_query($con, "
    SELECT a.*, u.name AS student_name, st.roll_no, st.course, sub.`$subj_col` AS subject_name
    FROM attendance a
    JOIN students st ON a.student_id = st.student_id
    JOIN users u ON st.user_id = u.id
    JOIN subjects sub ON a.subject_id = sub.subject_id
    WHERE $where_sql
    ORDER BY a.attendance_date DESC, a.attendance_id DESC
");

$total_records = mysqli_num_rows($query);

include("../includes/header.php");
include("../includes/sidebar_faculty.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-calendar-days"></i> Lecture Attendance Register</h3>
            <span class="badge badge-primary"><?php echo $total_records; ?> Logs</span>
        </div>
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <form method="GET" class="search-toolbar">
                <input type="date" name="date" class="form-control" style="width:160px;" value="<?php echo htmlspecialchars($_GET['date'] ?? ''); ?>">
                <div class="search-input-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" class="form-control" placeholder="Search student..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-filter"></i></button>
                <?php if (!empty($search) || !empty($_GET['date'])): ?>
                    <a href="attendance_report.php" class="btn btn-outline" title="Reset Filters"><i class="fa-solid fa-rotate-left"></i></a>
                <?php endif; ?>
            </form>
            <a href="mark_attendance.php" class="btn btn-primary" style="background:#0284c7;">
                <i class="fa-solid fa-user-check"></i> Mark Attendance
            </a>
        </div>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Details</th>
                        <th>Subject</th>
                        <th>Lecture Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($query && mysqli_num_rows($query) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><strong>#<?php echo $row['attendance_id']; ?></strong></td>
                                <td>
                                    <div style="font-weight:700; color:var(--text-primary);"><?php echo htmlspecialchars($row['student_name']); ?></div>
                                    <div style="font-size:12px; color:var(--text-muted);"><i class="fa-solid fa-id-card"></i> <?php echo htmlspecialchars($row['roll_no']); ?> &bull; <?php echo htmlspecialchars($row['course']); ?></div>
                                </td>
                                <td><span class="badge badge-primary"><?php echo htmlspecialchars($row['subject_name']); ?></span></td>
                                <td><?php echo date('d M Y', strtotime($row['attendance_date'])); ?></td>
                                <td>
                                    <?php if ($row['status'] === 'Present'): ?>
                                        <span class="badge badge-success"><i class="fa-solid fa-check"></i> Present</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger"><i class="fa-solid fa-xmark"></i> Absent</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="empty-state">
                                <i class="fa-solid fa-calendar-xmark"></i>
                                <h4>No attendance records found</h4>
                                <p><?php echo (!empty($search) || !empty($_GET['date'])) ? 'No records matched your search filters.' : 'Click "Mark Attendance" to log today\'s attendance.'; ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
