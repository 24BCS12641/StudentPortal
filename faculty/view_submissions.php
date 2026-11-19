<?php
/**
 * View Student Submissions (Faculty)
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('faculty');

$page_title = "Student Submissions & Evaluation";
$user_id = $_SESSION['user_id'];
$subj_col = get_subject_col($con);

// Get faculty ID
$f_query = mysqli_query($con, "SELECT faculty_id FROM faculty WHERE user_id='$user_id' LIMIT 1");
$faculty = ($f_query && mysqli_num_rows($f_query) > 0) ? mysqli_fetch_assoc($f_query) : [];
$faculty_id = $faculty['faculty_id'] ?? 0;

$where_clauses = ["(a.faculty_id='$faculty_id' OR a.faculty_id IS NULL)"];

// Filter by assignment
if (isset($_GET['assignment_id']) && !empty($_GET['assignment_id'])) {
    $a_id = (int)$_GET['assignment_id'];
    $where_clauses[] = "s.assignment_id = '$a_id'";
}

// Search
$search = "";
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = escape($con, $_GET['search']);
    $where_clauses[] = "(u.name LIKE '%$search%' OR st.roll_no LIKE '%$search%' OR a.title LIKE '%$search%')";
}

$where_sql = implode(" AND ", $where_clauses);

$query = mysqli_query($con, "
    SELECT s.*, u.name AS student_name, u.email, st.roll_no, st.course, a.title AS assignment_title, sub.`$subj_col` AS subject_name
    FROM submissions s
    JOIN students st ON s.student_id = st.student_id
    JOIN users u ON st.user_id = u.id
    JOIN assignments a ON s.assignment_id = a.assignment_id
    LEFT JOIN subjects sub ON a.subject_id = sub.subject_id
    WHERE $where_sql
    ORDER BY s.submission_id DESC
");

include("../includes/header.php");
include("../includes/sidebar_faculty.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-inbox"></i> Student Assignment Submissions</h3>
            <span class="badge badge-primary"><?php echo mysqli_num_rows($query); ?> Submissions</span>
        </div>
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <form method="GET" class="search-toolbar">
                <?php if (isset($_GET['assignment_id'])): ?>
                    <input type="hidden" name="assignment_id" value="<?php echo htmlspecialchars($_GET['assignment_id']); ?>">
                <?php endif; ?>
                <div class="search-input-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" class="form-control" placeholder="Search student, roll no, assignment..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
                <?php if (!empty($search) || isset($_GET['assignment_id'])): ?>
                    <a href="view_submissions.php" class="btn btn-outline" title="Show All"><i class="fa-solid fa-rotate-left"></i></a>
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
                        <th>Student Details</th>
                        <th>Assignment & Subject</th>
                        <th>Submitted At</th>
                        <th>Solution File</th>
                        <th>Grading Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($query && mysqli_num_rows($query) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($query)): 
                            $is_graded = isset($row['marks']) && $row['marks'] !== null;
                        ?>
                            <tr>
                                <td><strong>#<?php echo $row['submission_id']; ?></strong></td>
                                <td>
                                    <div style="font-weight:700; color:var(--text-primary);"><?php echo htmlspecialchars($row['student_name']); ?></div>
                                    <div style="font-size:12px; color:var(--text-muted);">
                                        <i class="fa-solid fa-id-card"></i> <?php echo htmlspecialchars($row['roll_no']); ?> &bull; <?php echo htmlspecialchars($row['course']); ?>
                                    </div>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['assignment_title']); ?></strong>
                                    <div style="font-size:12px; color:var(--text-muted);"><?php echo htmlspecialchars($row['subject_name'] ?? 'General'); ?></div>
                                </td>
                                <td>
                                    <span style="font-size:13px; color:var(--text-secondary);">
                                        <?php echo date('d M Y, h:i A', strtotime($row['submitted_at'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($row['file_path']) && file_exists("../uploads/submissions/" . $row['file_path'])): ?>
                                        <a href="../uploads/submissions/<?php echo urlencode($row['file_path']); ?>" class="btn btn-outline btn-sm" download>
                                            <i class="fa-solid fa-download"></i> Download Solution
                                        </a>
                                    <?php else: ?>
                                        <span style="color:#94a3b8; font-size:12px;">File unavailable</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($is_graded): ?>
                                        <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> <?php echo $row['marks']; ?>/100</span>
                                        <?php if (!empty($row['remarks'])): ?>
                                            <div style="font-size:11px; color:#475569; margin-top:3px; max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                                "<?php echo htmlspecialchars($row['remarks']); ?>"
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge badge-warning"><i class="fa-solid fa-clock"></i> Pending Evaluation</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:right;">
                                    <a href="grade_assignment.php?id=<?php echo $row['submission_id']; ?>" class="btn btn-primary btn-sm" style="background:#0284c7;">
                                        <i class="fa-solid fa-award"></i> <?php echo $is_graded ? 'Re-Grade' : 'Grade'; ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="empty-state">
                                <i class="fa-solid fa-inbox"></i>
                                <h4>No student submissions found</h4>
                                <p><?php echo !empty($search) ? 'No submissions matched your search query.' : 'There are currently no submissions recorded.'; ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
