<?php
/**
 * Faculty - Modern University Examination Marks Upload & Evaluation
 * Apex Imperial University Portal
 */

include("../config/auth.php");
include("../config/db.php");
check_role('faculty');

$page_title = "University Examination Marks Upload & Grading";
$user_id = $_SESSION['user_id'];
$subj_col = get_subject_col($con);

// Get faculty details
$f_res = mysqli_query($con, "SELECT faculty_id, department FROM faculty WHERE user_id='$user_id' LIMIT 1");
$faculty = mysqli_fetch_assoc($f_res);
$faculty_id = $faculty['faculty_id'] ?? 0;

// Fetch all subjects & courses
$subjects_q = mysqli_query($con, "SELECT subject_id, `$subj_col` AS subject_name, subject_code, course, semester FROM subjects ORDER BY `$subj_col` ASC");
$all_subjects = [];
while ($sub = mysqli_fetch_assoc($subjects_q)) {
    $all_subjects[] = $sub;
}

$courses_q = mysqli_query($con, "SELECT course_id, department_id, name, code FROM courses ORDER BY name ASC");
$all_courses = [];
while ($crs = mysqli_fetch_assoc($courses_q)) {
    $all_courses[] = $crs;
}

// Current setup parameters
$active_subject_id = isset($_GET['subject_id']) ? (int)$_GET['subject_id'] : ($all_subjects[0]['subject_id'] ?? 1);
$active_exam_type = isset($_GET['exam_type']) ? escape($con, $_GET['exam_type']) : 'End Semester Examination';
$active_max_marks = isset($_GET['max_marks']) ? (int)$_GET['max_marks'] : 100;
if ($active_max_marks <= 0) $active_max_marks = 100;

// Find active subject details
$active_subject = null;
foreach ($all_subjects as $s) {
    if ((int)$s['subject_id'] === $active_subject_id) {
        $active_subject = $s;
        break;
    }
}
$active_course_code = $active_subject['course'] ?? 'BCA';
$active_sem = (int)($active_subject['semester'] ?? 4);

// Find course_id for active subject
$active_course_id = 2; // BCA default
foreach ($all_courses as $c) {
    if ($c['code'] === $active_course_code) {
        $active_course_id = (int)$c['course_id'];
        break;
    }
}

// 1. Handle Batch Marks Upload
if (isset($_POST['publish_batch_results'])) {
    $sub_name = escape($con, $_POST['subject_name']);
    $crs_id = (int)$_POST['course_id'];
    $sem = (int)$_POST['semester'];
    $exam_t = escape($con, $_POST['exam_type']);
    $total_m = (int)$_POST['total_marks'];
    if ($total_m <= 0) $total_m = 100;

    $marks_data = $_POST['marks_obtained'] ?? [];
    $published_count = 0;

    foreach ($marks_data as $st_id => $raw_marks) {
        $st_id = (int)$st_id;
        if ($raw_marks === '' || $raw_marks === null) continue;
        $m_obtained = (int)$raw_marks;
        if ($m_obtained > $total_m) $m_obtained = $total_m;
        if ($m_obtained < 0) $m_obtained = 0;

        // Auto Grade Calculation
        $pct = ($total_m > 0) ? ($m_obtained / $total_m) * 100 : 0;
        if ($pct >= 90) $grade = 'O';
        elseif ($pct >= 80) $grade = 'A+';
        elseif ($pct >= 70) $grade = 'A';
        elseif ($pct >= 60) $grade = 'B+';
        elseif ($pct >= 50) $grade = 'B';
        elseif ($pct >= 40) $grade = 'C';
        else $grade = 'F';

        $status = ($grade !== 'F') ? 'Pass' : 'Fail';

        // Check if existing result exists
        $chk = mysqli_query($con, "
            SELECT result_id FROM results
            WHERE student_id='$st_id' AND subject_name='$sub_name' AND exam_type='$exam_t' AND semester='$sem'
            LIMIT 1
        ");

        if ($chk && mysqli_num_rows($chk) > 0) {
            $ex = mysqli_fetch_assoc($chk);
            mysqli_query($con, "
                UPDATE results
                SET marks_obtained='$m_obtained', total_marks='$total_m', grade='$grade', status='$status', published_at=NOW()
                WHERE result_id='{$ex['result_id']}'
            ");
        } else {
            mysqli_query($con, "
                INSERT INTO results (student_id, course_id, semester, subject_name, exam_type, total_marks, marks_obtained, grade, status, published_at)
                VALUES ('$st_id', '$crs_id', '$sem', '$sub_name', '$exam_t', '$total_m', '$m_obtained', '$grade', '$status', NOW())
            ");
        }
        $published_count++;
    }

    set_flash("success", "✓ Successfully published examination results for {$published_count} candidates in '{$sub_name}' ({$exam_t})!");
    header("Location: manage_results.php?subject_id={$active_subject_id}&exam_type=" . urlencode($exam_t) . "&max_marks={$total_m}");
    exit();
}

// 2. Delete Result
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    mysqli_query($con, "DELETE FROM results WHERE result_id='$del_id'");
    set_flash("success", "Examination result record deleted.");
    header("Location: manage_results.php");
    exit();
}

// Fetch Class Roster for active subject's course & semester
$sub_title_safe = escape($con, $active_subject['subject_name'] ?? '');
$roster_q = mysqli_query($con, "
    SELECT s.student_id, s.roll_no, s.course, s.semester, s.profile_picture,
           u.name AS student_name, u.email AS student_email,
           d.name AS department_name, d.code AS department_code,
           r.marks_obtained, r.total_marks, r.grade, r.status
    FROM students s
    JOIN users u ON s.user_id = u.id
    LEFT JOIN departments d ON s.department_id = d.department_id
    LEFT JOIN results r ON (r.student_id = s.student_id 
                        AND r.subject_name = '$sub_title_safe' 
                        AND r.exam_type = '$active_exam_type'
                        AND r.semester = '$active_sem')
    WHERE (s.course = '$active_course_code' OR s.semester = '$active_sem')
    ORDER BY s.roll_no ASC
");

// Fallback to all students if none matched specific course code
if (mysqli_num_rows($roster_q) === 0) {
    $roster_q = mysqli_query($con, "
        SELECT s.student_id, s.roll_no, s.course, s.semester, s.profile_picture,
               u.name AS student_name, u.email AS student_email,
               d.name AS department_name, d.code AS department_code,
               r.marks_obtained, r.total_marks, r.grade, r.status
        FROM students s
        JOIN users u ON s.user_id = u.id
        LEFT JOIN departments d ON s.department_id = d.department_id
        LEFT JOIN results r ON (r.student_id = s.student_id 
                            AND r.subject_name = '$sub_title_safe' 
                            AND r.exam_type = '$active_exam_type')
        ORDER BY s.roll_no ASC
    ");
}

$candidates = [];
$initial_pass = 0;
$initial_fail = 0;
$marks_sum = 0;
$marks_count = 0;
$highest_score = 0;

while ($cand = mysqli_fetch_assoc($roster_q)) {
    if ($cand['marks_obtained'] !== null) {
        $marks_count++;
        $marks_sum += (int)$cand['marks_obtained'];
        if ((int)$cand['marks_obtained'] > $highest_score) {
            $highest_score = (int)$cand['marks_obtained'];
        }
        if ($cand['status'] === 'Pass') $initial_pass++;
        else $initial_fail++;
    }
    $candidates[] = $cand;
}
$class_avg = ($marks_count > 0) ? round(($marks_sum / ($marks_count * $active_max_marks)) * 100, 1) : 0;

// Fetch Published Results History
$results_history_q = mysqli_query($con, "
    SELECT r.*, u.name AS student_name, s.roll_no, c.code AS course_code, d.code AS department_code
    FROM results r
    JOIN students s ON r.student_id = s.student_id
    JOIN users u ON s.user_id = u.id
    LEFT JOIN courses c ON r.course_id = c.course_id
    LEFT JOIN departments d ON s.department_id = d.department_id
    ORDER BY r.result_id DESC
");

include("../includes/header.php");
include("../includes/sidebar_faculty.php");
include("../includes/navbar.php");
?>

<style>
.marks-input {
    width: 90px;
    height: 38px;
    text-align: center;
    font-size: 15px;
    font-weight: 700;
    border-radius: 6px;
    border: 1.5px solid #cbd5e1;
    transition: all 0.2s;
}
.marks-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    outline: none;
    background: #eff6ff;
}
.grade-pill {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 800;
    font-size: 13px;
    min-width: 38px;
    text-align: center;
}
</style>

<!-- Exam Module Configuration Banner -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-header" style="flex-wrap:wrap; gap:12px;">
        <div>
            <h3 style="margin:0; font-size:18px;">
                <i class="fa-solid fa-graduation-cap text-primary"></i> 
                University Examination Marks Entry &bull; Class Evaluation Sheet
            </h3>
            <p style="margin:4px 0 0; font-size:13px; color:var(--text-muted);">
                Select subject course and examination component to enter marks with real-time grade and SGPA calculation.
            </p>
        </div>
        <div style="display:flex; gap:8px;">
            <a href="#historyTable" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-clock-rotate-left"></i> View Published Results
            </a>
        </div>
    </div>

    <div class="card-body">
        <form method="GET" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:15px; align-items:flex-end;">
            <div class="form-group" style="margin:0;">
                <label class="form-label">Subject / Paper Module <span class="required">*</span></label>
                <select name="subject_id" class="form-control" onchange="this.form.submit()">
                    <?php foreach ($all_subjects as $sub): ?>
                        <option value="<?php echo $sub['subject_id']; ?>" <?php if ($active_subject_id == $sub['subject_id']) echo 'selected'; ?>>
                            [<?php echo htmlspecialchars($sub['subject_code']); ?>] <?php echo htmlspecialchars($sub['subject_name']); ?> (<?php echo htmlspecialchars($sub['course']); ?> - Sem <?php echo $sub['semester']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin:0;">
                <label class="form-label">Examination Category <span class="required">*</span></label>
                <select name="exam_type" class="form-control" onchange="this.form.submit()">
                    <option <?php if ($active_exam_type == 'End Semester Examination') echo 'selected'; ?>>End Semester Examination</option>
                    <option <?php if ($active_exam_type == 'Mid-Semester Examination') echo 'selected'; ?>>Mid-Semester Examination</option>
                    <option <?php if ($active_exam_type == 'Continuous Internal Assessment (CIA)') echo 'selected'; ?>>Continuous Internal Assessment (CIA)</option>
                    <option <?php if ($active_exam_type == 'Practical Lab & Viva Voce') echo 'selected'; ?>>Practical Lab & Viva Voce</option>
                </select>
            </div>

            <div class="form-group" style="margin:0;">
                <label class="form-label">Maximum Total Marks</label>
                <input type="number" name="max_marks" id="maxMarksField" class="form-control" value="<?php echo $active_max_marks; ?>" min="10" max="200" onchange="this.form.submit()">
            </div>

            <div style="display:flex; gap:8px;">
                <button type="submit" class="btn btn-secondary" style="flex:1;">
                    <i class="fa-solid fa-arrows-rotate"></i> Load Candidate Sheet
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Marks Entry Grid -->
<form method="POST">
    <input type="hidden" name="subject_name" value="<?php echo htmlspecialchars($active_subject['subject_name'] ?? 'Database Management Systems'); ?>">
    <input type="hidden" name="course_id" value="<?php echo $active_course_id; ?>">
    <input type="hidden" name="semester" value="<?php echo $active_sem; ?>">
    <input type="hidden" name="exam_type" value="<?php echo htmlspecialchars($active_exam_type); ?>">
    <input type="hidden" name="total_marks" id="formTotalMarks" value="<?php echo $active_max_marks; ?>">

    <div class="card" style="margin-bottom:28px;">
        <div class="card-header" style="flex-wrap:wrap; gap:14px; background:#f8fafc; border-bottom:1px solid #e2e8f0;">
            <!-- Live Class Metrics -->
            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                <span class="badge badge-dark" style="font-size:13px; padding:8px 14px;">
                    <i class="fa-solid fa-users"></i> Enrolled: <strong><?php echo count($candidates); ?></strong>
                </span>
                <span class="badge badge-success" style="font-size:13px; padding:8px 14px; background:#dcfce7; color:#15803d;">
                    <i class="fa-solid fa-circle-check"></i> Passed: <strong id="livePassCount"><?php echo $initial_pass; ?></strong>
                </span>
                <span class="badge badge-danger" style="font-size:13px; padding:8px 14px; background:#fee2e2; color:#b91c1c;">
                    <i class="fa-solid fa-circle-xmark"></i> Failed: <strong id="liveFailCount"><?php echo $initial_fail; ?></strong>
                </span>
                <span class="badge badge-primary" style="font-size:13px; padding:8px 14px;">
                    <i class="fa-solid fa-chart-line"></i> Class Average: <strong id="liveClassAvg"><?php echo $class_avg; ?>%</strong>
                </span>
                <span class="badge badge-warning" style="font-size:13px; padding:8px 14px; background:#fef3c7; color:#b45309;">
                    <i class="fa-solid fa-trophy"></i> Top Score: <strong id="liveHighScore"><?php echo $highest_score; ?> / <?php echo $active_max_marks; ?></strong>
                </span>
            </div>

            <!-- Quick Autofill Tools -->
            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                <button type="button" class="btn btn-outline btn-sm" onclick="quickFillMarks(85)">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Preset 85 (A+)
                </button>
                <button type="button" class="btn btn-outline btn-sm" onclick="clearAllMarks()">
                    <i class="fa-solid fa-eraser"></i> Clear
                </button>
            </div>
        </div>

        <div class="card-body" style="padding:0;">
            <div class="table-responsive">
                <table class="custom-table" id="marksTable">
                    <thead>
                        <tr>
                            <th style="width:70px;">Sl. No.</th>
                            <th>Student Information</th>
                            <th>Roll Number</th>
                            <th>Department & Program</th>
                            <th style="text-align:center;">Marks (Max: <?php echo $active_max_marks; ?>)</th>
                            <th style="text-align:center;">Percentage</th>
                            <th style="text-align:center;">Grade</th>
                            <th style="text-align:center;">Result Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($candidates) > 0): ?>
                            <?php 
                            $idx = 1;
                            foreach ($candidates as $c): 
                                $st_id = (int)$c['student_id'];
                                $pic = !empty($c['profile_picture']) && file_exists("../uploads/profile_pictures/" . $c['profile_picture'])
                                    ? "../uploads/profile_pictures/" . $c['profile_picture']
                                    : "../assets/images/default-user.png";
                                $m_val = ($c['marks_obtained'] !== null) ? (int)$c['marks_obtained'] : '';
                                $pct_val = ($m_val !== '') ? round(($m_val / $active_max_marks) * 100, 1) : 0;
                                $g_val = $c['grade'] ?? '—';
                                $s_val = $c['status'] ?? '—';
                            ?>
                                <tr>
                                    <td><strong><?php echo $idx++; ?>.</strong></td>
                                    <td>
                                        <div class="table-user-cell">
                                            <img src="<?php echo $pic; ?>" alt="Avatar" class="table-avatar">
                                            <div>
                                                <div class="user-name"><?php echo htmlspecialchars($c['student_name']); ?></div>
                                                <div class="user-subtext"><?php echo htmlspecialchars($c['student_email'] ?? ''); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-dark" style="font-family:monospace; font-size:12.5px; font-weight:700;">
                                            <?php echo htmlspecialchars($c['roll_no']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary" style="font-size:11px;"><?php echo htmlspecialchars($c['department_code'] ?? 'CSE'); ?></span>
                                        <strong><?php echo htmlspecialchars($c['course']); ?></strong>
                                        <span style="font-size:11.5px; color:var(--text-muted);">Sem <?php echo $c['semester']; ?></span>
                                    </td>
                                    <td style="text-align:center;">
                                        <input type="number" 
                                               name="marks_obtained[<?php echo $st_id; ?>]" 
                                               class="marks-input" 
                                               value="<?php echo $m_val; ?>" 
                                               min="0" 
                                               max="<?php echo $active_max_marks; ?>" 
                                               step="1"
                                               data-sid="<?php echo $st_id; ?>"
                                               placeholder="0"
                                               oninput="calcRow(this)"
                                               onkeydown="navigateCells(event, this)">
                                    </td>
                                    <td style="text-align:center; font-weight:700;" id="pct_<?php echo $st_id; ?>">
                                        <?php echo ($m_val !== '') ? $pct_val . '%' : '—'; ?>
                                    </td>
                                    <td style="text-align:center;" id="grade_<?php echo $st_id; ?>">
                                        <?php if ($g_val === 'O' || $g_val === 'A+'): ?>
                                            <span class="grade-pill" style="background:#dcfce7; color:#15803d;"><?php echo $g_val; ?></span>
                                        <?php elseif ($g_val === 'A' || $g_val === 'B+'): ?>
                                            <span class="grade-pill" style="background:#dbeafe; color:#1d4ed8;"><?php echo $g_val; ?></span>
                                        <?php elseif ($g_val === 'B' || $g_val === 'C'): ?>
                                            <span class="grade-pill" style="background:#fef3c7; color:#b45309;"><?php echo $g_val; ?></span>
                                        <?php elseif ($g_val === 'F'): ?>
                                            <span class="grade-pill" style="background:#fee2e2; color:#b91c1c;"><?php echo $g_val; ?></span>
                                        <?php else: ?>
                                            <span style="color:#94a3b8;">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align:center;" id="status_<?php echo $st_id; ?>">
                                        <?php if ($s_val === 'Pass'): ?>
                                            <span class="badge badge-success"><i class="fa-solid fa-check"></i> PASS</span>
                                        <?php elseif ($s_val === 'Fail'): ?>
                                            <span class="badge badge-danger"><i class="fa-solid fa-xmark"></i> FAIL</span>
                                        <?php else: ?>
                                            <span style="color:#94a3b8;">—</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="empty-state">
                                    <i class="fa-solid fa-user-slash"></i>
                                    <h4>No candidates found in this class</h4>
                                    <p>Select another subject or program from the top configuration bar.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; padding:16px 24px; background:#f8fafc; border-top:1px solid #e2e8f0;">
            <div style="font-size:13px; color:var(--text-muted);">
                <i class="fa-solid fa-shield-halved text-primary"></i> 
                Marks are officially authenticated and instantly viewable on students' grade report cards.
            </div>
            <button type="submit" name="publish_batch_results" class="btn btn-primary" style="padding:10px 28px; font-size:14px; font-weight:700;">
                <i class="fa-solid fa-award"></i> Publish & Finalize Examination Results
            </button>
        </div>
    </div>
</form>

<!-- Published Results History Table -->
<div class="card" id="historyTable">
    <div class="card-header" style="flex-wrap:wrap; gap:12px;">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-clock-rotate-left text-primary"></i> Published Examination Results Registry</h3>
            <span class="badge badge-primary"><?php echo mysqli_num_rows($results_history_q); ?> Records</span>
        </div>
        <button type="button" class="btn btn-outline btn-sm" onclick="window.print()">
            <i class="fa-solid fa-print"></i> Print Gazette
        </button>
    </div>

    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Roll Number</th>
                        <th>Department</th>
                        <th>Subject & Semester</th>
                        <th>Exam Type</th>
                        <th>Score</th>
                        <th>Grade</th>
                        <th>Status</th>
                        <th style="text-align:right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($results_history_q && mysqli_num_rows($results_history_q) > 0): ?>
                        <?php while ($r = mysqli_fetch_assoc($results_history_q)): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($r['student_name']); ?></strong></td>
                                <td><span class="badge badge-dark"><?php echo htmlspecialchars($r['roll_no']); ?></span></td>
                                <td><span class="badge badge-primary" style="font-size:11px;"><?php echo htmlspecialchars($r['department_code'] ?? 'CSE'); ?></span></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($r['subject_name']); ?></strong>
                                    <div style="font-size:11.5px; color:var(--text-muted);"><?php echo htmlspecialchars($r['course_code'] ?? 'BCA'); ?> &bull; Sem <?php echo $r['semester']; ?></div>
                                </td>
                                <td><span class="badge badge-primary"><?php echo htmlspecialchars($r['exam_type']); ?></span></td>
                                <td><strong><?php echo $r['marks_obtained']; ?></strong> / <?php echo $r['total_marks']; ?></td>
                                <td>
                                    <span class="grade-pill" style="<?php echo ($r['grade'] === 'F') ? 'background:#fee2e2; color:#b91c1c;' : 'background:#dcfce7; color:#15803d;'; ?>">
                                        <?php echo htmlspecialchars($r['grade']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($r['status'] === 'Pass'): ?>
                                        <span class="badge badge-success"><i class="fa-solid fa-check"></i> Pass</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger"><i class="fa-solid fa-xmark"></i> Fail</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:right;">
                                    <a href="manage_results.php?delete=<?php echo $r['result_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this exam result entry?');" title="Delete Result">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="9" class="empty-state">No examination results recorded yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
var maxMarks = <?php echo $active_max_marks; ?>;

function calcRow(input) {
    var sid = input.getAttribute('data-sid');
    var val = input.value.trim();
    var pctEl = document.getElementById('pct_' + sid);
    var gradeEl = document.getElementById('grade_' + sid);
    var statusEl = document.getElementById('status_' + sid);

    if (val === '') {
        pctEl.textContent = '—';
        gradeEl.innerHTML = '<span style="color:#94a3b8;">—</span>';
        statusEl.innerHTML = '<span style="color:#94a3b8;">—</span>';
        updateSummary();
        return;
    }

    var m = parseFloat(val) || 0;
    if (m > maxMarks) {
        m = maxMarks;
        input.value = maxMarks;
    }
    if (m < 0) {
        m = 0;
        input.value = 0;
    }

    var pct = Math.round((m / maxMarks) * 100 * 10) / 10;
    pctEl.textContent = pct + '%';

    var grade = 'F';
    var badgeStyle = 'background:#fee2e2; color:#b91c1c;';
    var status = 'Fail';
    var statusBadge = '<span class="badge badge-danger"><i class="fa-solid fa-xmark"></i> FAIL</span>';

    if (pct >= 90) {
        grade = 'O';
        badgeStyle = 'background:#dcfce7; color:#15803d;';
        status = 'Pass';
        statusBadge = '<span class="badge badge-success"><i class="fa-solid fa-check"></i> PASS</span>';
    } else if (pct >= 80) {
        grade = 'A+';
        badgeStyle = 'background:#dcfce7; color:#15803d;';
        status = 'Pass';
        statusBadge = '<span class="badge badge-success"><i class="fa-solid fa-check"></i> PASS</span>';
    } else if (pct >= 70) {
        grade = 'A';
        badgeStyle = 'background:#dbeafe; color:#1d4ed8;';
        status = 'Pass';
        statusBadge = '<span class="badge badge-success"><i class="fa-solid fa-check"></i> PASS</span>';
    } else if (pct >= 60) {
        grade = 'B+';
        badgeStyle = 'background:#dbeafe; color:#1d4ed8;';
        status = 'Pass';
        statusBadge = '<span class="badge badge-success"><i class="fa-solid fa-check"></i> PASS</span>';
    } else if (pct >= 50) {
        grade = 'B';
        badgeStyle = 'background:#fef3c7; color:#b45309;';
        status = 'Pass';
        statusBadge = '<span class="badge badge-success"><i class="fa-solid fa-check"></i> PASS</span>';
    } else if (pct >= 40) {
        grade = 'C';
        badgeStyle = 'background:#fef3c7; color:#b45309;';
        status = 'Pass';
        statusBadge = '<span class="badge badge-success"><i class="fa-solid fa-check"></i> PASS</span>';
    }

    gradeEl.innerHTML = '<span class="grade-pill" style="' + badgeStyle + '">' + grade + '</span>';
    statusEl.innerHTML = statusBadge;

    updateSummary();
}

function updateSummary() {
    var inputs = document.querySelectorAll('.marks-input');
    var totalEntered = 0, sum = 0, passed = 0, failed = 0, max = 0;

    inputs.forEach(function(inp) {
        var v = inp.value.trim();
        if (v !== '') {
            var m = parseFloat(v) || 0;
            totalEntered++;
            sum += m;
            if (m > max) max = m;
            var pct = (m / maxMarks) * 100;
            if (pct >= 40) passed++;
            else failed++;
        }
    });

    document.getElementById('livePassCount').textContent = passed;
    document.getElementById('liveFailCount').textContent = failed;
    var avg = totalEntered > 0 ? Math.round((sum / (totalEntered * maxMarks)) * 100 * 10) / 10 : 0;
    document.getElementById('liveClassAvg').textContent = avg + '%';
    document.getElementById('liveHighScore').textContent = max + ' / ' + maxMarks;
}

function quickFillMarks(presetValue) {
    document.querySelectorAll('.marks-input').forEach(function(input) {
        input.value = presetValue;
        calcRow(input);
    });
}

function clearAllMarks() {
    document.querySelectorAll('.marks-input').forEach(function(input) {
        input.value = '';
        calcRow(input);
    });
}

function navigateCells(e, currentInput) {
    if (e.key === 'Enter' || e.key === 'ArrowDown') {
        e.preventDefault();
        var inputs = Array.from(document.querySelectorAll('.marks-input'));
        var idx = inputs.indexOf(currentInput);
        if (idx !== -1 && idx < inputs.length - 1) {
            inputs[idx + 1].focus();
            inputs[idx + 1].select();
        }
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        var inputs = Array.from(document.querySelectorAll('.marks-input'));
        var idx = inputs.indexOf(currentInput);
        if (idx > 0) {
            inputs[idx - 1].focus();
            inputs[idx - 1].select();
        }
    }
}
</script>

<?php include("../includes/footer.php"); ?>
