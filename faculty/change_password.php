<?php
/**
 * Faculty Change Password
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('faculty');

$page_title = "Change Password";
$user_id = $_SESSION['user_id'];
$error = "";

$query = mysqli_query($con, "SELECT password FROM users WHERE id='$user_id' LIMIT 1");
$user = ($query && mysqli_num_rows($query) > 0) ? mysqli_fetch_assoc($query) : [];

if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $cur_valid = (password_verify($current_password, $user['password']) || $current_password === $user['password']);

    if (!$cur_valid) {
        $error = "Current password is incorrect.";
    } elseif (strlen($new_password) < 6) {
        $error = "New password must be at least 6 characters long.";
    } elseif ($new_password !== $confirm_password) {
        $error = "New password and confirmation password do not match.";
    } else {
        mysqli_query($con, "UPDATE users SET password='$new_password' WHERE id='$user_id'");
        set_flash("success", "Password changed successfully!");
        header("Location: profile.php");
        exit();
    }
}

include("../includes/header.php");
include("../includes/sidebar_faculty.php");
include("../includes/navbar.php");
?>

<div class="card" style="max-width: 650px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fa-solid fa-key"></i> Change Password</h3>
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

        <form method="POST">
            <div class="form-group">
                <label class="form-label">Current Password <span class="required">*</span></label>
                <input type="password" name="current_password" class="form-control" placeholder="Enter current password" required>
            </div>

            <div class="form-group">
                <label class="form-label">New Password <span class="required">*</span></label>
                <input type="password" name="new_password" class="form-control" placeholder="Minimum 6 characters" required>
            </div>

            <div class="form-group">
                <label class="form-label">Confirm New Password <span class="required">*</span></label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Re-type new password" required>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:25px;">
                <a href="profile.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="change_password" class="btn btn-primary" style="background:#0284c7;">
                    <i class="fa-solid fa-save"></i> Update Password
                </button>
            </div>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
