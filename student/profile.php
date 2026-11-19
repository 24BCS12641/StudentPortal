<?php
/**
 * Student Profile
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('student');

$page_title = "My Student Profile";
$user_id = $_SESSION['user_id'];

$query = mysqli_query($con, "
    SELECT u.*, s.student_id, s.roll_no, s.course, s.semester, s.profile_picture AS s_pic
    FROM users u
    JOIN students s ON u.id = s.user_id
    WHERE u.id='$user_id'
    LIMIT 1
");

$student = ($query && safe_num_rows($query) > 0) ? safe_fetch_assoc($query) : [];

$pic = !empty($student['s_pic']) && file_exists("../uploads/profile_pictures/" . $student['s_pic'])
    ? "../uploads/profile_pictures/" . $student['s_pic']
    : (!empty($student['profile_picture']) && file_exists("../uploads/profile_pictures/" . $student['profile_picture'])
        ? "../uploads/profile_pictures/" . $student['profile_picture']
        : "../assets/images/default-user.png");

include("../includes/header.php");
include("../includes/sidebar_student.php");
include("../includes/navbar.php");
?>

<div class="card" style="max-width: 750px; margin: 0 auto;">
    <div class="profile-hero">
        <img src="<?php echo $pic; ?>" alt="Student Photo" class="profile-avatar-large">
        <h2><?php echo htmlspecialchars($student['name']); ?></h2>
        <span class="badge badge-primary"><i class="fa-solid fa-graduation-cap"></i> <?php echo htmlspecialchars($student['roll_no']); ?></span>
    </div>

    <div class="card-body">
        <table class="profile-details-table">
            <tr>
                <td>Full Name</td>
                <td><strong><?php echo htmlspecialchars($student['name']); ?></strong></td>
            </tr>
            <tr>
                <td>Email Address</td>
                <td><?php echo htmlspecialchars($student['email']); ?></td>
            </tr>
            <tr>
                <td>Roll Number</td>
                <td><span class="badge badge-dark"><?php echo htmlspecialchars($student['roll_no']); ?></span></td>
            </tr>
            <tr>
                <td>Enrolled Course / Degree</td>
                <td><strong><?php echo htmlspecialchars($student['course']); ?></strong></td>
            </tr>
            <tr>
                <td>Current Semester</td>
                <td><span class="badge badge-info">Semester <?php echo htmlspecialchars($student['semester']); ?></span></td>
            </tr>
            <tr>
                <td>Student ID</td>
                <td>#<?php echo $student['student_id']; ?></td>
            </tr>
        </table>

        <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:25px;">
            <a href="edit_profile.php" class="btn btn-primary" style="background:#2563eb;">
                <i class="fa-solid fa-user-pen"></i> Edit Profile
            </a>
            <a href="upload_profile_picture.php" class="btn btn-secondary">
                <i class="fa-solid fa-camera"></i> Change Photo
            </a>
            <a href="change_password.php" class="btn btn-outline">
                <i class="fa-solid fa-key"></i> Change Password
            </a>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
