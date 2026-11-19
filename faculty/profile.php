<?php
/**
 * Faculty Profile
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('faculty');

$page_title = "Faculty Profile";
$user_id = $_SESSION['user_id'];

$query = mysqli_query($con, "
    SELECT u.*, f.department, f.designation, f.profile_picture AS f_pic
    FROM users u
    JOIN faculty f ON u.id = f.user_id
    WHERE u.id='$user_id'
    LIMIT 1
");

$faculty = ($query && safe_num_rows($query) > 0) ? safe_fetch_assoc($query) : [];

$pic = !empty($faculty['f_pic']) && file_exists("../uploads/profile_pictures/" . $faculty['f_pic'])
    ? "../uploads/profile_pictures/" . $faculty['f_pic']
    : (!empty($faculty['profile_picture']) && file_exists("../uploads/profile_pictures/" . $faculty['profile_picture'])
        ? "../uploads/profile_pictures/" . $faculty['profile_picture']
        : "../assets/images/default-user.png");

include("../includes/header.php");
include("../includes/sidebar_faculty.php");
include("../includes/navbar.php");
?>

<div class="card" style="max-width: 750px; margin: 0 auto;">
    <div class="profile-hero">
        <img src="<?php echo $pic; ?>" alt="Faculty Profile" class="profile-avatar-large">
        <h2><?php echo htmlspecialchars($faculty['name']); ?></h2>
        <span class="badge badge-primary"><i class="fa-solid fa-chalkboard-user"></i> <?php echo htmlspecialchars($faculty['designation']); ?></span>
    </div>

    <div class="card-body">
        <table class="profile-details-table">
            <tr>
                <td>Full Name</td>
                <td><strong><?php echo htmlspecialchars($faculty['name']); ?></strong></td>
            </tr>
            <tr>
                <td>Email Address</td>
                <td><?php echo htmlspecialchars($faculty['email']); ?></td>
            </tr>
            <tr>
                <td>Department</td>
                <td><span class="badge badge-info"><?php echo htmlspecialchars($faculty['department']); ?></span></td>
            </tr>
            <tr>
                <td>Academic Designation</td>
                <td><?php echo htmlspecialchars($faculty['designation']); ?></td>
            </tr>
            <tr>
                <td>Role Permission</td>
                <td><span class="badge badge-success">Faculty Member</span></td>
            </tr>
        </table>

        <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:25px;">
            <a href="edit_profile.php" class="btn btn-primary" style="background:#0284c7;">
                <i class="fa-solid fa-pen-to-square"></i> Edit Profile
            </a>
            <a href="change_password.php" class="btn btn-outline">
                <i class="fa-solid fa-key"></i> Change Password
            </a>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
