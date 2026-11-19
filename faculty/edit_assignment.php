<?php
/**
 * Edit Assignment (Faculty)
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('faculty');

$page_title = "Edit Assignment";
$user_id = $_SESSION['user_id'];
$error = "";
$subj_col = get_subject_col($con);

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: view_assignments.php");
    exit();
}

$assignment_id = (int)$_GET['id'];

$query = mysqli_query($con, "SELECT * FROM assignments WHERE assignment_id='$assignment_id' LIMIT 1");
if (!$query || safe_num_rows($query) === 0) {
    set_flash("danger", "Assignment not found.");
    header("Location: view_assignments.php");
    exit();
}

$row = ($query && safe_num_rows($query) > 0) ? safe_fetch_assoc($query) : [];

// Subjects
$subjects = mysqli_query($con, "SELECT subject_id, `$subj_col` AS subject_name, subject_code FROM subjects ORDER BY `$subj_col` ASC");

if (isset($_POST['update_assignment'])) {
    $title = escape($con, $_POST['title']);
    $description = escape($con, $_POST['description']);
    $subject_id = !empty($_POST['subject_id']) ? (int)$_POST['subject_id'] : "NULL";
    $deadline = escape($con, $_POST['deadline']);

    if (empty($title) || empty($description) || empty($deadline)) {
        $error = "Title, description, and deadline are required.";
    } else {
        $file_sql = "";
        if (!empty($_FILES['file']['name'])) {
            $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            $allowed = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar'];
            if (in_array($ext, $allowed)) {
                $new_file = time() . "_" . uniqid() . "." . $ext;
                if (!is_dir("../uploads/assignments")) {
                    mkdir("../uploads/assignments", 0777, true);
                }
                if (move_uploaded_file($_FILES['file']['tmp_name'], "../uploads/assignments/" . $new_file)) {
                    $file_sql = ", file_path='$new_file'";
                }
            } else {
                $error = "Only PDF, DOC, DOCX, TXT, and ZIP formats are allowed.";
            }
        }

        if (empty($error)) {
            $subject_val = ($subject_id === "NULL") ? "NULL" : "'$subject_id'";
            $update = mysqli_query($con, "
                UPDATE assignments 
                SET title='$title', description='$description', subject_id=$subject_val, deadline='$deadline' $file_sql
                WHERE assignment_id='$assignment_id'
            ");

            if ($update) {
                set_flash("success", "Assignment updated successfully!");
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
        <h3><i class="fa-solid fa-pen-to-square"></i> Edit Assignment: <?php echo htmlspecialchars($row['title']); ?></h3>
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
                <label class="form-label">Assignment Title <span class="required">*</span></label>
                <input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($row['title']); ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Subject / Course <span class="required">*</span></label>
                    <select name="subject_id" class="form-control" required>
                        <option value="">-- Select Subject --</option>
                        <?php if ($subjects && safe_num_rows($subjects) > 0): ?>
                            <?php while ($s = safe_fetch_assoc($subjects)): ?>
                                <option value="<?php echo $s['subject_id']; ?>" <?php echo ($row['subject_id'] == $s['subject_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($s['subject_name']); ?> (<?php echo htmlspecialchars($s['subject_code']); ?>)
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Submission Deadline <span class="required">*</span></label>
                    <input type="date" name="deadline" class="form-control" required value="<?php echo htmlspecialchars($row['deadline']); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Problem Statement / Description <span class="required">*</span></label>
                <textarea name="description" class="form-control" rows="6" required><?php echo htmlspecialchars($row['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Replace Attachment File (Optional)</label>
                <?php if (!empty($row['file_path'])): ?>
                    <div style="font-size:13px; color:var(--text-secondary); margin-bottom:8px;">
                        <i class="fa-solid fa-paperclip"></i> Current file: <strong><?php echo htmlspecialchars($row['file_path']); ?></strong>
                    </div>
                <?php endif; ?>
                <input type="file" name="file" accept=".pdf,.doc,.docx,.txt,.zip" class="form-control">
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:25px;">
                <a href="view_assignments.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="update_assignment" class="btn btn-primary" style="background:#0284c7;">
                    <i class="fa-solid fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
