<?php
/**
 * Manage Students - Department-Wise Directory & Enrollment
 * Apex Imperial University Portal
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Manage Students by Department";

// Filter parameters
$dept_filter = isset($_GET['dept']) && is_numeric($_GET['dept']) ? (int)$_GET['dept'] : 0;
$course_filter = isset($_GET['course']) && is_numeric($_GET['course']) ? (int)$_GET['course'] : 0;
$sem_filter = isset($_GET['sem']) && is_numeric($_GET['sem']) ? (int)$_GET['sem'] : 0;
$search = isset($_GET['search']) ? escape($con, trim($_GET['search'])) : '';

// Build dynamic WHERE clause
$where_clauses = ["1=1"];

if ($dept_filter > 0) {
    $where_clauses[] = "students.department_id = '$dept_filter'";
}
if ($course_filter > 0) {
    $where_clauses[] = "students.course_id = '$course_filter'";
}
if ($sem_filter > 0) {
    $where_clauses[] = "students.semester = '$sem_filter'";
}
if (!empty($search)) {
    $where_clauses[] = "(users.name LIKE '%$search%' OR users.email LIKE '%$search%' OR students.roll_no LIKE '%$search%' OR students.course LIKE '%$search%')";
}

$where_sql = implode(" AND ", $where_clauses);

// Main Student Query with Joins
$query = mysqli_query($con, "
    SELECT students.*, users.name, users.email, 
           d.name AS department_name, d.code AS department_code, d.icon AS department_icon,
           c.name AS course_full_name, c.code AS course_code
    FROM students
    INNER JOIN users ON students.user_id = users.id
    LEFT JOIN departments d ON students.department_id = d.department_id
    LEFT JOIN courses c ON students.course_id = c.course_id
    WHERE $where_sql
    ORDER BY students.department_id ASC, students.semester ASC, students.roll_no ASC
");

// Fetch Departments with live student counts
$depts_list = [];
$total_all_students = 0;
$d_res = @mysqli_query($con, "
    SELECT d.department_id, d.name, d.code, d.icon,
           (SELECT COUNT(*) FROM students s WHERE s.department_id = d.department_id) AS student_count
    FROM departments d
    ORDER BY d.department_id ASC
");
if ($d_res) {
    while ($d = mysqli_fetch_assoc($d_res)) {
        $depts_list[] = $d;
        $total_all_students += (int)$d['student_count'];
    }
}

// Fetch Courses for filter dropdown
$courses_list = [];
$c_res = @mysqli_query($con, "SELECT course_id, department_id, name, code FROM courses ORDER BY name ASC");
if ($c_res) {
    while ($c = mysqli_fetch_assoc($c_res)) {
        $courses_list[] = $c;
    }
}

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<!-- Department Overview Stats Grid -->
<div class="stats-grid" style="margin-bottom: 24px;">
    <!-- All Departments Card -->
    <a href="manage_students.php" style="text-decoration:none;">
        <div class="stat-card <?php echo ($dept_filter === 0) ? 'primary' : ''; ?>" style="cursor:pointer; border: <?php echo ($dept_filter === 0) ? '2px solid #2563eb' : '1px solid #e2e8f0'; ?>;">
            <div class="stat-info">
                <div class="stat-label">Total University Enrolled</div>
                <div class="stat-value"><?php echo $total_all_students; ?></div>
                <div class="stat-subtext"><i class="fa-solid fa-users"></i> All Departments Active</div>
            </div>
            <div class="stat-icon-wrapper" style="background:#dbeafe; color:#2563eb;">
                <i class="fa-solid fa-building-columns"></i>
            </div>
        </div>
    </a>

    <?php 
    $dept_colors = [
        1 => ['bg' => '#dbeafe', 'color' => '#1d4ed8'], // CSE Blue
        2 => ['bg' => '#fef3c7', 'color' => '#b45309'], // BBA Amber
        3 => ['bg' => '#dcfce7', 'color' => '#15803d'], // IT Green
        4 => ['bg' => '#ffedd5', 'color' => '#c2410c'], // MECH Orange
        5 => ['bg' => '#f3e8ff', 'color' => '#7e22ce']  // HAJ Purple
    ];
    foreach ($depts_list as $d): 
        $d_id = (int)$d['department_id'];
        $c_theme = $dept_colors[$d_id] ?? ['bg' => '#f1f5f9', 'color' => '#475569'];
        $is_selected = ($dept_filter === $d_id);
    ?>
        <a href="manage_students.php?dept=<?php echo $d_id; ?>" style="text-decoration:none;">
            <div class="stat-card" style="cursor:pointer; <?php if ($is_selected) echo 'border:2px solid ' . $c_theme['color'] . '; box-shadow:0 8px 20px rgba(0,0,0,0.08);'; ?>">
                <div class="stat-info">
                    <div class="stat-label"><?php echo htmlspecialchars($d['code']); ?> &bull; Department</div>
                    <div class="stat-value" style="<?php if ($is_selected) echo 'color:' . $c_theme['color'] . ';'; ?>">
                        <?php echo (int)$d['student_count']; ?>
                    </div>
                    <div class="stat-subtext" style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="<?php echo htmlspecialchars($d['name']); ?>">
                        <?php echo htmlspecialchars($d['name']); ?>
                    </div>
                </div>
                <div class="stat-icon-wrapper" style="background:<?php echo $c_theme['bg']; ?>; color:<?php echo $c_theme['color']; ?>;">
                    <i class="fa-solid <?php echo htmlspecialchars($d['icon'] ?: 'fa-laptop-code'); ?>"></i>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<!-- Department Filter Tabs -->
<div style="display:flex; align-items:center; gap:8px; overflow-x:auto; padding-bottom:8px; margin-bottom:20px;">
    <a href="manage_students.php" class="btn <?php echo ($dept_filter === 0) ? 'btn-primary' : 'btn-outline'; ?>" style="font-size:13px; border-radius:20px; padding:6px 16px;">
        <i class="fa-solid fa-list-check"></i> All Departments (<?php echo $total_all_students; ?>)
    </a>
    <?php foreach ($depts_list as $d): 
        $d_id = (int)$d['department_id'];
        $active_class = ($dept_filter === $d_id) ? 'btn-primary' : 'btn-outline';
    ?>
        <a href="manage_students.php?dept=<?php echo $d_id; ?>" class="btn <?php echo $active_class; ?>" style="font-size:13px; border-radius:20px; padding:6px 16px; white-space:nowrap;">
            <i class="fa-solid <?php echo htmlspecialchars($d['icon'] ?: 'fa-graduation-cap'); ?>"></i>
            <?php echo htmlspecialchars($d['code']); ?> (<?php echo (int)$d['student_count']; ?>)
        </a>
    <?php endforeach; ?>
</div>

<!-- Students Directory Card -->
<div class="card">
    <div class="card-header" style="flex-wrap:wrap; gap:14px;">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3>
                <i class="fa-solid fa-user-graduate text-primary"></i> 
                <?php 
                if ($dept_filter > 0) {
                    $selected_dname = "Department";
                    foreach ($depts_list as $dl) {
                        if ((int)$dl['department_id'] === $dept_filter) {
                            $selected_dname = $dl['name'];
                            break;
                        }
                    }
                    echo htmlspecialchars($selected_dname) . " &bull; Enrolled Students";
                } else {
                    echo "All Departments &bull; Enrolled Students Directory";
                }
                ?>
            </h3>
            <span class="badge badge-primary"><?php echo mysqli_num_rows($query); ?> Students</span>
        </div>

        <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
            <!-- Detailed Filter Toolbar -->
            <form method="GET" class="search-toolbar" style="display:flex; gap:8px; flex-wrap:wrap;">
                <?php if ($dept_filter > 0): ?>
                    <input type="hidden" name="dept" value="<?php echo $dept_filter; ?>">
                <?php endif; ?>

                <!-- Course Dropdown -->
                <select name="course" class="form-control" style="width:160px; font-size:13px;" onchange="this.form.submit()">
                    <option value="">All Programs</option>
                    <?php foreach ($courses_list as $crs): ?>
                        <option value="<?php echo $crs['course_id']; ?>" <?php if ($course_filter == $crs['course_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($crs['code']); ?> (<?php echo htmlspecialchars($crs['name']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Semester Dropdown -->
                <select name="sem" class="form-control" style="width:130px; font-size:13px;" onchange="this.form.submit()">
                    <option value="">All Semesters</option>
                    <?php for ($s = 1; $s <= 8; $s++): ?>
                        <option value="<?php echo $s; ?>" <?php if ($sem_filter === $s) echo 'selected'; ?>>Semester <?php echo $s; ?></option>
                    <?php endfor; ?>
                </select>

                <div class="search-input-group" style="min-width:200px;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" class="form-control" placeholder="Search name, roll no..." value="<?php echo htmlspecialchars($search); ?>">
                </div>

                <button type="submit" class="btn btn-secondary" title="Apply Filter"><i class="fa-solid fa-filter"></i></button>

                <?php if ($dept_filter > 0 || $course_filter > 0 || $sem_filter > 0 || !empty($search)): ?>
                    <a href="manage_students.php" class="btn btn-outline" title="Reset All Filters"><i class="fa-solid fa-rotate-left"></i> Reset</a>
                <?php endif; ?>
            </form>

            <a href="add_student.php" class="btn btn-primary">
                <i class="fa-solid fa-user-plus"></i> Enroll Student
            </a>
        </div>
    </div>

    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name & Email</th>
                        <th>Roll Number</th>
                        <th>Department</th>
                        <th>Degree Program</th>
                        <th>Semester</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($query && mysqli_num_rows($query) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($query)): 
                            $pic = !empty($row['profile_picture']) && file_exists("../uploads/profile_pictures/" . $row['profile_picture'])
                                ? "../uploads/profile_pictures/" . $row['profile_picture']
                                : "../assets/images/default-user.png";
                            
                            $d_code = $row['department_code'] ?? 'CSE';
                            $d_icon = $row['department_icon'] ?? 'fa-graduation-cap';
                            $c_code = $row['course_code'] ?? ($row['course'] ?? 'BCA');
                        ?>
                            <tr>
                                <td><strong>#<?php echo $row['student_id']; ?></strong></td>
                                <td>
                                    <div class="table-user-cell">
                                        <img src="<?php echo $pic; ?>" alt="Student Photo" class="table-avatar">
                                        <div>
                                            <div class="user-name"><?php echo htmlspecialchars($row['name']); ?></div>
                                            <div class="user-subtext"><?php echo htmlspecialchars($row['email']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-dark" style="font-family:monospace; font-size:12.5px; font-weight:700;">
                                        <?php echo htmlspecialchars($row['roll_no']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-primary" style="font-size:12px; gap:6px;">
                                        <i class="fa-solid <?php echo htmlspecialchars($d_icon); ?>"></i>
                                        <?php echo htmlspecialchars($d_code); ?>
                                    </span>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($c_code); ?></strong>
                                    <div style="font-size:11.5px; color:var(--text-muted);"><?php echo htmlspecialchars($row['course_full_name'] ?? ''); ?></div>
                                </td>
                                <td>
                                    <span class="badge badge-info" style="font-weight:600;">Semester <?php echo htmlspecialchars($row['semester']); ?></span>
                                </td>
                                <td style="text-align:right;">
                                    <div class="table-actions">
                                        <a href="edit_student.php?id=<?php echo $row['student_id']; ?>" class="btn btn-outline btn-sm" title="Edit Student">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>
                                        <a href="delete_student.php?id=<?php echo $row['student_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete student <?php echo htmlspecialchars(addslashes($row['name'])); ?>? This will remove all related attendance and grades records.')" title="Delete Student">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="empty-state">
                                <i class="fa-solid fa-user-slash"></i>
                                <h4>No students found in this department or criteria</h4>
                                <p><?php echo (!empty($search) || $dept_filter > 0) ? 'Try resetting filters or searching with different terms.' : 'No students registered yet.'; ?></p>
                                <a href="manage_students.php" class="btn btn-secondary btn-sm" style="margin-top:10px;">
                                    <i class="fa-solid fa-rotate-left"></i> View All Students
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
