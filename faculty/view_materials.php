<?php
/**
 * View Study Materials (Faculty)
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('faculty');

$page_title = "Study Materials & Notes";
$user_id = $_SESSION['user_id'];
$subj_col = get_subject_col($con);

// Delete action
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    mysqli_query($con, "DELETE FROM study_materials WHERE material_id='$del_id'");
    set_flash("success", "Study material deleted successfully.");
    header("Location: view_materials.php");
    exit();
}

$search = "";
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = escape($con, $_GET['search']);
    $query = mysqli_query($con, "
        SELECT sm.*, s.`$subj_col` AS subject_name, s.subject_code
        FROM study_materials sm
        LEFT JOIN subjects s ON sm.subject_id = s.subject_id
        WHERE sm.title LIKE '%$search%'
           OR sm.description LIKE '%$search%'
           OR s.`$subj_col` LIKE '%$search%'
        ORDER BY sm.material_id DESC
    ");
} else {
    $query = mysqli_query($con, "
        SELECT sm.*, s.`$subj_col` AS subject_name, s.subject_code
        FROM study_materials sm
        LEFT JOIN subjects s ON sm.subject_id = s.subject_id
        ORDER BY sm.material_id DESC
    ");
}

include("../includes/header.php");
include("../includes/sidebar_faculty.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-folder-open"></i> Uploaded Course Materials</h3>
            <span class="badge badge-primary"><?php echo mysqli_num_rows($query); ?> Files</span>
        </div>
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <form method="GET" class="search-toolbar">
                <div class="search-input-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" class="form-control" placeholder="Search by title or subject..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
                <?php if (!empty($search)): ?>
                    <a href="view_materials.php" class="btn btn-outline" title="Clear Search"><i class="fa-solid fa-rotate-left"></i></a>
                <?php endif; ?>
            </form>
            <a href="upload_material.php" class="btn btn-primary" style="background:#0284c7;">
                <i class="fa-solid fa-cloud-arrow-up"></i> Upload Material
            </a>
        </div>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Material Title</th>
                        <th>Subject</th>
                        <th>Description</th>
                        <th>Uploaded Date</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($query && mysqli_num_rows($query) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><strong>#<?php echo $row['material_id']; ?></strong></td>
                                <td>
                                    <div style="font-weight:700; color:var(--text-primary); font-size:15px;">
                                        <i class="fa-solid fa-file-pdf" style="color:#ef4444; margin-right:6px;"></i>
                                        <?php echo htmlspecialchars($row['title']); ?>
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
                                    <div style="font-size:13px; color:#475569; max-width:280px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                        <?php echo htmlspecialchars($row['description'] ?? 'No description'); ?>
                                    </div>
                                </td>
                                <td><?php echo date('d M Y', strtotime($row['uploaded_at'])); ?></td>
                                <td style="text-align:right;">
                                    <div class="table-actions">
                                        <?php if (!empty($row['file_path']) && file_exists("../uploads/study_materials/" . $row['file_path'])): ?>
                                            <a href="../uploads/study_materials/<?php echo urlencode($row['file_path']); ?>" class="btn btn-outline btn-sm" download title="Download Material">
                                                <i class="fa-solid fa-download"></i> Download
                                            </a>
                                        <?php endif; ?>
                                        <a href="?delete=<?php echo $row['material_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this study material?')" title="Delete Material">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fa-solid fa-folder-open"></i>
                                <h4>No study materials found</h4>
                                <p><?php echo !empty($search) ? 'No materials matched your search query.' : 'Click "Upload Material" to share notes with students.'; ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
