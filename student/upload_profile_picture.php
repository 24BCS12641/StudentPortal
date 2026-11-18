<?php
/**
 * Upload Student Profile Picture
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('student');

$page_title = "Update Profile Photo";
$user_id = $_SESSION['user_id'];
$error = "";

$query = mysqli_query($con, "
    SELECT u.name, u.email, u.profile_picture AS u_pic, s.roll_no, s.profile_picture AS s_pic
    FROM users u
    LEFT JOIN students s ON u.id = s.user_id
    WHERE u.id='$user_id'
    LIMIT 1
");
$student = mysqli_fetch_assoc($query);

$current_pic = !empty($student['s_pic']) && file_exists("../uploads/profile_pictures/" . $student['s_pic'])
    ? "../uploads/profile_pictures/" . $student['s_pic']
    : (!empty($student['u_pic']) && file_exists("../uploads/profile_pictures/" . $student['u_pic'])
        ? "../uploads/profile_pictures/" . $student['u_pic']
        : "../assets/images/default-user.png");

if (isset($_POST['upload'])) {
    if (!isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
        $error = "Please select a valid image file to upload.";
    } else {
        $file = $_FILES['profile_picture'];
        $max_size = 3 * 1024 * 1024; // 3MB
        $allowed_exts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if ($file['size'] > $max_size) {
            $error = "Image size exceeds 3MB. Please upload a smaller photo.";
        } elseif (!in_array($ext, $allowed_exts)) {
            $error = "Invalid file type. Allowed formats: JPG, JPEG, PNG, WEBP, GIF.";
        } else {
            // Verify real image
            $image_info = @getimagesize($file['tmp_name']);
            if ($image_info === false) {
                $error = "Uploaded file is not a valid image.";
            } else {
                $target_dir = "../uploads/profile_pictures/";
                if (!is_dir($target_dir)) {
                    @mkdir($target_dir, 0777, true);
                }

                $filename = "student_" . $user_id . "_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
                $target_path = $target_dir . $filename;

                if (move_uploaded_file($file['tmp_name'], $target_path)) {
                    // Update students and users table
                    mysqli_query($con, "UPDATE students SET profile_picture='$filename' WHERE user_id='$user_id'");
                    mysqli_query($con, "UPDATE users SET profile_picture='$filename' WHERE id='$user_id'");
                    $_SESSION['profile_picture'] = $filename;

                    set_flash("success", "Profile picture updated successfully!");
                    header("Location: profile.php");
                    exit();
                } else {
                    $error = "Failed to save the uploaded image. Check folder permissions.";
                }
            }
        }
    }
}

include("../includes/header.php");
include("../includes/sidebar_student.php");
include("../includes/navbar.php");
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fa-solid fa-camera"></i> Update Profile Photo</h3>
        <a href="profile.php" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Back to Profile
        </a>
    </div>

    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div><?php echo htmlspecialchars($error); ?></div>
            </div>
        <?php endif; ?>

        <div style="text-align: center; margin-bottom: 24px;">
            <div style="position: relative; display: inline-block;">
                <img id="avatarPreview" src="<?php echo $current_pic; ?>" alt="Preview" 
                     style="width: 130px; height: 130px; border-radius: 50%; object-fit: cover; border: 4px solid var(--border-color); box-shadow: var(--shadow-md);">
            </div>
            <h4 style="margin: 12px 0 4px; font-weight: 700; color: var(--text-dark);"><?php echo htmlspecialchars($student['name'] ?? 'Student'); ?></h4>
            <p style="margin: 0; color: var(--text-muted); font-size: 0.9rem;">
                <i class="fa-solid fa-id-badge"></i> <?php echo htmlspecialchars($student['roll_no'] ?? 'N/A'); ?>
            </p>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Select New Photo <span class="required">*</span></label>
                <div style="border: 2px dashed var(--border-color); border-radius: var(--radius-md); padding: 25px; text-align: center; background: var(--bg-body); cursor: pointer; transition: var(--transition);"
                     onclick="document.getElementById('profilePicInput').click();"
                     id="dropZone">
                    <i class="fa-solid fa-cloud-arrow-up" style="font-size: 2.2rem; color: var(--primary); margin-bottom: 10px;"></i>
                    <p style="margin: 0 0 6px; font-weight: 600; color: var(--text-dark);">Click to browse or drag image here</p>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">Accepted formats: JPG, PNG, WEBP, GIF (Max 3MB)</span>
                    <input type="file" name="profile_picture" id="profilePicInput" accept="image/*" style="display: none;" required onchange="previewImage(this);">
                    <div id="fileNameDisplay" style="margin-top: 10px; font-size: 0.85rem; font-weight: 600; color: var(--primary);"></div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 25px;">
                <a href="profile.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="upload" class="btn btn-primary" style="background: #2563eb;">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload Photo
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        document.getElementById('fileNameDisplay').innerHTML = '<i class="fa-solid fa-check-circle"></i> Selected: ' + file.name + ' (' + (file.size/1024).toFixed(1) + ' KB)';
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
</script>

<?php include("../includes/footer.php"); ?>
