<?php
/**
 * Admin Sidebar Component
 * Apex Imperial University Portal
 */
$current_page = basename($_SERVER['PHP_SELF']);
$admin_name = $_SESSION['name'] ?? 'Administrator';

// Check for profile picture
$admin_pic = "../assets/images/default-user.png";
if (isset($con) && isset($_SESSION['user_id'])) {
    $u_res = mysqli_query($con, "SELECT profile_picture FROM users WHERE id='" . $_SESSION['user_id'] . "'");
    if ($u_res && $u_row = mysqli_fetch_assoc($u_res)) {
        if (!empty($u_row['profile_picture']) && file_exists("../uploads/profile_pictures/" . $u_row['profile_picture'])) {
            $admin_pic = "../uploads/profile_pictures/" . $u_row['profile_picture'];
        }
    }
}
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="fa-solid fa-building-columns"></i>
        </div>
        <div class="brand-text">
            <h3>Apex Portal</h3>
            <span class="brand-badge"><i class="fa-solid fa-shield-halved"></i> Administrator</span>
        </div>
    </div>
    
    <div class="sidebar-menu">
        <div class="menu-category">MAIN</div>
        <a href="dashboard.php" class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-chart-pie"></i>
            <span>Executive Dashboard</span>
        </a>

        <a href="manage_admissions.php" class="nav-link <?php echo ($current_page == 'manage_admissions.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-user-plus"></i>
            <span>Online Admissions</span>
        </a>
        
        <div class="menu-category">ACADEMIC MANAGEMENT</div>
        <a href="manage_students.php" class="nav-link <?php echo in_array($current_page, ['manage_students.php', 'add_student.php', 'edit_student.php']) ? 'active' : ''; ?>">
            <i class="fa-solid fa-user-graduate"></i>
            <span>Students Directory</span>
        </a>
        
        <a href="manage_faculty.php" class="nav-link <?php echo in_array($current_page, ['manage_faculty.php', 'add_faculty.php', 'edit_faculty.php']) ? 'active' : ''; ?>">
            <i class="fa-solid fa-chalkboard-user"></i>
            <span>Faculty Roster</span>
        </a>

        <a href="manage_departments.php" class="nav-link <?php echo ($current_page == 'manage_departments.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-sitemap"></i>
            <span>Departments</span>
        </a>

        <a href="manage_courses.php" class="nav-link <?php echo ($current_page == 'manage_courses.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-graduation-cap"></i>
            <span>Degree Programs</span>
        </a>
        
        <a href="manage_subjects.php" class="nav-link <?php echo in_array($current_page, ['manage_subjects.php', 'add_subject.php', 'edit_subject.php']) ? 'active' : ''; ?>">
            <i class="fa-solid fa-book-open"></i>
            <span>Subjects Curriculum</span>
        </a>
        
        <div class="menu-category">ASSESSMENT & TRACKING</div>
        <a href="manage_assignments.php" class="nav-link <?php echo in_array($current_page, ['manage_assignments.php', 'view_assignment.php']) ? 'active' : ''; ?>">
            <i class="fa-solid fa-file-lines"></i>
            <span>Assignments</span>
        </a>
        
        <a href="manage_attendance.php" class="nav-link <?php echo ($current_page == 'manage_attendance.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-calendar-check"></i>
            <span>Attendance Audit</span>
        </a>
        
        <a href="manage_results.php" class="nav-link <?php echo in_array($current_page, ['manage_grades.php', 'manage_results.php']) ? 'active' : ''; ?>">
            <i class="fa-solid fa-trophy"></i>
            <span>Grades & Results</span>
        </a>

        <div class="menu-category">CAMPUS & PUBLIC SITE</div>
        <a href="manage_notices.php" class="nav-link <?php echo in_array($current_page, ['manage_notices.php', 'add_notice.php', 'edit_notice.php']) ? 'active' : ''; ?>">
            <i class="fa-solid fa-bullhorn"></i>
            <span>Campus Notices</span>
        </a>

        <a href="manage_events.php" class="nav-link <?php echo ($current_page == 'manage_events.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-calendar-days"></i>
            <span>Campus Events</span>
        </a>

        <a href="manage_placements.php" class="nav-link <?php echo ($current_page == 'manage_placements.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-briefcase"></i>
            <span>Placements</span>
        </a>

        <a href="manage_gallery.php" class="nav-link <?php echo ($current_page == 'manage_gallery.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-images"></i>
            <span>Photo Gallery</span>
        </a>

        <a href="manage_contacts.php" class="nav-link <?php echo ($current_page == 'manage_contacts.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-envelope-open-text"></i>
            <span>Public Enquiries</span>
        </a>
        
        <a href="reports.php" class="nav-link <?php echo ($current_page == 'reports.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-chart-line"></i>
            <span>Reports & Analytics</span>
        </a>
        
        <div class="menu-category">SYSTEM</div>
        <a href="../index.php" class="nav-link" target="_blank">
            <i class="fa-solid fa-globe"></i>
            <span>Public Website <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:10px;"></i></span>
        </a>

        <a href="settings.php" class="nav-link <?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-sliders"></i>
            <span>Portal Settings</span>
        </a>
        
        <a href="profile.php" class="nav-link <?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-id-card"></i>
            <span>My Profile</span>
        </a>
    </div>
    
    <div class="sidebar-footer">
        <div class="user-mini">
            <img src="<?php echo $admin_pic; ?>" alt="Admin Avatar" class="user-avatar">
            <div class="user-info">
                <div class="name"><?php echo htmlspecialchars($admin_name); ?></div>
                <div class="role-tag">Administrator</div>
            </div>
        </div>
        <a href="../logout.php" class="logout-icon" title="Logout">
            <i class="fa-solid fa-right-from-bracket"></i>
        </a>
    </div>
</aside>
