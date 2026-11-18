<?php
/**
 * View Assignments (Student)
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('student');

$page_title = "Available Assignments";
$user_id = $_SESSION['user_id'];
$subj_col = get_subject_col($con);

// Get student_id
$st_query = mysqli_query($con, "SELECT student_id FROM students WHERE user_id='$user_id' LIMIT 1");
$student = mysqli_fetch_assoc($st_query);
$student_id = $student['student_id'] ?? 0;

$search = "";
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = escape($con, $_GET['search']);
    $query = mysqli_query($con, "
        SELECT a.*, s.`$subj_col` AS subject_name, u.name AS faculty_name,
               (SELECT submission_id FROM submissions sub WHERE sub.assignment_id = a.assignment_id AND sub.student_id = '$student_id' LIMIT 1) as submitted_id
        FROM assignments a
        LEFT JOIN subjects s ON a.subject_id = s.subject_id
        LEFT JOIN faculty f ON a.faculty_id = f.faculty_id
        LEFT JOIN users u ON f.user_id = u.id
        WHERE a.title LIKE '%$search%'
           OR a.description LIKE '%$search%'
           OR s.`$subj_col` LIKE '%$search%'
        ORDER BY a.assignment_id DESC
    ");
} else {
    $query = mysqli_query($con, "
        SELECT a.*, s.`$subj_col` AS subject_name, u.name AS faculty_name,
               (SELECT submission_id FROM submissions sub WHERE sub.assignment_id = a.assignment_id AND sub.student_id = '$student_id' LIMIT 1) as submitted_id
        FROM assignments a
        LEFT JOIN subjects s ON a.subject_id = s.subject_id
        LEFT JOIN faculty f ON a.faculty_id = f.faculty_id
        LEFT JOIN users u ON f.user_id = u.id
        ORDER BY a.assignment_id DESC
    ");
}

include("../includes/header.php");
include("../includes/sidebar_student.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-file-pen"></i> Available Coursework & Assignments</h3>
            <span class="badge badge-primary"><?php echo mysqli_num_rows($query); ?> Assignments</span>
        </div>
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <form method="GET" class="search-toolbar">
                <div class="search-input-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" class="form-control" placeholder="Search by title, subject..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
                <?php if (!empty($search)): ?>
                    <a href="view_assignments.php" class="btn btn-outline" title="Clear Search"><i class="fa-solid fa-rotate-left"></i></a>
                <?php endif; ?>
            </form>
            <a href="my_submissions.php" class="btn btn-outline">
                <i class="fa-solid fa-paper-plane"></i> My Submissions
            </a>
        </div>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Assignment Title & Instructions</th>
                        <th>Subject</th>
                        <th>Instructor</th>
                        <th>Deadline</th>
                        <th>Attachment</th>
                        <th style="text-align:right;">Submission Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($query && mysqli_num_rows($query) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($query)): 
                            $is_submitted = !empty($row['submitted_id']);
                            $is_overdue = (strtotime($row['deadline']) < strtotime(date('Y-m-d')));
                        ?>
                            <tr>
                                <td><strong>#<?php echo $row['assignment_id']; ?></strong></td>
                                <td>
                                    <div style="font-weight:700; color:var(--text-primary); font-size:15px;"><?php echo htmlspecialchars($row['title']); ?></div>
                                    <div style="font-size:13px; color:#475569; max-width:320px; line-height:1.4; margin-top:4px;">
                                        <?php echo htmlspecialchars($row['description']); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($row['subject_name'])): ?>
                                        <span class="badge badge-primary"><?php echo htmlspecialchars($row['subject_name']); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-dark">General</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['faculty_name'] ?? 'Faculty Staff'); ?></td>
                                <td>
                                    <?php if ($is_overdue): ?>
                                        <span class="badge badge-danger"><i class="fa-solid fa-clock"></i> <?php echo date('d M Y', strtotime($row['deadline'])); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-success"><i class="fa-solid fa-calendar-check"></i> <?php echo date('d M Y', strtotime($row['deadline'])); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($row['file_path']) && file_exists("../uploads/assignments/" . $row['file_path'])): ?>
                                        <a href="../uploads/assignments/<?php echo urlencode($row['file_path']); ?>" class="btn btn-outline btn-sm" download title="Download Problem Sheet">
                                            <i class="fa-solid fa-download"></i> Task Sheet
                                        </a>
                                    <?php else: ?>
                                        <span style="color:#94a3b8; font-size:12px;">No File</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:right;">
                                    <?php if ($is_submitted): ?>
                                        <span class="badge badge-success" style="padding:8px 12px; font-size:12px;">
                                            <i class="fa-solid fa-circle-check"></i> Submitted
                                        </span>
                                        <a href="my_submissions.php" class="btn btn-outline btn-sm" style="margin-left:6px;">View</a>
                                    <?php else: ?>
                                        <a href="submit_assignment.php?id=<?php echo $row['assignment_id']; ?>" class="btn btn-primary btn-sm" style="background:#2563eb;">
                                            <i class="fa-solid fa-upload"></i> Submit Solution
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="empty-state">
                                <i class="fa-solid fa-file-circle-xmark"></i>
                                <h4>No assignments available</h4>
                                <p><?php echo !empty($search) ? 'No coursework matched your search query.' : 'There are currently no open assignments.'; ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
