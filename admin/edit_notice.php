<?php
/**
 * Edit Notice (Admin)
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Edit Campus Notice";
$error = "";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_notices.php");
    exit();
}

$notice_id = (int)$_GET['id'];

$query = mysqli_query($con, "SELECT * FROM notices WHERE notice_id='$notice_id' LIMIT 1");
if (!$query || mysqli_num_rows($query) === 0) {
    set_flash("danger", "Notice not found.");
    header("Location: manage_notices.php");
    exit();
}

$row = mysqli_fetch_assoc($query);

if (isset($_POST['update_notice'])) {
    $title = escape($con, $_POST['title']);
    $description = escape($con, $_POST['description']);

    if (empty($title) || empty($description)) {
        $error = "Notice title and description are required.";
    } else {
        $update = mysqli_query($con, "
            UPDATE notices 
            SET title='$title', description='$description'
            WHERE notice_id='$notice_id'
        ");

        if ($update) {
            set_flash("success", "Notice updated successfully!");
            header("Location: manage_notices.php");
            exit();
        } else {
            $error = "Database Error: " . mysqli_error($con);
        }
    }
}

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card" style="max-width: 750px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fa-solid fa-pen-to-square"></i> Edit Notice #<?php echo $notice_id; ?></h3>
        <a href="manage_notices.php" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Back to Notices
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
                <label class="form-label">Notice Headline / Title <span class="required">*</span></label>
                <input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($row['title']); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Notice Details & Description <span class="required">*</span></label>
                <textarea name="description" class="form-control" rows="7" required><?php echo htmlspecialchars($row['description']); ?></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:25px;">
                <a href="manage_notices.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="update_notice" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
