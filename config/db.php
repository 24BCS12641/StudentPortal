<?php
/**
 * Database Configuration & Helper Utilities
 * Apex Imperial University / Modern College Portal
 */

// Disable throwing uncaught mysqli exceptions in PHP 8.1+
mysqli_report(MYSQLI_REPORT_OFF);

$host = getenv('DB_HOST') ?: "localhost";
$user = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : "";
$database = getenv('DB_NAME') ?: "student_portal";
$port = getenv('DB_PORT') ? (int)getenv('DB_PORT') : 3306;

// Attempt connection
try {
    $con = @mysqli_connect($host, $user, $password, $database, $port);
} catch (Throwable $e) {
    $con = false;
}

// Fallback to college_website if student_portal was not found on local
if (!$con && $host === "localhost") {
    try {
        $con = @mysqli_connect($host, $user, $password, "college_website", $port);
        if ($con) {
            $database = "college_website";
        }
    } catch (Throwable $e) {
        $con = false;
    }
}

if (!$con) {
    $error_msg = mysqli_connect_error() ?: "Connection failed";
    $is_access_denied = stripos($error_msg, 'access denied') !== false;
    
    die("
    <div style='font-family:Segoe UI, sans-serif; max-width:650px; margin:50px auto; padding:30px; border-radius:12px; background:#fff; border:1px solid #e2e8f0; box-shadow:0 10px 25px -5px rgba(0,0,0,0.1); color:#1e293b;'>
        <div style='display:flex; align-items:center; gap:12px; margin-bottom:16px;'>
            <div style='width:40px; height:40px; border-radius:50%; background:#fee2e2; display:flex; align-items:center; justify-content:center; color:#dc2626; font-size:20px; font-weight:bold;'>!</div>
            <h2 style='margin:0; font-size:22px; color:#991b1b;'>MySQL Database Connection Error</h2>
        </div>
        <p style='color:#475569; font-size:15px; margin:8px 0;'>Could not connect to database <strong>{$database}</strong> on <strong>{$host}</strong>.</p>
        <div style='background:#fef2f2; border:1px solid #fecaca; padding:12px 16px; border-radius:8px; font-family:Consolas,monospace; font-size:13px; color:#991b1b; margin:15px 0;'>
            <strong>Error:</strong> " . htmlspecialchars($error_msg) . "
        </div>
        <div style='background:#eff6ff; border:1px solid #bfdbfe; padding:15px; border-radius:8px; font-size:14px; color:#1e40af;'>
            <strong>Checklist:</strong><br>
            1. Ensure MySQL service is running in XAMPP.<br>
            2. Import <code>database/college_website.sql</code> via phpMyAdmin or command line.
        </div>
    </div>
    ");
}

mysqli_set_charset($con, "utf8mb4");

// Global Helper to sanitize inputs
if (!function_exists('escape')) {
    function escape($con, $data) {
        if ($data === null) return '';
        return mysqli_real_escape_string($con, trim($data));
    }
}

// Global Helper to sanitize output
if (!function_exists('clean')) {
    function clean($data) {
        return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
    }
}

// Global Helper for active subject / course column resolution
if (!function_exists('get_subject_col')) {
    function get_subject_col($con) {
        static $resolved_col = null;
        if ($resolved_col !== null) return $resolved_col;

        $res = @mysqli_query($con, "SHOW COLUMNS FROM `subjects` LIKE 'suject_name'");
        if ($res && mysqli_num_rows($res) > 0) {
            $resolved_col = 'suject_name';
        } else {
            $resolved_col = 'subject_name';
        }
        return $resolved_col;
    }
}
?>
