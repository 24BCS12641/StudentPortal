<?php
/**
 * Comprehensive Seed Dummy Data for Apex Imperial University Portal
 * Populates and maintains realistic demo data (at least 30 departments, 30 faculty, 35 students).
 * Safe to call repeatedly on duplicate key update.
 */

if (!function_exists("seed_dummy_data")) {
    function seed_dummy_data($con) {
        if (!$con) return;

        $json_file = __DIR__ . "/../scratch/portal_dataset.json";
        if (!file_exists($json_file)) return;
        $data = json_decode(file_get_contents($json_file), true);
        if (!$data) return;

        // 1. Departments (at least 30)
        if (safe_count($con, "departments") < 30) {
            foreach ($data["departments"] as $d) {
                $id = (int)$d[0];
                $name = escape($con, $d[1]);
                $code = escape($con, $d[2]);
                $desc = escape($con, $d[3]);
                $hod = escape($con, $d[4]);
                $icon = escape($con, $d[5]);
                @mysqli_query($con, "
                    INSERT INTO departments (department_id, name, code, description, head_of_dept, icon)
                    VALUES ('$id', '$name', '$code', '$desc', '$hod', '$icon')
                    ON DUPLICATE KEY UPDATE name='$name', code='$code', description='$desc', head_of_dept='$hod', icon='$icon'
                ");
            }
        }

        // 2. Courses (at least 30)
        if (safe_count($con, "courses") < 30) {
            foreach ($data["courses"] as $c) {
                $cid = (int)$c[0];
                $dept_id = (int)$c[1];
                $name = escape($con, $c[2]);
                $code = escape($con, $c[3]);
                $lvl = escape($con, $c[4]);
                $dur = escape($con, $c[5]);
                $sem = (int)$c[6];
                $fee = (float)$c[7];
                $elig = escape($con, $c[8]);
                $desc = escape($con, $c[9]);
                @mysqli_query($con, "
                    INSERT INTO courses (course_id, department_id, name, code, degree_level, duration, semester_count, fees_per_year, eligibility, description, course_name, course_code)
                    VALUES ('$cid', '$dept_id', '$name', '$code', '$lvl', '$dur', '$sem', '$fee', '$elig', '$desc', '$name', '$code')
                    ON DUPLICATE KEY UPDATE department_id='$dept_id', name='$name', code='$code', degree_level='$lvl', duration='$dur', semester_count='$sem', fees_per_year='$fee'
                ");
            }
        }

        // 3. Users (at least 60)
        if (safe_count($con, "users") < 60) {
            foreach ($data["users"] as $u) {
                $uid = (int)$u[0];
                $uname = escape($con, $u[1]);
                $uemail = escape($con, $u[2]);
                $upass = escape($con, $u[3]);
                $urole = escape($con, $u[4]);
                $upic = escape($con, $u[5]);
                $uphone = escape($con, $u[6]);
                @mysqli_query($con, "
                    INSERT INTO users (id, name, email, password, role, profile_picture, phone)
                    VALUES ('$uid', '$uname', '$uemail', '$upass', '$urole', '$upic', '$uphone')
                    ON DUPLICATE KEY UPDATE name='$uname', password='$upass', role='$urole', phone='$uphone'
                ");
            }
        }

        // 4. Faculty (at least 30)
        if (safe_count($con, "faculty") < 30) {
            foreach ($data["faculty"] as $f) {
                $fid = (int)$f[0];
                $fuid = (int)$f[1];
                $fdept_id = (int)$f[2];
                $fdept = escape($con, $f[3]);
                $fdesig = escape($con, $f[4]);
                $fqual = escape($con, $f[5]);
                $fspec = escape($con, $f[6]);
                $foffice = escape($con, $f[7]);
                @mysqli_query($con, "
                    INSERT INTO faculty (faculty_id, user_id, department_id, department, designation, qualification, specialization, office)
                    VALUES ('$fid', '$fuid', '$fdept_id', '$fdept', '$fdesig', '$fqual', '$fspec', '$foffice')
                    ON DUPLICATE KEY UPDATE department_id='$fdept_id', department='$fdept', designation='$fdesig', qualification='$fqual', specialization='$fspec', office='$foffice'
                ");
            }
        }

        // 5. Students (at least 30)
        if (safe_count($con, "students") < 30) {
            foreach ($data["students"] as $s) {
                $sid = (int)$s[0];
                $suid = (int)$s[1];
                $sroll = escape($con, $s[2]);
                $sdept_id = (int)$s[3];
                $scourse_id = (int)$s[4];
                $scourse = escape($con, $s[5]);
                $ssem = (int)$s[6];
                $syear = (int)$s[7];
                $sgender = escape($con, $s[8]);
                $sdob = escape($con, $s[9]);
                $saddr = escape($con, $s[10]);
                $sstat = escape($con, $s[11]);
                @mysqli_query($con, "
                    INSERT INTO students (student_id, user_id, roll_no, department_id, course_id, course, semester, admission_year, gender, dob, address, status)
                    VALUES ('$sid', '$suid', '$sroll', '$sdept_id', '$scourse_id', '$scourse', '$ssem', '$syear', '$sgender', '$sdob', '$saddr', '$sstat')
                    ON DUPLICATE KEY UPDATE roll_no='$sroll', department_id='$sdept_id', course_id='$scourse_id', course='$scourse', semester='$ssem', status='$sstat'
                ");
            }
        }
    }
}
