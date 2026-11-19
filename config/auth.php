<?php
/**
 * Authentication & Role Protection Middleware
 * EduPortal Management System
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Determine relative path to login.php
    $login_path = file_exists("login.php") ? "login.php" : "../login.php";
    header("Location: " . $login_path);
    exit();
}

/**
 * Enforce role-based access control
 * @param string|array $allowed_roles e.g. 'admin' or ['admin', 'faculty']
 */
if (!function_exists('check_role')) {
    function check_role($allowed_roles) {
        if (!is_array($allowed_roles)) {
            $allowed_roles = [$allowed_roles];
        }

        $user_role = $_SESSION['role'] ?? '';

        if (!in_array($user_role, $allowed_roles)) {
            // Unauthorized access: redirect to their own dashboard
            if ($user_role === 'admin') {
                header("Location: ../admin/dashboard.php?error=unauthorized");
            } elseif ($user_role === 'faculty') {
                header("Location: ../faculty/dashboard.php?error=unauthorized");
            } elseif ($user_role === 'student') {
                header("Location: ../student/dashboard.php?error=unauthorized");
            } else {
                header("Location: ../login.php");
            }
            exit();
        }
    }
}

/**
 * Set a session flash message
 */
if (!function_exists('set_flash')) {
    function set_flash($type, $message) {
        $_SESSION['flash'] = [
            'type' => $type, // 'success', 'danger', 'warning', 'info'
            'message' => $message
        ];
    }
}

/**
 * Display session flash message if exists
 */
if (!function_exists('display_flash')) {
    function display_flash() {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            $type = $flash['type'] === 'error' ? 'danger' : $flash['type'];
            $icon = $type === 'success' ? 'fa-circle-check' : ($type === 'danger' ? 'fa-circle-exclamation' : 'fa-circle-info');
            return "
            <div class='alert alert-{$type} alert-dismissible' role='alert'>
                <i class='fa-solid {$icon} alert-icon'></i>
                <div class='alert-text'>" . htmlspecialchars($flash['message']) . "</div>
                <button type='button' class='alert-close' onclick='this.parentElement.remove();'>&times;</button>
            </div>";
        }
        return "";
    }
}

/**
 * Helper to get current user details
 */
if (!function_exists('get_current_user_data')) {
    function get_current_user_data($con) {
        $user_id = $_SESSION['user_id'] ?? 0;
        $res = mysqli_query($con, "SELECT * FROM users WHERE id='$user_id'");
        if ($res && safe_num_rows($res) > 0) {
            return safe_fetch_assoc($res);
        }
        return [
            'id' => $user_id,
            'name' => $_SESSION['name'] ?? 'User',
            'email' => '',
            'role' => $_SESSION['role'] ?? 'student',
            'profile_picture' => ''
        ];
    }
}
?>
