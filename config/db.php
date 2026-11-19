<?php
/**
 * Database Configuration & Helper Utilities
 * Apex Imperial University / Modern College Portal
 */

// Disable throwing uncaught mysqli exceptions in PHP 8.1+
mysqli_report(MYSQLI_REPORT_OFF);

$host = getenv('DB_HOST') ?: "127.0.0.1";
$user = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : "";
$database = getenv('DB_NAME') ?: "student_portal";
$port = getenv('DB_PORT') ? (int)getenv('DB_PORT') : 3306;

// Attempt primary connection via TCP
try {
    $con = @mysqli_connect($host, $user, $password, $database, $port);
} catch (Throwable $e) {
    $con = false;
}

// Fallback to localhost socket if 127.0.0.1 failed
if (!$con && ($host === "127.0.0.1" || $host === "localhost")) {
    try {
        $con = @mysqli_connect("localhost", $user, $password, $database, $port);
    } catch (Throwable $e) {
        $con = false;
    }
}

// Fallback to college_website database if student_portal was not found
if (!$con && ($host === "127.0.0.1" || $host === "localhost")) {
    try {
        $con = @mysqli_connect("127.0.0.1", $user, $password, "college_website", $port);
        if (!$con) {
            $con = @mysqli_connect("localhost", $user, $password, "college_website", $port);
        }
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

// Auto Self-Healing University Schema Verification
if ($con) {
    static $schema_checked = false;
    if (!$schema_checked) {
        $schema_checked = true;
        @mysqli_query($con, "ALTER TABLE `students` ADD COLUMN IF NOT EXISTS `department_id` INT DEFAULT 1");
        @mysqli_query($con, "ALTER TABLE `students` ADD COLUMN IF NOT EXISTS `course_id` INT DEFAULT 1");
        @mysqli_query($con, "ALTER TABLE `attendance` ADD COLUMN IF NOT EXISTS `attendance_date` DATE DEFAULT NULL");
        @mysqli_query($con, "ALTER TABLE `attendance` ADD COLUMN IF NOT EXISTS `date` DATE DEFAULT NULL");
        @mysqli_query($con, "ALTER TABLE `attendance` ADD COLUMN IF NOT EXISTS `marked_by` INT DEFAULT NULL");
        @mysqli_query($con, "ALTER TABLE `attendance` ADD COLUMN IF NOT EXISTS `remarks` VARCHAR(255) DEFAULT NULL");
        @mysqli_query($con, "ALTER TABLE `attendance` ADD COLUMN IF NOT EXISTS `course_id` INT DEFAULT 1");
        @mysqli_query($con, "ALTER TABLE `attendance` MODIFY COLUMN `status` ENUM('Present', 'Absent', 'Late', 'Excused') NOT NULL DEFAULT 'Present'");
        
        // Ensure results table exists
        @mysqli_query($con, "
            CREATE TABLE IF NOT EXISTS `results` (
                `result_id` int(11) NOT NULL AUTO_INCREMENT,
                `student_id` int(11) NOT NULL,
                `course_id` int(11) DEFAULT 1,
                `semester` int(11) NOT NULL DEFAULT 1,
                `subject_name` varchar(150) NOT NULL,
                `exam_type` varchar(50) DEFAULT 'End Semester Examination',
                `total_marks` int(11) NOT NULL DEFAULT 100,
                `marks_obtained` int(11) NOT NULL DEFAULT 0,
                `grade` varchar(10) NOT NULL DEFAULT 'A',
                `status` enum('Pass','Fail') DEFAULT 'Pass',
                `published_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`result_id`),
                KEY `student_id` (`student_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        // Ensure departments table exists
        @mysqli_query($con, "
            CREATE TABLE IF NOT EXISTS `departments` (
                `department_id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(150) NOT NULL,
                `code` varchar(20) NOT NULL,
                `description` text DEFAULT NULL,
                `head_of_dept` varchar(120) DEFAULT NULL,
                `icon` varchar(50) DEFAULT 'fa-laptop-code',
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`department_id`),
                UNIQUE KEY `code` (`code`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        // Ensure courses table exists
        @mysqli_query($con, "
            CREATE TABLE IF NOT EXISTS `courses` (
                `course_id` int(11) NOT NULL AUTO_INCREMENT,
                `department_id` int(11) NOT NULL DEFAULT 1,
                `name` varchar(150) NOT NULL,
                `code` varchar(30) NOT NULL,
                `degree_level` enum('Undergraduate','Postgraduate','Doctorate','Diploma') NOT NULL DEFAULT 'Undergraduate',
                `duration` varchar(50) NOT NULL DEFAULT '3 Years',
                `semester_count` int(11) NOT NULL DEFAULT 6,
                `fees_per_year` decimal(10,2) NOT NULL DEFAULT 65000.00,
                `eligibility` text DEFAULT NULL,
                `description` text DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`course_id`),
                UNIQUE KEY `code` (`code`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        // Seed default departments if empty
        $check_d = @mysqli_query($con, "SELECT COUNT(*) FROM `departments`");
        if ($check_d && ($row_d = mysqli_fetch_row($check_d)) && (int)$row_d[0] === 0) {
            @mysqli_query($con, "
                INSERT INTO `departments` (`department_id`, `name`, `code`, `icon`) VALUES
                (1, 'School of Computer Science & Engineering', 'CSE', 'fa-laptop-code'),
                (2, 'Department of Business Administration & Management', 'BBA', 'fa-briefcase'),
                (3, 'School of Information Technology & Data Science', 'IT', 'fa-database'),
                (4, 'Department of Mechanical & Robotics Engineering', 'MECH', 'fa-gears'),
                (5, 'Department of Humanities, Arts & Journalism', 'HAJ', 'fa-feather-pointed')
            ");
        }

        // Seed default courses if empty
        $check_c = @mysqli_query($con, "SELECT COUNT(*) FROM `courses`");
        if ($check_c && ($row_c = mysqli_fetch_row($check_c)) && (int)$row_c[0] === 0) {
            @mysqli_query($con, "
                INSERT INTO `courses` (`course_id`, `department_id`, `name`, `code`, `degree_level`, `duration`, `semester_count`) VALUES
                (1, 1, 'Bachelor of Technology in Computer Science & Engineering', 'BTECH-CSE', 'Undergraduate', '4 Years', 8),
                (2, 1, 'Bachelor of Computer Applications', 'BCA', 'Undergraduate', '3 Years', 6),
                (3, 2, 'Bachelor of Business Administration', 'BBA', 'Undergraduate', '3 Years', 6),
                (4, 2, 'Master of Business Administration', 'MBA', 'Postgraduate', '2 Years', 4),
                (5, 3, 'Master of Computer Applications', 'MCA', 'Postgraduate', '2 Years', 4),
                (6, 4, 'B.Tech in Mechanical Engineering & Automation', 'BTECH-MECH', 'Undergraduate', '4 Years', 8)
            ");
        }

        // Guarantee default system users exist (Admin, Faculty, Student)
        @mysqli_query($con, "
            CREATE TABLE IF NOT EXISTS `users` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(100) NOT NULL,
                `email` varchar(100) NOT NULL,
                `password` varchar(255) NOT NULL,
                `role` enum('admin','faculty','student') NOT NULL,
                `profile_picture` varchar(255) DEFAULT 'default.png',
                `phone` varchar(20) DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`id`),
                UNIQUE KEY `email` (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");

        // Ensure safe_num_rows is available early
if (!function_exists('safe_num_rows')) {
    function safe_num_rows($result) {
        if ($result && is_object($result)) {
            return mysqli_num_rows($result);
        }
        return 0;
    }
}

$check_admin = @mysqli_query($con, "SELECT id FROM `users` WHERE `email`='admin@gmail.com' LIMIT 1");
        if (!$check_admin || safe_num_rows($check_admin) === 0) {
            @mysqli_query($con, "
                INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `profile_picture`, `phone`)
                VALUES (1, 'System Administrator', 'admin@gmail.com', 'admin123', 'admin', 'default.png', '+91 98765 43210')
                ON DUPLICATE KEY UPDATE `password`='admin123'
            ");
        }

        $check_faculty = @mysqli_query($con, "SELECT id FROM `users` WHERE `email`='faculty@gmail.com' LIMIT 1");
        if (!$check_faculty || safe_num_rows($check_faculty) === 0) {
            @mysqli_query($con, "
                INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `profile_picture`, `phone`)
                VALUES (2, 'Dr. Rajesh Sharma', 'faculty@gmail.com', 'faculty123', 'faculty', 'default.png', '+91 98111 22334')
                ON DUPLICATE KEY UPDATE `password`='faculty123'
            ");
        }

        $check_student = @mysqli_query($con, "SELECT id FROM `users` WHERE `email`='student@gmail.com' LIMIT 1");
        if (!$check_student || safe_num_rows($check_student) === 0) {
            @mysqli_query($con, "
                INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `profile_picture`, `phone`)
                VALUES (5, 'Sunny Kumar', 'student@gmail.com', 'student123', 'student', 'default.png', '+91 91234 56789')
                ON DUPLICATE KEY UPDATE `password`='student123'
            ");
        }

        // Guarantee Student profile for user 5 exists
        $check_st_profile = @mysqli_query($con, "SELECT student_id FROM `students` WHERE `user_id`=5 LIMIT 1");
        if (!$check_st_profile || safe_num_rows($check_st_profile) === 0) {
            @mysqli_query($con, "
                INSERT INTO `students` (`user_id`, `roll_no`, `department_id`, `course_id`, `course`, `semester`, `dob`, `gender`, `address`)
                VALUES (5, '24BCS12641', 1, 2, 'BCA', 4, '2004-06-15', 'Male', 'Campus Block 4, Tech City')
                ON DUPLICATE KEY UPDATE `roll_no`='24BCS12641', `course`='BCA', `semester`=4
            ");
        }
    }
}

// Global Safe Counter Helper (Immune to false results and missing tables)
if (!function_exists('safe_count')) {
    function safe_count($con, $table, $where = "1=1") {
        if (!$con) return 0;
        $t_clean = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
        $res = @mysqli_query($con, "SELECT COUNT(*) FROM `$t_clean` WHERE $where");
        if ($res && $row = mysqli_fetch_row($res)) {
            return (int)$row[0];
        }
        return 0;
    }
}

// Global Safe Num Rows Helper
if (!function_exists('safe_num_rows')) {
    function safe_num_rows($result) {
        if ($result && is_object($result)) {
            return mysqli_num_rows($result);
        }
        return 0;
    }
}

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
        if ($res && safe_num_rows($res) > 0) {
            $resolved_col = 'suject_name';
        } else {
            $resolved_col = 'subject_name';
        }
        return $resolved_col;
    }
}

// Auto-Heal Schema (Ensures tables & columns exist even on fresh Render/Cloud databases)
if (!function_exists('auto_heal_schema')) {
    function auto_heal_schema($con) {
        if (!$con) return;

        // Check if courses table is missing
        $c_check = @mysqli_query($con, "SHOW TABLES LIKE 'courses'");
        if (!$c_check || safe_num_rows($c_check) == 0) {
            @mysqli_query($con, "CREATE TABLE IF NOT EXISTS `departments` (
                `department_id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(150) NOT NULL,
                `code` VARCHAR(20) NOT NULL,
                `head_of_dept` VARCHAR(100),
                `description` TEXT,
                `icon` VARCHAR(50) DEFAULT 'fa-building-columns'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            @mysqli_query($con, "CREATE TABLE IF NOT EXISTS `courses` (
                `course_id` INT AUTO_INCREMENT PRIMARY KEY,
                `department_id` INT DEFAULT 1,
                `name` VARCHAR(150) NOT NULL,
                `code` VARCHAR(30) NOT NULL,
                `course_name` VARCHAR(150) DEFAULT NULL,
                `course_code` VARCHAR(30) DEFAULT NULL,
                `degree_level` ENUM('Undergraduate','Postgraduate','Doctorate','Diploma') DEFAULT 'Undergraduate',
                `duration` VARCHAR(50) DEFAULT '4 Years',
                `semester_count` INT DEFAULT 8,
                `fees_per_year` DECIMAL(10,2) DEFAULT 120000.00,
                `eligibility` TEXT,
                `description` TEXT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            @mysqli_query($con, "CREATE TABLE IF NOT EXISTS `admissions` (
                `admission_id` INT AUTO_INCREMENT PRIMARY KEY,
                `application_no` VARCHAR(50) NOT NULL UNIQUE,
                `student_name` VARCHAR(120) NOT NULL,
                `email` VARCHAR(120) NOT NULL,
                `phone` VARCHAR(30) NOT NULL,
                `course_id` INT DEFAULT 1,
                `marks_12th` DECIMAL(5,2) DEFAULT 0.00,
                `prev_school` VARCHAR(200) DEFAULT NULL,
                `address` TEXT DEFAULT NULL,
                `status` ENUM('Pending','Under Review','Approved','Rejected') DEFAULT 'Pending',
                `applied_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `notes` TEXT DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            @mysqli_query($con, "CREATE TABLE IF NOT EXISTS `events` (
                `event_id` INT AUTO_INCREMENT PRIMARY KEY,
                `title` VARCHAR(255) NOT NULL,
                `description` TEXT,
                `event_date` DATE NOT NULL,
                `event_time` VARCHAR(50) DEFAULT '10:00 AM',
                `location` VARCHAR(150) DEFAULT 'Main Auditorium'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            @mysqli_query($con, "CREATE TABLE IF NOT EXISTS `placements` (
                `placement_id` INT AUTO_INCREMENT PRIMARY KEY,
                `student_name` VARCHAR(120) NOT NULL,
                `roll_no` VARCHAR(50) NOT NULL,
                `course_id` INT DEFAULT 1,
                `company_name` VARCHAR(120) NOT NULL,
                `package_lpa` DECIMAL(5,2) NOT NULL,
                `role` VARCHAR(100) DEFAULT 'Software Engineer'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            @mysqli_query($con, "CREATE TABLE IF NOT EXISTS `gallery` (
                `gallery_id` INT AUTO_INCREMENT PRIMARY KEY,
                `title` VARCHAR(150) NOT NULL,
                `category` VARCHAR(50) DEFAULT 'Campus',
                `image_url` VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            @mysqli_query($con, "CREATE TABLE IF NOT EXISTS `contact_messages` (
                `message_id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(120) NOT NULL,
                `email` VARCHAR(120) NOT NULL,
                `phone` VARCHAR(30) DEFAULT NULL,
                `subject` VARCHAR(200) NOT NULL,
                `message` TEXT NOT NULL,
                `status` ENUM('Unread','Replied','Archived') DEFAULT 'Unread',
                `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            @mysqli_query($con, "CREATE TABLE IF NOT EXISTS `results` (
                `result_id` INT AUTO_INCREMENT PRIMARY KEY,
                `student_id` INT NOT NULL,
                `course_id` INT DEFAULT 1,
                `semester` INT DEFAULT 1,
                `subject_name` VARCHAR(150) NOT NULL,
                `exam_type` VARCHAR(50) DEFAULT 'Semester Final',
                `total_marks` INT DEFAULT 100,
                `marks_obtained` INT DEFAULT 0,
                `grade` VARCHAR(10) DEFAULT 'Pass',
                `status` ENUM('Pass','Fail') DEFAULT 'Pass',
                `published_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            @mysqli_query($con, "CREATE TABLE IF NOT EXISTS `fees` (
                `fee_id` INT AUTO_INCREMENT PRIMARY KEY,
                `student_id` INT NOT NULL,
                `semester` INT DEFAULT 1,
                `total_fee` DECIMAL(10,2) DEFAULT 80000.00,
                `paid_fee` DECIMAL(10,2) DEFAULT 80000.00,
                `due_fee` DECIMAL(10,2) DEFAULT 0.00,
                `status` ENUM('Paid','Partial','Unpaid') DEFAULT 'Paid',
                `payment_date` DATE DEFAULT NULL,
                `transaction_id` VARCHAR(100) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            @mysqli_query($con, "CREATE TABLE IF NOT EXISTS `timetable` (
                `timetable_id` INT AUTO_INCREMENT PRIMARY KEY,
                `course_id` INT DEFAULT 1,
                `semester` INT DEFAULT 1,
                `day_of_week` ENUM('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') NOT NULL,
                `time_slot` VARCHAR(50) NOT NULL,
                `subject_name` VARCHAR(150) NOT NULL,
                `room_no` VARCHAR(50) DEFAULT 'LH-101',
                `faculty_name` VARCHAR(100) DEFAULT 'Faculty'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            @mysqli_query($con, "CREATE TABLE IF NOT EXISTS `subjects` (
                `subject_id` INT AUTO_INCREMENT PRIMARY KEY,
                `subject_name` VARCHAR(150) NOT NULL,
                `subject_code` VARCHAR(50) NOT NULL,
                `faculty_id` INT DEFAULT NULL,
                `course` VARCHAR(100) DEFAULT 'B.Tech CSE',
                `semester` INT DEFAULT 1,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            @mysqli_query($con, "CREATE TABLE IF NOT EXISTS `grades` (
                `grade_id` INT AUTO_INCREMENT PRIMARY KEY,
                `student_id` INT NOT NULL,
                `assignment_id` INT NOT NULL,
                `subject_id` INT DEFAULT NULL,
                `marks` INT NOT NULL DEFAULT 0,
                `remarks` TEXT,
                `graded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            @mysqli_query($con, "CREATE TABLE IF NOT EXISTS `study_materials` (
                `material_id` INT AUTO_INCREMENT PRIMARY KEY,
                `subject_id` INT NOT NULL,
                `faculty_id` INT NOT NULL,
                `title` VARCHAR(255) NOT NULL,
                `description` TEXT,
                `file_path` VARCHAR(255) DEFAULT NULL,
                `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            // Seed default department & course if empty
            $dc = safe_count($con, 'departments');
            if ($dc == 0) {
                @mysqli_query($con, "INSERT INTO departments (name, code, head_of_dept, description, icon) VALUES
                ('School of Computer Science & Engineering', 'SCSE', 'Dr. Rajesh Sharma', 'Core Computing, AI, Machine Learning, Cloud & Cybersecurity', 'fa-laptop-code'),
                ('School of Business & Management', 'SBM', 'Prof. Sunita Deshmukh', 'Business Administration, Fintech, Supply Chain & Analytics', 'fa-chart-line'),
                ('School of Information Technology', 'SIT', 'Prof. Ananya Verma', 'Software Engineering, Web Architectures & Cloud DevOps', 'fa-network-wired')");
            }
            $cc = safe_count($con, 'courses');
            if ($cc == 0) {
                @mysqli_query($con, "INSERT INTO courses (department_id, name, code, course_name, course_code, degree_level, duration, semester_count, fees_per_year, eligibility, description) VALUES
                (1, 'B.Tech Computer Science & Engineering', 'CS101', 'B.Tech Computer Science & Engineering', 'CS101', 'Undergraduate', '4 Years', 8, 160000.00, '10+2 with Physics & Mathematics (60%+)', 'Comprehensive software engineering, algorithms, AI, and cloud architectures.'),
                (1, 'Bachelor of Computer Applications (BCA)', 'BCA201', 'Bachelor of Computer Applications (BCA)', 'BCA201', 'Undergraduate', '3 Years', 6, 95000.00, '10+2 with Mathematics/Computer Science', 'Industry-focused application development, full-stack web, and database systems.'),
                (2, 'Master of Business Administration (MBA)', 'MBA301', 'Master of Business Administration (MBA)', 'MBA301', 'Postgraduate', '2 Years', 4, 180000.00, 'Graduation in any discipline (55%+)', 'Advanced managerial leadership, marketing strategy, and financial engineering.'),
                (1, 'Master of Computer Applications (MCA)', 'MCA401', 'Master of Computer Applications (MCA)', 'MCA401', 'Postgraduate', '2 Years', 4, 110000.00, 'BCA or B.Sc Computer Science', 'Advanced software engineering, distributed systems, and enterprise architecture.')");
            }
        }
    }
}

// Run auto-heal silently
auto_heal_schema($con);
require_once __DIR__ . '/seed_data.php';
if ($con && (safe_count($con, 'departments') < 30 || safe_count($con, 'students') < 30)) {
    seed_dummy_data($con);
}
// Global Safe Fetch Assoc Helper
if (!function_exists('safe_fetch_assoc')) {
    function safe_fetch_assoc($result) {
        // Return the first row as associative array if $result is a valid mysqli_result
        if ($result && is_object($result) && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        // Fallback to empty array for false results or no rows
        return [];
    }
}
?>
