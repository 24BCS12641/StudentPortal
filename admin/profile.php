<?php
/**
 * Admin Profile
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Admin Profile";
$user_id = $_SESSION['user_id'];
$error = "";

$query = mysqli_query($con, "SELECT * FROM users WHERE id='$user_id' LIMIT 1");
$user = ($query && safe_num_rows($query) > 0) ? safe_fetch_assoc($query) : [];

if (isset($_POST['update_profile'])) {
    $name = escape($con, $_POST['name']);
    $email = escape($con, $_POST['email']);

    if (empty($name) || empty($email)) {
        $error = "Name and email are required.";
    } else {
        $pic_sql = "";
        if (!empty($_FILES['profile_picture']['name'])) {
            $ext = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($ext, $allowed)) {
                $new_pic = time() . "_" . uniqid() . "." . $ext;
                if (!is_dir("../uploads/profile_pictures")) {
                    mkdir("../uploads/profile_pictures", 0777, true);
                }
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], "../uploads/profile_pictures/" . $new_pic)) {
                    $pic_sql = ", profile_picture='$new_pic'";
                }
            } else {
                $error = "Only JPG, PNG, and WEBP images are supported.";
            }
        }

        if (empty($error)) {
            mysqli_query($con, "UPDATE users SET name='$name', email='$email' $pic_sql WHERE id='$user_id'");
            $_SESSION['name'] = $name;
            set_flash("success", "Profile updated successfully!");
            header("Location: profile.php");
            exit();
        }
    }
}

$pic = !empty($user['profile_picture']) && file_exists("../uploads/profile_pictures/" . $user['profile_picture'])
    ? "../uploads/profile_pictures/" . $user['profile_picture']
    : "../assets/images/default-user.png";

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card" style="max-width: 750px; margin: 0 auto;">
    <div class="profile-hero">
        <img src="<?php echo $pic; ?>" alt="Admin Profile" class="profile-avatar-large">
        <h2><?php echo htmlspecialchars($user['name']); ?></h2>
        <span class="badge badge-primary"><i class="fa-solid fa-shield-halved"></i> System Administrator</span>
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
                    <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($user['name']); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($user['email']); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Update Profile Picture</label>
                <input type="file" name="profile_picture" accept="image/*" class="form-control">
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:20px;">
                <button type="submit" name="update_profile" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Save Profile Details
                </button>
            </div>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
