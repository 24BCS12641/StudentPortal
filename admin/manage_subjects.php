<?php
/**
 * Manage Subjects
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Manage Curriculum Subjects";
$search = "";
$subj_col = get_subject_col($con);

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = escape($con, $_GET['search']);
    $query = mysqli_query($con, "
        SELECT subjects.*, subjects.`$subj_col` AS display_subject_name, users.name AS faculty_name
        FROM subjects
        LEFT JOIN faculty ON subjects.faculty_id = faculty.faculty_id
        LEFT JOIN users ON faculty.user_id = users.id
        WHERE subjects.`$subj_col` LIKE '%$search%'
           OR subjects.subject_code LIKE '%$search%'
           OR users.name LIKE '%$search%'
        ORDER BY subjects.subject_id DESC
    ");
} else {
    $query = mysqli_query($con, "
        SELECT subjects.*, subjects.`$subj_col` AS display_subject_name, users.name AS faculty_name
        FROM subjects
        LEFT JOIN faculty ON subjects.faculty_id = faculty.faculty_id
        LEFT JOIN users ON faculty.user_id = users.id
        ORDER BY subjects.subject_id DESC
    ");
}

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-book-open"></i> Curriculum Subjects</h3>
            <span class="badge badge-primary"><?php echo safe_num_rows($query); ?> Subjects</span>
        </div>
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <form method="GET" class="search-toolbar">
                <div class="search-input-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" class="form-control" placeholder="Search subject name, code, faculty..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
                <?php if (!empty($search)): ?>
                    <a href="manage_subjects.php" class="btn btn-outline" title="Clear Search"><i class="fa-solid fa-rotate-left"></i></a>
                <?php endif; ?>
            </form>
            <a href="add_subject.php" class="btn btn-primary">
                <i class="fa-solid fa-book-medical"></i> Add Subject
            </a>
        </div>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Subject Title</th>
                        <th>Subject Code</th>
                        <th>Assigned Faculty</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($query && safe_num_rows($query) > 0): ?>
                        <?php while ($row = safe_fetch_assoc($query)): ?>
                            <tr>
                                <td><strong>#<?php echo $row['subject_id']; ?></strong></td>
                                <td>
                                    <div style="font-weight:700; color:var(--text-primary); font-size:15px;">
                                        <?php echo htmlspecialchars($row['display_subject_name']); ?>
                                    </div>
                                    <div style="font-size:12px; color:var(--text-muted);">
                                        <?php echo htmlspecialchars($row['course'] ?? 'BCA'); ?> &bull; Semester <?php echo htmlspecialchars($row['semester'] ?? 1); ?>
                                    </div>
                                </td>
                                <td><span class="badge badge-primary"><?php echo htmlspecialchars($row['subject_code']); ?></span></td>
                                <td>
                                    <?php if (!empty($row['faculty_name'])): ?>
                                        <span class="badge badge-success"><i class="fa-solid fa-user-tie"></i> <?php echo htmlspecialchars($row['faculty_name']); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-warning"><i class="fa-solid fa-triangle-exclamation"></i> Unassigned</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:right;">
                                    <div class="table-actions">
                                        <a href="edit_subject.php?id=<?php echo $row['subject_id']; ?>" class="btn btn-outline btn-sm" title="Edit Subject">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>
                                        <a href="delete_subject.php?id=<?php echo $row['subject_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete subject <?php echo htmlspecialchars(addslashes($row['display_subject_name'])); ?>?')" title="Delete Subject">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="empty-state">
                                <i class="fa-solid fa-book"></i>
                                <h4>No subjects found</h4>
                                <p><?php echo !empty($search) ? 'No subjects matched your search query.' : 'There are currently no subjects added.'; ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
