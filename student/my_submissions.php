<?php
/**
 * My Submissions (Student)
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('student');

$page_title = "My Assignment Submissions";
$user_id = $_SESSION['user_id'];
$subj_col = get_subject_col($con);

// Get student_id
$st_query = mysqli_query($con, "SELECT student_id FROM students WHERE user_id='$user_id' LIMIT 1");
$student = ($st_query && safe_num_rows($st_query) > 0) ? safe_fetch_assoc($st_query) : [];
$student_id = $student['student_id'] ?? 0;

$query = mysqli_query($con, "
    SELECT s.*, a.title AS assignment_title, sub.`$subj_col` AS subject_name,
           g.marks AS eval_marks, g.remarks AS eval_remarks
    FROM submissions s
    JOIN assignments a ON s.assignment_id = a.assignment_id
    LEFT JOIN subjects sub ON a.subject_id = sub.subject_id
    LEFT JOIN grades g ON (g.assignment_id = s.assignment_id AND g.student_id = s.student_id)
    WHERE s.student_id='$student_id'
    ORDER BY s.submission_id DESC
");

include("../includes/header.php");
include("../includes/sidebar_student.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-paper-plane"></i> My Turned-In Submissions</h3>
            <span class="badge badge-primary"><?php echo safe_num_rows($query); ?> Submissions</span>
        </div>
        <a href="view_assignments.php" class="btn btn-primary" style="background:#2563eb;">
            <i class="fa-solid fa-plus-circle"></i> Browse Coursework
        </a>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Assignment</th>
                        <th>Subject</th>
                        <th>Submitted On</th>
                        <th>Solution File</th>
                        <th>Grade / Score</th>
                        <th>Instructor Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($query && safe_num_rows($query) > 0): ?>
                        <?php while ($row = safe_fetch_assoc($query)): 
                            $final_marks = $row['eval_marks'] ?? $row['marks'];
                            $final_remarks = $row['eval_remarks'] ?? $row['remarks'];
                            $is_graded = ($final_marks !== null && $final_marks !== '');
                        ?>
                            <tr>
                                <td><strong>#<?php echo $row['submission_id']; ?></strong></td>
                                <td>
                                    <div style="font-weight:700; color:var(--text-primary); font-size:15px;"><?php echo htmlspecialchars($row['assignment_title']); ?></div>
                                    <span class="badge badge-info" style="font-size:11px;"><?php echo htmlspecialchars($row['status'] ?? 'Submitted'); ?></span>
                                </td>
                                <td><span class="badge badge-primary"><?php echo htmlspecialchars($row['subject_name'] ?? 'General'); ?></span></td>
                                <td>
                                    <span style="font-size:13px; color:var(--text-secondary);">
                                        <?php echo date('d M Y, h:i A', strtotime($row['submitted_at'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($row['file_path']) && file_exists("../uploads/submissions/" . $row['file_path'])): ?>
                                        <a href="../uploads/submissions/<?php echo urlencode($row['file_path']); ?>" class="btn btn-outline btn-sm" download title="Download Submitted File">
                                            <i class="fa-solid fa-download"></i> Download
                                        </a>
                                    <?php else: ?>
                                        <span style="color:#94a3b8; font-size:12px;">File on record</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($is_graded): ?>
                                        <span class="badge badge-success" style="font-size:13px; font-weight:700;">
                                            <i class="fa-solid fa-check"></i> <?php echo $final_marks; ?> / 100
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">
                                            <i class="fa-solid fa-hourglass-half"></i> Awaiting Grade
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="font-size:13px; color:#475569; max-width:250px;">
                                        <?php echo !empty($final_remarks) ? htmlspecialchars($final_remarks) : '<span style="color:#94a3b8;">Pending evaluation</span>'; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="empty-state">
                                <i class="fa-solid fa-file-arrow-up"></i>
                                <h4>No solutions turned in yet</h4>
                                <p>You have not submitted any assignments yet. Visit the Assignments tab to submit coursework.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
