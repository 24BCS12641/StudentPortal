<?php
/**
 * Add Student - With Department & Degree Course Assignment
 * Apex Imperial University Portal
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Enroll New Student";
$error = "";

// Fetch departments & courses
$depts = [];
$d_res = mysqli_query($con, "SELECT department_id, name, code, icon FROM departments ORDER BY department_id ASC");
while ($d = safe_fetch_assoc($d_res)) {
    $depts[] = $d;
}

$courses = [];
$c_res = mysqli_query($con, "SELECT course_id, department_id, name, code FROM courses ORDER BY name ASC");
while ($c = safe_fetch_assoc($c_res)) {
    $courses[] = $c;
}

if (isset($_POST['add_student'])) {
    $name = escape($con, trim($_POST['name']));
    $email = escape($con, trim($_POST['email']));
    $password = $_POST['password'];
    $roll_no = escape($con, trim($_POST['roll_no']));
    $department_id = (int)$_POST['department_id'];
    $course_id = (int)$_POST['course_id'];
    $semester = (int)$_POST['semester'];

    // Get course name/code
    $course_code = 'BCA';
    foreach ($courses as $crs) {
        if ($crs['course_id'] == $course_id) {
            $course_code = $crs['code'];
            break;
        }
    }

    if (empty($name) || empty($email) || empty($password) || empty($roll_no) || $department_id <= 0) {
        $error = "Please fill in all required fields and select a department.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Check duplicate email
        $check = mysqli_query($con, "SELECT id FROM users WHERE email='$email' LIMIT 1");
        if (safe_num_rows($check) > 0) {
            $error = "A user account with email '$email' already exists.";
        } else {
            // Check duplicate roll number
            $check_roll = mysqli_query($con, "SELECT student_id FROM students WHERE roll_no='$roll_no' LIMIT 1");
            if (safe_num_rows($check_roll) > 0) {
                $error = "A student with Roll Number '$roll_no' is already registered.";
            } else {
                // Profile picture upload
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
                        $error = "Only JPG, PNG, and WEBP formats are allowed for profile photos.";
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
                            INSERT INTO students (user_id, roll_no, department_id, course_id, course, semester, profile_picture)
                            VALUES ('$user_id', '$roll_no', '$department_id', '$course_id', '$course_code', '$semester', '$profile_picture')
                        ");

                        set_flash("success", "Student '$name' ($roll_no) has been successfully enrolled into Department!");
                        header("Location: manage_students.php?dept=" . $department_id);
                        exit();
                    } else {
                        $error = "Database Error: " . mysqli_error($con);
                    }
                }
            }
        }
    }
}

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card" style="max-width: 820px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fa-solid fa-user-plus text-primary"></i> Enroll New Student &bull; Department Assignment</h3>
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
                    <input type="text" name="password" class="form-control" required value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : 'student123'; ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Roll Number / Enrollment No. <span class="required">*</span></label>
                    <input type="text" name="roll_no" class="form-control" placeholder="e.g. 24BCS12641" required value="<?php echo isset($_POST['roll_no']) ? htmlspecialchars($_POST['roll_no']) : ''; ?>">
                </div>
            </div>

            <!-- Department & Course Assignment -->
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Department / School <span class="required">*</span></label>
                    <select name="department_id" id="deptSelect" class="form-control" required onchange="filterCourses()">
                        <option value="">-- Select Academic Department --</option>
                        <?php foreach ($depts as $d): ?>
                            <option value="<?php echo $d['department_id']; ?>" <?php if (isset($_POST['department_id']) && $_POST['department_id'] == $d['department_id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($d['name']); ?> (<?php echo $d['code']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Degree Program / Course <span class="required">*</span></label>
                    <select name="course_id" id="courseSelect" class="form-control" required>
                        <option value="">-- Select Degree Course --</option>
                        <?php foreach ($courses as $c): ?>
                            <option value="<?php echo $c['course_id']; ?>" data-dept="<?php echo $c['department_id']; ?>" <?php if (isset($_POST['course_id']) && $_POST['course_id'] == $c['course_id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($c['name']); ?> (<?php echo $c['code']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Enrolled Semester <span class="required">*</span></label>
                    <select name="semester" class="form-control" required>
                        <?php for ($s = 1; $s <= 8; $s++): ?>
                            <option value="<?php echo $s; ?>" <?php echo (isset($_POST['semester']) && $_POST['semester'] == $s) ? 'selected' : ($s == 1 ? 'selected' : ''); ?>>
                                Semester <?php echo $s; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Academic Status</label>
                    <input type="text" class="form-control" value="Active Enrolled" readonly style="background:#f8fafc;">
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
                    <i class="fa-solid fa-check"></i> Complete Student Enrollment
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
