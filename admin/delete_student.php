<?php
/**
 * Delete Student Action
 * EduPortal Management System
 */

include("../config/auth.php");
include("../config/db.php");
check_role('admin');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $student_id = (int)$_GET['id'];

    // Find student
    $find = mysqli_query($con, "SELECT user_id FROM students WHERE student_id='$student_id' LIMIT 1");
    if ($find && safe_num_rows($find) > 0) {
        $student = ($find && safe_num_rows($find) > 0) ? safe_fetch_assoc($find) : [];
        $user_id = $student['user_id'];

        // Clean up related records
        mysqli_query($con, "DELETE FROM attendance WHERE student_id='$student_id'");
        mysqli_query($con, "DELETE FROM submissions WHERE student_id='$student_id'");
        mysqli_query($con, "DELETE FROM grades WHERE student_id='$student_id'");
        mysqli_query($con, "DELETE FROM students WHERE student_id='$student_id'");
        mysqli_query($con, "DELETE FROM users WHERE id='$user_id'");

        set_flash("success", "Student record and all associated records have been successfully deleted.");
    } else {
        set_flash("danger", "Student record not found.");
    }
}

header("Location: manage_students.php");
exit();
?>
