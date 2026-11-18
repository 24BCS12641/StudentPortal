<?php
/**
 * Login Controller & View
 * EduPortal Management System
 */

session_start();
include("config/db.php");

// If already logged in, redirect directly to dashboard
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } elseif ($_SESSION['role'] === 'faculty') {
        header("Location: faculty/dashboard.php");
    } else {
        header("Location: student/dashboard.php");
    }
    exit();
}

$error = "";

if (isset($_POST['login'])) {
    $email = escape($con, $_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Support both hashed passwords and legacy plain text
            $password_valid = false;
            if (password_verify($password, $row['password']) || $password === $row['password']) {
                $password_valid = true;
            }

            if ($password_valid) {
                // Set authenticated session variables
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['email'] = $row['email'];

                // Redirect based on role
                if ($row['role'] === 'admin') {
                    header("Location: admin/dashboard.php");
                } elseif ($row['role'] === 'faculty') {
                    header("Location: faculty/dashboard.php");
                } else {
                    header("Location: student/dashboard.php");
                }
                exit();
            } else {
                $error = "Invalid password. Please check your credentials.";
            }
        } else {
            $error = "No user found with this email address.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | EduPortal Pro</title>
    <link rel="icon" type="image/png" href="assets/images/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome Local (Ad-blocker & Firewall Proof) -->
    <link rel="stylesheet" href="assets/vendor/fontawesome/css/all.min.css">
    <!-- Font Awesome Fallback CDNs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
    <!-- Font Awesome SVG with JS Fallback -->
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/js/all.min.js"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <h2>EduPortal Sign In</h2>
            <p>Academic & Student Management Portal</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div><?php echo htmlspecialchars($error); ?></div>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['registered'])): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <div>Account created successfully! Please sign in.</div>
            </div>
        <?php endif; ?>

        <!-- 1-Click Demo Login Selector for Teacher/Examiner Presentation -->
        <div class="demo-pill-section">
            <div class="demo-pill-title">
                <span><i class="fa-solid fa-bolt" style="color:var(--warning);"></i> 1-Click Demo Login</span>
                <span style="font-size:10px; color:var(--primary); font-weight:700;">FAST-FILL</span>
            </div>
            <div class="demo-pill-grid">
                <button type="button" class="demo-pill-btn" onclick="quickFill('admin@gmail.com', 'admin123', this)">
                    <span class="role-name"><i class="fa-solid fa-shield-halved"></i> Admin</span>
                    <span class="role-tag">admin@gmail.com</span>
                </button>
                <button type="button" class="demo-pill-btn" onclick="quickFill('faculty@gmail.com', 'faculty123', this)">
                    <span class="role-name"><i class="fa-solid fa-chalkboard-user"></i> Faculty</span>
                    <span class="role-tag">faculty@gmail.com</span>
                </button>
                <button type="button" class="demo-pill-btn active" onclick="quickFill('student@gmail.com', 'student123', this)">
                    <span class="role-name"><i class="fa-solid fa-user-graduate"></i> Student</span>
                    <span class="role-tag">student@gmail.com</span>
                </button>
            </div>
        </div>

        <form method="POST" action="login.php" autocomplete="off">
            <div class="form-group">
                <label class="form-label" for="loginEmail">Email Address</label>
                <div class="input-icon-wrapper">
                    <input type="email" id="loginEmail" name="email" placeholder="name@college.edu" required autofocus value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : 'student@gmail.com'; ?>">
                    <i class="fa-solid fa-envelope prefix-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                    <label class="form-label" for="loginPassword" style="margin-bottom:0;">Password</label>
                </div>
                <div class="input-icon-wrapper">
                    <input type="password" id="loginPassword" name="password" placeholder="Enter password" required value="student123">
                    <i class="fa-solid fa-lock prefix-icon"></i>
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility('loginPassword', this)" title="Show/Hide Password">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" name="login" class="btn-submit">
                <span>Sign In to Portal</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>

        <div class="auth-footer">
            Don't have an account yet? <a href="register.php">Create Account</a>
            <div style="margin-top:15px; border-top:1px solid #e2e8f0; padding-top:12px;">
                <a href="index.php" style="color:var(--text-muted); font-size:13px; text-decoration:none;">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Public University Website
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function quickFill(email, pass, btn) {
    document.getElementById('loginEmail').value = email;
    document.getElementById('loginPassword').value = pass;
    
    // Highlight selected demo button
    document.querySelectorAll('.demo-pill-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');
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

// Auto-fill role if passed in query string
window.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const r = params.get('role');
    const pills = document.querySelectorAll('.demo-pill-btn');
    if (r === 'admin' && pills[0]) {
        quickFill('admin@gmail.com', 'admin123', pills[0]);
    } else if (r === 'faculty' && pills[1]) {
        quickFill('faculty@gmail.com', 'faculty123', pills[1]);
    } else if (r === 'student' && pills[2]) {
        quickFill('student@gmail.com', 'student123', pills[2]);
    }
});
</script>

</body>
</html>
