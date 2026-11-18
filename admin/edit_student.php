<?php
/**
 * Edit Student
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Edit Student Profile";
$error = "";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_students.php");
    exit();
}

$student_id = (int)$_GET['id'];

// Fetch student & user data
$query = mysqli_query($con, "
    SELECT students.*, users.name, users.email
    FROM students
    JOIN users ON students.user_id = users.id
    WHERE students.student_id='$student_id'
    LIMIT 1
");

if (!$query || mysqli_num_rows($query) === 0) {
    set_flash("danger", "Student record not found.");
    header("Location: manage_students.php");
    exit();
}

$row = mysqli_fetch_assoc($query);

if (isset($_POST['update_student'])) {
    $name = escape($con, $_POST['name']);
    $email = escape($con, $_POST['email']);
    $roll_no = escape($con, $_POST['roll_no']);
    $course = escape($con, $_POST['course']);
    $semester = (int)$_POST['semester'];

    if (empty($name) || empty($email) || empty($roll_no)) {
        $error = "Please fill in all required fields.";
    } else {
        // Check duplicate email for another user
        $check = mysqli_query($con, "SELECT id FROM users WHERE email='$email' AND id != '{$row['user_id']}' LIMIT 1");
        if (mysqli_num_rows($check) > 0) {
            $error = "Another user is already using the email address '$email'.";
        } else {
            // Optional picture update
            $pic_sql_user = "";
            $pic_sql_student = "";
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
                        $pic_sql_student = ", profile_picture='$new_pic'";
                    }
                }
            }

            // Update users table
            mysqli_query($con, "
                UPDATE users 
                SET name='$name', email='$email' $pic_sql_user
                WHERE id='{$row['user_id']}'
            ");

            // Update students table
            mysqli_query($con, "
                UPDATE students
                SET roll_no='$roll_no', course='$course', semester='$semester' $pic_sql_student
                WHERE student_id='$student_id'
            ");

            set_flash("success", "Student record for '$name' updated successfully!");
            header("Location: manage_students.php");
            exit();
        }
    }
}

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fa-solid fa-user-pen"></i> Edit Student: <?php echo htmlspecialchars($row['name']); ?></h3>
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
                    <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($row['name']); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($row['email']); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Roll Number <span class="required">*</span></label>
                    <input type="text" name="roll_no" class="form-control" required value="<?php echo htmlspecialchars($row['roll_no']); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Course / Degree <span class="required">*</span></label>
                    <input type="text" name="course" class="form-control" required value="<?php echo htmlspecialchars($row['course']); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Semester <span class="required">*</span></label>
                    <select name="semester" class="form-control" required>
                        <?php for ($s = 1; $s <= 8; $s++): ?>
                            <option value="<?php echo $s; ?>" <?php echo ($row['semester'] == $s) ? 'selected' : ''; ?>>
                                Semester <?php echo $s; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Current Photo</label>
                    <div style="display:flex; align-items:center; gap:15px; margin-top:5px;">
                        <?php 
                        $pic = !empty($row['profile_picture']) && file_exists("../uploads/profile_pictures/" . $row['profile_picture'])
                            ? "../uploads/profile_pictures/" . $row['profile_picture']
                            : "../assets/images/default-user.png";
                        ?>
                        <img src="<?php echo $pic; ?>" alt="Student Photo" class="table-avatar" style="width:50px; height:50px;">
                        <input type="file" name="profile_picture" accept="image/*" class="form-control" style="flex:1;">
                    </div>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:25px;">
                <a href="manage_students.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="update_student" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
