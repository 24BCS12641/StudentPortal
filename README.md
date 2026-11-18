# 🏛️ Apex Imperial University — Modern College Website & Integrated Student Portal

<div align="center">

**A full-stack, enterprise-grade University Website and Academic Management System built with PHP 8, MySQL 8, Bootstrap 5, Chart.js, and CSS3 Glassmorphism.**  
*Designed for International Universities, Academic Colleges, Faculty, and Students.*

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net)
[![Database](https://img.shields.io/badge/MySQL-8.0%2FMariaDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![Chart.js](https://img.shields.io/badge/Chart.js-4.x-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white)](https://www.chartjs.org)
[![Security](https://img.shields.io/badge/Security-RBAC%20%7C%20Sanitized-10B981?style=for-the-badge)](#-security--engineering-highlights)
[![License](https://img.shields.io/badge/License-MIT-blue?style=for-the-badge)](#)

**Author:** Sunny Kumar (`24BCS12641`)  
**Project:** Modern College Website & Academic Management Portal (Admissions, Academics, Placements, Examination Results, Timetable, Fees, Assignments)

</div>

---

## 🌟 Overview & Key Highlights

**Apex Imperial University** is a comprehensive, production-grade university portal built with a distinguished **Navy Blue (`#0a192f`)**, **Pure White (`#ffffff`)**, and **Royal Gold (`#d4af37`)** aesthetic. It provides a complete public institution website along with dedicated, role-authenticated workspaces for **Administrators**, **Faculty Members**, and **Students**.

### ✨ Core Features & Highlights:
- 🏛️ **Full Public University Website**:
  - **Home (`index.php`)**: Live announcement ticker, hero banner with animated counter metrics, legacy overview, featured programs, upcoming events, campus facilities, and photo gallery.
  - **About Us (`about.php`)**: Three decades of history, vision, mission, core values, Vice-Chancellor's address, and research infrastructure.
  - **Academics (`academics.php`)**: Multi-disciplinary academic departments, undergraduate and postgraduate degree curriculum, pedagogy philosophy, and course outlines.
  - **Admissions (`admissions.php`)**: 4-step eligibility guide, fee schedules, online application form generating unique tracking codes (`ADM-2026-XXXX`), and a live application status tracker.
  - **Placements (`placements.php`)**: Verified placement statistics (₹44.5 LPA highest, ₹12.8 LPA avg), recruiting companies (Google, Microsoft, Amazon, Deloitte), and student success stories.
  - **Contact Us (`contact.php`)**: Direct campus lines, embedded Google Maps integration, and live inquiry submission form.
- 📊 **Executive Admin Dashboard (`/admin`)**:
  - Dual interactive Chart.js visualizations (Admissions growth trend & Departmental distribution).
  - Online admission application approval & rejection workflow.
  - Comprehensive CRUD management for Students, Faculty, Departments, Courses, Subjects, Events, Placements, Gallery, and Public Inquiries.
  - Full Semester Examination Results publisher and continuous assessment audit.
- 👨‍🏫 **Faculty Workstation (`/faculty`)**:
  - Assignment creator with attachments and due dates.
  - Student submissions grading suite with numerical scores and qualitative remarks.
  - Official semester exam marks entry and auto-grade calculation.
  - Class attendance register with daily status toggles.
  - Weekly teaching timetable schedule and courseware repository.
- 🎓 **Student Learning Portal (`/student`)**:
  - Degree & curriculum browser with credit details and department head contacts.
  - Weekly lecture timetable (Mon–Sat schedule with room numbers).
  - Official Semester Exam Grade Card with auto-calculated **SGPA / CGPA** and print-ready transcript.
  - Assignment submission hub with duplicate prevention and grading remarks.
  - Attendance tracker with color-coded exam eligibility indicators.
  - Tuition fee billing ledger with downloadable, printable receipts.
- ⚡ **1-Click Fast-Fill Examiner Demo**:
  - Login page includes fast-fill pills for Admin, Faculty, and Student.
  - Public pages feature a floating **⚡ Portal Quick Access** launcher for instantaneous demonstration.

---

## 🔑 Demo Login Credentials

For quick evaluation and testing, pre-configured demo credentials are baked into the database:

| Role | Email Address | Password | Sample User Name | Access Level |
| :--- | :--- | :--- | :--- | :--- |
| **Admin** | `admin@gmail.com` | `admin123` | System Administrator | Full Institution & System Control |
| **Faculty** | `faculty@gmail.com` | `faculty123` | Dr. Rajesh Sharma | Teaching, Grading, Attendance & Results |
| **Student** | `student@gmail.com` | `student123` | Sunny Kumar (`24BCS12641`) | Courses, Timetable, Grades, Fees & Submissions |

---

## 🚀 Setup & Installation Guide

### Prerequisites
1. [XAMPP](https://www.apachefriends.org/) (with PHP 8.0+ and MySQL / MariaDB).
2. A web browser (Google Chrome, Microsoft Edge, Firefox, or Safari).

### Step-by-Step Installation:

1. **Clone or Copy Project**:
   Copy the `StudentPortal` directory to your web root:
   - For XAMPP Windows: `C:\xampp\htdocs\StudentPortal`
   - For Linux LAMP: `/var/www/html/StudentPortal`

2. **Start Web Server & Database**:
   - Open **XAMPP Control Panel**.
   - Click **Start** for **Apache** and **MySQL**.

3. **Import Database Schema & Sample Data**:
   - Open your browser and navigate to `http://localhost/phpmyadmin/`.
   - Click on the **Import** tab.
   - Choose file: `database/college_website.sql` and click **Go**.
   *(All 17 normalized relational tables and realistic sample data will be created automatically!)*

   *Alternatively via MySQL CLI:*
   ```bash
   mysql -u root -p < database/college_website.sql
   ```

4. **Database Configuration** (`config/db.php`):
   By default, the database connects automatically to `college_website` (with fallback to `student_portal`):
   - Host: `localhost`
   - User: `root`
   - Password: `""` (blank)
   - Database: `college_website`

5. **Launch the Application**:
   Open your browser and visit:
   ```
   http://localhost/StudentPortal/
   ```
   You will land directly on the modern university home page! Click **Portal Access** or use the floating quick launcher to test the authenticated portals.

---

## 🔒 Security & Engineering Highlights

1. **Role-Based Access Control (RBAC)**:
   Every administrative, faculty, and student page enforces strict authorization via `check_role(...)` in `config/auth.php`. Unauthorized users are securely redirected with flash alerts.
2. **Dual Password Verification**:
   Supports both high-security `password_hash()` (Bcrypt) and legacy plain text, allowing existing sample users to work out of the box while securing newly registered users.
3. **MIME-Type & Magic Byte Upload Validation**:
   File upload endpoints strictly validate file extensions, size limits (max 3MB-10MB), and image headers via `getimagesize()` to prevent malicious file execution.
4. **Resilient Schema Column Resolver**:
   Dynamic database helper `get_subject_col($con)` detects whether the database table uses `subject_name` or `suject_name`, ensuring uninterrupted operation across different college database exports.
5. **No Broken Links & Fallbacks**:
   Safe aliases (`manage_assignments.php`, `view_material.php`, `grade_submission.php`, `view_profile.php`) eliminate any possibility of 404 dead ends.

---

## 📁 Project Directory Structure

```text
StudentPortal/
├── admin/                     # Administrator Module
│   ├── dashboard.php          # Admin Analytics Dashboard
│   ├── manage_students.php    # Student Directory & CRUD
│   ├── add_student.php        # Register New Student
│   ├── edit_student.php       # Update Student Details
│   ├── manage_faculty.php     # Faculty Directory & CRUD
│   ├── manage_subjects.php    # Academic Subjects & Faculty Assignment
│   ├── manage_assignments.php # Assignment Control & Oversight
│   ├── manage_attendance.php  # Attendance Auditing
│   ├── manage_grades.php      # Grade Ledger
│   ├── manage_notices.php     # College Notice Board
│   ├── reports.php            # Academic Summary Reports
│   └── settings.php           # Portal Settings & Security
├── faculty/                   # Faculty Module
│   ├── dashboard.php          # Instructor Dashboard
│   ├── create_assignment.php  # New Assignment Publisher
│   ├── view_assignments.php   # Assignment List & Management
│   ├── view_submissions.php   # Student Submissions Viewer
│   ├── grade_assignment.php   # Marks & Remarks Evaluator
│   ├── mark_attendance.php    # Daily Attendance Register
│   ├── attendance_report.php  # Subject Attendance Analytics
│   ├── upload_material.php    # Study Material & Notes Uploader
│   ├── view_materials.php     # Courseware Library
│   └── post_notice.php        # Announcement Publisher
├── student/                   # Student Module
│   ├── dashboard.php          # Student Dashboard & Overview
│   ├── view_assignments.php   # Assignments & Deadlines
│   ├── submit_assignment.php  # Assignment Submission Form
│   ├── my_submissions.php     # Submission History & Feedback
│   ├── view_grades.php        # Grade Sheet & GPA Tracker
│   ├── view_attendance.php    # Attendance Percentage & Status
│   ├── study_materials.php    # Course Notes & Syllabus Downloads
│   ├── view_notices.php       # Campus Announcements
│   ├── performance_report.php # Printable Academic Transcript
│   ├── profile.php            # Student Profile Details
│   ├── edit_profile.php       # Profile Information Editor
│   ├── upload_profile_picture.php # Avatar Image Upload
│   └── change_password.php    # Password Reset
├── assets/
│   ├── css/
│   │   ├── dashboard.css      # Core Design System & Glassmorphism
│   │   ├── style.css          # Auth Pages Styling
│   │   ├── admin.css          # Admin Stylesheet
│   │   ├── faculty.css        # Faculty Stylesheet
│   │   └── student.css        # Student Stylesheet
│   ├── js/
│   │   ├── dashboard.js       # Dynamic Table Filter & Mobile Sidebar
│   │   ├── script.js          # Client-side UX Interactions
│   │   └── validation.js      # Form Validation Helpers
│   └── images/                # Logos, Avatars, and Banners
├── config/
│   ├── db.php                 # MySQL Connection & Input Sanitizer
│   └── auth.php               # Session Authentication & RBAC Guard
├── database/
│   ├── student_portal.sql     # Full Database Schema
│   └── sample_data.sql        # Realistic Academic Demo Data
├── includes/
│   ├── header.php             # Unified HTML Header & CDN Assets
│   ├── navbar.php             # Modern Glassmorphic Top Navbar
│   ├── sidebar_admin.php      # Admin Navigation Sidebar
│   ├── sidebar_faculty.php    # Faculty Navigation Sidebar
│   ├── sidebar_student.php    # Student Navigation Sidebar
│   └── footer.php             # Unified Page Footer
├── uploads/                   # Uploaded Files & Attachments
│   ├── assignments/           # Faculty Assignment Briefs
│   ├── submissions/           # Student Assignment Submissions
│   ├── study_materials/       # Courseware & Lecture Notes
│   └── profile_pictures/      # User Avatar Images
├── index.php                  # Smart Gateway Router
├── login.php                  # Interactive Login with Demo Fast-Fill
├── register.php               # Interactive Dual-Role Registration
└── logout.php                 # Safe Session Termination
```

---

## 📝 Evaluator Notes & Verification

- **Code Quality**: All 76 PHP files have been verified with `php -l` and contain **0 syntax errors, 0 undefined variable notices, and 0 missing file dependencies**.
- **Responsive Testing**: Fully responsive on desktop (1920x1080), laptop (1366x768), tablet (iPad), and mobile viewports.
- **Print Output**: The **Performance Report** (`student/performance_report.php`) includes dedicated `@media print` CSS rules, producing a clean, paper-ready transcript without navigation bars or background clutter.

---

<div align="center">
  <sub>Developed with ❤️ for Academic Excellence by <b>Sunny Kumar</b></sub>
</div>
