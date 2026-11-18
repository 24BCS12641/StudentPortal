<?php
/**
 * Admin - Manage Student Examination Results
 * Apex Imperial University Portal
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Manage Student Examination Results";

// Handle Result Deletion
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    mysqli_query($con, "DELETE FROM results WHERE result_id='$del_id'");
    set_flash("success", "Examination result record deleted successfully.");
    header("Location: manage_results.php");
    exit();
}

// Handle Add Result
if (isset($_POST['add_result'])) {
    $student_id = (int)$_POST['student_id'];
    $course_id = (int)$_POST['course_id'];
    $semester = (int)$_POST['semester'];
    $subject_name = escape($con, trim($_POST['subject_name']));
    $exam_type = escape($con, trim($_POST['exam_type']));
    $total_marks = (int)$_POST['total_marks'];
    $marks_obtained = (int)$_POST['marks_obtained'];

    // Auto-calculate letter grade and pass/fail status
    $pct = ($total_marks > 0) ? ($marks_obtained / $total_marks) * 100 : 0;
    if ($pct >= 90) $grade = 'O';
    elseif ($pct >= 80) $grade = 'A+';
    elseif ($pct >= 70) $grade = 'A';
    elseif ($pct >= 60) $grade = 'B+';
    elseif ($pct >= 50) $grade = 'B';
    elseif ($pct >= 40) $grade = 'C';
    else $grade = 'F';

    $status = ($grade !== 'F') ? 'Pass' : 'Fail';

    if ($student_id <= 0 || empty($subject_name) || $total_marks <= 0) {
        set_flash("danger", "Please fill in all mandatory fields with valid numbers.");
    } else {
        $ins = mysqli_query($con, "
            INSERT INTO results (student_id, course_id, semester, subject_name, exam_type, total_marks, marks_obtained, grade, status)
            VALUES ('$student_id', '$course_id', '$semester', '$subject_name', '$exam_type', '$total_marks', '$marks_obtained', '$grade', '$status')
        ");
        if ($ins) {
            set_flash("success", "Examination result for '$subject_name' successfully published!");
        } else {
            set_flash("danger", "Error saving result: " . mysqli_error($con));
        }
        header("Location: manage_results.php");
        exit();
    }
}

// Fetch filter parameters
$sem_filter = isset($_GET['sem']) && is_numeric($_GET['sem']) ? (int)$_GET['sem'] : 0;
$search = isset($_GET['search']) ? escape($con, trim($_GET['search'])) : '';

$where_clauses = ["1=1"];
if ($sem_filter > 0) {
    $where_clauses[] = "r.semester = '$sem_filter'";
}
if (!empty($search)) {
    $where_clauses[] = "(u.name LIKE '%$search%' OR s.roll_no LIKE '%$search%' OR r.subject_name LIKE '%$search%' OR c.name LIKE '%$search%')";
}
$where_sql = implode(" AND ", $where_clauses);

// Main Results Query
$results_query = mysqli_query($con, "
    SELECT r.*, u.name AS student_name, s.roll_no, c.name AS course_name, c.code AS course_code
    FROM results r
    JOIN students s ON r.student_id = s.student_id
    JOIN users u ON s.user_id = u.id
    LEFT JOIN courses c ON r.course_id = c.course_id
    WHERE $where_sql
    ORDER BY r.result_id DESC
");

// Calculate Analytics
$stat_total = 0;
$stat_passed = 0;
$stat_o_grade = 0;
$stat_marks_sum = 0;
$stat_res = mysqli_query($con, "SELECT total_marks, marks_obtained, grade, status FROM results");
while ($sr = mysqli_fetch_assoc($stat_res)) {
    $stat_total++;
    if ($sr['status'] === 'Pass') $stat_passed++;
    if ($sr['grade'] === 'O' || $sr['grade'] === 'A+') $stat_o_grade++;
    if ($sr['total_marks'] > 0) {
        $stat_marks_sum += ($sr['marks_obtained'] / $sr['total_marks']) * 100;
    }
}
$pass_rate = ($stat_total > 0) ? round(($stat_passed / $stat_total) * 100, 1) : 0;
$avg_pct = ($stat_total > 0) ? round($stat_marks_sum / $stat_total, 1) : 0;

// Fetch Students & Courses for Add Modal
$students_list = mysqli_query($con, "SELECT s.student_id, s.roll_no, s.course_id, s.semester, u.name, c.name AS course_name FROM students s JOIN users u ON s.user_id = u.id LEFT JOIN courses c ON s.course_id = c.course_id ORDER BY u.name ASC");
$courses_list = mysqli_query($con, "SELECT course_id, name, code FROM courses ORDER BY name ASC");

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<!-- Tab Switcher Header -->
<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; margin-bottom:24px;">
    <div style="display:flex; align-items:center; gap:10px;">
        <a href="manage_results.php" class="btn btn-primary" style="font-weight:700;">
            <i class="fa-solid fa-graduation-cap"></i> Semester Exam Results
        </a>
        <a href="manage_grades.php" class="btn btn-outline" style="font-weight:600;">
            <i class="fa-solid fa-trophy"></i> Assignment Continuous Grades
        </a>
    </div>

    <button type="button" class="btn btn-primary" onclick="openModal('addResultModal')" style="display:flex; align-items:center; gap:8px;">
        <i class="fa-solid fa-plus-circle"></i> Publish Exam Result
    </button>
</div>

<!-- KPI Summary Cards -->
<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-bottom:25px;">
    <div class="stat-card">
        <div class="stat-icon" style="background:#eef2ff; color:#4f46e5;">
            <i class="fa-solid fa-file-invoice"></i>
        </div>
        <div class="stat-details">
            <span class="stat-label">Total Results</span>
            <span class="stat-value"><?php echo $stat_total; ?></span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#ecfdf5; color:#10b981;">
            <i class="fa-solid fa-chart-line"></i>
        </div>
        <div class="stat-details">
            <span class="stat-label">Pass Rate</span>
            <span class="stat-value"><?php echo $pass_rate; ?>%</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef9c3; color:#ca8a04;">
            <i class="fa-solid fa-star"></i>
        </div>
        <div class="stat-details">
            <span class="stat-label">O & A+ Distinctions</span>
            <span class="stat-value"><?php echo $stat_o_grade; ?></span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#faf5ff; color:#8b5cf6;">
            <i class="fa-solid fa-chart-simple"></i>
        </div>
        <div class="stat-details">
            <span class="stat-label">Average Score</span>
            <span class="stat-value"><?php echo $avg_pct; ?>%</span>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-certificate"></i> Official Semester Examination Results</h3>
            <span class="badge badge-primary"><?php echo safe_num_rows($results_query); ?> Records</span>
        </div>

        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <!-- Semester Filter -->
            <div style="display:flex; gap:6px;">
                <a href="manage_results.php" class="btn btn-sm <?php echo $sem_filter == 0 ? 'btn-primary' : 'btn-outline'; ?>">All Semesters</a>
                <?php for ($s = 1; $s <= 6; $s++): ?>
                    <a href="manage_results.php?sem=<?php echo $s; ?>" class="btn btn-sm <?php echo $sem_filter == $s ? 'btn-primary' : 'btn-outline'; ?>">Sem <?php echo $s; ?></a>
                <?php endfor; ?>
            </div>

            <!-- Search Form -->
            <form method="GET" class="search-toolbar">
                <?php if ($sem_filter > 0): ?>
                    <input type="hidden" name="sem" value="<?php echo $sem_filter; ?>">
                <?php endif; ?>
                <div class="search-input-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" class="form-control" placeholder="Search student, roll no, subject..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
                <?php if (!empty($search) || $sem_filter > 0): ?>
                    <a href="manage_results.php" class="btn btn-outline" title="Clear Filters"><i class="fa-solid fa-rotate-left"></i></a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student & Roll No</th>
                        <th>Program & Sem</th>
                        <th>Subject Name</th>
                        <th>Exam Type</th>
                        <th>Score (Obtained / Max)</th>
                        <th>Grade</th>
                        <th>Status</th>
                        <th>Published</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($results_query && mysqli_num_rows($results_query) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($results_query)): 
                            $grade = $row['grade'];
                            $badge = ($grade === 'O' || $grade === 'A+') ? 'badge-success' : (($grade === 'A' || $grade === 'B+') ? 'badge-primary' : (($grade === 'B' || $grade === 'C') ? 'badge-warning' : 'badge-danger'));
                            $status_badge = ($row['status'] === 'Pass') ? 'badge-success' : 'badge-danger';
                        ?>
                            <tr>
                                <td><strong>#<?php echo $row['result_id']; ?></strong></td>
                                <td>
                                    <div style="font-weight:700; color:var(--text-primary);"><?php echo htmlspecialchars($row['student_name']); ?></div>
                                    <div style="font-size:12px; color:var(--text-muted);"><i class="fa-solid fa-id-badge"></i> <?php echo htmlspecialchars($row['roll_no']); ?></div>
                                </td>
                                <td>
                                    <div style="font-weight:600; color:var(--text-secondary);"><?php echo htmlspecialchars($row['course_code'] ?? 'CS'); ?></div>
                                    <div style="font-size:12px; color:var(--text-muted);">Semester <?php echo $row['semester']; ?></div>
                                </td>
                                <td>
                                    <div style="font-weight:600; color:var(--text-primary); max-width:220px;"><?php echo htmlspecialchars($row['subject_name']); ?></div>
                                </td>
                                <td>
                                    <span class="badge badge-dark"><?php echo htmlspecialchars($row['exam_type'] ?? 'Semester Final'); ?></span>
                                </td>
                                <td>
                                    <strong style="font-size:15px; color:var(--text-primary);"><?php echo $row['marks_obtained']; ?></strong> / <?php echo $row['total_marks']; ?>
                                    <span style="font-size:11px; color:var(--text-muted); margin-left:4px;">
                                        (<?php echo ($row['total_marks'] > 0) ? round(($row['marks_obtained'] / $row['total_marks']) * 100) : 0; ?>%)
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?php echo $badge; ?>" style="font-size:13px; font-weight:800; padding:4px 10px;"><?php echo $grade; ?></span>
                                </td>
                                <td>
                                    <span class="badge <?php echo $status_badge; ?>" style="font-weight:700;"><?php echo $row['status']; ?></span>
                                </td>
                                <td>
                                    <span style="font-size:12px; color:var(--text-muted);">
                                        <?php echo isset($row['published_at']) ? date('d M Y', strtotime($row['published_at'])) : 'Recent'; ?>
                                    </span>
                                </td>
                                <td style="text-align:right;">
                                    <a href="?delete=<?php echo $row['result_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Permanently delete this exam result?')" title="Delete Result">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="empty-state">
                                <i class="fa-solid fa-award"></i>
                                <h4>No examination results found</h4>
                                <p><?php echo !empty($search) ? 'No results matched your search query.' : 'Click "Publish Exam Result" above to record student semester marks.'; ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Publish Exam Result -->
<div id="addResultModal" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#ffffff; border-radius:18px; max-width:600px; width:92%; padding:30px; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); max-height:90vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; border-bottom:1px solid #e2e8f0; padding-bottom:12px;">
            <h3 style="font-size:19px; font-weight:800; color:var(--text-primary); margin:0;">
                <i class="fa-solid fa-plus-circle" style="color:var(--primary); margin-right:8px;"></i> Publish Examination Result
            </h3>
            <button type="button" onclick="closeModal('addResultModal')" style="border:none; background:transparent; font-size:20px; cursor:pointer; color:var(--text-muted);">&times;</button>
        </div>

        <form method="POST" action="manage_results.php">
            <div class="form-group" style="margin-bottom:16px;">
                <label class="form-label" style="font-weight:700;">Select Student *</label>
                <select name="student_id" class="form-control" required id="studentSelect" onchange="autoFillCourse(this)">
                    <option value="">-- Choose Student --</option>
                    <?php 
                    mysqli_data_seek($students_list, 0);
                    while ($st = mysqli_fetch_assoc($students_list)): ?>
                        <option value="<?php echo $st['student_id']; ?>" data-course="<?php echo $st['course_id']; ?>" data-sem="<?php echo $st['semester']; ?>">
                            <?php echo htmlspecialchars($st['name']); ?> (Roll: <?php echo htmlspecialchars($st['roll_no']); ?> - <?php echo htmlspecialchars($st['course_name'] ?? 'B.Tech'); ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:16px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Academic Course *</label>
                    <select name="course_id" id="courseSelect" class="form-control" required>
                        <option value="">-- Degree Program --</option>
                        <?php 
                        mysqli_data_seek($courses_list, 0);
                        while ($co = mysqli_fetch_assoc($courses_list)): ?>
                            <option value="<?php echo $co['course_id']; ?>"><?php echo htmlspecialchars($co['code'] . ' - ' . $co['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Semester *</label>
                    <select name="semester" id="semesterSelect" class="form-control" required>
                        <?php for ($sem = 1; $sem <= 8; $sem++): ?>
                            <option value="<?php echo $sem; ?>" <?php echo $sem == 3 ? 'selected' : ''; ?>>Semester <?php echo $sem; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom:16px;">
                <label class="form-label" style="font-weight:700;">Subject Name *</label>
                <input type="text" name="subject_name" class="form-control" placeholder="e.g. Database Management Systems" required>
            </div>

            <div class="form-group" style="margin-bottom:16px;">
                <label class="form-label" style="font-weight:700;">Exam Type *</label>
                <select name="exam_type" class="form-control" required>
                    <option value="Final Semester Examination">Final Semester Examination</option>
                    <option value="Mid-Term Examination">Mid-Term Examination</option>
                    <option value="Practical & Viva Voce">Practical & Viva Voce</option>
                    <option value="Continuous Assessment Test">Continuous Assessment Test</option>
                </select>
            </div>

            <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:20px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Total Maximum Marks *</label>
                    <input type="number" name="total_marks" id="totalMarksInput" class="form-control" value="100" min="1" max="500" required>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">Marks Obtained *</label>
                    <input type="number" name="marks_obtained" id="obtainedMarksInput" class="form-control" placeholder="e.g. 88" min="0" max="500" required>
                </div>
            </div>

            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px 16px; margin-bottom:22px; font-size:12px; color:var(--text-muted);">
                <i class="fa-solid fa-circle-info" style="color:var(--primary); margin-right:6px;"></i>
                Grade scale: <strong>O (90%+)</strong>, <strong>A+ (80-89%)</strong>, <strong>A (70-79%)</strong>, <strong>B+ (60-69%)</strong>, <strong>B (50-59%)</strong>, <strong>C (40-49%)</strong>, <strong>F (&lt;40%)</strong>. Grade and Pass/Fail status are auto-calculated on submission.
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px;">
                <button type="button" class="btn btn-outline" onclick="closeModal('addResultModal')">Cancel</button>
                <button type="submit" name="add_result" class="btn btn-primary" style="font-weight:700;">
                    <i class="fa-solid fa-check"></i> Save & Publish Result
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
function autoFillCourse(sel) {
    const opt = sel.options[sel.selectedIndex];
    const cid = opt.getAttribute('data-course');
    const sem = opt.getAttribute('data-sem');
    if (cid) document.getElementById('courseSelect').value = cid;
    if (sem) document.getElementById('semesterSelect').value = sem;
}
</script>

<?php include("../includes/footer.php"); ?>
