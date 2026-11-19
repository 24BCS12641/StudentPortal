<?php
/**
 * Student Sidebar Component
 * Apex Imperial University Portal
 */
$current_page = basename($_SERVER['PHP_SELF']);
$student_name = $_SESSION['name'] ?? 'Student';

$student_pic = "../assets/images/default-user.png";
$roll_no_display = "Student";

if (isset($con) && isset($_SESSION['user_id'])) {
    $u_res = mysqli_query($con, "
        SELECT students.profile_picture, students.roll_no, students.course
        FROM students 
        WHERE user_id='" . $_SESSION['user_id'] . "'
    ");
    if ($u_res && $s_row = safe_fetch_assoc($u_res)) {
        if (!empty($s_row['profile_picture']) && file_exists("../uploads/profile_pictures/" . $s_row['profile_picture'])) {
            $student_pic = "../uploads/profile_pictures/" . $s_row['profile_picture'];
        }
        if (!empty($s_row['roll_no'])) {
            $roll_no_display = $s_row['roll_no'];
        }
    }
}
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon" style="background: linear-gradient(135deg, #2563eb 0%, #60a5fa 100%);">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>
        <div class="brand-text">
            <h3>Apex Portal</h3>
            <span class="brand-badge" style="color: #bfdbfe;"><i class="fa-solid fa-book-reader"></i> Student</span>
        </div>
    </div>
    
    <div class="sidebar-menu">
        <div class="menu-category">MAIN</div>
        <a href="dashboard.php" class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-chart-pie"></i>
            <span>Dashboard</span>
        </a>

        <a href="courses.php" class="nav-link <?php echo ($current_page == 'courses.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-book-bookmark"></i>
            <span>My Degree & Courses</span>
        </a>

        <a href="timetable.php" class="nav-link <?php echo ($current_page == 'timetable.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-calendar-days"></i>
            <span>Class Timetable</span>
        </a>
        
        <div class="menu-category">ACADEMIC WORK</div>
        <a href="view_assignments.php" class="nav-link <?php echo in_array($current_page, ['view_assignments.php', 'assignments.php', 'submit_assignment.php']) ? 'active' : ''; ?>">
            <i class="fa-solid fa-file-pen"></i>
            <span>Assignments</span>
        </a>
        
        <a href="my_submissions.php" class="nav-link <?php echo ($current_page == 'my_submissions.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-paper-plane"></i>
            <span>My Submissions</span>
        </a>
        
        <a href="view_grades.php" class="nav-link <?php echo in_array($current_page, ['view_grades.php', 'results.php']) ? 'active' : ''; ?>">
            <i class="fa-solid fa-award"></i>
            <span>Exam Results</span>
        </a>
        
        <div class="menu-category">FINANCE & RECORDS</div>
        <a href="view_attendance.php" class="nav-link <?php echo in_array($current_page, ['view_attendance.php', 'attendance.php']) ? 'active' : ''; ?>">
            <i class="fa-solid fa-clipboard-user"></i>
            <span>Attendance</span>
        </a>

        <a href="fees.php" class="nav-link <?php echo ($current_page == 'fees.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-receipt"></i>
            <span>Tuition & Fees</span>
        </a>
        
        <a href="performance_report.php" class="nav-link <?php echo ($current_page == 'performance_report.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-chart-simple"></i>
            <span>Performance Card</span>
        </a>
        
        <div class="menu-category">CAMPUS & LEARNING</div>
        <a href="study_materials.php" class="nav-link <?php echo ($current_page == 'study_materials.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-folder-open"></i>
            <span>Study Materials</span>
        </a>
        
        <a href="view_notices.php" class="nav-link <?php echo in_array($current_page, ['view_notices.php', 'notices.php']) ? 'active' : ''; ?>">
            <i class="fa-solid fa-bullhorn"></i>
            <span>Campus Notices</span>
        </a>
        
        <div class="menu-category">ACCOUNT</div>
        <a href="profile.php" class="nav-link <?php echo in_array($current_page, ['profile.php', 'edit_profile.php', 'upload_profile_picture.php']) ? 'active' : ''; ?>">
            <i class="fa-solid fa-user-gear"></i>
            <span>My Profile</span>
        </a>
        
        <a href="change_password.php" class="nav-link <?php echo ($current_page == 'change_password.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-key"></i>
            <span>Change Password</span>
        </a>
    </div>
    
    <div class="sidebar-footer">
        <div class="user-mini">
            <img src="<?php echo $student_pic; ?>" alt="Student Avatar" class="user-avatar">
            <div class="user-info">
                <div class="name"><?php echo htmlspecialchars($student_name); ?></div>
                <div class="role-tag"><?php echo htmlspecialchars($roll_no_display); ?></div>
            </div>
        </div>
        <a href="../logout.php" class="logout-icon" title="Logout">
            <i class="fa-solid fa-right-from-bracket"></i>
        </a>
    </div>
</aside>
