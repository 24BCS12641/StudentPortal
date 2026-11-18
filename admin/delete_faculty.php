<?php
/**
 * Delete Faculty Action
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $faculty_id = (int)$_GET['id'];

    $find = mysqli_query($con, "SELECT user_id FROM faculty WHERE faculty_id='$faculty_id' LIMIT 1");
    if ($find && mysqli_num_rows($find) > 0) {
        $faculty = mysqli_fetch_assoc($find);
        $user_id = $faculty['user_id'];

        // Detach subjects
        mysqli_query($con, "UPDATE subjects SET faculty_id = NULL WHERE faculty_id='$faculty_id'");
        mysqli_query($con, "DELETE FROM faculty WHERE faculty_id='$faculty_id'");
        mysqli_query($con, "DELETE FROM users WHERE id='$user_id'");

        set_flash("success", "Faculty staff member has been deleted successfully.");
    } else {
        set_flash("danger", "Faculty record not found.");
    }
}

header("Location: manage_faculty.php");
exit();
?>
