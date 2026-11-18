<?php
/**
 * Delete Assignment Action
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $assignment_id = (int)$_GET['id'];

    // Delete submissions for this assignment
    mysqli_query($con, "DELETE FROM grades WHERE assignment_id='$assignment_id'");
    mysqli_query($con, "DELETE FROM submissions WHERE assignment_id='$assignment_id'");
    mysqli_query($con, "DELETE FROM assignments WHERE assignment_id='$assignment_id'");

    set_flash("success", "Assignment and related student submissions have been deleted.");
}

header("Location: manage_assignments.php");
exit();
?>
