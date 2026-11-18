<?php
/**
 * Post Campus Notice (Faculty)
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('faculty');

$page_title = "Post Academic Notice";
$error = "";

if (isset($_POST['post_notice'])) {
    $title = escape($con, $_POST['title']);
    $description = escape($con, $_POST['description']);
    $posted_by = escape($con, $_SESSION['name'] ?? 'Faculty');

    if (empty($title) || empty($description)) {
        $error = "Notice title and description are required.";
    } else {
        $insert = mysqli_query($con, "
            INSERT INTO notices (title, description, posted_by)
            VALUES ('$title', '$description', '$posted_by')
        ");

        if ($insert) {
            set_flash("success", "Notice posted successfully!");
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Database Error: " . mysqli_error($con);
        }
    }
}

include("../includes/header.php");
include("../includes/sidebar_faculty.php");
include("../includes/navbar.php");
?>

<div class="card" style="max-width: 750px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fa-solid fa-bullhorn"></i> Post Class Announcement</h3>
        <a href="dashboard.php" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
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
                <label class="form-label">Notice Headline <span class="required">*</span></label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Lab Session Rescheduled for Thursday" required value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Notice Details & Instructions <span class="required">*</span></label>
                <textarea name="description" class="form-control" rows="6" placeholder="Provide complete information, room number, timings, requirements..." required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:25px;">
                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="post_notice" class="btn btn-primary" style="background:#0284c7;">
                    <i class="fa-solid fa-paper-plane"></i> Publish Notice
                </button>
            </div>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
