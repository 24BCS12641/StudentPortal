<?php
/**
 * Student Courses & Syllabus Details
 * Apex Imperial University Portal
 */

include("../config/auth.php");
include("../config/db.php");
check_role('student');

$page_title = "My Degree & Course Details";
$user_id = $_SESSION['user_id'];
$subj_col = get_subject_col($con);

// Get student details
$st_q = mysqli_query($con, "
    SELECT s.*, u.name, u.email
    FROM students s
    JOIN users u ON s.user_id = u.id
    WHERE s.user_id='$user_id'
    LIMIT 1
");
$student = mysqli_fetch_assoc($st_q);
$student_id = $student['student_id'] ?? 0;
$course_id = $student['course_id'] ?? 0;

// Get enrolled course details from courses table
$course_info = null;
if ($course_id > 0) {
    $c_res = mysqli_query($con, "
        SELECT c.*, d.name AS dept_name, d.head_of_dept
        FROM courses c
        LEFT JOIN departments d ON c.department_id = d.department_id
        WHERE c.course_id='$course_id'
        LIMIT 1
    ");
    $course_info = mysqli_fetch_assoc($c_res);
}

// Fetch active subjects in this curriculum
$subjects_q = mysqli_query($con, "
    SELECT s.*, f.specialization, u.name AS faculty_name, u.email AS faculty_email
    FROM subjects s
    LEFT JOIN faculty f ON s.faculty_id = f.faculty_id
    LEFT JOIN users u ON f.user_id = u.id
    ORDER BY s.subject_id ASC
");

include("../includes/header.php");
include("../includes/sidebar_student.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-graduation-cap"></i> Enrolled Degree Program Details</h3>
            <span class="badge badge-primary">Active Enrollment</span>
        </div>
        <a href="performance_report.php" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-chart-simple"></i> View Performance Card
        </a>
    </div>

    <div class="card-body">
        <div class="row" style="display:flex; flex-wrap:wrap; gap:20px; margin-bottom:25px;">
            <div style="flex:1; min-width:280px; background:var(--bg-body); padding:20px; border-radius:var(--radius-md); border:1px solid var(--border-color);">
                <small class="text-muted" style="text-transform:uppercase; font-size:11px; font-weight:700;">Degree Program</small>
                <h4 style="font-weight:800; color:var(--text-primary); margin:4px 0 8px;">
                    <?php echo htmlspecialchars($course_info['name'] ?? ($student['course'] ?? 'Bachelor of Computer Applications')); ?>
                </h4>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <span class="badge badge-primary"><?php echo htmlspecialchars($course_info['code'] ?? 'BCA'); ?></span>
                    <span class="badge badge-dark"><?php echo htmlspecialchars($course_info['degree_level'] ?? 'Undergraduate'); ?></span>
                    <span class="badge badge-info">Semester <?php echo htmlspecialchars($student['semester'] ?? 4); ?></span>
                </div>
            </div>

            <div style="flex:1; min-width:280px; background:var(--bg-body); padding:20px; border-radius:var(--radius-md); border:1px solid var(--border-color);">
                <small class="text-muted" style="text-transform:uppercase; font-size:11px; font-weight:700;">School & Academic Department</small>
                <h5 style="font-weight:700; color:var(--text-primary); margin:4px 0 8px;">
                    <?php echo htmlspecialchars($course_info['dept_name'] ?? 'School of Computer Science & Engineering'); ?>
                </h5>
                <p style="margin:0; font-size:13px; color:var(--text-secondary);">
                    <i class="fa-solid fa-user-tie text-gold"></i> <strong>Head of Department:</strong> <?php echo htmlspecialchars($course_info['head_of_dept'] ?? 'Dr. Rajesh Sharma'); ?>
                </p>
            </div>
        </div>

        <h4 style="font-weight:700; color:var(--text-primary); margin-bottom:15px; display:flex; align-items:center; gap:8px;">
            <i class="fa-solid fa-book-open text-primary"></i> Current Semester Curriculum Modules
        </h4>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Assigned Faculty</th>
                        <th>Teaching Department</th>
                        <th>Credits / Type</th>
                        <th style="text-align:right;">Materials</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($subjects_q && mysqli_num_rows($subjects_q) > 0): ?>
                        <?php while ($sub = mysqli_fetch_assoc($subjects_q)): 
                            $name = $sub['subject_name'] ?? ($sub['suject_name'] ?? 'Core Subject');
                        ?>
                            <tr>
                                <td><span class="badge badge-dark"><?php echo htmlspecialchars($sub['subject_code']); ?></span></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($name); ?></strong>
                                </td>
                                <td>
                                    <?php if (!empty($sub['faculty_name'])): ?>
                                        <div style="font-weight:600; color:var(--text-primary);"><?php echo htmlspecialchars($sub['faculty_name']); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($sub['faculty_email']); ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">Faculty Assigned</span>
                                    <?php endif; ?>
                                </td>
                                <td>Computer Science</td>
                                <td><span class="badge badge-primary">4 Credits &bull; Core Theory</span></td>
                                <td style="text-align:right;">
                                    <a href="study_materials.php" class="btn btn-outline btn-sm">
                                        <i class="fa-solid fa-folder-open"></i> Notes
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
