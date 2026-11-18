/**
 * MongoDB Setup & Seed Script for Student Portal
 * Database: student_portal
 * 
 * Usage:
 *  - In mongosh: mongosh < database/mongodb_setup.js
 *  - In MongoDB Compass: Open Compass, open the Mongosh pane at the bottom, and paste this script.
 *  - In VS Code (MongoDB Extension): Create a playground and paste this script.
 */

// Switch to student_portal database
use('student_portal');

// Drop existing collections to start clean (optional)
db.users.drop();
db.students.drop();
db.faculty.drop();
db.subjects.drop();
db.assignments.drop();
db.submissions.drop();
db.attendance.drop();
db.grades.drop();
db.notices.drop();
db.study_materials.drop();

// 1. Users Collection
db.users.insertMany([
  {
    userId: 1,
    name: "System Administrator",
    email: "admin@gmail.com",
    password: "admin123",
    role: "admin",
    profile_picture: null,
    created_at: new Date()
  },
  {
    userId: 2,
    name: "Dr. Rajesh Sharma",
    email: "faculty@gmail.com",
    password: "faculty123",
    role: "faculty",
    profile_picture: null,
    created_at: new Date()
  },
  {
    userId: 3,
    name: "Prof. Ananya Verma",
    email: "ananya.verma@college.edu",
    password: "faculty123",
    role: "faculty",
    profile_picture: null,
    created_at: new Date()
  },
  {
    userId: 4,
    name: "Sunny Kumar",
    email: "student@gmail.com",
    password: "student123",
    role: "student",
    profile_picture: null,
    created_at: new Date()
  },
  {
    userId: 5,
    name: "Priya Singh",
    email: "priya.singh@student.edu",
    password: "student123",
    role: "student",
    profile_picture: null,
    created_at: new Date()
  },
  {
    userId: 6,
    name: "Rahul Patel",
    email: "rahul.patel@student.edu",
    password: "student123",
    role: "student",
    profile_picture: null,
    created_at: new Date()
  },
  {
    userId: 7,
    name: "Sneha Kapoor",
    email: "sneha.kapoor@student.edu",
    password: "student123",
    role: "student",
    profile_picture: null,
    created_at: new Date()
  }
]);

// 2. Faculty Collection
db.faculty.insertMany([
  {
    facultyId: 1,
    userId: 2,
    name: "Dr. Rajesh Sharma",
    email: "faculty@gmail.com",
    department: "Computer Science & Engineering",
    designation: "Associate Professor",
    created_at: new Date()
  },
  {
    facultyId: 2,
    userId: 3,
    name: "Prof. Ananya Verma",
    email: "ananya.verma@college.edu",
    department: "Information Technology",
    designation: "Assistant Professor",
    created_at: new Date()
  }
]);

// 3. Students Collection
db.students.insertMany([
  {
    studentId: 1,
    userId: 4,
    name: "Sunny Kumar",
    email: "student@gmail.com",
    roll_no: "24BCS12641",
    course: "BCA",
    semester: 4,
    profile_picture: null,
    created_at: new Date()
  },
  {
    studentId: 2,
    userId: 5,
    name: "Priya Singh",
    email: "priya.singh@student.edu",
    roll_no: "24BCS12642",
    course: "BCA",
    semester: 4,
    profile_picture: null,
    created_at: new Date()
  },
  {
    studentId: 3,
    userId: 6,
    name: "Rahul Patel",
    email: "rahul.patel@student.edu",
    roll_no: "24BCS12643",
    course: "BCA",
    semester: 4,
    profile_picture: null,
    created_at: new Date()
  },
  {
    studentId: 4,
    userId: 7,
    name: "Sneha Kapoor",
    email: "sneha.kapoor@student.edu",
    roll_no: "24BCS12644",
    course: "BCA",
    semester: 4,
    profile_picture: null,
    created_at: new Date()
  }
]);

// 4. Subjects Collection
db.subjects.insertMany([
  {
    subjectId: 1,
    subject_name: "Database Management Systems",
    subject_code: "CS401",
    facultyId: 1,
    course: "BCA",
    semester: 4
  },
  {
    subjectId: 2,
    subject_name: "Web Development & PHP",
    subject_code: "CS402",
    facultyId: 1,
    course: "BCA",
    semester: 4
  },
  {
    subjectId: 3,
    subject_name: "Data Structures & Algorithms",
    subject_code: "CS403",
    facultyId: 2,
    course: "BCA",
    semester: 4
  },
  {
    subjectId: 4,
    subject_name: "Operating Systems",
    subject_code: "CS404",
    facultyId: 2,
    course: "BCA",
    semester: 4
  }
]);

// 5. Assignments Collection
db.assignments.insertMany([
  {
    assignmentId: 1,
    subjectId: 1,
    subject_name: "Database Management Systems",
    facultyId: 1,
    title: "SQL Joins & Normalization Assignment",
    description: "Implement 3NF normalization schema and write complex multi-table queries using INNER and LEFT JOINs.",
    deadline: new Date(Date.now() + 7 * 24 * 60 * 60 * 1000),
    file_path: "sql_assignment.pdf",
    created_at: new Date()
  },
  {
    assignmentId: 2,
    subjectId: 2,
    subject_name: "Web Development & PHP",
    facultyId: 1,
    title: "PHP CRUD Application Project",
    description: "Develop a secure student CRUD module with MySQL database connection and session-based authentication.",
    deadline: new Date(Date.now() + 10 * 24 * 60 * 60 * 1000),
    file_path: "php_crud_brief.pdf",
    created_at: new Date()
  },
  {
    assignmentId: 3,
    subjectId: 3,
    subject_name: "Data Structures & Algorithms",
    facultyId: 2,
    title: "Binary Search Trees & Traversal",
    description: "Write programs in C/C++ to create a BST, insert nodes, and perform Inorder, Preorder, and Postorder traversals.",
    deadline: new Date(Date.now() + 5 * 24 * 60 * 60 * 1000),
    file_path: "bst_traversal.pdf",
    created_at: new Date()
  },
  {
    assignmentId: 4,
    subjectId: 4,
    subject_name: "Operating Systems",
    facultyId: 2,
    title: "CPU Scheduling Algorithms Simulation",
    description: "Demonstrate FCFS, SJF, and Round Robin scheduling algorithms with turnaround and waiting time calculations.",
    deadline: new Date(Date.now() + 14 * 24 * 60 * 60 * 1000),
    file_path: "cpu_scheduling.pdf",
    created_at: new Date()
  }
]);

// 6. Submissions Collection
db.submissions.insertMany([
  {
    submissionId: 1,
    assignmentId: 1,
    studentId: 1,
    student_name: "Sunny Kumar",
    file_path: "sunny_sql_joins_solution.pdf",
    status: "Graded",
    marks: 92,
    remarks: "Excellent query optimization and thorough normalization analysis!",
    submitted_at: new Date()
  },
  {
    submissionId: 2,
    assignmentId: 3,
    studentId: 1,
    student_name: "Sunny Kumar",
    file_path: "sunny_bst_solution.pdf",
    status: "Graded",
    marks: 88,
    remarks: "Good implementation of BST recursive traversals.",
    submitted_at: new Date()
  },
  {
    submissionId: 3,
    assignmentId: 1,
    studentId: 2,
    student_name: "Priya Singh",
    file_path: "priya_sql_solution.pdf",
    status: "Graded",
    marks: 95,
    remarks: "Outstanding documentation and clean SQL scripts.",
    submitted_at: new Date()
  },
  {
    submissionId: 4,
    assignmentId: 2,
    studentId: 1,
    student_name: "Sunny Kumar",
    file_path: "sunny_php_project.zip",
    status: "Submitted",
    marks: null,
    remarks: null,
    submitted_at: new Date()
  }
]);

// 7. Grades Collection
db.grades.insertMany([
  {
    gradeId: 1,
    studentId: 1,
    assignmentId: 1,
    subjectId: 1,
    marks: 92,
    remarks: "Excellent query optimization and thorough normalization analysis!",
    graded_at: new Date()
  },
  {
    gradeId: 2,
    studentId: 1,
    assignmentId: 3,
    subjectId: 3,
    marks: 88,
    remarks: "Good implementation of BST recursive traversals.",
    graded_at: new Date()
  },
  {
    gradeId: 3,
    studentId: 2,
    assignmentId: 1,
    subjectId: 1,
    marks: 95,
    remarks: "Outstanding documentation and clean SQL scripts.",
    graded_at: new Date()
  }
]);

// 8. Attendance Collection
db.attendance.insertMany([
  { attendanceId: 1, studentId: 1, subjectId: 1, attendance_date: new Date(Date.now() - 6*86400000), status: "Present" },
  { attendanceId: 2, studentId: 1, subjectId: 1, attendance_date: new Date(Date.now() - 5*86400000), status: "Present" },
  { attendanceId: 3, studentId: 1, subjectId: 1, attendance_date: new Date(Date.now() - 4*86400000), status: "Present" },
  { attendanceId: 4, studentId: 1, subjectId: 2, attendance_date: new Date(Date.now() - 3*86400000), status: "Present" },
  { attendanceId: 5, studentId: 1, subjectId: 2, attendance_date: new Date(Date.now() - 2*86400000), status: "Present" },
  { attendanceId: 6, studentId: 1, subjectId: 3, attendance_date: new Date(Date.now() - 1*86400000), status: "Present" },
  { attendanceId: 7, studentId: 1, subjectId: 4, attendance_date: new Date(), status: "Present" },
  { attendanceId: 8, studentId: 2, subjectId: 1, attendance_date: new Date(), status: "Present" },
  { attendanceId: 9, studentId: 3, subjectId: 1, attendance_date: new Date(), status: "Absent" },
  { attendanceId: 10, studentId: 4, subjectId: 1, attendance_date: new Date(), status: "Present" }
]);

// 9. Notices Collection
db.notices.insertMany([
  {
    noticeId: 1,
    title: "Mid-Semester Examination Schedule",
    description: "The mid-semester theory examinations for BCA Semester 4 will commence from the 1st of next month. Detailed datesheet is available on the departmental notice board.",
    posted_by: "Academic Cell",
    posted_on: new Date()
  },
  {
    noticeId: 2,
    title: "Annual Tech Fest \"Innovate 2026\" Registration Open",
    description: "Registrations are now open for coding competitions, hackathons, and web design challenges. Certificates and cash prizes will be awarded to winners.",
    posted_by: "Dr. Rajesh Sharma",
    posted_on: new Date()
  },
  {
    noticeId: 3,
    title: "Library Book Return & Clearance Reminder",
    description: "All students are requested to return or renew overdue library books before the upcoming semester break to avoid late clearance fees.",
    posted_by: "Librarian",
    posted_on: new Date()
  },
  {
    noticeId: 4,
    title: "Guest Lecture on Cloud Computing & AI",
    description: "An expert session on \"Cloud Architecture and Modern AI Microservices\" will be held this Friday in Seminar Hall 2 at 11:00 AM. Attendance is mandatory.",
    posted_by: "HOD Computer Science",
    posted_on: new Date()
  }
]);

// 10. Study Materials Collection
db.study_materials.insertMany([
  {
    materialId: 1,
    subjectId: 1,
    facultyId: 1,
    title: "DBMS Unit 1 & 2 - Relational Algebra & ER Modeling",
    description: "Comprehensive lecture notes covering ER diagrams, relational schema conversion, and relational algebra queries.",
    file_path: "dbms_unit1_unit2_notes.pdf",
    uploaded_at: new Date()
  },
  {
    materialId: 2,
    subjectId: 2,
    facultyId: 1,
    title: "PHP & MySQL Full Stack Handbook",
    description: "Complete practical guide with hands-on examples for forms, sessions, prepared statements, and database integration.",
    file_path: "php_mysql_handbook.pdf",
    uploaded_at: new Date()
  },
  {
    materialId: 3,
    subjectId: 3,
    facultyId: 2,
    title: "Data Structures Quick Revision Sheet",
    description: "Key formulas, time complexity comparisons, and algorithm pseudo-codes for Trees, Graphs, and Sorting.",
    file_path: "dsa_quick_revision.pdf",
    uploaded_at: new Date()
  },
  {
    materialId: 4,
    subjectId: 4,
    facultyId: 2,
    title: "Operating Systems Memory Management Slides",
    description: "Presentation slides covering paging, segmentation, virtual memory, and page replacement algorithms.",
    file_path: "os_memory_management.pdf",
    uploaded_at: new Date()
  }
]);

// Create Useful Indexes
db.users.createIndex({ email: 1 }, { unique: true });
db.students.createIndex({ roll_no: 1 }, { unique: true });
db.students.createIndex({ userId: 1 });
db.faculty.createIndex({ userId: 1 });
db.subjects.createIndex({ subject_code: 1 }, { unique: true });
db.assignments.createIndex({ subjectId: 1 });
db.submissions.createIndex({ assignmentId: 1, studentId: 1 });
db.attendance.createIndex({ studentId: 1, subjectId: 1 });

print("Student Portal database successfully populated in MongoDB!");
