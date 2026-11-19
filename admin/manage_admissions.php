<?php
/**
 * Admin - Manage Admissions & Enquiries
 * Apex Imperial University Portal
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Manage Student Admissions";

// Status Update Action
if (isset($_POST['update_status'])) {
    $admission_id = (int)$_POST['admission_id'];
    $new_status = escape($con, $_POST['status']);
    $notes = escape($con, $_POST['notes']);

    mysqli_query($con, "
        UPDATE admissions 
        SET status='$new_status', notes='$notes' 
        WHERE admission_id='$admission_id'
    ");
    set_flash("success", "Admission Application #ADM-$admission_id updated to $new_status!");
    header("Location: manage_admissions.php");
    exit();
}

// Delete Action
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    mysqli_query($con, "DELETE FROM admissions WHERE admission_id='$del_id'");
    set_flash("success", "Application record deleted successfully.");
    header("Location: manage_admissions.php");
    exit();
}

// Fetch Admissions
$filter_status = isset($_GET['status']) ? escape($con, $_GET['status']) : '';
$where = !empty($filter_status) ? "WHERE a.status='$filter_status'" : "";

$query = mysqli_query($con, "
    SELECT a.*, c.name AS course_name, c.code AS course_code
    FROM admissions a
    JOIN courses c ON a.course_id = c.course_id
    $where
    ORDER BY a.admission_id DESC
");

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-user-plus"></i> Online Admissions & Application Reviews</h3>
            <span class="badge badge-primary"><?php echo safe_num_rows($query); ?> Applications</span>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="manage_admissions.php" class="btn btn-secondary btn-sm <?php echo empty($filter_status) ? 'btn-primary' : ''; ?>">All</a>
            <a href="manage_admissions.php?status=Pending" class="btn btn-secondary btn-sm <?php echo ($filter_status == 'Pending') ? 'btn-primary' : ''; ?>">Pending</a>
            <a href="manage_admissions.php?status=Under+Review" class="btn btn-secondary btn-sm <?php echo ($filter_status == 'Under Review') ? 'btn-primary' : ''; ?>">Under Review</a>
            <a href="manage_admissions.php?status=Approved" class="btn btn-secondary btn-sm <?php echo ($filter_status == 'Approved') ? 'btn-primary' : ''; ?>">Approved</a>
            <a href="manage_admissions.php?status=Rejected" class="btn btn-secondary btn-sm <?php echo ($filter_status == 'Rejected') ? 'btn-primary' : ''; ?>">Rejected</a>
        </div>
    </div>

    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>App No</th>
                        <th>Candidate Details</th>
                        <th>Applied Degree</th>
                        <th>12th Marks</th>
                        <th>Applied On</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($query && safe_num_rows($query) > 0): ?>
                        <?php while ($row = safe_fetch_assoc($query)): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['application_no']); ?></strong>
                                </td>
                                <td>
                                    <div style="font-weight:700; color:var(--text-primary);"><?php echo htmlspecialchars($row['student_name']); ?></div>
                                    <small class="text-muted"><i class="fa-solid fa-envelope"></i> <?php echo htmlspecialchars($row['email']); ?> &bull; <i class="fa-solid fa-phone"></i> <?php echo htmlspecialchars($row['phone']); ?></small>
                                </td>
                                <td>
                                    <span class="badge badge-primary"><?php echo htmlspecialchars($row['course_code']); ?></span>
                                    <div style="font-size:12px; color:var(--text-muted);"><?php echo htmlspecialchars($row['course_name']); ?></div>
                                </td>
                                <td><strong><?php echo $row['marks_12th']; ?>%</strong></td>
                                <td><?php echo date('d M Y', strtotime($row['applied_at'])); ?></td>
                                <td>
                                    <?php if ($row['status'] === 'Approved'): ?>
                                        <span class="badge badge-success"><i class="fa-solid fa-check"></i> Approved</span>
                                    <?php elseif ($row['status'] === 'Under Review'): ?>
                                        <span class="badge badge-info"><i class="fa-solid fa-spinner"></i> Reviewing</span>
                                    <?php elseif ($row['status'] === 'Rejected'): ?>
                                        <span class="badge badge-danger"><i class="fa-solid fa-xmark"></i> Rejected</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning"><i class="fa-solid fa-clock"></i> Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:right;">
                                    <button type="button" class="btn btn-outline btn-sm" onclick="openReviewModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                        <i class="fa-solid fa-pen-to-square"></i> Review
                                    </button>
                                    <a href="manage_admissions.php?delete=<?php echo $row['admission_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete application from database?');">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="empty-state">No admission applications found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Review & Status Modal Dialog -->
<div id="reviewModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:var(--radius-lg); padding:30px; max-width:550px; width:90%; box-shadow:var(--shadow-xl); position:relative;">
        <h3 style="font-weight:700; margin-bottom:15px; color:var(--text-primary);"><i class="fa-solid fa-stamp text-primary"></i> Application Decision</h3>
        <form method="POST">
            <input type="hidden" name="admission_id" id="modalAppId">
            <div class="form-group">
                <label class="form-label">Candidate Name</label>
                <input type="text" id="modalCandidate" class="form-control" readonly style="background:#f1f5f9;">
            </div>
            <div class="form-group">
                <label class="form-label">Decision Status <span class="required">*</span></label>
                <select name="status" id="modalStatus" class="form-control" required>
                    <option value="Pending">Pending</option>
                    <option value="Under Review">Under Review</option>
                    <option value="Approved">Approved (Grant Admission)</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Review Committee Notes / Scholarship Grant</label>
                <textarea name="notes" id="modalNotes" rows="3" class="form-control" placeholder="e.g. Merit scholarship approved; verify original 12th certificate."></textarea>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:20px;">
                <button type="button" class="btn btn-secondary" onclick="closeReviewModal()">Cancel</button>
                <button type="submit" name="update_status" class="btn btn-primary">Save Decision</button>
            </div>
        </form>
    </div>
</div>

<script>
function openReviewModal(data) {
    document.getElementById('modalAppId').value = data.admission_id;
    document.getElementById('modalCandidate').value = data.student_name + ' (' + data.application_no + ')';
    document.getElementById('modalStatus').value = data.status;
    document.getElementById('modalNotes').value = data.notes || '';
    document.getElementById('reviewModal').style.display = 'flex';
}
function closeReviewModal() {
    document.getElementById('reviewModal').style.display = 'none';
}
</script>

<?php include("../includes/footer.php"); ?>
