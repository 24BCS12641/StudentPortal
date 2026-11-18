<?php
/**
 * Faculty - Enter & Manage Student Exam Results
 * Apex Imperial University Portal
 */

include("../config/auth.php");
include("../config/db.php");
check_role('faculty');

$page_title = "Enter & Manage Examination Results";
$user_id = $_SESSION['user_id'];

// Get faculty record
$f_res = mysqli_query($con, "SELECT faculty_id FROM faculty WHERE user_id='$user_id' LIMIT 1");
$faculty = mysqli_fetch_assoc($f_res);
$faculty_id = $faculty['faculty_id'] ?? 0;

// Save Exam Result
if (isset($_POST['save_result'])) {
    $student_id = (int)$_POST['student_id'];
    $course_id = (int)$_POST['course_id'];
    $semester = (int)$_POST['semester'];
    $subject_name = escape($con, $_POST['subject_name']);
    $exam_type = escape($con, $_POST['exam_type']);
    $total_marks = (int)$_POST['total_marks'];
    $marks_obtained = (int)$_POST['marks_obtained'];

    // Calculate Grade
    $pct = ($total_marks > 0) ? ($marks_obtained / $total_marks) * 100 : 0;
    if ($pct >= 90) $grade = 'O';
    elseif ($pct >= 80) $grade = 'A+';
    elseif ($pct >= 70) $grade = 'A';
    elseif ($pct >= 60) $grade = 'B+';
    elseif ($pct >= 50) $grade = 'B';
    elseif ($pct >= 40) $grade = 'C';
    else $grade = 'F';

    $status = ($grade !== 'F') ? 'Pass' : 'Fail';

    if ($student_id <= 0 || empty($subject_name)) {
        set_flash("danger", "Please select a student and enter the subject name.");
    } else {
        mysqli_query($con, "
            INSERT INTO results (student_id, course_id, semester, subject_name, exam_type, total_marks, marks_obtained, grade, status)
            VALUES ('$student_id', '$course_id', '$semester', '$subject_name', '$exam_type', '$total_marks', '$marks_obtained', '$grade', '$status')
        ");
        set_flash("success", "Examination result for '$subject_name' recorded successfully!");
        header("Location: manage_results.php");
        exit();
    }
}

// Delete Result
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    mysqli_query($con, "DELETE FROM results WHERE result_id='$del_id'");
    set_flash("success", "Result record deleted.");
    header("Location: manage_results.php");
    exit();
}

// Fetch results
$results_q = mysqli_query($con, "
    SELECT r.*, u.name AS student_name, s.roll_no, c.code AS course_code
    FROM results r
    JOIN students s ON r.student_id = s.student_id
    JOIN users u ON s.user_id = u.id
    LEFT JOIN courses c ON r.course_id = c.course_id
    ORDER BY r.result_id DESC
");

// Fetch student list for select dropdown
$students_list = mysqli_query($con, "
    SELECT s.student_id, s.roll_no, s.course_id, s.semester, u.name
    FROM students s
    JOIN users u ON s.user_id = u.id
    ORDER BY s.roll_no ASC
");

// Fetch courses
$courses_list = mysqli_query($con, "SELECT course_id, name, code FROM courses ORDER BY name ASC");

include("../includes/header.php");
include("../includes/sidebar_faculty.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-square-poll-vertical"></i> Student Semester Examination Results</h3>
            <span class="badge badge-primary"><?php echo mysqli_num_rows($results_q); ?> Published Results</span>
        </div>
        <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('resultModal').style.display='flex'">
            <i class="fa-solid fa-plus"></i> Enter Student Marks
        </button>
    </div>

    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Roll Number</th>
                        <th>Subject & Semester</th>
                        <th>Exam Type</th>
                        <th>Score</th>
                        <th>Grade</th>
                        <th>Status</th>
                        <th style="text-align:right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($results_q && mysqli_num_rows($results_q) > 0): ?>
                        <?php while ($r = mysqli_fetch_assoc($results_q)): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($r['student_name']); ?></strong>
                                </td>
                                <td><span class="badge badge-dark"><?php echo htmlspecialchars($r['roll_no']); ?></span></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($r['subject_name']); ?></strong>
                                    <div style="font-size:12px; color:var(--text-muted);">Sem <?php echo $r['semester']; ?> &bull; <?php echo htmlspecialchars($r['course_code'] ?? 'BCA'); ?></div>
                                </td>
                                <td><span class="badge badge-primary"><?php echo htmlspecialchars($r['exam_type']); ?></span></td>
                                <td><strong><?php echo $r['marks_obtained']; ?></strong> / <?php echo $r['total_marks']; ?></td>
                                <td><span class="badge badge-success" style="font-weight:800; font-size:13px;"><?php echo htmlspecialchars($r['grade']); ?></span></td>
                                <td>
                                    <?php if ($r['status'] === 'Pass'): ?>
                                        <span class="badge badge-success"><i class="fa-solid fa-check"></i> Pass</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger"><i class="fa-solid fa-xmark"></i> Fail</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:right;">
                                    <a href="manage_results.php?delete=<?php echo $r['result_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this result record?');">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="empty-state">No examination results published yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Result Modal -->
<div id="resultModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:var(--radius-lg); padding:30px; max-width:600px; width:90%; box-shadow:var(--shadow-xl);">
        <h3 style="font-weight:700; margin-bottom:15px; color:var(--text-primary);">Enter Examination Marks</h3>
        <form method="POST">
            <div class="form-group">
                <label class="form-label">Select Student <span class="required">*</span></label>
                <select name="student_id" class="form-control" required>
                    <option value="">-- Choose Student --</option>
                    <?php 
                    mysqli_data_seek($students_list, 0);
                    while ($st = mysqli_fetch_assoc($students_list)): 
                    ?>
                        <option value="<?php echo $st['student_id']; ?>">
                            <?php echo htmlspecialchars($st['name']); ?> (Roll: <?php echo htmlspecialchars($st['roll_no']); ?> &bull; Sem <?php echo $st['semester']; ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Degree Program <span class="required">*</span></label>
                    <select name="course_id" class="form-control" required>
                        <?php 
                        mysqli_data_seek($courses_list, 0);
                        while ($crs = mysqli_fetch_assoc($courses_list)): 
                        ?>
                            <option value="<?php echo $crs['course_id']; ?>">
                                <?php echo htmlspecialchars($crs['name']); ?> (<?php echo $crs['code']; ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Semester <span class="required">*</span></label>
                    <select name="semester" class="form-control" required>
                        <option value="1">Semester 1</option>
                        <option value="2">Semester 2</option>
                        <option value="3">Semester 3</option>
                        <option value="4" selected>Semester 4</option>
                        <option value="5">Semester 5</option>
                        <option value="6">Semester 6</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Subject / Paper Name <span class="required">*</span></label>
                <input type="text" name="subject_name" class="form-control" placeholder="e.g. Database Management Systems" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Exam Type</label>
                    <select name="exam_type" class="form-control">
                        <option value="End Semester Examination">End Semester Examination</option>
                        <option value="Mid-Term Theory Test">Mid-Term Theory Test</option>
                        <option value="Practical Lab Viva">Practical Lab Viva</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Marks Obtained <span class="required">*</span></label>
                    <input type="number" name="marks_obtained" class="form-control" placeholder="e.g. 92" min="0" max="100" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Total Maximum Marks</label>
                    <input type="number" name="total_marks" class="form-control" value="100" required>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:20px;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('resultModal').style.display='none'">Cancel</button>
                <button type="submit" name="save_result" class="btn btn-primary">Publish Result</button>
            </div>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
