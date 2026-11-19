<?php
/**
 * Faculty - Modern Class Roster Attendance Marking System
 * Apex Imperial University Portal
 */

include("../config/auth.php");
include("../config/db.php");
check_role('faculty');

$page_title = "Class Roster Attendance Register";
$error = "";
$user_id = $_SESSION['user_id'];
$subj_col = get_subject_col($con);

// Get faculty ID
$f_res = mysqli_query($con, "SELECT faculty_id, department FROM faculty WHERE user_id='$user_id' LIMIT 1");
$faculty = ($f_res && safe_num_rows($f_res) > 0) ? safe_fetch_assoc($f_res) : [];
$faculty_id = $faculty['faculty_id'] ?? 0;

// Fetch faculty courses & subjects
$subjects_q = mysqli_query($con, "
    SELECT subject_id, `$subj_col` AS subject_name, subject_code, course, semester
    FROM subjects
    ORDER BY `$subj_col` ASC
");
$all_subjects = [];
while ($sub = safe_fetch_assoc($subjects_q)) {
    $all_subjects[] = $sub;
}

// Session parameters
$selected_subject_id = isset($_GET['subject_id']) ? (int)$_GET['subject_id'] : ($all_subjects[0]['subject_id'] ?? 1);
$selected_date = isset($_GET['date']) && !empty($_GET['date']) ? escape($con, $_GET['date']) : date('Y-m-d');
$selected_slot = isset($_GET['slot']) ? escape($con, $_GET['slot']) : 'Period 1 (09:00 - 10:00 AM)';
$selected_course = isset($_GET['course']) ? escape($con, $_GET['course']) : '';
$selected_sem = isset($_GET['sem']) ? (int)$_GET['sem'] : 0;

// Find subject info
$active_subject = null;
foreach ($all_subjects as $s) {
    if ((int)$s['subject_id'] === $selected_subject_id) {
        $active_subject = $s;
        if (empty($selected_course)) $selected_course = $s['course'];
        if ($selected_sem === 0) $selected_sem = (int)$s['semester'];
        break;
    }
}

// Handle Batch Attendance Submission
if (isset($_POST['submit_batch_attendance'])) {
    $sub_id = (int)$_POST['subject_id'];
    $att_date = escape($con, $_POST['attendance_date']);
    $crs_id = (int)$_POST['course_id'];
    $attendance_entries = $_POST['att_status'] ?? [];
    $remarks_entries = $_POST['att_remarks'] ?? [];

    $count_present = 0;
    $count_absent = 0;
    $count_late = 0;
    $count_excused = 0;
    $total_marked = 0;

    foreach ($attendance_entries as $st_id => $status) {
        $st_id = (int)$st_id;
        $status = in_array($status, ['Present', 'Absent', 'Late', 'Excused']) ? $status : 'Present';
        $remark = isset($remarks_entries[$st_id]) ? escape($con, trim($remarks_entries[$st_id])) : '';

        // Check if attendance record already exists for (student_id, subject_id, attendance_date)
        $chk = @mysqli_query($con, "
            SELECT attendance_id FROM attendance 
            WHERE student_id='$st_id' AND subject_id='$sub_id' AND attendance_date='$att_date'
            LIMIT 1
        ");

        if ($chk && safe_num_rows($chk) > 0) {
            $existing_att = safe_fetch_assoc($chk);
            @mysqli_query($con, "
                UPDATE attendance 
                SET status='$status', remarks='$remark', marked_by='$faculty_id', course_id='$crs_id', attendance_date='$att_date'
                WHERE attendance_id='{$existing_att['attendance_id']}'
            ");
        } else {
            @mysqli_query($con, "
                INSERT INTO attendance (student_id, subject_id, course_id, attendance_date, status, marked_by, remarks)
                VALUES ('$st_id', '$sub_id', '$crs_id', '$att_date', '$status', '$faculty_id', '$remark')
            ");
        }

        $total_marked++;
        if ($status === 'Present') $count_present++;
        elseif ($status === 'Absent') $count_absent++;
        elseif ($status === 'Late') $count_late++;
        elseif ($status === 'Excused') $count_excused++;
    }

    set_flash("success", "✓ Attendance successfully recorded for {$total_marked} students ({$count_present} Present, {$count_absent} Absent, {$count_late} Late) on {$att_date}!");
    header("Location: mark_attendance.php?subject_id={$sub_id}&date={$att_date}&course=" . urlencode($selected_course) . "&sem={$selected_sem}");
    exit();
}

// Fetch Students Roster for the selected Course/Semester
$roster_where = ["1=1"];
if (!empty($selected_course)) {
    $roster_where[] = "students.course = '$selected_course'";
}
if ($selected_sem > 0) {
    $roster_where[] = "students.semester = '$selected_sem'";
}
$roster_sql = implode(" AND ", $roster_where);

// Primary roster query with safe joins
$roster_q = @mysqli_query($con, "
    SELECT students.student_id, students.roll_no, students.course, students.semester, students.profile_picture,
           users.name, users.email,
           COALESCE(d.name, 'School of Computer Science & Engineering') AS department_name, 
           COALESCE(d.code, 'CSE') AS department_code,
           COALESCE(c.course_id, 1) AS course_id, 
           COALESCE(c.code, students.course) AS course_code,
           (SELECT status FROM attendance a 
            WHERE a.student_id = students.student_id 
              AND a.subject_id = '$selected_subject_id' 
              AND a.attendance_date = '$selected_date' 
            LIMIT 1) as existing_status,
           (SELECT remarks FROM attendance a 
            WHERE a.student_id = students.student_id 
              AND a.subject_id = '$selected_subject_id' 
              AND a.attendance_date = '$selected_date' 
            LIMIT 1) as existing_remark
    FROM students
    JOIN users ON students.user_id = users.id
    LEFT JOIN departments d ON students.department_id = d.department_id
    LEFT JOIN courses c ON students.course_id = c.course_id
    WHERE $roster_sql
    ORDER BY students.roll_no ASC
");

// Fallback to all students if specific filter returned 0
if (!$roster_q || safe_num_rows($roster_q) === 0) {
    $roster_q = @mysqli_query($con, "
        SELECT students.student_id, students.roll_no, students.course, students.semester, students.profile_picture,
               users.name, users.email,
               COALESCE(d.name, 'School of Computer Science & Engineering') AS department_name, 
               COALESCE(d.code, 'CSE') AS department_code,
               COALESCE(c.course_id, 1) AS course_id, 
               COALESCE(c.code, students.course) AS course_code,
               (SELECT status FROM attendance a 
                WHERE a.student_id = students.student_id 
                  AND a.subject_id = '$selected_subject_id' 
                  AND a.attendance_date = '$selected_date' 
                LIMIT 1) as existing_status,
               (SELECT remarks FROM attendance a 
                WHERE a.student_id = students.student_id 
                  AND a.subject_id = '$selected_subject_id' 
                  AND a.attendance_date = '$selected_date' 
                LIMIT 1) as existing_remark
        FROM students
        JOIN users ON students.user_id = users.id
        LEFT JOIN departments d ON students.department_id = d.department_id
        LEFT JOIN courses c ON students.course_id = c.course_id
        ORDER BY students.roll_no ASC
    ");
}

// Second ultra-safe fallback query if departments or courses joins fail
if (!$roster_q || safe_num_rows($roster_q) === 0) {
    $roster_q = @mysqli_query($con, "
        SELECT students.student_id, students.roll_no, students.course, students.semester, students.profile_picture,
               users.name, users.email,
               'CSE' AS department_code,
               'School of Computer Science & Engineering' AS department_name,
               1 AS course_id,
               students.course AS course_code,
               'Present' AS existing_status,
               '' AS existing_remark
        FROM students
        JOIN users ON students.user_id = users.id
        ORDER BY students.roll_no ASC
    ");
}

$students_list = [];
$initial_present = 0;
$initial_absent = 0;
$initial_late = 0;
$initial_excused = 0;
if ($roster_q && safe_num_rows($roster_q) > 0) {
    while ($st = safe_fetch_assoc($roster_q)) {
        $status = $st['existing_status'] ?? 'Present';
        if ($status === 'Present') $initial_present++;
        elseif ($status === 'Absent') $initial_absent++;
        elseif ($status === 'Late') $initial_late++;
        elseif ($status === 'Excused') $initial_excused++;
        $students_list[] = $st;
    }
}
$total_students_count = count($students_list);

include("../includes/header.php");
include("../includes/sidebar_faculty.php");
include("../includes/navbar.php");
?>

<style>
/* Attendance Modern Toggle Buttons */
.att-toggle-group {
    display: inline-flex;
    background: #f1f5f9;
    border-radius: 8px;
    padding: 3px;
    gap: 4px;
}
.att-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    border: 1px solid transparent;
    transition: all 0.15s ease-in-out;
    background: transparent;
    color: #64748b;
    user-select: none;
}
.att-btn input[type="radio"] {
    display: none;
}
.att-btn.present.active {
    background: #10b981;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(16, 185, 129, 0.35);
}
.att-btn.absent.active {
    background: #ef4444;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(239, 68, 68, 0.35);
}
.att-btn.late.active {
    background: #f59e0b;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(245, 158, 11, 0.35);
}
.att-btn.excused.active {
    background: #3b82f6;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(59, 130, 246, 0.35);
}
.att-btn:hover:not(.active) {
    background: rgba(0, 0, 0, 0.05);
    color: #1e293b;
}
</style>

<!-- Session Setup Filter Card -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-header" style="flex-wrap:wrap; gap:12px;">
        <div>
            <h3 style="margin:0; font-size:18px;">
                <i class="fa-solid fa-clipboard-user text-primary"></i> 
                Lecture Attendance Register &bull; Class Session Setup
            </h3>
            <p style="margin:4px 0 0; font-size:13px; color:var(--text-muted);">
                Select subject, lecture date, and period to load the full class roster for one-click marking.
            </p>
        </div>
        <a href="attendance_report.php" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-calendar-days"></i> View Complete Attendance Register
        </a>
    </div>

    <div class="card-body">
        <form method="GET" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:15px; align-items:flex-end;">
            <div class="form-group" style="margin:0;">
                <label class="form-label">Subject / Course Module <span class="required">*</span></label>
                <select name="subject_id" class="form-control" onchange="this.form.submit()">
                    <?php foreach ($all_subjects as $sub): ?>
                        <option value="<?php echo $sub['subject_id']; ?>" <?php if ($selected_subject_id == $sub['subject_id']) echo 'selected'; ?>>
                            [<?php echo htmlspecialchars($sub['subject_code']); ?>] <?php echo htmlspecialchars($sub['subject_name']); ?> (<?php echo htmlspecialchars($sub['course']); ?> - Sem <?php echo $sub['semester']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin:0;">
                <label class="form-label">Session Date <span class="required">*</span></label>
                <input type="date" name="date" class="form-control" value="<?php echo htmlspecialchars($selected_date); ?>" onchange="this.form.submit()">
            </div>

            <div class="form-group" style="margin:0;">
                <label class="form-label">Lecture Period Slot</label>
                <select name="slot" class="form-control">
                    <option <?php if ($selected_slot == 'Period 1 (09:00 - 10:00 AM)') echo 'selected'; ?>>Period 1 (09:00 - 10:00 AM)</option>
                    <option <?php if ($selected_slot == 'Period 2 (10:00 - 11:00 AM)') echo 'selected'; ?>>Period 2 (10:00 - 11:00 AM)</option>
                    <option <?php if ($selected_slot == 'Period 3 (11:15 - 12:15 PM)') echo 'selected'; ?>>Period 3 (11:15 - 12:15 PM)</option>
                    <option <?php if ($selected_slot == 'Period 4 (01:00 - 02:00 PM)') echo 'selected'; ?>>Period 4 (01:00 - 02:00 PM)</option>
                    <option <?php if ($selected_slot == 'Period 5 (02:00 - 03:00 PM)') echo 'selected'; ?>>Period 5 (02:00 - 03:00 PM)</option>
                </select>
            </div>

            <div style="display:flex; gap:8px;">
                <button type="submit" class="btn btn-secondary" style="flex:1;">
                    <i class="fa-solid fa-arrows-rotate"></i> Load Roster
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Class Attendance Roster Sheet -->
<form method="POST">
    <input type="hidden" name="subject_id" value="<?php echo $selected_subject_id; ?>">
    <input type="hidden" name="attendance_date" value="<?php echo htmlspecialchars($selected_date); ?>">
    <input type="hidden" name="course_id" value="<?php echo (int)($students_list[0]['course_id'] ?? 1); ?>">

    <div class="card">
        <div class="card-header" style="flex-wrap:wrap; gap:15px; background:#f8fafc; border-bottom:1px solid #e2e8f0;">
            <!-- Live Class Analytics Pills -->
            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                <span class="badge badge-dark" style="font-size:13px; padding:8px 14px;">
                    <i class="fa-solid fa-users"></i> Total Roster: <strong id="totalBadge"><?php echo $total_students_count; ?></strong>
                </span>
                <span class="badge badge-success" style="font-size:13px; padding:8px 14px; background:#dcfce7; color:#15803d;">
                    <i class="fa-solid fa-circle-check"></i> Present: <strong id="presentBadge"><?php echo $initial_present; ?></strong>
                </span>
                <span class="badge badge-danger" style="font-size:13px; padding:8px 14px; background:#fee2e2; color:#b91c1c;">
                    <i class="fa-solid fa-circle-xmark"></i> Absent: <strong id="absentBadge"><?php echo $initial_absent; ?></strong>
                </span>
                <span class="badge badge-warning" style="font-size:13px; padding:8px 14px; background:#fef3c7; color:#b45309;">
                    <i class="fa-solid fa-clock"></i> Late: <strong id="lateBadge"><?php echo $initial_late; ?></strong>
                </span>
                <span class="badge badge-primary" style="font-size:13px; padding:8px 14px;">
                    <i class="fa-solid fa-percent"></i> Rate: <strong id="rateBadge"><?php echo ($total_students_count > 0) ? round(($initial_present / $total_students_count) * 100) : 0; ?>%</strong>
                </span>
            </div>

            <!-- Master Action Controls -->
            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                <div class="search-input-group" style="min-width:180px;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="rosterSearch" class="form-control" placeholder="Quick find student..." onkeyup="filterRosterTable()">
                </div>

                <button type="button" class="btn btn-outline" style="color:#15803d; border-color:#86efac;" onclick="markAll('Present')">
                    <i class="fa-solid fa-check-double"></i> Mark All Present
                </button>

                <button type="button" class="btn btn-outline" style="color:#b91c1c; border-color:#fca5a5;" onclick="markAll('Absent')">
                    <i class="fa-solid fa-user-xmark"></i> Mark All Absent
                </button>
            </div>
        </div>

        <div class="card-body" style="padding:0;">
            <div class="table-responsive">
                <table class="custom-table" id="attendanceTable">
                    <thead>
                        <tr>
                            <th style="width:70px;">Sl. No.</th>
                            <th>Student Information</th>
                            <th>Roll Number</th>
                            <th>Department & Program</th>
                            <th style="text-align:center;">Attendance Status</th>
                            <th>Session Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($students_list) > 0): ?>
                            <?php 
                            $sl = 1;
                            foreach ($students_list as $st): 
                                $st_id = (int)$st['student_id'];
                                $pic = !empty($st['profile_picture']) && file_exists("../uploads/profile_pictures/" . $st['profile_picture'])
                                    ? "../uploads/profile_pictures/" . $st['profile_picture']
                                    : "../assets/images/default-user.png";
                                $current_status = $st['existing_status'] ?? 'Present';
                                $current_remark = $st['existing_remark'] ?? '';
                            ?>
                                <tr class="roster-row" data-name="<?php echo strtolower(htmlspecialchars($st['name'])); ?>" data-roll="<?php echo strtolower(htmlspecialchars($st['roll_no'])); ?>">
                                    <td><strong><?php echo $sl++; ?>.</strong></td>
                                    <td>
                                        <div class="table-user-cell">
                                            <img src="<?php echo $pic; ?>" alt="Avatar" class="table-avatar">
                                            <div>
                                                <div class="user-name"><?php echo htmlspecialchars($st['name']); ?></div>
                                                <div class="user-subtext"><?php echo htmlspecialchars($st['email']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-dark" style="font-family:monospace; font-size:12.5px; font-weight:700;">
                                            <?php echo htmlspecialchars($st['roll_no']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary" style="font-size:11px;">
                                            <?php echo htmlspecialchars($st['department_code'] ?? 'CSE'); ?>
                                        </span>
                                        <strong style="margin-left:6px;"><?php echo htmlspecialchars($st['course_code'] ?? ($st['course'] ?? 'BCA')); ?></strong>
                                        <span style="font-size:11.5px; color:var(--text-muted);">Sem <?php echo $st['semester']; ?></span>
                                    </td>
                                    <td style="text-align:center;">
                                        <div class="att-toggle-group">
                                            <label class="att-btn present <?php if ($current_status === 'Present') echo 'active'; ?>" onclick="selectAtt(this, <?php echo $st_id; ?>, 'Present')">
                                                <input type="radio" name="att_status[<?php echo $st_id; ?>]" value="Present" <?php if ($current_status === 'Present') echo 'checked'; ?>>
                                                <i class="fa-solid fa-check"></i> Present
                                            </label>

                                            <label class="att-btn absent <?php if ($current_status === 'Absent') echo 'active'; ?>" onclick="selectAtt(this, <?php echo $st_id; ?>, 'Absent')">
                                                <input type="radio" name="att_status[<?php echo $st_id; ?>]" value="Absent" <?php if ($current_status === 'Absent') echo 'checked'; ?>>
                                                <i class="fa-solid fa-xmark"></i> Absent
                                            </label>

                                            <label class="att-btn late <?php if ($current_status === 'Late') echo 'active'; ?>" onclick="selectAtt(this, <?php echo $st_id; ?>, 'Late')">
                                                <input type="radio" name="att_status[<?php echo $st_id; ?>]" value="Late" <?php if ($current_status === 'Late') echo 'checked'; ?>>
                                                <i class="fa-solid fa-clock"></i> Late
                                            </label>

                                            <label class="att-btn excused <?php if ($current_status === 'Excused') echo 'active'; ?>" onclick="selectAtt(this, <?php echo $st_id; ?>, 'Excused')">
                                                <input type="radio" name="att_status[<?php echo $st_id; ?>]" value="Excused" <?php if ($current_status === 'Excused') echo 'checked'; ?>>
                                                <i class="fa-solid fa-shield"></i> OD/Leave
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" name="att_remarks[<?php echo $st_id; ?>]" class="form-control" style="font-size:12px; height:34px;" placeholder="Remarks (optional)..." value="<?php echo htmlspecialchars($current_remark); ?>">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <i class="fa-solid fa-users-slash"></i>
                                    <h4>No Students Enrolled in This Subject Class</h4>
                                    <p>Select another subject module or department from the top selector.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; padding:16px 24px; background:#f8fafc; border-top:1px solid #e2e8f0;">
            <div style="font-size:13px; color:var(--text-muted);">
                <i class="fa-solid fa-circle-info text-primary"></i> Records are immediately synchronized with the student portal and attendance reports.
            </div>
            <button type="submit" name="submit_batch_attendance" class="btn btn-primary" style="padding:10px 24px; font-size:14px; font-weight:700;">
                <i class="fa-solid fa-floppy-disk"></i> Save & Submit Attendance Register
            </button>
        </div>
    </div>
</form>

<script>
function selectAtt(labelElement, studentId, status) {
    var group = labelElement.closest('.att-toggle-group');
    group.querySelectorAll('.att-btn').forEach(function(btn) {
        btn.classList.remove('active');
    });
    labelElement.classList.add('active');
    labelElement.querySelector('input[type="radio"]').checked = true;
    updateLiveMetrics();
}

function markAll(targetStatus) {
    var rows = document.querySelectorAll('#attendanceTable tbody tr.roster-row');
    rows.forEach(function(row) {
        if (row.style.display !== 'none') {
            var group = row.querySelector('.att-toggle-group');
            if (group) {
                var btn = group.querySelector('.att-btn.' + targetStatus.toLowerCase());
                if (btn) {
                    group.querySelectorAll('.att-btn').forEach(function(b) { b.classList.remove('active'); });
                    btn.classList.add('active');
                    btn.querySelector('input[type="radio"]').checked = true;
                }
            }
        }
    });
    updateLiveMetrics();
}

function updateLiveMetrics() {
    var checkedRadios = document.querySelectorAll('#attendanceTable tbody input[type="radio"]:checked');
    var p = 0, a = 0, l = 0, total = 0;

    checkedRadios.forEach(function(radio) {
        total++;
        if (radio.value === 'Present') p++;
        else if (radio.value === 'Absent') a++;
        else if (radio.value === 'Late') l++;
    });

    document.getElementById('presentBadge').textContent = p;
    document.getElementById('absentBadge').textContent = a;
    document.getElementById('lateBadge').textContent = l;
    
    var rate = total > 0 ? Math.round((p / total) * 100) : 0;
    document.getElementById('rateBadge').textContent = rate + '%';
}

function filterRosterTable() {
    var q = document.getElementById('rosterSearch').value.toLowerCase();
    var rows = document.querySelectorAll('#attendanceTable tbody tr.roster-row');
    rows.forEach(function(row) {
        var name = row.getAttribute('data-name');
        var roll = row.getAttribute('data-roll');
        if (!q || name.indexOf(q) !== -1 || roll.indexOf(q) !== -1) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>

<?php include("../includes/footer.php"); ?>
