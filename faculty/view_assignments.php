<?php
/**
 * View & Manage Assignments (Faculty)
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('faculty');

$page_title = "Manage Assignments";
$user_id = $_SESSION['user_id'];
$subj_col = get_subject_col($con);

// Get faculty ID
$f_query = mysqli_query($con, "SELECT faculty_id FROM faculty WHERE user_id='$user_id' LIMIT 1");
$faculty = ($f_query && mysqli_num_rows($f_query) > 0) ? mysqli_fetch_assoc($f_query) : [];
$faculty_id = $faculty['faculty_id'] ?? 0;

$search = "";
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = escape($con, $_GET['search']);
    $query = mysqli_query($con, "
        SELECT a.*, s.`$subj_col` AS subject_name,
               (SELECT COUNT(*) FROM submissions sub WHERE sub.assignment_id = a.assignment_id) as submission_count
        FROM assignments a
        LEFT JOIN subjects s ON a.subject_id = s.subject_id
        WHERE (a.faculty_id='$faculty_id' OR a.faculty_id IS NULL)
          AND (a.title LIKE '%$search%' OR s.`$subj_col` LIKE '%$search%')
        ORDER BY a.assignment_id DESC
    ");
} else {
    $query = mysqli_query($con, "
        SELECT a.*, s.`$subj_col` AS subject_name,
               (SELECT COUNT(*) FROM submissions sub WHERE sub.assignment_id = a.assignment_id) as submission_count
        FROM assignments a
        LEFT JOIN subjects s ON a.subject_id = s.subject_id
        WHERE a.faculty_id='$faculty_id' OR a.faculty_id IS NULL
        ORDER BY a.assignment_id DESC
    ");
}

include("../includes/header.php");
include("../includes/sidebar_faculty.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-file-pen"></i> My Assignments & Coursework</h3>
            <span class="badge badge-primary"><?php echo mysqli_num_rows($query); ?> Assignments</span>
        </div>
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <form method="GET" class="search-toolbar">
                <div class="search-input-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" class="form-control" placeholder="Search by title, subject..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
                <?php if (!empty($search)): ?>
                    <a href="view_assignments.php" class="btn btn-outline" title="Clear Search"><i class="fa-solid fa-rotate-left"></i></a>
                <?php endif; ?>
            </form>
            <a href="create_assignment.php" class="btn btn-primary" style="background:#0284c7;">
                <i class="fa-solid fa-plus-circle"></i> Create Assignment
            </a>
        </div>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Assignment Title</th>
                        <th>Subject</th>
                        <th>Deadline</th>
                        <th>Submissions</th>
                        <th>Attachment</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($query && mysqli_num_rows($query) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($query)): 
                            $is_overdue = (strtotime($row['deadline']) < strtotime(date('Y-m-d')));
                        ?>
                            <tr>
                                <td><strong>#<?php echo $row['assignment_id']; ?></strong></td>
                                <td>
                                    <div style="font-weight:700; color:var(--text-primary); font-size:15px;"><?php echo htmlspecialchars($row['title']); ?></div>
                                    <div style="font-size:12px; color:var(--text-muted); max-width:280px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                        <?php echo htmlspecialchars($row['description']); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($row['subject_name'])): ?>
                                        <span class="badge badge-primary"><?php echo htmlspecialchars($row['subject_name']); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-dark">General</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($is_overdue): ?>
                                        <span class="badge badge-danger"><i class="fa-solid fa-clock"></i> <?php echo date('d M Y', strtotime($row['deadline'])); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-success"><i class="fa-solid fa-calendar-check"></i> <?php echo date('d M Y', strtotime($row['deadline'])); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="view_submissions.php?assignment_id=<?php echo $row['assignment_id']; ?>" class="badge badge-info" style="text-decoration:none;">
                                        <i class="fa-solid fa-inbox"></i> <?php echo $row['submission_count']; ?> Submissions
                                    </a>
                                </td>
                                <td>
                                    <?php if (!empty($row['file_path']) && file_exists("../uploads/assignments/" . $row['file_path'])): ?>
                                        <a href="../uploads/assignments/<?php echo urlencode($row['file_path']); ?>" class="btn btn-outline btn-sm" download>
                                            <i class="fa-solid fa-download"></i> File
                                        </a>
                                    <?php else: ?>
                                        <span style="color:#94a3b8; font-size:12px;">No File</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:right;">
                                    <div class="table-actions">
                                        <a href="edit_assignment.php?id=<?php echo $row['assignment_id']; ?>" class="btn btn-outline btn-sm" title="Edit Assignment">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>
                                        <a href="delete_assignment.php?id=<?php echo $row['assignment_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this assignment? All associated student submissions will also be deleted.')" title="Delete Assignment">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="empty-state">
                                <i class="fa-solid fa-file-circle-xmark"></i>
                                <h4>No assignments created yet</h4>
                                <p><?php echo !empty($search) ? 'No assignments matched your search query.' : 'Click "Create Assignment" to post coursework for your students.'; ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
