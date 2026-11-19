<?php
/**
 * Faculty - Weekly Teaching Timetable
 * Apex Imperial University Portal
 */

include("../config/auth.php");
include("../config/db.php");
check_role('faculty');

$page_title = "Faculty Teaching Timetable";
$user_id = $_SESSION['user_id'];

// Get faculty name
$faculty_name = $_SESSION['name'] ?? 'Dr. Rajesh Sharma';

// Fetch timetable entries for this faculty
$timetable_q = mysqli_query($con, "
    SELECT t.*, c.name AS course_name, c.code AS course_code
    FROM timetable t
    LEFT JOIN courses c ON t.course_id = c.course_id
    WHERE t.faculty_name LIKE '%" . escape($con, $faculty_name) . "%' OR t.faculty_name LIKE '%Sharma%'
    ORDER BY FIELD(t.day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), t.time_slot ASC
");

$schedule = [
    'Monday' => [],
    'Tuesday' => [],
    'Wednesday' => [],
    'Thursday' => [],
    'Friday' => [],
    'Saturday' => []
];

if ($timetable_q) {
    while ($slot = safe_fetch_assoc($timetable_q)) {
        $schedule[$slot['day_of_week']][] = $slot;
    }
}

include("../includes/header.php");
include("../includes/sidebar_faculty.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-calendar-days"></i> Faculty Instructional Timetable</h3>
            <span class="badge badge-primary"><?php echo htmlspecialchars($faculty_name); ?></span>
        </div>
        <button type="button" onclick="window.print()" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-print"></i> Print Teaching Schedule
        </button>
    </div>

    <div class="card-body">
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:20px;">
            <?php foreach ($schedule as $day => $slots): ?>
                <div style="background:var(--bg-card); border:1px solid var(--border-color); border-radius:var(--radius-md); overflow:hidden; box-shadow:var(--shadow-sm);">
                    <div style="background:linear-gradient(135deg, #0284c7 0%, #38bdf8 100%); color:#ffffff; padding:12px 18px; font-weight:700; display:flex; justify-content:space-between; align-items:center;">
                        <span><i class="fa-solid fa-clock me-1"></i> <?php echo $day; ?></span>
                        <span class="badge" style="background:rgba(255,255,255,0.25); color:#fff; font-size:11px;"><?php echo count($slots); ?> Sessions</span>
                    </div>
                    <div style="padding:15px; display:flex; flex-direction:column; gap:12px;">
                        <?php if (count($slots) > 0): ?>
                            <?php foreach ($slots as $s): ?>
                                <div style="background:var(--bg-body); padding:12px 15px; border-radius:var(--radius-sm); border-left:4px solid #0284c7;">
                                    <div style="font-size:12px; font-weight:700; color:#0284c7; margin-bottom:4px;">
                                        <i class="fa-regular fa-clock"></i> <?php echo htmlspecialchars($s['time_slot']); ?>
                                    </div>
                                    <div style="font-weight:700; color:var(--text-primary); font-size:14px;">
                                        <?php echo htmlspecialchars($s['subject_name']); ?>
                                    </div>
                                    <div style="display:flex; justify-content:space-between; font-size:12px; color:var(--text-muted); margin-top:6px;">
                                        <span><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($s['room_no']); ?></span>
                                        <span><i class="fa-solid fa-graduation-cap"></i> <?php echo htmlspecialchars($s['course_code'] ?? 'BCA'); ?> (Sem <?php echo $s['semester']; ?>)</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="text-align:center; padding:20px; color:var(--text-muted); font-size:13px;">
                                <i class="fa-solid fa-mug-hot" style="font-size:20px; margin-bottom:6px; display:block;"></i>
                                No scheduled lectures &bull; Research & Mentorship Hours
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
