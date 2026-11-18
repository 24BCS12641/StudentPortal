<?php
/**
 * Faculty - Lecture Attendance Register & Analytics Report
 * Apex Imperial University Portal
 */

include("../config/auth.php");
include("../config/db.php");
check_role('faculty');

$page_title = "Lecture Attendance Register & Reports";
$subj_col = get_subject_col($con);

$search = "";
$where_clauses = ["1=1"];

// Filters
$subject_filter = isset($_GET['subject_id']) && is_numeric($_GET['subject_id']) ? (int)$_GET['subject_id'] : 0;
$date_filter = isset($_GET['date']) && !empty($_GET['date']) ? escape($con, $_GET['date']) : '';
$status_filter = isset($_GET['status']) ? escape($con, $_GET['status']) : '';

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = escape($con, trim($_GET['search']));
    $where_clauses[] = "(u.name LIKE '%$search%' OR st.roll_no LIKE '%$search%' OR sub.`$subj_col` LIKE '%$search%')";
}
if ($subject_filter > 0) {
    $where_clauses[] = "a.subject_id = '$subject_filter'";
}
if (!empty($date_filter)) {
    $where_clauses[] = "(a.attendance_date = '$date_filter' OR a.date = '$date_filter')";
}
if (!empty($status_filter)) {
    $where_clauses[] = "a.status = '$status_filter'";
}

$where_sql = implode(" AND ", $where_clauses);

// Fetch query
$query = mysqli_query($con, "
    SELECT a.*, u.name AS student_name, u.email AS student_email, st.roll_no, st.course, st.semester, st.profile_picture,
           sub.`$subj_col` AS subject_name, sub.subject_code,
           d.name AS department_name, d.code AS department_code
    FROM attendance a
    JOIN students st ON a.student_id = st.student_id
    JOIN users u ON st.user_id = u.id
    JOIN subjects sub ON a.subject_id = sub.subject_id
    LEFT JOIN departments d ON st.department_id = d.department_id
    WHERE $where_sql
    ORDER BY a.attendance_date DESC, a.attendance_id DESC
");

$total_records = mysqli_num_rows($query);

// Analytics
$stat_p = safe_count($con, "attendance", "status='Present'");
$stat_a = safe_count($con, "attendance", "status='Absent'");
$stat_l = safe_count($con, "attendance", "status='Late'");
$stat_total = $stat_p + $stat_a + $stat_l;
$avg_rate = ($stat_total > 0) ? round(($stat_p / $stat_total) * 100, 1) : 0;

// Fetch subjects for filter
$subjects_list = mysqli_query($con, "SELECT subject_id, `$subj_col` as subject_name, subject_code FROM subjects ORDER BY `$subj_col` ASC");

include("../includes/header.php");
include("../includes/sidebar_faculty.php");
include("../includes/navbar.php");
?>

<!-- Attendance KPI Summary -->
<div class="stats-grid" style="margin-bottom:24px;">
    <div class="stat-card primary">
        <div class="stat-info">
            <div class="stat-label">Total Recorded Sessions</div>
            <div class="stat-value"><?php echo $stat_total; ?></div>
            <div class="stat-subtext">Across all university subjects</div>
        </div>
        <div class="stat-icon-wrapper" style="background:#dbeafe; color:#2563eb;">
            <i class="fa-solid fa-clipboard-list"></i>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-info">
            <div class="stat-label">Average Attendance Rate</div>
            <div class="stat-value"><?php echo $avg_rate; ?>%</div>
            <div class="stat-subtext"><?php echo $stat_p; ?> Present attendances logged</div>
        </div>
        <div class="stat-icon-wrapper" style="background:#dcfce7; color:#15803d;">
            <i class="fa-solid fa-circle-check"></i>
        </div>
    </div>

    <div class="stat-card danger">
        <div class="stat-info">
            <div class="stat-label">Total Absentee Logs</div>
            <div class="stat-value"><?php echo $stat_a; ?></div>
            <div class="stat-subtext">Requires attendance tracking</div>
        </div>
        <div class="stat-icon-wrapper" style="background:#fee2e2; color:#b91c1c;">
            <i class="fa-solid fa-user-xmark"></i>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-info">
            <div class="stat-label">Late / Partial Arrivals</div>
            <div class="stat-value"><?php echo $stat_l; ?></div>
            <div class="stat-subtext">Arrived after roll call</div>
        </div>
        <div class="stat-icon-wrapper" style="background:#fef3c7; color:#b45309;">
            <i class="fa-solid fa-clock"></i>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header" style="flex-wrap:wrap; gap:12px;">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-calendar-days text-primary"></i> Lecture Attendance Register</h3>
            <span class="badge badge-primary"><?php echo $total_records; ?> Logs Filtered</span>
        </div>
        <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
            <form method="GET" class="search-toolbar" style="display:flex; gap:8px; flex-wrap:wrap;">
                <select name="subject_id" class="form-control" style="width:170px; font-size:13px;" onchange="this.form.submit()">
                    <option value="">All Subjects</option>
                    <?php 
                    mysqli_data_seek($subjects_list, 0);
                    while ($sub = mysqli_fetch_assoc($subjects_list)): 
                    ?>
                        <option value="<?php echo $sub['subject_id']; ?>" <?php if ($subject_filter == $sub['subject_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($sub['subject_code']); ?> - <?php echo htmlspecialchars($sub['subject_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <input type="date" name="date" class="form-control" style="width:150px; font-size:13px;" value="<?php echo htmlspecialchars($date_filter); ?>" onchange="this.form.submit()">

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

                <?php if ($subject_filter > 0 || !empty($date_filter) || !empty($status_filter) || !empty($search)): ?>
                    <a href="attendance_report.php" class="btn btn-outline" title="Clear Filters"><i class="fa-solid fa-rotate-left"></i></a>
                <?php endif; ?>
            </form>

            <button type="button" class="btn btn-outline" onclick="window.print()">
                <i class="fa-solid fa-print"></i> Print
            </button>

            <a href="mark_attendance.php" class="btn btn-primary">
                <i class="fa-solid fa-clipboard-user"></i> Mark Attendance
            </a>
        </div>
    </div>

    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Session Date</th>
                        <th>Student Name & Roll No</th>
                        <th>Department & Program</th>
                        <th>Course Module / Subject</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($query && mysqli_num_rows($query) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($query)): 
                            $pic = !empty($row['profile_picture']) && file_exists("../uploads/profile_pictures/" . $row['profile_picture'])
                                ? "../uploads/profile_pictures/" . $row['profile_picture']
                                : "../assets/images/default-user.png";
                            $status_val = $row['status'];
                        ?>
                            <tr>
                                <td>
                                    <div style="font-weight:700; color:#1e293b;">
                                        <?php echo date('d M Y', strtotime($row['attendance_date'] ?: $row['date'])); ?>
                                    </div>
                                    <div style="font-size:11.5px; color:var(--text-muted);">
                                        <?php echo date('l', strtotime($row['attendance_date'] ?: $row['date'])); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-user-cell">
                                        <img src="<?php echo $pic; ?>" alt="Avatar" class="table-avatar">
                                        <div>
                                            <div class="user-name"><?php echo htmlspecialchars($row['student_name']); ?></div>
                                            <span class="badge badge-dark" style="font-family:monospace; font-size:11.5px;"><?php echo htmlspecialchars($row['roll_no']); ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-primary" style="font-size:11px;"><?php echo htmlspecialchars($row['department_code'] ?? 'CSE'); ?></span>
                                    <strong><?php echo htmlspecialchars($row['course']); ?></strong>
                                    <span style="font-size:11.5px; color:var(--text-muted);">Sem <?php echo $row['semester']; ?></span>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['subject_name']); ?></strong>
                                    <div style="font-size:11.5px; color:var(--text-muted);"><?php echo htmlspecialchars($row['subject_code'] ?? ''); ?></div>
                                </td>
                                <td>
                                    <?php if ($status_val === 'Present'): ?>
                                        <span class="badge badge-success" style="padding:6px 12px; font-weight:700;">
                                            <i class="fa-solid fa-check"></i> Present
                                        </span>
                                    <?php elseif ($status_val === 'Absent'): ?>
                                        <span class="badge badge-danger" style="padding:6px 12px; font-weight:700;">
                                            <i class="fa-solid fa-xmark"></i> Absent
                                        </span>
                                    <?php elseif ($status_val === 'Late'): ?>
                                        <span class="badge badge-warning" style="padding:6px 12px; font-weight:700; background:#fef3c7; color:#b45309;">
                                            <i class="fa-solid fa-clock"></i> Late
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-info" style="padding:6px 12px; font-weight:700;">
                                            <i class="fa-solid fa-shield"></i> <?php echo htmlspecialchars($status_val); ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span style="font-size:12.5px; color:var(--text-muted);">
                                        <?php echo !empty($row['remarks']) ? htmlspecialchars($row['remarks']) : '—'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fa-solid fa-calendar-xmark"></i>
                                <h4>No attendance records match your search criteria</h4>
                                <p>Try clearing your filters or mark attendance for this lecture session.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
