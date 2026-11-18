<?php
/**
 * Shared Header Include
 * EduPortal Management System
 */
if (!defined('PAGE_TITLE')) {
    $current_title = isset($page_title) ? $page_title : 'Dashboard';
} else {
    $current_title = PAGE_TITLE;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($current_title); ?> | EduPortal Pro</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Local (Ad-blocker & Firewall Proof) -->
    <link rel="stylesheet" href="../assets/vendor/fontawesome/css/all.min.css">
    <!-- Font Awesome Fallback CDNs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
    <!-- Font Awesome SVG with JS Fallback (Guarantees crisp vector icons even if webfonts are blocked) -->
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/js/all.min.js"></script>
    
    <!-- Chart.js 4.x CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Core Dashboard Stylesheet -->
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
<div class="app-wrapper">
    <div class="sidebar-overlay"></div>
