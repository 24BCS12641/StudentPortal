<?php
/**
 * Manage Campus Notices (Admin)
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Manage Campus Notices";
$search = "";

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = escape($con, $_GET['search']);
    $query = mysqli_query($con, "
        SELECT * FROM notices
        WHERE title LIKE '%$search%' OR description LIKE '%$search%'
        ORDER BY notice_id DESC
    ");
} else {
    $query = mysqli_query($con, "SELECT * FROM notices ORDER BY notice_id DESC");
}

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-bullhorn"></i> Campus Announcements & Circulars</h3>
            <span class="badge badge-primary"><?php echo mysqli_num_rows($query); ?> Notices</span>
        </div>
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <form method="GET" class="search-toolbar">
                <div class="search-input-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" class="form-control" placeholder="Search notices by keyword..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
                <?php if (!empty($search)): ?>
                    <a href="manage_notices.php" class="btn btn-outline" title="Clear Search"><i class="fa-solid fa-rotate-left"></i></a>
                <?php endif; ?>
            </form>
            <a href="add_notice.php" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Post Notice
            </a>
        </div>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Notice Title</th>
                        <th>Description Preview</th>
                        <th>Posted By</th>
                        <th>Date Published</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($query && mysqli_num_rows($query) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><strong>#<?php echo $row['notice_id']; ?></strong></td>
                                <td>
                                    <div style="font-weight:700; color:var(--text-primary); font-size:15px;">
                                        <?php echo htmlspecialchars($row['title']); ?>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size:13px; color:#475569; max-width:350px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                        <?php echo htmlspecialchars($row['description']); ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-primary">
                                        <i class="fa-solid fa-user-check"></i> <?php echo htmlspecialchars($row['posted_by'] ?? 'Admin'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="font-size:13px; color:var(--text-secondary);">
                                        <?php echo isset($row['posted_on']) ? date('d M Y, h:i A', strtotime($row['posted_on'])) : 'Recent'; ?>
                                    </span>
                                </td>
                                <td style="text-align:right;">
                                    <div class="table-actions">
                                        <a href="edit_notice.php?id=<?php echo $row['notice_id']; ?>" class="btn btn-outline btn-sm" title="Edit Notice">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>
                                        <a href="delete_notice.php?id=<?php echo $row['notice_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this notice?')" title="Delete Notice">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fa-solid fa-bell-slash"></i>
                                <h4>No campus notices found</h4>
                                <p><?php echo !empty($search) ? 'No notices matched your search query.' : 'There are currently no active announcements.'; ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
