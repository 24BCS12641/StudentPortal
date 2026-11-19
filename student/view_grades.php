<?php
/**
 * View Academic Examination Results & Grades (Student)
 * Apex Imperial University Portal
 */

include("../config/auth.php");
include("../config/db.php");
check_role('student');

$page_title = "Academic Results & Grade Card";
$user_id = $_SESSION['user_id'];
$subj_col = get_subject_col($con);

// Get student details
$st_query = mysqli_query($con, "
    SELECT s.student_id, s.roll_no, s.course, s.semester, s.course_id, u.name, c.name AS course_name, c.code AS course_code
    FROM students s
    JOIN users u ON s.user_id = u.id
    LEFT JOIN courses c ON s.course_id = c.course_id
    WHERE s.user_id = '$user_id'
    LIMIT 1
");
$student = ($st_query && mysqli_num_rows($st_query) > 0) ? mysqli_fetch_assoc($st_query) : [];
$student_id = $student['student_id'] ?? 0;

// 1. Fetch Official Semester Examination Results
$exam_results = [];
$exam_total_marks = 0;
$exam_obtained_marks = 0;
$distinction_count = 0;
$passed_count = 0;

if ($student_id > 0) {
    $res_q = mysqli_query($con, "
        SELECT r.*, c.name AS course_name, c.code AS course_code
        FROM results r
        LEFT JOIN courses c ON r.course_id = c.course_id
        WHERE r.student_id = '$student_id'
        ORDER BY r.semester DESC, r.result_id ASC
    ");
    if ($res_q) {
        while ($r = mysqli_fetch_assoc($res_q)) {
            $exam_results[] = $r;
            $exam_total_marks += (int)$r['total_marks'];
            $exam_obtained_marks += (int)$r['marks_obtained'];
            if ($r['status'] === 'Pass') $passed_count++;
            if ($r['grade'] === 'O' || $r['grade'] === 'A+') $distinction_count++;
        }
    }
}
$exam_count = count($exam_results);
$overall_pct = ($exam_total_marks > 0) ? round(($exam_obtained_marks / $exam_total_marks) * 100, 1) : 0;
// Calculate SGPA on 10-point scale: Percentage / 9.5
$sgpa = ($overall_pct > 0) ? round($overall_pct / 9.5, 2) : 0.0;
if ($sgpa > 10.0) $sgpa = 10.0;

// 2. Fetch Assignment Grades (Continuous Assessment)
$asg_grades = [];
$asg_total_marks = 0;
$asg_count = 0;

if ($student_id > 0) {
    $asg_q = mysqli_query($con, "
        SELECT g.*, a.title AS assignment_title, sub.`$subj_col` AS subject_name
        FROM grades g
        JOIN assignments a ON g.assignment_id = a.assignment_id
        LEFT JOIN subjects sub ON a.subject_id = sub.subject_id
        WHERE g.student_id = '$student_id'
        ORDER BY g.grade_id DESC
    ");
    if ($asg_q) {
        while ($g = mysqli_fetch_assoc($asg_q)) {
            $asg_grades[] = $g;
            $asg_total_marks += (int)$g['marks'];
            $asg_count++;
        }
    }
}
$asg_avg = ($asg_count > 0) ? round($asg_total_marks / $asg_count, 1) : 0;

include("../includes/header.php");
include("../includes/sidebar_student.php");
include("../includes/navbar.php");
?>

<!-- Score Overview Banner -->
<div style="background:linear-gradient(135deg, #0a192f 0%, #1e293b 100%); color:#ffffff; border-radius:var(--radius-lg); padding:28px 32px; margin-bottom:28px; box-shadow:0 12px 28px -6px rgba(10,25,47,0.3); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:24px;">
    <div style="display:flex; align-items:center; gap:20px;">
        <div style="width:68px; height:68px; border-radius:18px; background:linear-gradient(135deg, #d4af37 0%, #f59e0b 100%); color:#0a192f; display:flex; align-items:center; justify-content:center; font-size:32px; box-shadow:0 6px 20px rgba(212,175,55,0.4);">
            <i class="fa-solid fa-trophy"></i>
        </div>
        <div>
            <div style="font-size:12px; color:#cbd5e1; text-transform:uppercase; font-weight:700; letter-spacing:1px;">Cumulative Grade Performance</div>
            <div style="display:flex; align-items:baseline; gap:10px; margin-top:4px;">
                <span style="font-size:36px; font-weight:800; color:#ffffff; font-family:'Playfair Display', serif;"><?php echo $sgpa > 0 ? number_format($sgpa, 2) : 'N/A'; ?></span>
                <span style="font-size:14px; color:#94a3b8; font-weight:600;">SGPA / 10.0</span>
                <span style="margin-left:10px; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:700; background:rgba(212,175,55,0.2); color:#d4af37; border:1px solid rgba(212,175,55,0.4);">
                    <?php echo $overall_pct; ?>% Overall Aggregate
                </span>
            </div>
            <div style="font-size:13px; color:#94a3b8; margin-top:4px;">
                <i class="fa-solid fa-user-graduate" style="color:#d4af37; margin-right:6px;"></i>
                <?php echo htmlspecialchars($student['name'] ?? 'Student'); ?> &bull; Roll: <strong><?php echo htmlspecialchars($student['roll_no'] ?? 'N/A'); ?></strong> &bull; <?php echo htmlspecialchars($student['course_name'] ?? ($student['course'] ?? 'B.Tech CSE')); ?>
            </div>
        </div>
    </div>

    <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
        <div style="padding:12px 20px; background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12); border-radius:12px; text-align:center;">
            <div style="font-size:11px; color:#94a3b8; text-transform:uppercase; font-weight:700;">Papers Cleared</div>
            <div style="font-size:20px; font-weight:800; color:#34d399; margin-top:2px;"><?php echo $passed_count; ?> / <?php echo $exam_count; ?></div>
        </div>

        <div style="padding:12px 20px; background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12); border-radius:12px; text-align:center;">
            <div style="font-size:11px; color:#94a3b8; text-transform:uppercase; font-weight:700;">Standing</div>
            <div style="font-size:15px; font-weight:800; color:#fbbf24; margin-top:4px;">
                <?php echo ($overall_pct >= 85) ? 'Distinction' : (($overall_pct >= 60) ? 'First Class' : ($overall_pct >= 40 ? 'Passed' : 'Under Review')); ?>
            </div>
        </div>

        <button type="button" class="btn btn-gold" onclick="window.print()" style="padding:12px 20px; font-size:13px;">
            <i class="fa-solid fa-print"></i> Print Grade Sheet
        </button>
    </div>
</div>

<!-- Tabs Navigation -->
<div style="display:flex; gap:10px; margin-bottom:20px; border-bottom:1px solid #e2e8f0; padding-bottom:12px;">
    <button type="button" id="tabBtnExams" class="btn btn-primary" onclick="switchTab('exams')" style="font-weight:700;">
        <i class="fa-solid fa-certificate"></i> Official Semester Exam Results (<?php echo $exam_count; ?>)
    </button>
    <button type="button" id="tabBtnAssignments" class="btn btn-outline" onclick="switchTab('assignments')" style="font-weight:600;">
        <i class="fa-solid fa-file-pen"></i> Continuous Assessment & Assignments (<?php echo $asg_count; ?>)
    </button>
</div>

<!-- Tab 1: Official Semester Examination Results -->
<div id="tabExamsContent" class="card" style="display:block;">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-graduation-cap"></i> Semester End Examination Grade Card</h3>
            <span class="badge badge-primary">Semester <?php echo htmlspecialchars($student['semester'] ?? '3'); ?></span>
        </div>
        <div style="font-size:13px; color:var(--text-muted);">
            <i class="fa-solid fa-shield-check" style="color:var(--success);"></i> Controller of Examinations Certified
        </div>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Semester</th>
                        <th>Subject Name</th>
                        <th>Examination Type</th>
                        <th>Max Marks</th>
                        <th>Marks Secured</th>
                        <th>Grade</th>
                        <th>Status</th>
                        <th>Published Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($exam_count > 0): ?>
                        <?php $idx = 1; foreach ($exam_results as $row): 
                            $grade = $row['grade'];
                            $badge = ($grade === 'O' || $grade === 'A+') ? 'badge-success' : (($grade === 'A' || $grade === 'B+') ? 'badge-primary' : (($grade === 'B' || $grade === 'C') ? 'badge-warning' : 'badge-danger'));
                            $status_badge = ($row['status'] === 'Pass') ? 'badge-success' : 'badge-danger';
                        ?>
                            <tr>
                                <td><strong><?php echo $idx++; ?></strong></td>
                                <td><span class="badge badge-dark">Sem <?php echo $row['semester']; ?></span></td>
                                <td>
                                    <div style="font-weight:700; color:var(--text-primary); font-size:14px;"><?php echo htmlspecialchars($row['subject_name']); ?></div>
                                    <div style="font-size:11px; color:var(--text-muted);"><?php echo htmlspecialchars($row['course_code'] ?? 'CS'); ?> Core Curriculum</div>
                                </td>
                                <td>
                                    <span style="font-size:13px; color:var(--text-secondary);"><?php echo htmlspecialchars($row['exam_type'] ?? 'Semester Final'); ?></span>
                                </td>
                                <td><?php echo $row['total_marks']; ?></td>
                                <td>
                                    <strong style="font-size:16px; color:var(--text-primary);"><?php echo $row['marks_obtained']; ?></strong>
                                    <span style="font-size:11px; color:var(--text-muted);">/ <?php echo $row['total_marks']; ?></span>
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
                            </tr>
                        <?php endforeach; ?>
                        <tr style="background:#f8fafc; font-weight:700;">
                            <td colspan="4" style="text-align:right; font-size:14px; color:var(--text-primary);">Total Aggregate Marks:</td>
                            <td style="font-size:15px; color:var(--text-primary);"><?php echo $exam_total_marks; ?></td>
                            <td style="font-size:16px; color:var(--primary);"><?php echo $exam_obtained_marks; ?> (<?php echo $overall_pct; ?>%)</td>
                            <td colspan="3" style="color:var(--success); font-size:14px;">SGPA: <?php echo number_format($sgpa, 2); ?> / 10.0</td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="empty-state">
                                <i class="fa-solid fa-award"></i>
                                <h4>No semester results released yet</h4>
                                <p>Official semester results will be published here upon verification by the examination division.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tab 2: Assignment & Continuous Assessment Grades -->
<div id="tabAssignmentsContent" class="card" style="display:none;">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-file-pen"></i> Continuous Evaluation & Graded Assignments</h3>
            <span class="badge badge-primary"><?php echo $asg_count; ?> Tasks</span>
        </div>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Assignment</th>
                        <th>Subject</th>
                        <th>Score</th>
                        <th>Letter Grade</th>
                        <th>Faculty Remarks & Feedback</th>
                        <th>Graded Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($asg_count > 0): ?>
                        <?php foreach ($asg_grades as $row): 
                            $m = (int)$row['marks'];
                            if ($m >= 90) { $letter = 'O'; $badge = 'badge-success'; }
                            elseif ($m >= 80) { $letter = 'A+'; $badge = 'badge-success'; }
                            elseif ($m >= 70) { $letter = 'A'; $badge = 'badge-primary'; }
                            elseif ($m >= 60) { $letter = 'B+'; $badge = 'badge-primary'; }
                            elseif ($m >= 50) { $letter = 'B'; $badge = 'badge-warning'; }
                            elseif ($m >= 40) { $letter = 'C'; $badge = 'badge-warning'; }
                            else { $letter = 'F'; $badge = 'badge-danger'; }
                        ?>
                            <tr>
                                <td><strong>#<?php echo $row['grade_id']; ?></strong></td>
                                <td>
                                    <div style="font-weight:700; color:var(--text-primary); font-size:14px;"><?php echo htmlspecialchars($row['assignment_title']); ?></div>
                                </td>
                                <td><span class="badge badge-primary"><?php echo htmlspecialchars($row['subject_name'] ?? 'General'); ?></span></td>
                                <td>
                                    <strong style="font-size:16px; color:var(--text-primary);"><?php echo $m; ?></strong> / 100
                                </td>
                                <td><span class="badge <?php echo $badge; ?>" style="font-size:13px; font-weight:700;"><?php echo $letter; ?></span></td>
                                <td>
                                    <div style="font-size:13px; color:#475569; max-width:280px;">
                                        "<?php echo htmlspecialchars($row['remarks'] ?? 'Good work.'); ?>"
                                    </div>
                                </td>
                                <td>
                                    <span style="font-size:12px; color:var(--text-muted);">
                                        <?php echo isset($row['graded_at']) ? date('d M Y', strtotime($row['graded_at'])) : 'Recent'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="empty-state">
                                <i class="fa-solid fa-award"></i>
                                <h4>No evaluated assignments yet</h4>
                                <p>Once your assignments are reviewed and graded by instructors, their marks will show here.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    const examsTab = document.getElementById('tabExamsContent');
    const asgTab = document.getElementById('tabAssignmentsContent');
    const btnExams = document.getElementById('tabBtnExams');
    const btnAsg = document.getElementById('tabBtnAssignments');

    if (tab === 'exams') {
        examsTab.style.display = 'block';
        asgTab.style.display = 'none';
        btnExams.className = 'btn btn-primary';
        btnAsg.className = 'btn btn-outline';
    } else {
        examsTab.style.display = 'none';
        asgTab.style.display = 'block';
        btnExams.className = 'btn btn-outline';
        btnAsg.className = 'btn btn-primary';
    }
}
</script>

<?php include("../includes/footer.php"); ?>
