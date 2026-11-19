<?php
/**
 * Admin Settings
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Account & System Settings";
$user_id = $_SESSION['user_id'];
$error = "";

$query = mysqli_query($con, "SELECT * FROM users WHERE id='$user_id' LIMIT 1");
$user = ($query && safe_num_rows($query) > 0) ? safe_fetch_assoc($query) : [];

// Handle Profile Update
if (isset($_POST['update_profile'])) {
    $name = escape($con, $_POST['name']);
    $email = escape($con, $_POST['email']);

    if (empty($name) || empty($email)) {
        $error = "Name and email are required.";
    } else {
        $check = mysqli_query($con, "SELECT id FROM users WHERE email='$email' AND id != '$user_id' LIMIT 1");
        if (safe_num_rows($check) > 0) {
            $error = "Another account is already using the email address '$email'.";
        } else {
            mysqli_query($con, "UPDATE users SET name='$name', email='$email' WHERE id='$user_id'");
            $_SESSION['name'] = $name;
            set_flash("success", "Profile details updated successfully!");
            header("Location: settings.php");
            exit();
        }
    }
}

// Handle Password Change
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verify current password
    $cur_valid = (password_verify($current_password, $user['password']) || $current_password === $user['password']);

    if (!$cur_valid) {
        $error = "Your current password is incorrect.";
    } elseif (strlen($new_password) < 6) {
        $error = "New password must be at least 6 characters long.";
    } elseif ($new_password !== $confirm_password) {
        $error = "New password and confirmation password do not match.";
    } else {
        mysqli_query($con, "UPDATE users SET password='$new_password' WHERE id='$user_id'");
        set_flash("success", "Password updated successfully!");
        header("Location: settings.php");
        exit();
    }
}

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div style="max-width: 850px; margin: 0 auto;">
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <i class="fa-solid fa-circle-exclamation"></i>
            <div><?php echo htmlspecialchars($error); ?></div>
        </div>
    <?php endif; ?>

    <!-- Update Profile Card -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-user-gear"></i> Update Admin Profile</h3>
        </div>
        <div class="card-body">
            <form method="POST">
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

                <div style="display:flex; justify-content:flex-end;">
                    <button type="submit" name="update_profile" class="btn btn-primary">
                        <i class="fa-solid fa-save"></i> Save Profile Details
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password Card -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-shield-halved"></i> Security & Password Change</h3>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Current Password <span class="required">*</span></label>
                    <input type="password" name="current_password" class="form-control" placeholder="Enter current password" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">New Password <span class="required">*</span></label>
                        <input type="password" name="new_password" class="form-control" placeholder="At least 6 characters" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm New Password <span class="required">*</span></label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Repeat new password" required>
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end;">
                    <button type="submit" name="change_password" class="btn btn-primary">
                        <i class="fa-solid fa-key"></i> Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
