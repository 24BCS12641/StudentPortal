<?php
/**
 * Manage Students
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Manage Students";
$search = "";

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = escape($con, $_GET['search']);
    $query = mysqli_query($con, "
        SELECT students.*, users.name, users.email
        FROM students
        INNER JOIN users ON students.user_id = users.id
        WHERE users.name LIKE '%$search%'
           OR users.email LIKE '%$search%'
           OR students.roll_no LIKE '%$search%'
           OR students.course LIKE '%$search%'
        ORDER BY students.student_id DESC
    ");
} else {
    $query = mysqli_query($con, "
        SELECT students.*, users.name, users.email
        FROM students
        INNER JOIN users ON students.user_id = users.id
        ORDER BY students.student_id DESC
    ");
}

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-user-graduate"></i> Enrolled Students Directory</h3>
            <span class="badge badge-primary"><?php echo mysqli_num_rows($query); ?> Students</span>
        </div>
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <form method="GET" class="search-toolbar">
                <div class="search-input-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" class="form-control" placeholder="Search by name, roll no, email..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
                <?php if (!empty($search)): ?>
                    <a href="manage_students.php" class="btn btn-outline" title="Clear Search"><i class="fa-solid fa-rotate-left"></i></a>
                <?php endif; ?>
            </form>
            <a href="add_student.php" class="btn btn-primary">
                <i class="fa-solid fa-user-plus"></i> Add Student
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
                        <th>Course</th>
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
                                    <span class="badge badge-dark"><?php echo htmlspecialchars($row['roll_no']); ?></span>
                                </td>
                                <td><strong><?php echo htmlspecialchars($row['course']); ?></strong></td>
                                <td><span class="badge badge-info">Semester <?php echo htmlspecialchars($row['semester']); ?></span></td>
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
                            <td colspan="6" class="empty-state">
                                <i class="fa-solid fa-user-slash"></i>
                                <h4>No students found</h4>
                                <p><?php echo !empty($search) ? 'No students matched your search query.' : 'There are currently no students registered.'; ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
