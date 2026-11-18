<?php
/**
 * Delete Assignment Action (Faculty)
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('faculty');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $assignment_id = (int)$_GET['id'];

    mysqli_query($con, "DELETE FROM grades WHERE assignment_id='$assignment_id'");
    mysqli_query($con, "DELETE FROM submissions WHERE assignment_id='$assignment_id'");
    mysqli_query($con, "DELETE FROM assignments WHERE assignment_id='$assignment_id'");

    set_flash("success", "Assignment and all student submissions have been deleted.");
}

header("Location: view_assignments.php");
exit();
?>
