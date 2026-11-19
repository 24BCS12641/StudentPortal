<?php
/**
 * Admin - Manage Photo Gallery
 * Apex Imperial University Portal
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

$page_title = "Manage Photo Gallery";

// Add Image
if (isset($_POST['save_photo'])) {
    $title = escape($con, $_POST['title']);
    $category = escape($con, $_POST['category']);
    $image_url = escape($con, $_POST['image_url']);
    $description = escape($con, $_POST['description']);

    if (empty($title) || empty($image_url)) {
        set_flash("danger", "Title and image URL are required.");
    } else {
        mysqli_query($con, "
            INSERT INTO gallery (title, category, image_url, description)
            VALUES ('$title', '$category', '$image_url', '$description')
        ");
        set_flash("success", "Photo added to gallery successfully!");
        header("Location: manage_gallery.php");
        exit();
    }
}

// Delete Image
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    mysqli_query($con, "DELETE FROM gallery WHERE gallery_id='$del_id'");
    set_flash("success", "Photo deleted from gallery.");
    header("Location: manage_gallery.php");
    exit();
}

$photos = mysqli_query($con, "SELECT * FROM gallery ORDER BY gallery_id DESC");

include("../includes/header.php");
include("../includes/sidebar_admin.php");
include("../includes/navbar.php");
?>

<div class="card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <h3><i class="fa-solid fa-images"></i> Campus Photo Gallery & Visual Media</h3>
            <span class="badge badge-primary"><?php echo safe_num_rows($photos); ?> Photos</span>
        </div>
        <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('galleryModal').style.display='flex'">
            <i class="fa-solid fa-plus"></i> Add Gallery Image
        </button>
    </div>

    <div class="card-body">
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:20px;">
            <?php if ($photos && safe_num_rows($photos) > 0): ?>
                <?php while ($p = safe_fetch_assoc($photos)): ?>
                    <div style="border:1px solid var(--border-color); border-radius:var(--radius-md); overflow:hidden; background:var(--bg-card); box-shadow:var(--shadow-sm);">
                        <div style="height:180px; overflow:hidden; position:relative;">
                            <img src="<?php echo htmlspecialchars($p['image_url']); ?>" alt="<?php echo htmlspecialchars($p['title']); ?>" style="width:100%; height:100%; object-fit:cover;">
                            <span class="badge badge-primary" style="position:absolute; top:10px; right:10px;">
                                <?php echo htmlspecialchars($p['category']); ?>
                            </span>
                        </div>
                        <div style="padding:15px;">
                            <h5 style="font-size:15px; font-weight:700; margin-bottom:4px; color:var(--text-primary);"><?php echo htmlspecialchars($p['title']); ?></h5>
                            <p style="font-size:12px; color:var(--text-muted); margin-bottom:12px; line-height:1.4;"><?php echo htmlspecialchars($p['description'] ?? ''); ?></p>
                            <div style="display:flex; justify-content:flex-end;">
                                <a href="manage_gallery.php?delete=<?php echo $p['gallery_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this image from gallery?');">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state" style="grid-column:1/-1;">No gallery photos uploaded.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Gallery Modal -->
<div id="galleryModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:var(--radius-lg); padding:30px; max-width:550px; width:90%; box-shadow:var(--shadow-xl);">
        <h3 style="font-weight:700; margin-bottom:15px; color:var(--text-primary);">Add Photo to Gallery</h3>
        <form method="POST">
            <div class="form-group">
                <label class="form-label">Photo Title <span class="required">*</span></label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Central Library Silent Pods" required>
            </div>
            <div class="form-group">
                <label class="form-label">Category <span class="required">*</span></label>
                <select name="category" class="form-control" required>
                    <option value="Campus">Campus Infrastructure</option>
                    <option value="Labs">Computing & AI Labs</option>
                    <option value="Events">Convocation & Events</option>
                    <option value="Sports">Sports Complex</option>
                    <option value="Library">Central Library</option>
                    <option value="Auditorium">Grand Auditorium</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Image URL <span class="required">*</span></label>
                <input type="url" name="image_url" class="form-control" placeholder="https://images.unsplash.com/..." required>
                <small class="text-muted">Enter a direct image link or web URL.</small>
            </div>
            <div class="form-group">
                <label class="form-label">Description / Caption</label>
                <textarea name="description" rows="2" class="form-control" placeholder="Brief caption of the event or location"></textarea>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:20px;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('galleryModal').style.display='none'">Cancel</button>
                <button type="submit" name="save_photo" class="btn btn-primary">Add to Gallery</button>
            </div>
        </form>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
