<?php
/**
 * Grade Student Assignment (Faculty)
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('faculty');

$page_title = "Evaluate & Grade Submission";
$error = "";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: view_submissions.php");
    exit();
}

$submission_id = (int)$_GET['id'];
$subj_col = get_subject_col($con);

// Fetch submission details with student and assignment info
$query = mysqli_query($con, "
    SELECT s.*, u.name AS student_name, u.email, st.roll_no, st.course, 
           a.title AS assignment_title, a.description AS assignment_desc,
           sub.`$subj_col` AS subject_name, sub.subject_id
    FROM submissions s
    JOIN students st ON s.student_id = st.student_id
    JOIN users u ON st.user_id = u.id
    JOIN assignments a ON s.assignment_id = a.assignment_id
    LEFT JOIN subjects sub ON a.subject_id = sub.subject_id
    WHERE s.submission_id='$submission_id'
    LIMIT 1
");

if (!$query || mysqli_num_rows($query) === 0) {
    set_flash("danger", "Submission record not found.");
    header("Location: view_submissions.php");
    exit();
}

$data = ($query && mysqli_num_rows($query) > 0) ? mysqli_fetch_assoc($query) : [];

if (isset($_POST['save_grade'])) {
    $marks = (int)$_POST['marks'];
    $remarks = escape($con, $_POST['remarks']);

    if ($marks < 0 || $marks > 100) {
        $error = "Marks must be an integer between 0 and 100.";
    } else {
        $student_id = $data['student_id'];
        $assignment_id = $data['assignment_id'];
        $subject_id = !empty($data['subject_id']) ? "'{$data['subject_id']}'" : "NULL";

        // Update submissions table
        mysqli_query($con, "
            UPDATE submissions 
            SET marks='$marks', remarks='$remarks', status='Graded' 
            WHERE submission_id='$submission_id'
        ");

        // Upsert into grades table (check if existing grade exists for this student & assignment)
        $check_grade = mysqli_query($con, "
            SELECT grade_id FROM grades 
            WHERE student_id='$student_id' AND assignment_id='$assignment_id' 
            LIMIT 1
        ");

        if (mysqli_num_rows($check_grade) > 0) {
            $g_row = mysqli_fetch_assoc($check_grade);
            mysqli_query($con, "
                UPDATE grades 
                SET marks='$marks', remarks='$remarks', subject_id=$subject_id 
                WHERE grade_id='{$g_row['grade_id']}'
            ");
        } else {
            mysqli_query($con, "
                INSERT INTO grades (student_id, assignment_id, subject_id, marks, remarks)
                VALUES ('$student_id', '$assignment_id', $subject_id, '$marks', '$remarks')
            ");
        }

        set_flash("success", "Grade of {$marks}/100 saved successfully for {$data['student_name']}!");
        header("Location: view_submissions.php");
        exit();
    }
}

include("../includes/header.php");
include("../includes/sidebar_faculty.php");
include("../includes/navbar.php");
?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fa-solid fa-award"></i> Grade Student Submission</h3>
        <a href="view_submissions.php" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Back to Submissions
        </a>
    </div>
    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div><?php echo htmlspecialchars($error); ?></div>
            </div>
        <?php endif; ?>

        <!-- Submission Summary Header -->
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:20px; margin-bottom:24px;">
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:15px;">
                <div>
                    <span style="font-size:12px; color:var(--text-muted); text-transform:uppercase;">Student</span>
                    <div style="font-weight:700; font-size:16px; color:var(--text-primary);"><?php echo htmlspecialchars($data['student_name']); ?></div>
                    <div style="font-size:12px; color:var(--text-secondary);"><i class="fa-solid fa-id-card"></i> <?php echo htmlspecialchars($data['roll_no']); ?> &bull; <?php echo htmlspecialchars($data['course']); ?></div>
                </div>

                <div>
                    <span style="font-size:12px; color:var(--text-muted); text-transform:uppercase;">Assignment</span>
                    <div style="font-weight:700; font-size:16px; color:var(--text-primary);"><?php echo htmlspecialchars($data['assignment_title']); ?></div>
                    <div style="font-size:12px; color:var(--text-secondary);"><?php echo htmlspecialchars($data['subject_name'] ?? 'General'); ?></div>
                </div>

                <div>
                    <span style="font-size:12px; color:var(--text-muted); text-transform:uppercase;">Submitted On</span>
                    <div style="font-weight:600; color:var(--text-primary);"><?php echo date('d M Y, h:i A', strtotime($data['submitted_at'])); ?></div>
                    <?php if (!empty($data['file_path']) && file_exists("../uploads/submissions/" . $data['file_path'])): ?>
                        <a href="../uploads/submissions/<?php echo urlencode($data['file_path']); ?>" class="btn btn-outline btn-sm" download style="margin-top:6px;">
                            <i class="fa-solid fa-download"></i> Download Solution
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Award Marks (out of 100) <span class="required">*</span></label>
                    <input type="number" name="marks" class="form-control" min="0" max="100" placeholder="e.g. 85" required value="<?php echo isset($data['marks']) ? htmlspecialchars($data['marks']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Quick Score Presets</label>
                    <div style="display:flex; gap:8px; margin-top:5px;">
                        <button type="button" class="btn btn-outline btn-sm" onclick="document.querySelector('input[name=marks]').value=95;">95 (A+)</button>
                        <button type="button" class="btn btn-outline btn-sm" onclick="document.querySelector('input[name=marks]').value=85;">85 (A)</button>
                        <button type="button" class="btn btn-outline btn-sm" onclick="document.querySelector('input[name=marks]').value=75;">75 (B)</button>
                        <button type="button" class="btn btn-outline btn-sm" onclick="document.querySelector('input[name=marks]').value=65;">65 (C)</button>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Faculty Feedback & Remarks <span class="required">*</span></label>
                <textarea name="remarks" class="form-control" rows="5" placeholder="Provide constructive feedback, praise strengths, and note areas of improvement..." required><?php echo isset($data['remarks']) ? htmlspecialchars($data['remarks']) : 'Well done. Keep up the good work!'; ?></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:25px;">
                <a href="view_submissions.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="save_grade" class="btn btn-primary" style="background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);">
                    <i class="fa-solid fa-check"></i> Submit Evaluation & Grade
                </button>
            </div>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
