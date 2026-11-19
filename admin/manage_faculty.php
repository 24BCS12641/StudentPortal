<?php
/**
 * Manage Faculty
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Manage Faculty Staff";
$search = "";

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = escape($con, $_GET['search']);
    $query = mysqli_query($con, "
        SELECT faculty.*, users.name, users.email
        FROM faculty
        INNER JOIN users ON faculty.user_id = users.id
        WHERE users.name LIKE '%$search%'
           OR users.email LIKE '%$search%'
           OR faculty.department LIKE '%$search%'
           OR faculty.designation LIKE '%$search%'
        ORDER BY faculty.faculty_id DESC
    ");
} else {
    $query = mysqli_query($con, "
        SELECT faculty.*, users.name, users.email
        FROM faculty
        INNER JOIN users ON faculty.user_id = users.id
        ORDER BY faculty.faculty_id DESC
    ");
}

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-chalkboard-user"></i> Faculty Members Directory</h3>
            <span class="badge badge-primary"><?php echo safe_num_rows($query); ?> Faculty</span>
        </div>
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <form method="GET" class="search-toolbar">
                <div class="search-input-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" class="form-control" placeholder="Search by name, department, designation..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
                <?php if (!empty($search)): ?>
                    <a href="manage_faculty.php" class="btn btn-outline" title="Clear Search"><i class="fa-solid fa-rotate-left"></i></a>
                <?php endif; ?>
            </form>
            <a href="add_faculty.php" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Add Faculty
            </a>
        </div>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Faculty Name & Email</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($query && safe_num_rows($query) > 0): ?>
                        <?php while ($row = safe_fetch_assoc($query)): 
                            $pic = !empty($row['profile_picture']) && file_exists("../uploads/profile_pictures/" . $row['profile_picture'])
                                ? "../uploads/profile_pictures/" . $row['profile_picture']
                                : "../assets/images/default-user.png";
                        ?>
                            <tr>
                                <td><strong>#<?php echo $row['faculty_id']; ?></strong></td>
                                <td>
                                    <div class="table-user-cell">
                                        <img src="<?php echo $pic; ?>" alt="Faculty Photo" class="table-avatar">
                                        <div>
                                            <div class="user-name"><?php echo htmlspecialchars($row['name']); ?></div>
                                            <div class="user-subtext"><?php echo htmlspecialchars($row['email']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-info"><?php echo htmlspecialchars($row['department']); ?></span></td>
                                <td><strong><?php echo htmlspecialchars($row['designation']); ?></strong></td>
                                <td style="text-align:right;">
                                    <div class="table-actions">
                                        <a href="edit_faculty.php?id=<?php echo $row['faculty_id']; ?>" class="btn btn-outline btn-sm" title="Edit Faculty">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>
                                        <a href="delete_faculty.php?id=<?php echo $row['faculty_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete faculty <?php echo htmlspecialchars(addslashes($row['name'])); ?>?')" title="Delete Faculty">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="empty-state">
                                <i class="fa-solid fa-chalkboard-user"></i>
                                <h4>No faculty members found</h4>
                                <p><?php echo !empty($search) ? 'No faculty matched your search query.' : 'There are currently no faculty members registered.'; ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
