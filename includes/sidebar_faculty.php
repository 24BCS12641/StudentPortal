<?php
/**
 * Faculty Sidebar Component
 * Apex Imperial University Portal
 */
$current_page = basename($_SERVER['PHP_SELF']);
$faculty_name = $_SESSION['name'] ?? 'Faculty Member';

$faculty_pic = "../assets/images/default-user.png";
if (isset($con) && isset($_SESSION['user_id'])) {
    $u_res = mysqli_query($con, "SELECT profile_picture FROM users WHERE id='" . $_SESSION['user_id'] . "'");
    if ($u_res && $u_row = mysqli_fetch_assoc($u_res)) {
        if (!empty($u_row['profile_picture']) && file_exists("../uploads/profile_pictures/" . $u_row['profile_picture'])) {
            $faculty_pic = "../uploads/profile_pictures/" . $u_row['profile_picture'];
        }
    }
}
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon" style="background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);">
            <i class="fa-solid fa-chalkboard-user"></i>
        </div>
        <div class="brand-text">
            <h3>Apex Portal</h3>
            <span class="brand-badge" style="color: #bae6fd;"><i class="fa-solid fa-user-tie"></i> Faculty</span>
        </div>
    </div>
    
    <div class="sidebar-menu">
        <div class="menu-category">MAIN</div>
        <a href="dashboard.php" class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-chart-pie"></i>
            <span>Dashboard</span>
        </a>

        <a href="timetable.php" class="nav-link <?php echo ($current_page == 'timetable.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-calendar-days"></i>
            <span>Teaching Schedule</span>
        </a>
        
        <div class="menu-category">ASSIGNMENTS & GRADING</div>
        <a href="create_assignment.php" class="nav-link <?php echo ($current_page == 'create_assignment.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-plus-circle"></i>
            <span>Create Assignment</span>
        </a>
        
        <a href="view_assignments.php" class="nav-link <?php echo in_array($current_page, ['view_assignments.php', 'edit_assignment.php']) ? 'active' : ''; ?>">
            <i class="fa-solid fa-book"></i>
            <span>Manage Assignments</span>
        </a>
        
        <a href="view_submissions.php" class="nav-link <?php echo in_array($current_page, ['view_submissions.php', 'grade_assignment.php']) ? 'active' : ''; ?>">
            <i class="fa-solid fa-inbox"></i>
            <span>Submissions & Grading</span>
        </a>

        <a href="manage_results.php" class="nav-link <?php echo ($current_page == 'manage_results.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-square-poll-vertical"></i>
            <span>Enter Exam Results</span>
        </a>
        
        <div class="menu-category">ATTENDANCE</div>
        <a href="mark_attendance.php" class="nav-link <?php echo ($current_page == 'mark_attendance.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-user-check"></i>
            <span>Mark Attendance</span>
        </a>
        
        <a href="attendance_report.php" class="nav-link <?php echo ($current_page == 'attendance_report.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-clipboard-user"></i>
            <span>Attendance Report</span>
        </a>
        
        <div class="menu-category">COURSEWARE & NOTICES</div>
        <a href="upload_material.php" class="nav-link <?php echo ($current_page == 'upload_material.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-cloud-arrow-up"></i>
            <span>Upload Material</span>
        </a>
        
        <a href="view_materials.php" class="nav-link <?php echo ($current_page == 'view_materials.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-folder-open"></i>
            <span>Study Materials</span>
        </a>
        
        <a href="post_notice.php" class="nav-link <?php echo ($current_page == 'post_notice.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-bullhorn"></i>
            <span>Post Notice</span>
        </a>
        
        <div class="menu-category">ACCOUNT</div>
        <a href="profile.php" class="nav-link <?php echo in_array($current_page, ['profile.php', 'edit_profile.php']) ? 'active' : ''; ?>">
            <i class="fa-solid fa-id-card"></i>
            <span>My Profile</span>
        </a>
        
        <a href="change_password.php" class="nav-link <?php echo ($current_page == 'change_password.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-key"></i>
            <span>Change Password</span>
        </a>
    </div>
    
    <div class="sidebar-footer">
        <div class="user-mini">
            <img src="<?php echo $faculty_pic; ?>" alt="Faculty Avatar" class="user-avatar">
            <div class="user-info">
                <div class="name"><?php echo htmlspecialchars($faculty_name); ?></div>
                <div class="role-tag">Faculty Member</div>
            </div>
        </div>
        <a href="../logout.php" class="logout-icon" title="Logout">
            <i class="fa-solid fa-right-from-bracket"></i>
        </a>
    </div>
</aside>
