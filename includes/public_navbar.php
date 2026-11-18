<?php
/**
 * Public Website Navigation Bar
 * Apex Imperial University
 */
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Top Notification & Contact Strip -->
<div class="top-info-bar py-2">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
        <div class="top-info-left d-flex align-items-center gap-3">
            <span class="info-item"><i class="fa-solid fa-phone text-gold me-1"></i> +91 (011) 2876-5400</span>
            <span class="info-item d-none d-md-inline"><i class="fa-solid fa-envelope text-gold me-1"></i> admissions@apexuniversity.edu.in</span>
            <span class="info-badge d-none d-lg-inline-flex align-items-center gap-1">
                <i class="fa-solid fa-award text-gold"></i> NAAC Grade A++ Accredited &bull; NIRF Top 25
            </span>
        </div>
        <div class="top-info-right d-flex align-items-center gap-2">
            <a href="admissions.php" class="top-link text-gold font-weight-bold"><i class="fa-solid fa-bullhorn me-1"></i> Admissions 2026–27 Open</a>
            <span class="text-white-50">|</span>
            <a href="login.php" class="top-portal-btn"><i class="fa-solid fa-lock me-1"></i> Portal Login</a>
        </div>
    </div>
</div>

<!-- Main University Header & Navbar -->
<nav class="navbar navbar-expand-lg sticky-top main-university-nav">
    <div class="container">
        <!-- University Brand Logo & Crest -->
        <a class="navbar-brand d-flex align-items-center gap-3" href="index.php">
            <div class="university-crest">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <div class="brand-titles">
                <div class="university-name">APEX IMPERIAL UNIVERSITY</div>
                <div class="university-tagline">ESTD. 1988 &bull; AUTONOMOUS APEX INSTITUTION OF EXCELLENCE</div>
            </div>
        </a>

        <!-- Mobile Hamburger Toggle -->
        <button class="navbar-toggler border-0 shadow-none text-white" type="button" data-bs-toggle="collapse" data-bs-target="#universityNavCollapse" aria-controls="universityNavCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars-staggered fs-3"></i>
        </button>

        <!-- Navbar Menu Items -->
        <div class="collapse navbar-collapse" id="universityNavCollapse">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-1">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>" href="about.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'academics.php') ? 'active' : ''; ?>" href="academics.php">Academics</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'admissions.php') ? 'active' : ''; ?>" href="admissions.php">Admissions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'placements.php') ? 'active' : ''; ?>" href="placements.php">Placements</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>" href="contact.php">Contact</a>
                </li>
                <li class="nav-item ms-lg-2 mt-2 mt-lg-0 d-flex align-items-center gap-2">
                    <a href="admissions.php#applyNow" class="btn btn-gold btn-sm px-3 shadow-sm">
                        <i class="fa-solid fa-paper-plane me-1"></i> Apply Online
                    </a>
                    <button type="button" class="search-modal-btn" onclick="openSearchModal()" title="Quick Search (Ctrl + K)">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>
