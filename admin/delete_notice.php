<?php
/**
 * Delete Notice Action
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $notice_id = (int)$_GET['id'];
    mysqli_query($con, "DELETE FROM notices WHERE notice_id='$notice_id'");
    set_flash("success", "Campus notice has been deleted.");
}

header("Location: manage_notices.php");
exit();
?>
