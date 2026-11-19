<?php
/**
 * Edit Faculty Member
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Edit Faculty Profile";
$error = "";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_faculty.php");
    exit();
}

$faculty_id = (int)$_GET['id'];

$query = mysqli_query($con, "
    SELECT faculty.*, users.name, users.email
    FROM faculty
    JOIN users ON faculty.user_id = users.id
    WHERE faculty.faculty_id='$faculty_id'
    LIMIT 1
");

if (!$query || safe_num_rows($query) === 0) {
    set_flash("danger", "Faculty record not found.");
    header("Location: manage_faculty.php");
    exit();
}

$row = ($query && safe_num_rows($query) > 0) ? safe_fetch_assoc($query) : [];

if (isset($_POST['update_faculty'])) {
    $name = escape($con, $_POST['name']);
    $email = escape($con, $_POST['email']);
    $department = escape($con, $_POST['department']);
    $designation = escape($con, $_POST['designation']);

    if (empty($name) || empty($email) || empty($department)) {
        $error = "Please fill in all required fields.";
    } else {
        // Check duplicate email
        $check = mysqli_query($con, "SELECT id FROM users WHERE email='$email' AND id != '{$row['user_id']}' LIMIT 1");
        if (safe_num_rows($check) > 0) {
            $error = "Another user is already using the email address '$email'.";
        } else {
            $pic_sql_user = "";
            $pic_sql_faculty = "";
            if (!empty($_FILES['profile_picture']['name'])) {
                $ext = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                if (in_array($ext, $allowed)) {
                    $new_pic = time() . "_" . uniqid() . "." . $ext;
                    if (!is_dir("../uploads/profile_pictures")) {
                        mkdir("../uploads/profile_pictures", 0777, true);
                    }
                    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], "../uploads/profile_pictures/" . $new_pic)) {
                        $pic_sql_user = ", profile_picture='$new_pic'";
                        $pic_sql_faculty = ", profile_picture='$new_pic'";
                    }
                }
            }

            mysqli_query($con, "
                UPDATE users 
                SET name='$name', email='$email' $pic_sql_user
                WHERE id='{$row['user_id']}'
            ");

            mysqli_query($con, "
                UPDATE faculty 
                SET department='$department', designation='$designation' $pic_sql_faculty
                WHERE faculty_id='$faculty_id'
            ");

            set_flash("success", "Faculty profile for '$name' updated successfully!");
            header("Location: manage_faculty.php");
            exit();
        }
    }
}

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fa-solid fa-user-pen"></i> Edit Faculty: <?php echo htmlspecialchars($row['name']); ?></h3>
        <a href="manage_faculty.php" class="btn btn-outline btn-sm">
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

        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Full Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($row['name']); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($row['email']); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Department <span class="required">*</span></label>
                    <input type="text" name="department" class="form-control" required value="<?php echo htmlspecialchars($row['department']); ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Designation <span class="required">*</span></label>
                    <select name="designation" class="form-control" required>
                        <option value="Professor" <?php echo ($row['designation'] == 'Professor') ? 'selected' : ''; ?>>Professor</option>
                        <option value="Associate Professor" <?php echo ($row['designation'] == 'Associate Professor') ? 'selected' : ''; ?>>Associate Professor</option>
                        <option value="Assistant Professor" <?php echo ($row['designation'] == 'Assistant Professor') ? 'selected' : ''; ?>>Assistant Professor</option>
                        <option value="Lecturer" <?php echo ($row['designation'] == 'Lecturer') ? 'selected' : ''; ?>>Lecturer</option>
                        <option value="HOD" <?php echo ($row['designation'] == 'HOD') ? 'selected' : ''; ?>>Head of Department (HOD)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Profile Photo</label>
                <div style="display:flex; align-items:center; gap:15px;">
                    <?php 
                    $pic = !empty($row['profile_picture']) && file_exists("../uploads/profile_pictures/" . $row['profile_picture'])
                        ? "../uploads/profile_pictures/" . $row['profile_picture']
                        : "../assets/images/default-user.png";
                    ?>
                    <img src="<?php echo $pic; ?>" alt="Faculty Photo" class="table-avatar" style="width:50px; height:50px;">
                    <input type="file" name="profile_picture" accept="image/*" class="form-control" style="flex:1;">
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:25px;">
                <a href="manage_faculty.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="update_faculty" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
