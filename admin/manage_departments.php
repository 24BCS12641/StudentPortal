<?php
/**
 * Admin - Manage Departments
 * Apex Imperial University Portal
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Manage Academic Departments";

// Add or Edit Department
if (isset($_POST['save_department'])) {
    $dept_id = (int)($_POST['department_id'] ?? 0);
    $name = escape($con, $_POST['name']);
    $code = escape($con, $_POST['code']);
    $description = escape($con, $_POST['description']);
    $head_of_dept = escape($con, $_POST['head_of_dept']);
    $icon = escape($con, $_POST['icon'] ?? 'fa-laptop-code');

    if (empty($name) || empty($code)) {
        set_flash("danger", "Department name and code are mandatory.");
    } else {
        if ($dept_id > 0) {
            mysqli_query($con, "
                UPDATE departments 
                SET name='$name', code='$code', description='$description', head_of_dept='$head_of_dept', icon='$icon'
                WHERE department_id='$dept_id'
            ");
            set_flash("success", "Department '$name' updated successfully!");
        } else {
            mysqli_query($con, "
                INSERT INTO departments (name, code, description, head_of_dept, icon)
                VALUES ('$name', '$code', '$description', '$head_of_dept', '$icon')
            ");
            set_flash("success", "New department '$name' added successfully!");
        }
        header("Location: manage_departments.php");
        exit();
    }
}

// Delete Department
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    mysqli_query($con, "DELETE FROM departments WHERE department_id='$del_id'");
    set_flash("success", "Department deleted successfully.");
    header("Location: manage_departments.php");
    exit();
}

$departments = mysqli_query($con, "SELECT * FROM departments ORDER BY department_id ASC");

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-building-columns"></i> Academic Departments & Faculties</h3>
            <span class="badge badge-primary"><?php echo safe_num_rows($departments); ?> Departments</span>
        </div>
        <button type="button" class="btn btn-primary btn-sm" onclick="openDeptModal()">
            <i class="fa-solid fa-plus"></i> Add New Department
        </button>
    </div>

    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Department Name</th>
                        <th>Head of Department</th>
                        <th>Description</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($departments && safe_num_rows($departments) > 0): ?>
                        <?php while ($d = safe_fetch_assoc($departments)): ?>
                            <tr>
                                <td>
                                    <span class="badge badge-primary"><i class="fa-solid <?php echo htmlspecialchars($d['icon']); ?> me-1"></i> <?php echo htmlspecialchars($d['code']); ?></span>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($d['name']); ?></strong>
                                </td>
                                <td>
                                    <i class="fa-solid fa-user-tie text-gold me-1"></i> <?php echo htmlspecialchars($d['head_of_dept']); ?>
                                </td>
                                <td>
                                    <div style="font-size:13px; color:var(--text-secondary); max-width:320px;">
                                        <?php echo htmlspecialchars(substr($d['description'], 0, 100)) . '...'; ?>
                                    </div>
                                </td>
                                <td style="text-align:right;">
                                    <button type="button" class="btn btn-outline btn-sm" onclick='editDeptModal(<?php echo json_encode($d); ?>)'>
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <a href="manage_departments.php?delete=<?php echo $d['department_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this department and related courses?');">
                                        <i class="fa-solid fa-trash"></i>
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

<!-- Department Modal -->
<div id="deptModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:var(--radius-lg); padding:30px; max-width:550px; width:90%; box-shadow:var(--shadow-xl);">
        <h3 id="modalTitle" style="font-weight:700; margin-bottom:15px; color:var(--text-primary);">Department Details</h3>
        <form method="POST">
            <input type="hidden" name="department_id" id="dId" value="0">
            <div class="form-group">
                <label class="form-label">Department Name <span class="required">*</span></label>
                <input type="text" name="name" id="dName" class="form-control" placeholder="e.g. School of Computer Science" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Code <span class="required">*</span></label>
                    <input type="text" name="code" id="dCode" class="form-control" placeholder="e.g. CSE" required>
                </div>
                <div class="form-group">
                    <label class="form-label">FontAwesome Icon</label>
                    <input type="text" name="icon" id="dIcon" class="form-control" placeholder="fa-laptop-code" value="fa-laptop-code">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Head of Department (HOD)</label>
                <input type="text" name="head_of_dept" id="dHOD" class="form-control" placeholder="e.g. Dr. Rajesh Sharma">
            </div>
            <div class="form-group">
                <label class="form-label">Description / Scope</label>
                <textarea name="description" id="dDesc" rows="3" class="form-control" placeholder="Short description of research and courses"></textarea>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:20px;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('deptModal').style.display='none'">Cancel</button>
                <button type="submit" name="save_department" class="btn btn-primary">Save Department</button>
            </div>
        </form>
    </div>
</div>

<script>
function openDeptModal() {
    document.getElementById('dId').value = '0';
    document.getElementById('dName').value = '';
    document.getElementById('dCode').value = '';
    document.getElementById('dHOD').value = '';
    document.getElementById('dDesc').value = '';
    document.getElementById('dIcon').value = 'fa-laptop-code';
    document.getElementById('modalTitle').textContent = 'Add New Department';
    document.getElementById('deptModal').style.display = 'flex';
}
function editDeptModal(d) {
    document.getElementById('dId').value = d.department_id;
    document.getElementById('dName').value = d.name;
    document.getElementById('dCode').value = d.code;
    document.getElementById('dHOD').value = d.head_of_dept || '';
    document.getElementById('dDesc').value = d.description || '';
    document.getElementById('dIcon').value = d.icon || 'fa-laptop-code';
    document.getElementById('modalTitle').textContent = 'Edit Department';
    document.getElementById('deptModal').style.display = 'flex';
}
</script>

<?php include("../includes/footer.php"); ?>
