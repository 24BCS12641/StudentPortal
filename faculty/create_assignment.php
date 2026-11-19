<?php
/**
 * Create Assignment (Faculty)
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('faculty');

$page_title = "Create New Assignment";
$user_id = $_SESSION['user_id'];
$error = "";
$subj_col = get_subject_col($con);

// Get faculty ID
$f_query = mysqli_query($con, "SELECT faculty_id FROM faculty WHERE user_id='$user_id' LIMIT 1");
$faculty = ($f_query && safe_num_rows($f_query) > 0) ? safe_fetch_assoc($f_query) : [];
$faculty_id = $faculty['faculty_id'] ?? 0;

// Get available subjects
$subjects = mysqli_query($con, "SELECT subject_id, `$subj_col` AS subject_name, subject_code FROM subjects ORDER BY `$subj_col` ASC");

if (isset($_POST['create_assignment'])) {
    $title = escape($con, $_POST['title']);
    $description = escape($con, $_POST['description']);
    $subject_id = !empty($_POST['subject_id']) ? (int)$_POST['subject_id'] : "NULL";
    $deadline = escape($con, $_POST['deadline']);

    if (empty($title) || empty($description) || empty($deadline)) {
        $error = "Assignment title, description, and deadline date are required.";
    } else {
        $filename = "";
        if (!empty($_FILES['file']['name'])) {
            $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            $allowed = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar'];
            if (in_array($ext, $allowed)) {
                $filename = time() . "_" . uniqid() . "." . $ext;
                if (!is_dir("../uploads/assignments")) {
                    mkdir("../uploads/assignments", 0777, true);
                }
                move_uploaded_file($_FILES['file']['tmp_name'], "../uploads/assignments/" . $filename);
            } else {
                $error = "Only PDF, DOC, DOCX, TXT, and ZIP/RAR file types are permitted.";
            }
        }

        if (empty($error)) {
            $subject_val = ($subject_id === "NULL") ? "NULL" : "'$subject_id'";
            $insert = mysqli_query($con, "
                INSERT INTO assignments (subject_id, faculty_id, title, description, deadline, file_path)
                VALUES ($subject_val, '$faculty_id', '$title', '$description', '$deadline', '$filename')
            ");

            if ($insert) {
                set_flash("success", "Assignment '$title' created successfully!");
                header("Location: view_assignments.php");
                exit();
            } else {
                $error = "Database Error: " . mysqli_error($con);
            }
        }
    }
}

include("../includes/header.php");
include("../includes/sidebar_faculty.php");
include("../includes/navbar.php");
?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fa-solid fa-plus-circle"></i> Create Assignment</h3>
        <a href="view_assignments.php" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-left"></i> View All Assignments
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
                <label class="form-label">Assignment Title <span class="required">*</span></label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Relational Normalization & SQL Queries Practice" required value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Subject / Course <span class="required">*</span></label>
                    <select name="subject_id" class="form-control" required>
                        <option value="">-- Select Subject --</option>
                        <?php if ($subjects && safe_num_rows($subjects) > 0): ?>
                            <?php while ($s = safe_fetch_assoc($subjects)): ?>
                                <option value="<?php echo $s['subject_id']; ?>" <?php echo (isset($_POST['subject_id']) && $_POST['subject_id'] == $s['subject_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($s['subject_name']); ?> (<?php echo htmlspecialchars($s['subject_code']); ?>)
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Submission Deadline <span class="required">*</span></label>
                    <input type="date" name="deadline" class="form-control" required value="<?php echo isset($_POST['deadline']) ? htmlspecialchars($_POST['deadline']) : date('Y-m-d', strtotime('+7 days')); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Assignment Problem Statement / Description <span class="required">*</span></label>
                <textarea name="description" class="form-control" rows="6" placeholder="Provide clear instructions, rubric details, questions, and submission requirements..." required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Attachment / Problem Sheet (Optional)</label>
                <div class="file-upload-box">
                    <i class="fa-solid fa-file-pdf"></i>
                    <p><strong>Click to attach PDF / DOCX file</strong> or drag & drop</p>
                    <span style="font-size:12px; color:#94a3b8;">Max 10MB PDF, DOCX, ZIP</span>
                    <input type="file" name="file" accept=".pdf,.doc,.docx,.txt,.zip,.rar">
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:25px;">
                <a href="view_assignments.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="create_assignment" class="btn btn-primary" style="background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);">
                    <i class="fa-solid fa-paper-plane"></i> Publish Assignment
                </button>
            </div>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
