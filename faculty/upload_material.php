<?php
/**
 * Upload Study Material (Faculty)
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('faculty');

$page_title = "Upload Study Material";
$user_id = $_SESSION['user_id'];
$error = "";
$subj_col = get_subject_col($con);

// Get faculty ID
$f_query = mysqli_query($con, "SELECT faculty_id FROM faculty WHERE user_id='$user_id' LIMIT 1");
$faculty = ($f_query && safe_num_rows($f_query) > 0) ? safe_fetch_assoc($f_query) : [];
$faculty_id = $faculty['faculty_id'] ?? 0;

// Subjects
$subjects = mysqli_query($con, "SELECT subject_id, `$subj_col` AS subject_name, subject_code FROM subjects ORDER BY `$subj_col` ASC");

if (isset($_POST['upload_material'])) {
    $title = escape($con, $_POST['title']);
    $description = escape($con, $_POST['description']);
    $subject_id = !empty($_POST['subject_id']) ? (int)$_POST['subject_id'] : "NULL";

    if (empty($title) || empty($_FILES['file']['name'])) {
        $error = "Material title and resource file are required.";
    } else {
        $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        $allowed = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'zip', 'txt'];

        if (in_array($ext, $allowed)) {
            $filename = time() . "_" . uniqid() . "." . $ext;
            if (!is_dir("../uploads/study_materials")) {
                mkdir("../uploads/study_materials", 0777, true);
            }

            if (move_uploaded_file($_FILES['file']['tmp_name'], "../uploads/study_materials/" . $filename)) {
                $subject_val = ($subject_id === "NULL") ? "NULL" : "'$subject_id'";
                $insert = mysqli_query($con, "
                    INSERT INTO study_materials (subject_id, faculty_id, title, description, file_path)
                    VALUES ($subject_val, '$faculty_id', '$title', '$description', '$filename')
                ");

                if ($insert) {
                    set_flash("success", "Study material '$title' uploaded successfully!");
                    header("Location: view_materials.php");
                    exit();
                } else {
                    $error = "Database Error: " . mysqli_error($con);
                }
            } else {
                $error = "Failed to upload file to server.";
            }
        } else {
            $error = "Supported file formats: PDF, DOC, DOCX, PPT, PPTX, TXT, and ZIP.";
        }
    }
}

include("../includes/header.php");
include("../includes/sidebar_faculty.php");
include("../includes/navbar.php");
?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fa-solid fa-cloud-arrow-up"></i> Upload Learning Material</h3>
        <a href="view_materials.php" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-left"></i> View All Materials
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
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Material Title <span class="required">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Unit 3 - Tree Traversal & Balancing Notes" required value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Subject / Course <span class="required">*</span></label>
                    <select name="subject_id" class="form-control" required>
                        <option value="">-- Select Subject --</option>
                        <?php if ($subjects && safe_num_rows($subjects) > 0): ?>
                            <?php while ($s = safe_fetch_assoc($subjects)): ?>
                                <option value="<?php echo $s['subject_id']; ?>">
                                    <?php echo htmlspecialchars($s['subject_name']); ?> (<?php echo htmlspecialchars($s['subject_code']); ?>)
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Topic Summary / Description</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Brief outline of syllabus topics, lecture notes, or reference reading..."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Upload File (PDF, PPT, DOC, ZIP) <span class="required">*</span></label>
                <div class="file-upload-box">
                    <i class="fa-solid fa-file-arrow-up"></i>
                    <p><strong>Click to browse file</strong> or drag & drop</p>
                    <span style="font-size:12px; color:#94a3b8;">PDF, PPT, PPTX, DOC, DOCX up to 25MB</span>
                    <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.txt" required>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:25px;">
                <a href="view_materials.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="upload_material" class="btn btn-primary" style="background:#0284c7;">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload Material
                </button>
            </div>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
