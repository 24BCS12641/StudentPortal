<?php
/**
 * Student Change Password
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('student');

$page_title = "Change Password";
$user_id = $_SESSION['user_id'];
$error = "";

$query = mysqli_query($con, "SELECT password FROM users WHERE id='$user_id' LIMIT 1");
$user = ($query && safe_num_rows($query) > 0) ? safe_fetch_assoc($query) : [];

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
        $hashed_pwd = password_hash($new_password, PASSWORD_DEFAULT);
        mysqli_query($con, "UPDATE users SET password='$new_password' WHERE id='$user_id'");
        set_flash("success", "Password updated successfully!");
        header("Location: profile.php");
        exit();
    }
}

include("../includes/header.php");
include("../includes/sidebar_student.php");
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
                <div style="position: relative;">
                    <input type="password" name="current_password" id="curPwd" class="form-control" placeholder="Enter current password" required style="padding-right: 40px;">
                    <button type="button" onclick="togglePwd('curPwd', this)" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); border: none; background: transparent; color: var(--text-muted); cursor: pointer;">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">New Password <span class="required">*</span></label>
                <div style="position: relative;">
                    <input type="password" name="new_password" id="newPwd" class="form-control" placeholder="Minimum 6 characters" required style="padding-right: 40px;">
                    <button type="button" onclick="togglePwd('newPwd', this)" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); border: none; background: transparent; color: var(--text-muted); cursor: pointer;">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Confirm New Password <span class="required">*</span></label>
                <div style="position: relative;">
                    <input type="password" name="confirm_password" id="confPwd" class="form-control" placeholder="Re-type new password" required style="padding-right: 40px;">
                    <button type="button" onclick="togglePwd('confPwd', this)" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); border: none; background: transparent; color: var(--text-muted); cursor: pointer;">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:25px;">
                <a href="profile.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="change_password" class="btn btn-primary" style="background:#2563eb;">
                    <i class="fa-solid fa-save"></i> Update Password
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePwd(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

<?php include("../includes/footer.php"); ?>
