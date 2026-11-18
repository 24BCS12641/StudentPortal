<?php
/**
 * Campus Notices (Student)
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('student');

$page_title = "Campus Announcements & Notices";

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
include("../includes/sidebar_student.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-bullhorn"></i> Official Campus Circulars & Announcements</h3>
            <span class="badge badge-primary"><?php echo mysqli_num_rows($query); ?> Notices</span>
        </div>
        <form method="GET" class="search-toolbar">
            <div class="search-input-group">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" class="form-control" placeholder="Search notices by keyword..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
            <?php if (!empty($search)): ?>
                <a href="view_notices.php" class="btn btn-outline" title="Clear Search"><i class="fa-solid fa-rotate-left"></i></a>
            <?php endif; ?>
        </form>
    </div>
    <div class="card-body">
        <div class="notice-feed">
            <?php if ($query && mysqli_num_rows($query) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <div class="notice-card">
                        <div class="notice-header">
                            <div>
                                <div class="notice-title"><?php echo htmlspecialchars($row['title']); ?></div>
                                <div class="notice-meta" style="margin-top:4px;">
                                    <i class="fa-solid fa-user-circle"></i> Posted by: <strong><?php echo htmlspecialchars($row['posted_by'] ?? 'Administration'); ?></strong> &bull;
                                    <i class="fa-solid fa-calendar-day"></i> <?php echo isset($row['posted_on']) ? date('d M Y, h:i A', strtotime($row['posted_on'])) : 'Recent'; ?>
                                </div>
                            </div>
                            <span class="badge badge-primary">Circular #<?php echo $row['notice_id']; ?></span>
                        </div>
                        <div class="notice-body" style="margin-top:14px; font-size:15px; color:#334155;">
                            <?php echo nl2br(htmlspecialchars($row['description'])); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fa-solid fa-bell-slash"></i>
                    <h4>No notices found</h4>
                    <p><?php echo !empty($search) ? 'No announcements matched your search.' : 'There are currently no active announcements.'; ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
