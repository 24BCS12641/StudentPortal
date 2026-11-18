<?php
/**
 * Mark Attendance (Faculty)
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('faculty');

$page_title = "Mark Attendance";
$error = "";
$subj_col = get_subject_col($con);

// Fetch students list
$students = mysqli_query($con, "
    SELECT students.student_id, students.roll_no, students.course, users.name
    FROM students
    JOIN users ON students.user_id = users.id
    ORDER BY students.roll_no ASC
");

// Fetch subjects
$subjects = mysqli_query($con, "
    SELECT subject_id, `$subj_col` AS subject_name, subject_code
    FROM subjects
    ORDER BY `$subj_col` ASC
");

if (isset($_POST['save_attendance'])) {
    $student_id = (int)$_POST['student_id'];
    $subject_id = (int)$_POST['subject_id'];
    $attendance_date = escape($con, $_POST['attendance_date']);
    $status = escape($con, $_POST['status']);

    if (empty($student_id) || empty($subject_id) || empty($attendance_date) || empty($status)) {
        $error = "Please fill in all required fields.";
    } else {
        // Check if attendance already exists for this date, student, and subject
        $check = mysqli_query($con, "
            SELECT attendance_id FROM attendance
            WHERE student_id='$student_id' AND subject_id='$subject_id' AND attendance_date='$attendance_date'
            LIMIT 1
        ");

        if (mysqli_num_rows($check) > 0) {
            // Update existing record
            $att_row = mysqli_fetch_assoc($check);
            mysqli_query($con, "
                UPDATE attendance 
                SET status='$status' 
                WHERE attendance_id='{$att_row['attendance_id']}'
            ");
            set_flash("info", "Attendance record for this session was updated to '$status'.");
        } else {
            // Insert new record
            mysqli_query($con, "
                INSERT INTO attendance (student_id, subject_id, attendance_date, status)
                VALUES ('$student_id', '$subject_id', '$attendance_date', '$status')
            ");
            set_flash("success", "Attendance marked as '$status' successfully!");
        }

        header("Location: attendance_report.php");
        exit();
    }
}

include("../includes/header.php");
include("../includes/sidebar_faculty.php");
include("../includes/navbar.php");
?>

<div class="card" style="max-width: 750px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fa-solid fa-clipboard-user"></i> Mark Student Attendance</h3>
        <a href="attendance_report.php" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-calendar-days"></i> View Attendance Report
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
            <div class="form-group">
                <label class="form-label">Select Student <span class="required">*</span></label>
                <select name="student_id" class="form-control" required>
                    <option value="">-- Choose Student --</option>
                    <?php if ($students && mysqli_num_rows($students) > 0): ?>
                        <?php while ($st = mysqli_fetch_assoc($students)): ?>
                            <option value="<?php echo $st['student_id']; ?>">
                                [<?php echo htmlspecialchars($st['roll_no']); ?>] <?php echo htmlspecialchars($st['name']); ?> &bull; <?php echo htmlspecialchars($st['course']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Subject / Course Module <span class="required">*</span></label>
                    <select name="subject_id" class="form-control" required>
                        <option value="">-- Choose Subject --</option>
                        <?php if ($subjects && mysqli_num_rows($subjects) > 0): ?>
                            <?php while ($sub = mysqli_fetch_assoc($subjects)): ?>
                                <option value="<?php echo $sub['subject_id']; ?>">
                                    <?php echo htmlspecialchars($sub['subject_name']); ?> (<?php echo htmlspecialchars($sub['subject_code']); ?>)
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Lecture Date <span class="required">*</span></label>
                    <input type="date" name="attendance_date" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Attendance Status <span class="required">*</span></label>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-top:8px;">
                    <label style="border:2px solid var(--success); padding:14px; border-radius:12px; cursor:pointer; display:flex; align-items:center; gap:10px; background:#f0fdf4;">
                        <input type="radio" name="status" value="Present" checked>
                        <span style="font-weight:700; color:#166534;"><i class="fa-solid fa-check"></i> Present</span>
                    </label>

                    <label style="border:2px solid var(--danger); padding:14px; border-radius:12px; cursor:pointer; display:flex; align-items:center; gap:10px; background:#fef2f2;">
                        <input type="radio" name="status" value="Absent">
                        <span style="font-weight:700; color:#991b1b;"><i class="fa-solid fa-xmark"></i> Absent</span>
                    </label>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:25px;">
                <a href="attendance_report.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="save_attendance" class="btn btn-primary" style="background:#0284c7;">
                    <i class="fa-solid fa-save"></i> Save Attendance Record
                </button>
            </div>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
