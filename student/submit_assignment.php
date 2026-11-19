<?php
/**
 * Submit Assignment (Student)
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('student');

$page_title = "Submit Assignment Solution";
$user_id = $_SESSION['user_id'];
$error = "";
$subj_col = get_subject_col($con);

// Get student_id
$st_query = mysqli_query($con, "SELECT student_id FROM students WHERE user_id='$user_id' LIMIT 1");
$student = ($st_query && safe_num_rows($st_query) > 0) ? safe_fetch_assoc($st_query) : [];
$student_id = $student['student_id'] ?? 0;

$preset_assignment_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch all assignments
$assignments = mysqli_query($con, "
    SELECT a.*, s.`$subj_col` AS subject_name 
    FROM assignments a
    LEFT JOIN subjects s ON a.subject_id = s.subject_id
    ORDER BY a.assignment_id DESC
");

if (isset($_POST['submit_solution'])) {
    $assignment_id = (int)$_POST['assignment_id'];

    if (empty($assignment_id) || empty($_FILES['file']['name'])) {
        $error = "Please select an assignment and attach your solution file.";
    } else {
        // Check if student already submitted this assignment
        $check_sub = mysqli_query($con, "
            SELECT submission_id FROM submissions 
            WHERE assignment_id='$assignment_id' AND student_id='$student_id' 
            LIMIT 1
        ");

        $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        $allowed = ['pdf', 'doc', 'docx', 'zip', 'rar', 'txt', 'py', 'java', 'cpp', 'c', 'php', 'html'];

        if (in_array($ext, $allowed)) {
            $filename = time() . "_stu" . $student_id . "_" . uniqid() . "." . $ext;
            if (!is_dir("../uploads/submissions")) {
                mkdir("../uploads/submissions", 0777, true);
            }

            if (move_uploaded_file($_FILES['file']['tmp_name'], "../uploads/submissions/" . $filename)) {
                if (safe_num_rows($check_sub) > 0) {
                    // Update existing submission with new file
                    $existing = safe_fetch_assoc($check_sub);
                    mysqli_query($con, "
                        UPDATE submissions 
                        SET file_path='$filename', submitted_at=NOW(), status='Resubmitted'
                        WHERE submission_id='{$existing['submission_id']}'
                    ");
                    set_flash("success", "Your solution has been successfully updated and resubmitted!");
                } else {
                    // Insert new submission with the CORRECT student_id
                    mysqli_query($con, "
                        INSERT INTO submissions (assignment_id, student_id, file_path, status)
                        VALUES ('$assignment_id', '$student_id', '$filename', 'Submitted')
                    ");
                    set_flash("success", "Assignment solution uploaded and submitted successfully!");
                }

                header("Location: my_submissions.php");
                exit();
            } else {
                $error = "Failed to save file to server uploads directory.";
            }
        } else {
            $error = "Permitted formats: PDF, DOC, DOCX, ZIP, RAR, TXT, code files (PY, JAVA, CPP, PHP).";
        }
    }
}

include("../includes/header.php");
include("../includes/sidebar_student.php");
include("../includes/navbar.php");
?>

<div class="card" style="max-width: 750px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fa-solid fa-upload"></i> Turn In Assignment Solution</h3>
        <a href="view_assignments.php" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Back to Assignments
        </a>
    </div>
    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div><?php echo htmlspecialchars($error); ?></div>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Select Coursework Assignment <span class="required">*</span></label>
                <select name="assignment_id" class="form-control" required id="assignmentSelect" onchange="showDeadlineInfo(this)">
                    <option value="">-- Choose Assignment --</option>
                    <?php if ($assignments && safe_num_rows($assignments) > 0): ?>
                        <?php while ($a = safe_fetch_assoc($assignments)): 
                            $selected = ($preset_assignment_id == $a['assignment_id']) ? 'selected' : '';
                        ?>
                            <option value="<?php echo $a['assignment_id']; ?>" data-deadline="<?php echo $a['deadline']; ?>" <?php echo $selected; ?>>
                                <?php echo htmlspecialchars($a['title']); ?> (<?php echo htmlspecialchars($a['subject_name'] ?? 'General'); ?>) - Due: <?php echo date('d M Y', strtotime($a['deadline'])); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Attach Solution File (PDF, DOCX, ZIP, Code) <span class="required">*</span></label>
                <div class="file-upload-box">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <p><strong>Click to browse solution file</strong> or drag & drop</p>
                    <span style="font-size:12px; color:#94a3b8;">PDF, Word, or ZIP up to 25MB</span>
                    <input type="file" name="file" required accept=".pdf,.doc,.docx,.zip,.rar,.txt,.py,.java,.cpp,.c,.php,.html">
                </div>
            </div>

            <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:15px; margin-top:20px; font-size:13px; color:#166534;">
                <i class="fa-solid fa-circle-info"></i> Tip: Ensure your name and roll number are written in the header or title page of your document before uploading.
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:25px;">
                <a href="view_assignments.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="submit_solution" class="btn btn-primary" style="background:#2563eb;">
                    <i class="fa-solid fa-paper-plane"></i> Turn In Solution
                </button>
            </div>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
