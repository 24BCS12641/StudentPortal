<?php
/**
 * Admin - Manage Courses & Programs
 * Apex Imperial University Portal
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Manage Degree Programs & Courses";

// Add or Edit Course
if (isset($_POST['save_course'])) {
    $course_id = (int)($_POST['course_id'] ?? 0);
    $department_id = (int)$_POST['department_id'];
    $name = escape($con, $_POST['name']);
    $code = escape($con, $_POST['code']);
    $degree_level = escape($con, $_POST['degree_level']);
    $duration = escape($con, $_POST['duration']);
    $semester_count = (int)$_POST['semester_count'];
    $fees_per_year = (float)$_POST['fees_per_year'];
    $eligibility = escape($con, $_POST['eligibility']);
    $description = escape($con, $_POST['description']);

    if (empty($name) || empty($code) || empty($department_id)) {
        set_flash("danger", "Course name, code, and department are required.");
    } else {
        if ($course_id > 0) {
            mysqli_query($con, "
                UPDATE courses 
                SET department_id='$department_id', name='$name', code='$code', degree_level='$degree_level',
                    duration='$duration', semester_count='$semester_count', fees_per_year='$fees_per_year',
                    eligibility='$eligibility', description='$description'
                WHERE course_id='$course_id'
            ");
            set_flash("success", "Degree Program '$code' updated successfully!");
        } else {
            mysqli_query($con, "
                INSERT INTO courses (department_id, name, code, degree_level, duration, semester_count, fees_per_year, eligibility, description)
                VALUES ('$department_id', '$name', '$code', '$degree_level', '$duration', '$semester_count', '$fees_per_year', '$eligibility', '$description')
            ");
            set_flash("success", "New program '$name' created successfully!");
        }
        header("Location: manage_courses.php");
        exit();
    }
}

// Delete Course
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    mysqli_query($con, "DELETE FROM courses WHERE course_id='$del_id'");
    set_flash("success", "Course program deleted.");
    header("Location: manage_courses.php");
    exit();
}

$search = isset($_GET['search']) ? escape($con, trim($_GET['search'])) : '';
$where_search = "";
if (!empty($search)) {
    $where_search = "WHERE c.name LIKE '%$search%' OR c.code LIKE '%$search%' OR d.name LIKE '%$search%' OR d.code LIKE '%$search%'";
}

$courses = mysqli_query($con, "
    SELECT c.*, d.name AS dept_name, d.code AS dept_code
    FROM courses c
    JOIN departments d ON c.department_id = d.department_id
    $where_search
    ORDER BY c.degree_level DESC, c.course_id ASC
");

$depts_list = mysqli_query($con, "SELECT department_id, name, code FROM departments ORDER BY name ASC");

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-graduation-cap"></i> Academic Degree Programs</h3>
            <span class="badge badge-primary"><?php echo safe_num_rows($courses); ?> Programs</span>
        </div>
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <form method="GET" class="search-toolbar">
                <div class="search-input-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" class="form-control" placeholder="Search programs or departments..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
                <?php if (!empty($search)): ?>
                    <a href="manage_courses.php" class="btn btn-outline" title="Clear Search"><i class="fa-solid fa-rotate-left"></i></a>
                <?php endif; ?>
            </form>
            <button type="button" class="btn btn-primary" onclick="openCourseModal()">
                <i class="fa-solid fa-plus"></i> Add New Program
            </button>
        </div>
    </div>

    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Program Name</th>
                        <th>School / Department</th>
                        <th>Level</th>
                        <th>Duration</th>
                        <th>Annual Tuition</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($courses && safe_num_rows($courses) > 0): ?>
                        <?php while ($c = safe_fetch_assoc($courses)): ?>
                            <tr>
                                <td><span class="badge badge-primary"><?php echo htmlspecialchars($c['code']); ?></span></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($c['name']); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($c['dept_name']); ?></td>
                                <td><span class="badge badge-dark"><?php echo htmlspecialchars($c['degree_level']); ?></span></td>
                                <td><?php echo htmlspecialchars($c['duration']); ?> (<?php echo $c['semester_count']; ?> Sems)</td>
                                <td><strong style="color:#0284c7;">₹<?php echo number_format($c['fees_per_year'], 2); ?></strong></td>
                                <td style="text-align:right;">
                                    <button type="button" class="btn btn-outline btn-sm" onclick='editCourseModal(<?php echo json_encode($c); ?>)'>
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <a href="manage_courses.php?delete=<?php echo $c['course_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this course program?');">
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

<!-- Course Modal -->
<div id="courseModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:var(--radius-lg); padding:30px; max-width:650px; width:90%; max-height:90vh; overflow-y:auto; box-shadow:var(--shadow-xl);">
        <h3 id="cModalTitle" style="font-weight:700; margin-bottom:15px; color:var(--text-primary);">Program Details</h3>
        <form method="POST">
            <input type="hidden" name="course_id" id="cId" value="0">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Program Name <span class="required">*</span></label>
                    <input type="text" name="name" id="cName" class="form-control" placeholder="e.g. Bachelor of Computer Applications" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Code <span class="required">*</span></label>
                    <input type="text" name="code" id="cCode" class="form-control" placeholder="e.g. BCA" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Department <span class="required">*</span></label>
                    <select name="department_id" id="cDeptId" class="form-control" required>
                        <?php 
                        mysqli_data_seek($depts_list, 0);
                        while ($d = safe_fetch_assoc($depts_list)): 
                        ?>
                            <option value="<?php echo $d['department_id']; ?>"><?php echo htmlspecialchars($d['name']); ?> (<?php echo $d['code']; ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Degree Level <span class="required">*</span></label>
                    <select name="degree_level" id="cLevel" class="form-control" required>
                        <option value="Undergraduate">Undergraduate</option>
                        <option value="Postgraduate">Postgraduate</option>
                        <option value="Doctorate">Doctorate</option>
                        <option value="Diploma">Diploma</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Duration</label>
                    <input type="text" name="duration" id="cDuration" class="form-control" placeholder="e.g. 3 Years" value="3 Years">
                </div>
                <div class="form-group">
                    <label class="form-label">Number of Semesters</label>
                    <input type="number" name="semester_count" id="cSemCount" class="form-control" value="6">
                </div>
                <div class="form-group">
                    <label class="form-label">Annual Tuition Fee (₹)</label>
                    <input type="number" step="100" name="fees_per_year" id="cFees" class="form-control" placeholder="e.g. 75000" value="75000">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Eligibility Criteria</label>
                <input type="text" name="eligibility" id="cElig" class="form-control" placeholder="e.g. 10+2 with Mathematics/CS >= 50%">
            </div>

            <div class="form-group">
                <label class="form-label">Description / Course Overview</label>
                <textarea name="description" id="cDesc" rows="3" class="form-control" placeholder="Short description of curriculum and core subjects"></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:20px;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('courseModal').style.display='none'">Cancel</button>
                <button type="submit" name="save_course" class="btn btn-primary">Save Degree Program</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCourseModal() {
    document.getElementById('cId').value = '0';
    document.getElementById('cName').value = '';
    document.getElementById('cCode').value = '';
    document.getElementById('cDuration').value = '3 Years';
    document.getElementById('cSemCount').value = '6';
    document.getElementById('cFees').value = '75000';
    document.getElementById('cElig').value = '';
    document.getElementById('cDesc').value = '';
    document.getElementById('cModalTitle').textContent = 'Add New Degree Program';
    document.getElementById('courseModal').style.display = 'flex';
}
function editCourseModal(c) {
    document.getElementById('cId').value = c.course_id;
    document.getElementById('cName').value = c.name;
    document.getElementById('cCode').value = c.code;
    document.getElementById('cDeptId').value = c.department_id;
    document.getElementById('cLevel').value = c.degree_level;
    document.getElementById('cDuration').value = c.duration;
    document.getElementById('cSemCount').value = c.semester_count;
    document.getElementById('cFees').value = c.fees_per_year;
    document.getElementById('cElig').value = c.eligibility || '';
    document.getElementById('cDesc').value = c.description || '';
    document.getElementById('cModalTitle').textContent = 'Edit Degree Program';
    document.getElementById('courseModal').style.display = 'flex';
}
</script>

<?php include("../includes/footer.php"); ?>
