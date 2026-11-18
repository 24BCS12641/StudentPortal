<?php
/**
 * Add Student
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Add New Student";
$error = "";

if (isset($_POST['add_student'])) {
    $name = escape($con, $_POST['name']);
    $email = escape($con, $_POST['email']);
    $password = $_POST['password'];
    $roll_no = escape($con, $_POST['roll_no']);
    $course = escape($con, $_POST['course']);
    $semester = (int)$_POST['semester'];

    if (empty($name) || empty($email) || empty($password) || empty($roll_no)) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Check duplicate email
        $check = mysqli_query($con, "SELECT id FROM users WHERE email='$email' LIMIT 1");
        if (mysqli_num_rows($check) > 0) {
            $error = "A user account with email '$email' already exists.";
        } else {
            // Handle profile picture upload
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
                    $error = "Only JPG, PNG, and WEBP image formats are supported for profile photos.";
                }
            }

            if (empty($error)) {
                $insert_user = mysqli_query($con, "
                    INSERT INTO users (name, email, password, role, profile_picture)
                    VALUES ('$name', '$email', '$password', 'student', '$profile_picture')
                ");

                if ($insert_user) {
                    $user_id = mysqli_insert_id($con);
                    mysqli_query($con, "
                        INSERT INTO students (user_id, roll_no, course, semester, profile_picture)
                        VALUES ('$user_id', '$roll_no', '$course', '$semester', '$profile_picture')
                    ");

                    set_flash("success", "Student '$name' has been successfully enrolled!");
                    header("Location: manage_students.php");
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
        <h3><i class="fa-solid fa-user-plus"></i> Enroll New Student</h3>
        <a href="manage_students.php" class="btn btn-outline btn-sm">
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
                    <input type="text" name="name" class="form-control" placeholder="e.g. Sunny Kumar" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="e.g. sunny@student.edu" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Login Password <span class="required">*</span></label>
                    <input type="text" name="password" class="form-control" placeholder="Create temporary or permanent password" required value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : 'student123'; ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Roll Number / Enrollment No. <span class="required">*</span></label>
                    <input type="text" name="roll_no" class="form-control" placeholder="e.g. 24BCS12641" required value="<?php echo isset($_POST['roll_no']) ? htmlspecialchars($_POST['roll_no']) : ''; ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Course / Degree Program <span class="required">*</span></label>
                    <input type="text" name="course" class="form-control" placeholder="e.g. BCA, B.Tech CSE" required value="<?php echo isset($_POST['course']) ? htmlspecialchars($_POST['course']) : 'BCA'; ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Semester <span class="required">*</span></label>
                    <select name="semester" class="form-control" required>
                        <?php for ($s = 1; $s <= 8; $s++): ?>
                            <option value="<?php echo $s; ?>" <?php echo (isset($_POST['semester']) && $_POST['semester'] == $s) ? 'selected' : ($s == 1 ? 'selected' : ''); ?>>
                                Semester <?php echo $s; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Profile Photo (Optional)</label>
                <div class="file-upload-box">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <p><strong>Click to choose image file</strong> or drag & drop</p>
                    <span style="font-size:12px; color:#94a3b8;">PNG, JPG, or WEBP up to 5MB</span>
                    <input type="file" name="profile_picture" accept="image/*">
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:25px;">
                <a href="manage_students.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="add_student" class="btn btn-primary">
                    <i class="fa-solid fa-check"></i> Enroll Student
                </button>
            </div>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
