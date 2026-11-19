<?php
/**
 * Edit Faculty Profile
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('faculty');

$page_title = "Edit Profile";
$user_id = $_SESSION['user_id'];
$error = "";

$query = mysqli_query($con, "
    SELECT u.*, f.department, f.designation, f.profile_picture AS f_pic
    FROM users u
    JOIN faculty f ON u.id = f.user_id
    WHERE u.id='$user_id'
    LIMIT 1
");

$faculty = ($query && safe_num_rows($query) > 0) ? safe_fetch_assoc($query) : [];

if (isset($_POST['update_profile'])) {
    $name = escape($con, $_POST['name']);
    $email = escape($con, $_POST['email']);
    $department = escape($con, $_POST['department']);
    $designation = escape($con, $_POST['designation']);

    if (empty($name) || empty($email) || empty($department)) {
        $error = "Name, email, and department are required.";
    } else {
        $check = mysqli_query($con, "SELECT id FROM users WHERE email='$email' AND id != '$user_id' LIMIT 1");
        if (safe_num_rows($check) > 0) {
            $error = "Another account is already using the email address '$email'.";
        } else {
            $pic_sql_user = "";
            $pic_sql_faculty = "";
            if (!empty($_FILES['profile_picture']['name'])) {
                $ext = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                if (in_array($ext, $allowed)) {
                    $new_pic = time() . "_" . uniqid() . "." . $ext;
                    if (!is_dir("../uploads/profile_pictures")) {
                        mkdir("../uploads/profile_pictures", 0777, true);
                    }
                    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], "../uploads/profile_pictures/" . $new_pic)) {
                        $pic_sql_user = ", profile_picture='$new_pic'";
                        $pic_sql_faculty = ", profile_picture='$new_pic'";
                    }
                } else {
                    $error = "Only JPG, PNG, and WEBP image files are allowed.";
                }
            }

            if (empty($error)) {
                mysqli_query($con, "UPDATE users SET name='$name', email='$email' $pic_sql_user WHERE id='$user_id'");
                mysqli_query($con, "UPDATE faculty SET department='$department', designation='$designation' $pic_sql_faculty WHERE user_id='$user_id'");
                $_SESSION['name'] = $name;

                set_flash("success", "Profile updated successfully!");
                header("Location: profile.php");
                exit();
            }
        }
    }
}

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
    <div class="card-header">
        <h3><i class="fa-solid fa-user-pen"></i> Edit Profile</h3>
        <a href="profile.php" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Back to Profile
        </a>
    </div>
    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div><?php echo htmlspecialchars($error); ?></div>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Full Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($faculty['name']); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($faculty['email']); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Department <span class="required">*</span></label>
                    <input type="text" name="department" class="form-control" required value="<?php echo htmlspecialchars($faculty['department']); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Designation <span class="required">*</span></label>
                    <input type="text" name="designation" class="form-control" required value="<?php echo htmlspecialchars($faculty['designation']); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Update Profile Photo</label>
                <div style="display:flex; align-items:center; gap:15px;">
                    <img src="<?php echo $pic; ?>" alt="Current Avatar" class="table-avatar" style="width:50px; height:50px;">
                    <input type="file" name="profile_picture" accept="image/*" class="form-control" style="flex:1;">
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:25px;">
                <a href="profile.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="update_profile" class="btn btn-primary" style="background:#0284c7;">
                    <i class="fa-solid fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
