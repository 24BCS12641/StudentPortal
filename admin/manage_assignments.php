<?php
/**
 * Manage Assignments (Admin)
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Manage Assignments";
$search = "";
$subj_col = get_subject_col($con);

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = escape($con, $_GET['search']);
    $query = mysqli_query($con, "
        SELECT assignments.*, subjects.`$subj_col` AS subject_name, users.name AS faculty_name
        FROM assignments
        LEFT JOIN subjects ON assignments.subject_id = subjects.subject_id
        LEFT JOIN faculty ON assignments.faculty_id = faculty.faculty_id
        LEFT JOIN users ON faculty.user_id = users.id
        WHERE assignments.title LIKE '%$search%'
           OR subjects.`$subj_col` LIKE '%$search%'
           OR users.name LIKE '%$search%'
        ORDER BY assignments.assignment_id DESC
    ");
} else {
    $query = mysqli_query($con, "
        SELECT assignments.*, subjects.`$subj_col` AS subject_name, users.name AS faculty_name
        FROM assignments
        LEFT JOIN subjects ON assignments.subject_id = subjects.subject_id
        LEFT JOIN faculty ON assignments.faculty_id = faculty.faculty_id
        LEFT JOIN users ON faculty.user_id = users.id
        ORDER BY assignments.assignment_id DESC
    ");
}

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-file-lines"></i> Coursework & Assignments</h3>
            <span class="badge badge-primary"><?php echo safe_num_rows($query); ?> Assignments</span>
        </div>
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <form method="GET" class="search-toolbar">
                <div class="search-input-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" class="form-control" placeholder="Search by title, subject, faculty..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
                <?php if (!empty($search)): ?>
                    <a href="manage_assignments.php" class="btn btn-outline" title="Clear Search"><i class="fa-solid fa-rotate-left"></i></a>
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
                        <th>Assignment Title</th>
                        <th>Subject</th>
                        <th>Faculty</th>
                        <th>Deadline</th>
                        <th>Attachment</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($query && safe_num_rows($query) > 0): ?>
                        <?php while ($row = safe_fetch_assoc($query)): 
                            $is_overdue = (strtotime($row['deadline']) < strtotime(date('Y-m-d')));
                        ?>
                            <tr>
                                <td><strong>#<?php echo $row['assignment_id']; ?></strong></td>
                                <td>
                                    <div style="font-weight:700; color:var(--text-primary);"><?php echo htmlspecialchars($row['title']); ?></div>
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
                                <td><?php echo htmlspecialchars($row['faculty_name'] ?? 'Faculty'); ?></td>
                                <td>
                                    <?php if ($is_overdue): ?>
                                        <span class="badge badge-danger"><i class="fa-solid fa-clock"></i> <?php echo date('d M Y', strtotime($row['deadline'])); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-success"><i class="fa-solid fa-calendar-check"></i> <?php echo date('d M Y', strtotime($row['deadline'])); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($row['file_path']) && file_exists("../uploads/assignments/" . $row['file_path'])): ?>
                                        <a href="../uploads/assignments/<?php echo urlencode($row['file_path']); ?>" class="btn btn-outline btn-sm" download>
                                            <i class="fa-solid fa-download"></i> Download
                                        </a>
                                    <?php else: ?>
                                        <span style="color:#94a3b8; font-size:12px;">No Attachment</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:right;">
                                    <div class="table-actions">
                                        <a href="delete_assignment.php?id=<?php echo $row['assignment_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this assignment and its submissions?')" title="Delete Assignment">
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
                                <h4>No assignments found</h4>
                                <p><?php echo !empty($search) ? 'No assignments matched your search query.' : 'There are currently no assignments created.'; ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
