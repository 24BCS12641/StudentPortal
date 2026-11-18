<?php
/**
 * Shared Top Navbar Component
 * EduPortal Management System
 */
$user_role_label = ucfirst($_SESSION['role'] ?? 'Student');
$user_name = $_SESSION['name'] ?? 'User';
$header_title = isset($page_title) ? $page_title : 'Overview';

// Avatar resolution
$navbar_avatar = "../assets/images/default-user.png";
$notices_count = 0;

if (isset($con) && isset($_SESSION['user_id'])) {
    $nav_res = mysqli_query($con, "SELECT profile_picture FROM users WHERE id='" . $_SESSION['user_id'] . "'");
    if ($nav_res && $nav_user = mysqli_fetch_assoc($nav_res)) {
        if (!empty($nav_user['profile_picture']) && file_exists("../uploads/profile_pictures/" . $nav_user['profile_picture'])) {
            $navbar_avatar = "../uploads/profile_pictures/" . $nav_user['profile_picture'];
        }
    }
    
    // Count active notices
    $nc_q = mysqli_query($con, "SELECT COUNT(*) FROM notices");
    if ($nc_q) {
        $notices_count = (int)(mysqli_fetch_row($nc_q)[0] ?? 0);
    }
}

// Notice route based on role
$notices_link = 'view_notices.php';
if (($_SESSION['role'] ?? '') === 'admin') {
    $notices_link = 'manage_notices.php';
} elseif (($_SESSION['role'] ?? '') === 'faculty') {
    $notices_link = 'post_notice.php';
}
?>
<main class="main-wrapper">
    <header class="top-navbar">
        <div class="navbar-left">
            <button type="button" class="toggle-sidebar-btn" id="sidebarToggle" title="Toggle Navigation">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="page-title-box">
                <h1><?php echo htmlspecialchars($header_title); ?></h1>
                <div class="page-breadcrumb">
                    <span>EduPortal</span> / <span style="color:var(--primary); font-weight:600;"><?php echo htmlspecialchars($header_title); ?></span>
                </div>
            </div>
        </div>
        
        <div class="navbar-right">
            <div class="navbar-search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="navbarGlobalSearch" placeholder="Quick search..." title="Filter tables in real time">
            </div>

            <div class="live-chip">
                <span class="live-dot"></span>
                <span><?php echo date('D, d M Y'); ?></span>
            </div>

            <a href="<?php echo $notices_link; ?>" class="btn-icon btn-secondary" title="Campus Notices" style="position:relative; width:36px; height:36px; border-radius:50%; text-decoration:none;">
                <i class="fa-solid fa-bell" style="font-size:14px; color:var(--text-secondary);"></i>
                <?php if ($notices_count > 0): ?>
                    <span style="position:absolute; top:-3px; right:-3px; background:#ef4444; color:#fff; font-size:10px; font-weight:800; width:17px; height:17px; border-radius:50%; display:flex; align-items:center; justify-content:center; border:2px solid #fff; box-shadow:0 2px 4px rgba(239,68,68,0.4);">
                        <?php echo $notices_count; ?>
                    </span>
                <?php endif; ?>
            </a>
            
            <a href="profile.php" class="nav-user-pill" title="View Profile">
                <img src="<?php echo $navbar_avatar; ?>" alt="Profile" class="user-avatar" style="width:28px; height:28px;">
                <span style="font-size:13px; font-weight:600; color:var(--text-primary);"><?php echo htmlspecialchars($user_name); ?></span>
                <span class="badge badge-primary" style="font-size:10px; padding:2px 8px;"><?php echo $user_role_label; ?></span>
            </a>
            
            <a href="../logout.php" class="btn btn-outline btn-sm" title="Sign Out">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span style="display:inline-block;">Logout</span>
            </a>
        </div>
    </header>
    
    <div class="content-body">
        <?php 
        // Render flash alert if present
        if (function_exists('display_flash')) {
            echo display_flash();
        } 
        ?>
