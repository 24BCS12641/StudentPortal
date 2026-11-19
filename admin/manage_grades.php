<?php
/**
 * Manage Grades (Admin)
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Manage Student Grades";
$search = "";
$subj_col = get_subject_col($con);

if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    mysqli_query($con, "DELETE FROM grades WHERE grade_id='$del_id'");
    set_flash("success", "Grade record has been deleted.");
    header("Location: manage_grades.php");
    exit();
}

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = escape($con, $_GET['search']);
    $query = mysqli_query($con, "
        SELECT grades.*, users.name AS student_name, students.roll_no, assignments.title AS assignment_title, subjects.`$subj_col` AS subject_name
        FROM grades
        LEFT JOIN students ON grades.student_id = students.student_id
        LEFT JOIN users ON students.user_id = users.id
        LEFT JOIN assignments ON grades.assignment_id = assignments.assignment_id
        LEFT JOIN subjects ON assignments.subject_id = subjects.subject_id
        WHERE users.name LIKE '%$search%'
           OR students.roll_no LIKE '%$search%'
           OR assignments.title LIKE '%$search%'
           OR subjects.`$subj_col` LIKE '%$search%'
        ORDER BY grades.grade_id DESC
    ");
} else {
    $query = mysqli_query($con, "
        SELECT grades.*, users.name AS student_name, students.roll_no, assignments.title AS assignment_title, subjects.`$subj_col` AS subject_name
        FROM grades
        LEFT JOIN students ON grades.student_id = students.student_id
        LEFT JOIN users ON students.user_id = users.id
        LEFT JOIN assignments ON grades.assignment_id = assignments.assignment_id
        LEFT JOIN subjects ON assignments.subject_id = subjects.subject_id
        ORDER BY grades.grade_id DESC
    ");
}

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<!-- Tab Switcher Header -->
<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; margin-bottom:24px;">
    <div style="display:flex; align-items:center; gap:10px;">
        <a href="manage_results.php" class="btn btn-outline" style="font-weight:600;">
            <i class="fa-solid fa-graduation-cap"></i> Semester Exam Results
        </a>
        <a href="manage_grades.php" class="btn btn-primary" style="font-weight:700;">
            <i class="fa-solid fa-trophy"></i> Assignment Continuous Grades
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-trophy"></i> Student Grades & Evaluation Records</h3>
            <span class="badge badge-primary"><?php echo safe_num_rows($query); ?> Grades</span>
        </div>
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <form method="GET" class="search-toolbar">
                <div class="search-input-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" class="form-control" placeholder="Search student, assignment, subject..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
                <?php if (!empty($search)): ?>
                    <a href="manage_grades.php" class="btn btn-outline" title="Clear Search"><i class="fa-solid fa-rotate-left"></i></a>
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
                        <th>Student & Roll No</th>
                        <th>Assignment</th>
                        <th>Subject</th>
                        <th>Score</th>
                        <th>Faculty Remarks</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($query && safe_num_rows($query) > 0): ?>
                        <?php while ($row = safe_fetch_assoc($query)): 
                            $marks = (int)$row['marks'];
                            $badge_class = ($marks >= 80) ? 'badge-success' : (($marks >= 50) ? 'badge-primary' : 'badge-danger');
                        ?>
                            <tr>
                                <td><strong>#<?php echo $row['grade_id']; ?></strong></td>
                                <td>
                                    <div style="font-weight:600; color:var(--text-primary);"><?php echo htmlspecialchars($row['student_name'] ?? 'Student'); ?></div>
                                    <div style="font-size:12px; color:var(--text-muted);"><i class="fa-solid fa-id-card"></i> <?php echo htmlspecialchars($row['roll_no'] ?? 'N/A'); ?></div>
                                </td>
                                <td><?php echo htmlspecialchars($row['assignment_title'] ?? 'Assignment'); ?></td>
                                <td><span class="badge badge-dark"><?php echo htmlspecialchars($row['subject_name'] ?? 'Coursework'); ?></span></td>
                                <td>
                                    <span class="badge <?php echo $badge_class; ?>" style="font-size:13px; font-weight:700;">
                                        <?php echo $marks; ?> / 100
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size:13px; color:#475569; max-width:240px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                        <?php echo htmlspecialchars($row['remarks'] ?? 'No remarks'); ?>
                                    </div>
                                </td>
                                <td style="text-align:right;">
                                    <a href="?delete=<?php echo $row['grade_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this grade record?')" title="Delete Grade">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="empty-state">
                                <i class="fa-solid fa-award"></i>
                                <h4>No grades recorded</h4>
                                <p><?php echo !empty($search) ? 'No grade records matched your search query.' : 'There are currently no graded submissions.'; ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
