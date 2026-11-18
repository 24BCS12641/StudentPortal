-- Student Portal Realistic Sample Data
-- Import this file into phpMyAdmin after importing student_portal.sql

USE `student_portal`;

-- Clear existing data
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE `study_materials`;
TRUNCATE TABLE `grades`;
TRUNCATE TABLE `attendance`;
TRUNCATE TABLE `submissions`;
TRUNCATE TABLE `assignments`;
TRUNCATE TABLE `subjects`;
TRUNCATE TABLE `faculty`;
TRUNCATE TABLE `students`;
TRUNCATE TABLE `notices`;
TRUNCATE TABLE `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Insert Users
-- Passwords:
-- admin@gmail.com -> admin123
-- faculty@gmail.com -> faculty123
-- student@gmail.com -> student123
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `profile_picture`) VALUES
(1, 'System Administrator', 'admin@gmail.com', 'admin123', 'admin', NULL),
(2, 'Dr. Rajesh Sharma', 'faculty@gmail.com', 'faculty123', 'faculty', NULL),
(3, 'Prof. Ananya Verma', 'ananya.verma@college.edu', 'faculty123', 'faculty', NULL),
(4, 'Sunny Kumar', 'student@gmail.com', 'student123', 'student', NULL),
(5, 'Priya Singh', 'priya.singh@student.edu', 'student123', 'student', NULL),
(6, 'Rahul Patel', 'rahul.patel@student.edu', 'student123', 'student', NULL),
(7, 'Sneha Kapoor', 'sneha.kapoor@student.edu', 'student123', 'student', NULL);

-- 2. Insert Faculty Details
INSERT INTO `faculty` (`faculty_id`, `user_id`, `department`, `designation`) VALUES
(1, 2, 'Computer Science & Engineering', 'Associate Professor'),
(2, 3, 'Information Technology', 'Assistant Professor');

-- 3. Insert Students Details
INSERT INTO `students` (`student_id`, `user_id`, `roll_no`, `course`, `semester`) VALUES
(1, 4, '24BCS12641', 'BCA', 4),
(2, 5, '24BCS12642', 'BCA', 4),
(3, 6, '24BCS12643', 'BCA', 4),
(4, 7, '24BCS12644', 'BCA', 4);

-- 4. Insert Subjects
INSERT INTO `subjects` (`subject_id`, `subject_name`, `subject_code`, `faculty_id`, `course`, `semester`) VALUES
(1, 'Database Management Systems', 'CS401', 1, 'BCA', 4),
(2, 'Web Development & PHP', 'CS402', 1, 'BCA', 4),
(3, 'Data Structures & Algorithms', 'CS403', 2, 'BCA', 4),
(4, 'Operating Systems', 'CS404', 2, 'BCA', 4);

-- 5. Insert Assignments
INSERT INTO `assignments` (`assignment_id`, `subject_id`, `faculty_id`, `title`, `description`, `deadline`, `file_path`) VALUES
(1, 1, 1, 'SQL Joins & Normalization Assignment', 'Implement 3NF normalization schema and write complex multi-table queries using INNER and LEFT JOINs.', DATE_ADD(CURRENT_DATE, INTERVAL 7 DAY), 'sql_assignment.pdf'),
(2, 2, 1, 'PHP CRUD Application Project', 'Develop a secure student CRUD module with MySQL database connection and session-based authentication.', DATE_ADD(CURRENT_DATE, INTERVAL 10 DAY), 'php_crud_brief.pdf'),
(3, 3, 2, 'Binary Search Trees & Traversal', 'Write programs in C/C++ to create a BST, insert nodes, and perform Inorder, Preorder, and Postorder traversals.', DATE_ADD(CURRENT_DATE, INTERVAL 5 DAY), 'bst_traversal.pdf'),
(4, 4, 2, 'CPU Scheduling Algorithms Simulation', 'Demonstrate FCFS, SJF, and Round Robin scheduling algorithms with turnaround and waiting time calculations.', DATE_ADD(CURRENT_DATE, INTERVAL 14 DAY), 'cpu_scheduling.pdf');

-- 6. Insert Submissions
INSERT INTO `submissions` (`submission_id`, `assignment_id`, `student_id`, `file_path`, `status`, `marks`, `remarks`) VALUES
(1, 1, 1, 'sunny_sql_joins_solution.pdf', 'Graded', 92, 'Excellent query optimization and thorough normalization analysis!'),
(2, 3, 1, 'sunny_bst_solution.pdf', 'Graded', 88, 'Good implementation of BST recursive traversals.'),
(3, 1, 2, 'priya_sql_solution.pdf', 'Graded', 95, 'Outstanding documentation and clean SQL scripts.'),
(4, 2, 1, 'sunny_php_project.zip', 'Submitted', NULL, NULL);

-- 7. Insert Grades
INSERT INTO `grades` (`grade_id`, `student_id`, `assignment_id`, `subject_id`, `marks`, `remarks`) VALUES
(1, 1, 1, 1, 92, 'Excellent query optimization and thorough normalization analysis!'),
(2, 1, 3, 3, 88, 'Good implementation of BST recursive traversals.'),
(3, 2, 1, 1, 95, 'Outstanding documentation and clean SQL scripts.');

-- 8. Insert Attendance
INSERT INTO `attendance` (`attendance_id`, `student_id`, `subject_id`, `attendance_date`, `status`) VALUES
(1, 1, 1, DATE_SUB(CURRENT_DATE, INTERVAL 6 DAY), 'Present'),
(2, 1, 1, DATE_SUB(CURRENT_DATE, INTERVAL 5 DAY), 'Present'),
(3, 1, 1, DATE_SUB(CURRENT_DATE, INTERVAL 4 DAY), 'Present'),
(4, 1, 2, DATE_SUB(CURRENT_DATE, INTERVAL 3 DAY), 'Present'),
(5, 1, 2, DATE_SUB(CURRENT_DATE, INTERVAL 2 DAY), 'Present'),
(6, 1, 3, DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY), 'Present'),
(7, 1, 4, CURRENT_DATE, 'Present'),
(8, 2, 1, CURRENT_DATE, 'Present'),
(9, 3, 1, CURRENT_DATE, 'Absent'),
(10, 4, 1, CURRENT_DATE, 'Present');

-- 9. Insert Notices
INSERT INTO `notices` (`notice_id`, `title`, `description`, `posted_by`) VALUES
(1, 'Mid-Semester Examination Schedule', 'The mid-semester theory examinations for BCA Semester 4 will commence from the 1st of next month. Detailed datesheet is available on the departmental notice board.', 'Academic Cell'),
(2, 'Annual Tech Fest "Innovate 2026" Registration Open', 'Registrations are now open for coding competitions, hackathons, and web design challenges. Certificates and cash prizes will be awarded to winners.', 'Dr. Rajesh Sharma'),
(3, 'Library Book Return & Clearance Reminder', 'All students are requested to return or renew overdue library books before the upcoming semester break to avoid late clearance fees.', 'Librarian'),
(4, 'Guest Lecture on Cloud Computing & AI', 'An expert session on "Cloud Architecture and Modern AI Microservices" will be held this Friday in Seminar Hall 2 at 11:00 AM. Attendance is mandatory.', 'HOD Computer Science');

-- 10. Insert Study Materials
INSERT INTO `study_materials` (`material_id`, `subject_id`, `faculty_id`, `title`, `description`, `file_path`) VALUES
(1, 1, 1, 'DBMS Unit 1 & 2 - Relational Algebra & ER Modeling', 'Comprehensive lecture notes covering ER diagrams, relational schema conversion, and relational algebra queries.', 'dbms_unit1_unit2_notes.pdf'),
(2, 2, 1, 'PHP & MySQL Full Stack Handbook', 'Complete practical guide with hands-on examples for forms, sessions, prepared statements, and database integration.', 'php_mysql_handbook.pdf'),
(3, 3, 2, 'Data Structures Quick Revision Sheet', 'Key formulas, time complexity comparisons, and algorithm pseudo-codes for Trees, Graphs, and Sorting.', 'dsa_quick_revision.pdf'),
(4, 4, 2, 'Operating Systems Memory Management Slides', 'Presentation slides covering paging, segmentation, virtual memory, and page replacement algorithms.', 'os_memory_management.pdf');
