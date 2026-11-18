<?php
/**
 * Admin - Manage Placements
 * Apex Imperial University Portal
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Manage Student Placements";

// Add or Edit Placement
if (isset($_POST['save_placement'])) {
    $placement_id = (int)($_POST['placement_id'] ?? 0);
    $student_name = escape($con, $_POST['student_name']);
    $roll_no = escape($con, $_POST['roll_no']);
    $company_name = escape($con, $_POST['company_name']);
    $package_lpa = (float)$_POST['package_lpa'];
    $role = escape($con, $_POST['role']);
    $batch_year = (int)$_POST['batch_year'];

    if (empty($student_name) || empty($company_name) || empty($package_lpa)) {
        set_flash("danger", "Student name, company, and package are required.");
    } else {
        if ($placement_id > 0) {
            mysqli_query($con, "
                UPDATE placements 
                SET student_name='$student_name', roll_no='$roll_no', company_name='$company_name',
                    package_lpa='$package_lpa', role='$role', batch_year='$batch_year'
                WHERE placement_id='$placement_id'
            ");
            set_flash("success", "Placement record for '$student_name' updated successfully!");
        } else {
            mysqli_query($con, "
                INSERT INTO placements (student_name, roll_no, company_name, package_lpa, role, batch_year)
                VALUES ('$student_name', '$roll_no', '$company_name', '$package_lpa', '$role', '$batch_year')
            ");
            set_flash("success", "New placement record added successfully!");
        }
        header("Location: manage_placements.php");
        exit();
    }
}

// Delete Placement
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    mysqli_query($con, "DELETE FROM placements WHERE placement_id='$del_id'");
    set_flash("success", "Placement record deleted.");
    header("Location: manage_placements.php");
    exit();
}

$placements = mysqli_query($con, "SELECT * FROM placements ORDER BY package_lpa DESC");

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-briefcase"></i> Corporate Placements & Salary Records</h3>
            <span class="badge badge-primary"><?php echo mysqli_num_rows($placements); ?> Records</span>
        </div>
        <button type="button" class="btn btn-primary btn-sm" onclick="openPlacementModal()">
            <i class="fa-solid fa-plus"></i> Add Placement Record
        </button>
    </div>

    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Recruiting Company</th>
                        <th>Job Role</th>
                        <th>Package (CTC)</th>
                        <th>Batch Year</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($placements && mysqli_num_rows($placements) > 0): ?>
                        <?php while ($p = mysqli_fetch_assoc($placements)): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($p['student_name']); ?></strong>
                                    <div style="font-size:12px; color:var(--text-muted);"><i class="fa-solid fa-id-card"></i> <?php echo htmlspecialchars($p['roll_no']); ?></div>
                                </td>
                                <td>
                                    <span class="badge badge-primary"><i class="fa-solid fa-building me-1"></i> <?php echo htmlspecialchars($p['company_name']); ?></span>
                                </td>
                                <td><?php echo htmlspecialchars($p['role']); ?></td>
                                <td><strong style="color:#d4af37; font-size:15px;">₹<?php echo $p['package_lpa']; ?> LPA</strong></td>
                                <td><span class="badge badge-dark">Class of <?php echo $p['batch_year']; ?></span></td>
                                <td style="text-align:right;">
                                    <button type="button" class="btn btn-outline btn-sm" onclick='editPlacementModal(<?php echo json_encode($p); ?>)'>
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <a href="manage_placements.php?delete=<?php echo $p['placement_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this placement record?');">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="empty-state">No placement records found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Placement Modal -->
<div id="pModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:var(--radius-lg); padding:30px; max-width:550px; width:90%; box-shadow:var(--shadow-xl);">
        <h3 id="pModalTitle" style="font-weight:700; margin-bottom:15px; color:var(--text-primary);">Placement Details</h3>
        <form method="POST">
            <input type="hidden" name="placement_id" id="pId" value="0">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Student Name <span class="required">*</span></label>
                    <input type="text" name="student_name" id="pName" class="form-control" placeholder="e.g. Sunny Kumar" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Roll Number</label>
                    <input type="text" name="roll_no" id="pRoll" class="form-control" placeholder="e.g. 24BCS12641">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Company Name <span class="required">*</span></label>
                    <input type="text" name="company_name" id="pCompany" class="form-control" placeholder="e.g. Microsoft" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Package (LPA) <span class="required">*</span></label>
                    <input type="number" step="0.1" name="package_lpa" id="pPackage" class="form-control" placeholder="e.g. 44.5" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Job Role / Designation</label>
                    <input type="text" name="role" id="pRole" class="form-control" placeholder="e.g. Software Development Engineer" value="Software Engineer">
                </div>
                <div class="form-group">
                    <label class="form-label">Graduating Batch Year</label>
                    <input type="number" name="batch_year" id="pBatch" class="form-control" value="2025">
                </div>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:20px;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('pModal').style.display='none'">Cancel</button>
                <button type="submit" name="save_placement" class="btn btn-primary">Save Record</button>
            </div>
        </form>
    </div>
</div>

<script>
function openPlacementModal() {
    document.getElementById('pId').value = '0';
    document.getElementById('pName').value = '';
    document.getElementById('pRoll').value = '';
    document.getElementById('pCompany').value = '';
    document.getElementById('pPackage').value = '12.0';
    document.getElementById('pRole').value = 'Software Engineer';
    document.getElementById('pBatch').value = '2025';
    document.getElementById('pModalTitle').textContent = 'Add Placement Record';
    document.getElementById('pModal').style.display = 'flex';
}
function editPlacementModal(p) {
    document.getElementById('pId').value = p.placement_id;
    document.getElementById('pName').value = p.student_name;
    document.getElementById('pRoll').value = p.roll_no;
    document.getElementById('pCompany').value = p.company_name;
    document.getElementById('pPackage').value = p.package_lpa;
    document.getElementById('pRole').value = p.role;
    document.getElementById('pBatch').value = p.batch_year;
    document.getElementById('pModalTitle').textContent = 'Edit Placement Record';
    document.getElementById('pModal').style.display = 'flex';
}
</script>

<?php include("../includes/footer.php"); ?>
