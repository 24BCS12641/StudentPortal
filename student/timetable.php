<?php
/**
 * Student Class Timetable & Schedule
 * Apex Imperial University Portal
 */

include("../config/auth.php");
include("../config/db.php");
check_role('student');

$page_title = "Class Timetable & Schedule";
$user_id = $_SESSION['user_id'];

// Get student details
$st_q = mysqli_query($con, "
    SELECT s.*, u.name 
    FROM students s
    JOIN users u ON s.user_id = u.id
    WHERE s.user_id='$user_id'
    LIMIT 1
");
$student = ($st_q && safe_num_rows($st_q) > 0) ? safe_fetch_assoc($st_q) : [];
$student_id = $student['student_id'] ?? 0;
$course_id = $student['course_id'] ?? 2; // Default BCA course_id
$semester = $student['semester'] ?? 4;

// Fetch timetable entries
$timetable_q = mysqli_query($con, "
    SELECT * FROM timetable 
    WHERE course_id='$course_id' OR course_id=2
    ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), time_slot ASC
");

// Group by day of week
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
include("../includes/sidebar_student.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-calendar-days"></i> Weekly Lecture & Practical Schedule</h3>
            <span class="badge badge-primary">Semester <?php echo htmlspecialchars($semester); ?> (BCA)</span>
        </div>
        <button type="button" onclick="window.print()" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-print"></i> Print Schedule
        </button>
    </div>

    <div class="card-body">
        <p style="color:var(--text-secondary); font-size:14px; margin-bottom:20px;">
            Lectures are conducted in Central Academic Block A and Computer Laboratories. Attendance is recorded at the start of each session.
        </p>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:20px;">
            <?php foreach ($schedule as $day => $slots): ?>
                <div style="background:var(--bg-card); border:1px solid var(--border-color); border-radius:var(--radius-md); overflow:hidden; box-shadow:var(--shadow-sm);">
                    <div style="background:var(--primary-gradient); color:#ffffff; padding:12px 18px; font-weight:700; display:flex; justify-content:space-between; align-items:center;">
                        <span><i class="fa-solid fa-clock me-1"></i> <?php echo $day; ?></span>
                        <span class="badge" style="background:rgba(255,255,255,0.2); color:#fff; font-size:11px;"><?php echo count($slots); ?> Lectures</span>
                    </div>
                    <div style="padding:15px; display:flex; flex-direction:column; gap:12px;">
                        <?php if (count($slots) > 0): ?>
                            <?php foreach ($slots as $s): ?>
                                <div style="background:var(--bg-body); padding:12px 15px; border-radius:var(--radius-sm); border-left:4px solid var(--primary);">
                                    <div style="font-size:12px; font-weight:700; color:var(--primary); margin-bottom:4px;">
                                        <i class="fa-regular fa-clock"></i> <?php echo htmlspecialchars($s['time_slot']); ?>
                                    </div>
                                    <div style="font-weight:700; color:var(--text-primary); font-size:14px;">
                                        <?php echo htmlspecialchars($s['subject_name']); ?>
                                    </div>
                                    <div style="display:flex; justify-content:space-between; font-size:12px; color:var(--text-muted); margin-top:6px;">
                                        <span><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($s['room_no']); ?></span>
                                        <span><i class="fa-solid fa-user-tie"></i> <?php echo htmlspecialchars($s['faculty_name']); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="text-align:center; padding:20px; color:var(--text-muted); font-size:13px;">
                                <i class="fa-solid fa-mug-hot" style="font-size:20px; margin-bottom:6px; display:block;"></i>
                                No scheduled lectures &bull; Self Study / Lab Work
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
