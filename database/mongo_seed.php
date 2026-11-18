<?php
/**
 * MongoDB Seed & Migration Script for Student Portal
 * Automatically populates the 'student_portal' database in MongoDB.
 */

$uri = "mongodb://127.0.0.1:27017";
$dbName = "student_portal";

echo "Connecting to MongoDB at {$uri}..." . PHP_EOL;

try {
    $manager = new MongoDB\Driver\Manager($uri);
    
    // Ping MongoDB
    $pingCommand = new MongoDB\Driver\Command(['ping' => 1]);
    $manager->executeCommand('admin', $pingCommand);
    echo "✓ Connected to MongoDB successfully!" . PHP_EOL;

    // Helper function to insert documents
    function insertCollection($manager, $dbName, $collectionName, $documents) {
        // Drop existing collection to start clean
        try {
            $dropCommand = new MongoDB\Driver\Command(['drop' => $collectionName]);
            $manager->executeCommand($dbName, $dropCommand);
        } catch (Exception $e) {
            // Collection might not exist yet, ignore
        }

        $bulk = new MongoDB\Driver\BulkWrite();
        foreach ($documents as $doc) {
            $bulk->insert($doc);
        }
        $result = $manager->executeBulkWrite("{$dbName}.{$collectionName}", $bulk);
        echo "✓ [{$collectionName}] Inserted " . $result->getInsertedCount() . " documents." . PHP_EOL;
    }

    // 1. Users
    $users = [
        ['userId' => 1, 'name' => 'System Administrator', 'email' => 'admin@gmail.com', 'password' => 'admin123', 'role' => 'admin', 'profile_picture' => null, 'created_at' => date('Y-m-d H:i:s')],
        ['userId' => 2, 'name' => 'Dr. Rajesh Sharma', 'email' => 'faculty@gmail.com', 'password' => 'faculty123', 'role' => 'faculty', 'profile_picture' => null, 'created_at' => date('Y-m-d H:i:s')],
        ['userId' => 3, 'name' => 'Prof. Ananya Verma', 'email' => 'ananya.verma@college.edu', 'password' => 'faculty123', 'role' => 'faculty', 'profile_picture' => null, 'created_at' => date('Y-m-d H:i:s')],
        ['userId' => 4, 'name' => 'Sunny Kumar', 'email' => 'student@gmail.com', 'password' => 'student123', 'role' => 'student', 'profile_picture' => null, 'created_at' => date('Y-m-d H:i:s')],
        ['userId' => 5, 'name' => 'Priya Singh', 'email' => 'priya.singh@student.edu', 'password' => 'student123', 'role' => 'student', 'profile_picture' => null, 'created_at' => date('Y-m-d H:i:s')],
        ['userId' => 6, 'name' => 'Rahul Patel', 'email' => 'rahul.patel@student.edu', 'password' => 'student123', 'role' => 'student', 'profile_picture' => null, 'created_at' => date('Y-m-d H:i:s')],
        ['userId' => 7, 'name' => 'Sneha Kapoor', 'email' => 'sneha.kapoor@student.edu', 'password' => 'student123', 'role' => 'student', 'profile_picture' => null, 'created_at' => date('Y-m-d H:i:s')]
    ];
    insertCollection($manager, $dbName, 'users', $users);

    // 2. Faculty
    $faculty = [
        ['facultyId' => 1, 'userId' => 2, 'name' => 'Dr. Rajesh Sharma', 'department' => 'Computer Science & Engineering', 'designation' => 'Associate Professor'],
        ['facultyId' => 2, 'userId' => 3, 'name' => 'Prof. Ananya Verma', 'department' => 'Information Technology', 'designation' => 'Assistant Professor']
    ];
    insertCollection($manager, $dbName, 'faculty', $faculty);

    // 3. Students
    $students = [
        ['studentId' => 1, 'userId' => 4, 'name' => 'Sunny Kumar', 'email' => 'student@gmail.com', 'roll_no' => '24BCS12641', 'course' => 'BCA', 'semester' => 4],
        ['studentId' => 2, 'userId' => 5, 'name' => 'Priya Singh', 'email' => 'priya.singh@student.edu', 'roll_no' => '24BCS12642', 'course' => 'BCA', 'semester' => 4],
        ['studentId' => 3, 'userId' => 6, 'name' => 'Rahul Patel', 'email' => 'rahul.patel@student.edu', 'roll_no' => '24BCS12643', 'course' => 'BCA', 'semester' => 4],
        ['studentId' => 4, 'userId' => 7, 'name' => 'Sneha Kapoor', 'email' => 'sneha.kapoor@student.edu', 'roll_no' => '24BCS12644', 'course' => 'BCA', 'semester' => 4]
    ];
    insertCollection($manager, $dbName, 'students', $students);

    // 4. Subjects
    $subjects = [
        ['subjectId' => 1, 'subject_name' => 'Database Management Systems', 'subject_code' => 'CS401', 'facultyId' => 1, 'course' => 'BCA', 'semester' => 4],
        ['subjectId' => 2, 'subject_name' => 'Web Development & PHP', 'subject_code' => 'CS402', 'facultyId' => 1, 'course' => 'BCA', 'semester' => 4],
        ['subjectId' => 3, 'subject_name' => 'Data Structures & Algorithms', 'subject_code' => 'CS403', 'facultyId' => 2, 'course' => 'BCA', 'semester' => 4],
        ['subjectId' => 4, 'subject_name' => 'Operating Systems', 'subject_code' => 'CS404', 'facultyId' => 2, 'course' => 'BCA', 'semester' => 4]
    ];
    insertCollection($manager, $dbName, 'subjects', $subjects);

    // 5. Assignments
    $assignments = [
        ['assignmentId' => 1, 'subjectId' => 1, 'facultyId' => 1, 'title' => 'SQL Joins & Normalization Assignment', 'description' => 'Implement 3NF normalization schema and write complex multi-table queries using INNER and LEFT JOINs.', 'deadline' => date('Y-m-d', strtotime('+7 days')), 'file_path' => 'sql_assignment.pdf'],
        ['assignmentId' => 2, 'subjectId' => 2, 'facultyId' => 1, 'title' => 'PHP CRUD Application Project', 'description' => 'Develop a secure student CRUD module with MySQL database connection and session-based authentication.', 'deadline' => date('Y-m-d', strtotime('+10 days')), 'file_path' => 'php_crud_brief.pdf'],
        ['assignmentId' => 3, 'subjectId' => 3, 'facultyId' => 2, 'title' => 'Binary Search Trees & Traversal', 'description' => 'Write programs in C/C++ to create a BST, insert nodes, and perform Inorder, Preorder, and Postorder traversals.', 'deadline' => date('Y-m-d', strtotime('+5 days')), 'file_path' => 'bst_traversal.pdf'],
        ['assignmentId' => 4, 'subjectId' => 4, 'facultyId' => 2, 'title' => 'CPU Scheduling Algorithms Simulation', 'description' => 'Demonstrate FCFS, SJF, and Round Robin scheduling algorithms with turnaround and waiting time calculations.', 'deadline' => date('Y-m-d', strtotime('+14 days')), 'file_path' => 'cpu_scheduling.pdf']
    ];
    insertCollection($manager, $dbName, 'assignments', $assignments);

    // 6. Submissions
    $submissions = [
        ['submissionId' => 1, 'assignmentId' => 1, 'studentId' => 1, 'file_path' => 'sunny_sql_joins_solution.pdf', 'status' => 'Graded', 'marks' => 92, 'remarks' => 'Excellent query optimization and thorough normalization analysis!'],
        ['submissionId' => 2, 'assignmentId' => 3, 'studentId' => 1, 'file_path' => 'sunny_bst_solution.pdf', 'status' => 'Graded', 'marks' => 88, 'remarks' => 'Good implementation of BST recursive traversals.'],
        ['submissionId' => 3, 'assignmentId' => 1, 'studentId' => 2, 'file_path' => 'priya_sql_solution.pdf', 'status' => 'Graded', 'marks' => 95, 'remarks' => 'Outstanding documentation and clean SQL scripts.'],
        ['submissionId' => 4, 'assignmentId' => 2, 'studentId' => 1, 'file_path' => 'sunny_php_project.zip', 'status' => 'Submitted', 'marks' => null, 'remarks' => null]
    ];
    insertCollection($manager, $dbName, 'submissions', $submissions);

    // 7. Grades
    $grades = [
        ['gradeId' => 1, 'studentId' => 1, 'assignmentId' => 1, 'subjectId' => 1, 'marks' => 92, 'remarks' => 'Excellent query optimization and thorough normalization analysis!'],
        ['gradeId' => 2, 'studentId' => 1, 'assignmentId' => 3, 'subjectId' => 3, 'marks' => 88, 'remarks' => 'Good implementation of BST recursive traversals.'],
        ['gradeId' => 3, 'studentId' => 2, 'assignmentId' => 1, 'subjectId' => 1, 'marks' => 95, 'remarks' => 'Outstanding documentation and clean SQL scripts.']
    ];
    insertCollection($manager, $dbName, 'grades', $grades);

    // 8. Attendance
    $attendance = [
        ['attendanceId' => 1, 'studentId' => 1, 'subjectId' => 1, 'attendance_date' => date('Y-m-d', strtotime('-6 days')), 'status' => 'Present'],
        ['attendanceId' => 2, 'studentId' => 1, 'subjectId' => 1, 'attendance_date' => date('Y-m-d', strtotime('-5 days')), 'status' => 'Present'],
        ['attendanceId' => 3, 'studentId' => 1, 'subjectId' => 1, 'attendance_date' => date('Y-m-d', strtotime('-4 days')), 'status' => 'Present'],
        ['attendanceId' => 4, 'studentId' => 1, 'subjectId' => 2, 'attendance_date' => date('Y-m-d', strtotime('-3 days')), 'status' => 'Present'],
        ['attendanceId' => 5, 'studentId' => 1, 'subjectId' => 2, 'attendance_date' => date('Y-m-d', strtotime('-2 days')), 'status' => 'Present'],
        ['attendanceId' => 6, 'studentId' => 1, 'subjectId' => 3, 'attendance_date' => date('Y-m-d', strtotime('-1 days')), 'status' => 'Present'],
        ['attendanceId' => 7, 'studentId' => 1, 'subjectId' => 4, 'attendance_date' => date('Y-m-d'), 'status' => 'Present'],
        ['attendanceId' => 8, 'studentId' => 2, 'subjectId' => 1, 'attendance_date' => date('Y-m-d'), 'status' => 'Present'],
        ['attendanceId' => 9, 'studentId' => 3, 'subjectId' => 1, 'attendance_date' => date('Y-m-d'), 'status' => 'Absent'],
        ['attendanceId' => 10, 'studentId' => 4, 'subjectId' => 1, 'attendance_date' => date('Y-m-d'), 'status' => 'Present']
    ];
    insertCollection($manager, $dbName, 'attendance', $attendance);

    // 9. Notices
    $notices = [
        ['noticeId' => 1, 'title' => 'Mid-Semester Examination Schedule', 'description' => 'The mid-semester theory examinations for BCA Semester 4 will commence from the 1st of next month.', 'posted_by' => 'Academic Cell'],
        ['noticeId' => 2, 'title' => 'Annual Tech Fest "Innovate 2026" Registration Open', 'description' => 'Registrations are now open for coding competitions, hackathons, and web design challenges.', 'posted_by' => 'Dr. Rajesh Sharma'],
        ['noticeId' => 3, 'title' => 'Library Book Return & Clearance Reminder', 'description' => 'All students are requested to return or renew overdue library books before semester break.', 'posted_by' => 'Librarian'],
        ['noticeId' => 4, 'title' => 'Guest Lecture on Cloud Computing & AI', 'description' => 'An expert session on "Cloud Architecture and Modern AI Microservices" will be held this Friday.', 'posted_by' => 'HOD Computer Science']
    ];
    insertCollection($manager, $dbName, 'notices', $notices);

    // 10. Study Materials
    $studyMaterials = [
        ['materialId' => 1, 'subjectId' => 1, 'facultyId' => 1, 'title' => 'DBMS Unit 1 & 2 - Relational Algebra & ER Modeling', 'file_path' => 'dbms_unit1_unit2_notes.pdf'],
        ['materialId' => 2, 'subjectId' => 2, 'facultyId' => 1, 'title' => 'PHP & MySQL Full Stack Handbook', 'file_path' => 'php_mysql_handbook.pdf'],
        ['materialId' => 3, 'subjectId' => 3, 'facultyId' => 2, 'title' => 'Data Structures Quick Revision Sheet', 'file_path' => 'dsa_quick_revision.pdf'],
        ['materialId' => 4, 'subjectId' => 4, 'facultyId' => 2, 'title' => 'Operating Systems Memory Management Slides', 'file_path' => 'os_memory_management.pdf']
    ];
    insertCollection($manager, $dbName, 'study_materials', $studyMaterials);

    // Create Indexes
    $indexCmd = new MongoDB\Driver\Command([
        'createIndexes' => 'users',
        'indexes' => [
            ['key' => ['email' => 1], 'name' => 'email_unique', 'unique' => true]
        ]
    ]);
    $manager->executeCommand($dbName, $indexCmd);

    $indexCmd2 = new MongoDB\Driver\Command([
        'createIndexes' => 'students',
        'indexes' => [
            ['key' => ['roll_no' => 1], 'name' => 'roll_no_unique', 'unique' => true]
        ]
    ]);
    $manager->executeCommand($dbName, $indexCmd2);

    echo PHP_EOL . "==========================================" . PHP_EOL;
    echo "SUCCESS: MongoDB 'student_portal' is fully seeded and ready!" . PHP_EOL;
    echo "==========================================" . PHP_EOL;

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
}
