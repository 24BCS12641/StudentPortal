<?php
/**
 * Registration Controller & View
 * EduPortal Management System
 */

session_start();
include("config/db.php");

$error = "";
$success = "";

if (isset($_POST['register'])) {
    $name = escape($con, $_POST['name']);
    $email = escape($con, $_POST['email']);
    $password = $_POST['password'];
    $role = escape($con, $_POST['role']);

    // Extra fields based on role
    $roll_no = isset($_POST['roll_no']) ? escape($con, $_POST['roll_no']) : '';
    $course = isset($_POST['course']) ? escape($con, $_POST['course']) : 'BCA';
    $semester = isset($_POST['semester']) ? (int)$_POST['semester'] : 1;
    $department = isset($_POST['department']) ? escape($con, $_POST['department']) : 'Computer Science';
    $designation = isset($_POST['designation']) ? escape($con, $_POST['designation']) : 'Assistant Professor';

    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Check if email already exists
        $check = mysqli_query($con, "SELECT id FROM users WHERE email='$email' LIMIT 1");

        if ($check && safe_num_rows($check) > 0) {
            $error = "An account with this email address already exists.";
        } elseif (!$check) {
            $error = "Database query error: " . mysqli_error($con);
        } else {
            // Insert user
            $insert_user = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
            $result = mysqli_query($con, $insert_user);

            if ($result) {
                $user_id = mysqli_insert_id($con);

                if ($role === "student") {
                    $final_roll = !empty($roll_no) ? $roll_no : 'STU-' . time();
                    $check_cid = @mysqli_query($con, "SHOW COLUMNS FROM students LIKE 'course_id'");
                    if ($check_cid && safe_num_rows($check_cid) > 0) {
                        mysqli_query($con, "INSERT INTO students (user_id, roll_no, department_id, course_id, semester) VALUES ('$user_id', '$final_roll', 1, 2, '$semester')");
                    } else {
                        mysqli_query($con, "INSERT INTO students (user_id, roll_no, course, semester) VALUES ('$user_id', '$final_roll', '$course', '$semester')");
                    }
                } elseif ($role === "faculty") {
                    $check_did = @mysqli_query($con, "SHOW COLUMNS FROM faculty LIKE 'department_id'");
                    if ($check_did && safe_num_rows($check_did) > 0) {
                        mysqli_query($con, "INSERT INTO faculty (user_id, department_id, designation) VALUES ('$user_id', 1, '$designation')");
                    } else {
                        mysqli_query($con, "INSERT INTO faculty (user_id, department, designation) VALUES ('$user_id', '$department', '$designation')");
                    }
                }

                header("Location: login.php?registered=1");
                exit();
            } else {
                $error = "Registration failed: " . mysqli_error($con);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | EduPortal Pro</title>
    <link rel="icon" type="image/png" href="assets/images/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Zero-Tofu Font Awesome (1: Embedded Base64 Fonts, 2: Local SVG Engine, 3: Vector Fallback) -->
    <link rel="stylesheet" href="assets/css/fontawesome-embedded.css">
    <script defer src="assets/vendor/fontawesome/js/all.min.js"></script>
    <script defer src="assets/js/icon-fixer.js"></script>
    
    <!-- Secondary Online Fallback CDNs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="auth-wrapper" style="max-width: 520px;">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h2>Create an Account</h2>
            <p>Join the EduPortal Academic Community</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div><?php echo htmlspecialchars($error); ?></div>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php" autocomplete="off">
            <!-- Role Selection -->
            <label class="form-label">Select Your Role <span style="color:#ef4444;">*</span></label>
            <div class="role-selector">
                <div class="role-option">
                    <input type="radio" name="role" id="roleStudent" value="student" checked onchange="toggleRoleFields('student')">
                    <label for="roleStudent" class="role-card">
                        <i class="fa-solid fa-user-graduate"></i>
                        <span>Student</span>
                    </label>
                </div>
                <div class="role-option">
                    <input type="radio" name="role" id="roleFaculty" value="faculty" onchange="toggleRoleFields('faculty')">
                    <label for="roleFaculty" class="role-card">
                        <i class="fa-solid fa-chalkboard-user"></i>
                        <span>Faculty</span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="regName">Full Name <span style="color:#ef4444;">*</span></label>
                <div class="input-icon-wrapper">
                    <input type="text" id="regName" name="name" placeholder="e.g. Sunny Kumar" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    <i class="fa-solid fa-user prefix-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="regEmail">Email Address <span style="color:#ef4444;">*</span></label>
                <div class="input-icon-wrapper">
                    <input type="email" id="regEmail" name="email" placeholder="name@college.edu" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    <i class="fa-solid fa-envelope prefix-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="regPassword">Password <span style="color:#ef4444;">*</span></label>
                <div class="input-icon-wrapper">
                    <input type="password" id="regPassword" name="password" placeholder="Create a strong password" required>
                    <i class="fa-solid fa-lock prefix-icon"></i>
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('regPassword', this)" title="Show/Hide Password">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Student Specific Fields -->
            <div id="studentFields">
                <div class="form-group">
                    <label class="form-label" for="regRoll">Roll Number / Enrollment No.</label>
                    <div class="input-icon-wrapper">
                        <input type="text" id="regRoll" name="roll_no" placeholder="e.g. 24BCS12641">
                        <i class="fa-solid fa-id-card prefix-icon"></i>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div class="form-group">
                        <label class="form-label" for="regCourse">Course</label>
                        <div class="input-icon-wrapper">
                            <input type="text" id="regCourse" name="course" value="BCA" placeholder="Course / Degree">
                            <i class="fa-solid fa-graduation-cap prefix-icon"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="regSemester">Semester</label>
                        <div class="input-icon-wrapper">
                            <select id="regSemester" name="semester">
                                <option value="1">Semester 1</option>
                                <option value="2">Semester 2</option>
                                <option value="3">Semester 3</option>
                                <option value="4" selected>Semester 4</option>
                                <option value="5">Semester 5</option>
                                <option value="6">Semester 6</option>
                            </select>
                            <i class="fa-solid fa-layer-group prefix-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Faculty Specific Fields -->
            <div id="facultyFields" style="display:none;">
                <div class="form-group">
                    <label class="form-label" for="regDept">Department</label>
                    <div class="input-icon-wrapper">
                        <input type="text" id="regDept" name="department" value="Computer Science" placeholder="e.g. Computer Science">
                        <i class="fa-solid fa-building-columns prefix-icon"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="regDesig">Designation</label>
                    <div class="input-icon-wrapper">
                        <input type="text" id="regDesig" name="designation" value="Assistant Professor" placeholder="e.g. Assistant Professor">
                        <i class="fa-solid fa-user-tie prefix-icon"></i>
                    </div>
                </div>
            </div>

            <button type="submit" name="register" class="btn-submit">
                <span>Create Account</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="login.php">Sign In</a>
            <div style="margin-top:15px; border-top:1px solid #e2e8f0; padding-top:12px;">
                <a href="index.php" style="color:#64748b; font-size:13px; text-decoration:none;">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Public University Website
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function toggleRoleFields(role) {
    const sFields = document.getElementById('studentFields');
    const fFields = document.getElementById('facultyFields');
    if (role === 'student') {
        sFields.style.display = 'block';
        fFields.style.display = 'none';
    } else {
        sFields.style.display = 'none';
        fFields.style.display = 'block';
    }
}

function togglePasswordVisibility(inputId, btn) {
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
<script src="assets/js/icon-fixer.js"></script>

</body>
</html>
