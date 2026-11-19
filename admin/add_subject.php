<?php
/**
 * Add Subject
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Add Subject";
$error = "";
$subj_col = get_subject_col($con);

// Fetch faculty list for dropdown
$faculty_list = mysqli_query($con, "
    SELECT faculty.faculty_id, users.name, faculty.department
    FROM faculty
    JOIN users ON faculty.user_id = users.id
    ORDER BY users.name ASC
");

if (isset($_POST['add_subject'])) {
    $subject_name = escape($con, $_POST['subject_name']);
    $subject_code = escape($con, $_POST['subject_code']);
    $faculty_id = !empty($_POST['faculty_id']) ? (int)$_POST['faculty_id'] : "NULL";
    $course = escape($con, $_POST['course'] ?? 'BCA');
    $semester = (int)($_POST['semester'] ?? 1);

    if (empty($subject_name) || empty($subject_code)) {
        $error = "Subject title and subject code are required.";
    } else {
        // Check duplicate subject code
        $check = mysqli_query($con, "SELECT subject_id FROM subjects WHERE subject_code='$subject_code' LIMIT 1");
        if (safe_num_rows($check) > 0) {
            $error = "A subject with code '$subject_code' already exists.";
        } else {
            $faculty_val = ($faculty_id === "NULL") ? "NULL" : "'$faculty_id'";
            $insert = mysqli_query($con, "
                INSERT INTO subjects (`$subj_col`, subject_code, faculty_id, course, semester)
                VALUES ('$subject_name', '$subject_code', $faculty_val, '$course', '$semester')
            ");

            if ($insert) {
                set_flash("success", "Subject '$subject_name' ($subject_code) added successfully!");
                header("Location: manage_subjects.php");
                exit();
            } else {
                $error = "Database Error: " . mysqli_error($con);
            }
        }
    }
}

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card" style="max-width: 750px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fa-solid fa-book-medical"></i> Add Curriculum Subject</h3>
        <a href="manage_subjects.php" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Back to Directory
        </a>
    </div>
    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div><?php echo htmlspecialchars($error); ?></div>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Subject Name <span class="required">*</span></label>
                    <input type="text" name="subject_name" class="form-control" placeholder="e.g. Database Management Systems" required value="<?php echo isset($_POST['subject_name']) ? htmlspecialchars($_POST['subject_name']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Subject Code <span class="required">*</span></label>
                    <input type="text" name="subject_code" class="form-control" placeholder="e.g. CS401" required value="<?php echo isset($_POST['subject_code']) ? htmlspecialchars($_POST['subject_code']) : ''; ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Assigned Faculty Incharge</label>
                <select name="faculty_id" class="form-control">
                    <option value="">-- Select Faculty (Optional) --</option>
                    <?php if ($faculty_list && safe_num_rows($faculty_list) > 0): ?>
                        <?php while ($f = safe_fetch_assoc($faculty_list)): ?>
                            <option value="<?php echo $f['faculty_id']; ?>">
                                <?php echo htmlspecialchars($f['name']); ?> (<?php echo htmlspecialchars($f['department']); ?>)
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Course</label>
                    <input type="text" name="course" class="form-control" value="BCA">
                </div>

                <div class="form-group">
                    <label class="form-label">Semester</label>
                    <select name="semester" class="form-control">
                        <?php for ($s = 1; $s <= 8; $s++): ?>
                            <option value="<?php echo $s; ?>">Semester <?php echo $s; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:25px;">
                <a href="manage_subjects.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="add_subject" class="btn btn-primary">
                    <i class="fa-solid fa-check"></i> Add Subject
                </button>
            </div>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
