<?php
/**
 * Edit Student - Department & Academic Course Update
 * Apex Imperial University Portal
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

// Fetch departments & courses
$depts = [];
$d_res = mysqli_query($con, "SELECT department_id, name, code, icon FROM departments ORDER BY department_id ASC");
while ($d = mysqli_fetch_assoc($d_res)) {
    $depts[] = $d;
}

$courses = [];
$c_res = mysqli_query($con, "SELECT course_id, department_id, name, code FROM courses ORDER BY name ASC");
while ($c = mysqli_fetch_assoc($c_res)) {
    $courses[] = $c;
}

if (isset($_POST['update_student'])) {
    $name = escape($con, trim($_POST['name']));
    $email = escape($con, trim($_POST['email']));
    $roll_no = escape($con, trim($_POST['roll_no']));
    $department_id = (int)$_POST['department_id'];
    $course_id = (int)$_POST['course_id'];
    $semester = (int)$_POST['semester'];

    // Get course code
    $course_code = $row['course'] ?? 'BCA';
    foreach ($courses as $crs) {
        if ($crs['course_id'] == $course_id) {
            $course_code = $crs['code'];
            break;
        }
    }

    if (empty($name) || empty($email) || empty($roll_no) || $department_id <= 0) {
        $error = "Please fill in all required fields and select an academic department.";
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
                SET roll_no='$roll_no', department_id='$department_id', course_id='$course_id', course='$course_code', semester='$semester' $pic_sql_student
                WHERE student_id='$student_id'
            ");

            set_flash("success", "Student record for '$name' ($roll_no) updated successfully!");
            header("Location: manage_students.php?dept=" . $department_id);
            exit();
        }
    }
}

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card" style="max-width: 820px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fa-solid fa-user-pen text-primary"></i> Edit Student Profile &bull; Department Re-assignment</h3>
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
                    <label class="form-label">Roll Number / Enrollment No. <span class="required">*</span></label>
                    <input type="text" name="roll_no" class="form-control" required value="<?php echo htmlspecialchars($row['roll_no']); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Enrolled Semester <span class="required">*</span></label>
                    <select name="semester" class="form-control" required>
                        <?php for ($s = 1; $s <= 8; $s++): ?>
                            <option value="<?php echo $s; ?>" <?php echo ($row['semester'] == $s) ? 'selected' : ''; ?>>
                                Semester <?php echo $s; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Academic Department <span class="required">*</span></label>
                    <select name="department_id" id="deptSelect" class="form-control" required onchange="filterCourses()">
                        <option value="">-- Choose Department --</option>
                        <?php foreach ($depts as $d): ?>
                            <option value="<?php echo $d['department_id']; ?>" <?php echo ($row['department_id'] == $d['department_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($d['name']); ?> (<?php echo $d['code']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Degree Course Program <span class="required">*</span></label>
                    <select name="course_id" id="courseSelect" class="form-control" required>
                        <option value="">-- Select Degree Course --</option>
                        <?php foreach ($courses as $c): ?>
                            <option value="<?php echo $c['course_id']; ?>" data-dept="<?php echo $c['department_id']; ?>" <?php echo ($row['course_id'] == $c['course_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c['name']); ?> (<?php echo $c['code']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Current Photo</label>
                <div style="display:flex; align-items:center; gap:16px; margin-bottom:12px;">
                    <?php 
                    $curr_pic = !empty($row['profile_picture']) && file_exists("../uploads/profile_pictures/" . $row['profile_picture'])
                        ? "../uploads/profile_pictures/" . $row['profile_picture']
                        : "../assets/images/default-user.png";
                    ?>
                    <img src="<?php echo $curr_pic; ?>" alt="Current Photo" style="width:55px; height:55px; border-radius:50%; object-fit:cover; border:2px solid #e2e8f0;">
                    <span style="font-size:13px; color:var(--text-muted);">Leave the file upload blank if you want to keep the current photo.</span>
                </div>
                <div class="file-upload-box">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <p><strong>Upload new photo</strong> (PNG, JPG, WEBP)</p>
                    <input type="file" name="profile_picture" accept="image/*">
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

<script>
function filterCourses() {
    var deptId = document.getElementById('deptSelect').value;
    var courseSelect = document.getElementById('courseSelect');
    var options = courseSelect.querySelectorAll('option');

    for (var i = 1; i < options.length; i++) {
        var opt = options[i];
        if (!deptId || opt.getAttribute('data-dept') === deptId) {
            opt.style.display = '';
        } else {
            opt.style.display = 'none';
        }
    }
}
</script>

<?php include("../includes/footer.php"); ?>
