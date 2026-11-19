<?php
/**
 * Add Faculty Staff
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Add Faculty Member";
$error = "";

if (isset($_POST['add_faculty'])) {
    $name = escape($con, $_POST['name']);
    $email = escape($con, $_POST['email']);
    $password = $_POST['password'];
    $department = escape($con, $_POST['department']);
    $designation = escape($con, $_POST['designation']);

    if (empty($name) || empty($email) || empty($password) || empty($department)) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Check duplicate email
        $check = mysqli_query($con, "SELECT id FROM users WHERE email='$email' LIMIT 1");
        if (safe_num_rows($check) > 0) {
            $error = "A user account with email '$email' already exists.";
        } else {
            // Profile photo upload
            $profile_picture = "";
            if (!empty($_FILES['profile_picture']['name'])) {
                $ext = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                if (in_array($ext, $allowed)) {
                    $profile_picture = time() . "_" . uniqid() . "." . $ext;
                    if (!is_dir("../uploads/profile_pictures")) {
                        mkdir("../uploads/profile_pictures", 0777, true);
                    }
                    move_uploaded_file($_FILES['profile_picture']['tmp_name'], "../uploads/profile_pictures/" . $profile_picture);
                } else {
                    $error = "Only JPG, PNG, and WEBP images are supported.";
                }
            }

            if (empty($error)) {
                $insert_user = mysqli_query($con, "
                    INSERT INTO users (name, email, password, role, profile_picture)
                    VALUES ('$name', '$email', '$password', 'faculty', '$profile_picture')
                ");

                if ($insert_user) {
                    $user_id = mysqli_insert_id($con);
                    mysqli_query($con, "
                        INSERT INTO faculty (user_id, department, designation, profile_picture)
                        VALUES ('$user_id', '$department', '$designation', '$profile_picture')
                    ");

                    set_flash("success", "Faculty member '$name' has been added successfully!");
                    header("Location: manage_faculty.php");
                    exit();
                } else {
                    $error = "Database Error: " . mysqli_error($con);
                }
            }
        }
    }
}

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fa-solid fa-user-tie"></i> Add New Faculty Member</h3>
        <a href="manage_faculty.php" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Back to Directory
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
                    <input type="text" name="name" class="form-control" placeholder="e.g. Dr. Rajesh Sharma" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="e.g. rajesh.sharma@college.edu" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Login Password <span class="required">*</span></label>
                    <input type="text" name="password" class="form-control" placeholder="Create temporary password" required value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : 'faculty123'; ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Department <span class="required">*</span></label>
                    <input type="text" name="department" class="form-control" placeholder="e.g. Computer Science & Engineering" required value="<?php echo isset($_POST['department']) ? htmlspecialchars($_POST['department']) : 'Computer Science'; ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Designation <span class="required">*</span></label>
                    <select name="designation" class="form-control" required>
                        <option value="Professor">Professor</option>
                        <option value="Associate Professor" selected>Associate Professor</option>
                        <option value="Assistant Professor">Assistant Professor</option>
                        <option value="Lecturer">Lecturer</option>
                        <option value="HOD">Head of Department (HOD)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Profile Photo (Optional)</label>
                    <input type="file" name="profile_picture" accept="image/*" class="form-control">
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:25px;">
                <a href="manage_faculty.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="add_faculty" class="btn btn-primary">
                    <i class="fa-solid fa-check"></i> Save Faculty
                </button>
            </div>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
