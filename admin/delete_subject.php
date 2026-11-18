<?php
/**
 * Delete Subject Action
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $subject_id = (int)$_GET['id'];

    // Nullify or clean assignments, study materials, attendance linked to this subject
    mysqli_query($con, "UPDATE assignments SET subject_id = NULL WHERE subject_id='$subject_id'");
    mysqli_query($con, "UPDATE study_materials SET subject_id = NULL WHERE subject_id='$subject_id'");
    mysqli_query($con, "DELETE FROM attendance WHERE subject_id='$subject_id'");
    mysqli_query($con, "DELETE FROM subjects WHERE subject_id='$subject_id'");

    set_flash("success", "Subject has been deleted successfully.");
}

header("Location: manage_subjects.php");
exit();
?>
