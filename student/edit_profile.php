<?php
/**
 * Edit Student Profile
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('student');

$page_title = "Edit My Profile";
$user_id = $_SESSION['user_id'];
$error = "";

$query = mysqli_query($con, "
    SELECT u.*, s.student_id, s.roll_no, s.course, s.semester, s.profile_picture AS s_pic
    FROM users u
    JOIN students s ON u.id = s.user_id
    WHERE u.id='$user_id'
    LIMIT 1
");

$student = mysqli_fetch_assoc($query);

if (isset($_POST['update_profile'])) {
    $name = escape($con, $_POST['name']);
    $email = escape($con, $_POST['email']);
    $course = escape($con, $_POST['course']);
    $semester = (int)$_POST['semester'];

    if (empty($name) || empty($email) || empty($course)) {
        $error = "Name, email, and course are required fields.";
    } else {
        // Check duplicate email
        $check = mysqli_query($con, "SELECT id FROM users WHERE email='$email' AND id != '$user_id' LIMIT 1");
        if (mysqli_num_rows($check) > 0) {
            $error = "Another account is already using the email address '$email'.";
        } else {
            $pic_sql_u = "";
            $pic_sql_s = "";
            if (!empty($_FILES['profile_picture']['name'])) {
                $ext = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                if (in_array($ext, $allowed)) {
                    $new_pic = time() . "_" . uniqid() . "." . $ext;
                    if (!is_dir("../uploads/profile_pictures")) {
                        mkdir("../uploads/profile_pictures", 0777, true);
                    }
                    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], "../uploads/profile_pictures/" . $new_pic)) {
                        $pic_sql_u = ", profile_picture='$new_pic'";
                        $pic_sql_s = ", profile_picture='$new_pic'";
                    }
                } else {
                    $error = "Only JPG, PNG, and WEBP image files are allowed.";
                }
            }

            if (empty($error)) {
                mysqli_query($con, "UPDATE users SET name='$name', email='$email' $pic_sql_u WHERE id='$user_id'");
                mysqli_query($con, "UPDATE students SET course='$course', semester='$semester' $pic_sql_s WHERE user_id='$user_id'");
                $_SESSION['name'] = $name;

                set_flash("success", "Profile updated successfully!");
                header("Location: profile.php");
                exit();
            }
        }
    }
}

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
                    <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($student['name']); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($student['email']); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Roll Number</label>
                    <input type="text" class="form-control" disabled value="<?php echo htmlspecialchars($student['roll_no']); ?>" style="background:#f1f5f9; cursor:not-allowed;">
                    <span style="font-size:12px; color:var(--text-muted);">Roll numbers are managed by administration.</span>
                </div>

                <div class="form-group">
                    <label class="form-label">Course / Program <span class="required">*</span></label>
                    <input type="text" name="course" class="form-control" required value="<?php echo htmlspecialchars($student['course']); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Semester <span class="required">*</span></label>
                    <select name="semester" class="form-control" required>
                        <?php for ($s = 1; $s <= 8; $s++): ?>
                            <option value="<?php echo $s; ?>" <?php echo ($student['semester'] == $s) ? 'selected' : ''; ?>>
                                Semester <?php echo $s; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Profile Photo</label>
                    <div style="display:flex; align-items:center; gap:12px;">
                        <img src="<?php echo $pic; ?>" alt="Student Photo" class="table-avatar" style="width:45px; height:45px;">
                        <input type="file" name="profile_picture" accept="image/*" class="form-control" style="flex:1;">
                    </div>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:25px;">
                <a href="profile.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="update_profile" class="btn btn-primary" style="background:#2563eb;">
                    <i class="fa-solid fa-save"></i> Save Profile Details
                </button>
            </div>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
