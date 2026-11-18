# 🎓 Apex Imperial University & Modern College Portal
## Full-Stack Web Development & Presentation Guide
**Developed by: Sunny Kumar (`24BCS12641`)**  
*Project: Modern College Website & Integrated Academic Management System*

---

## 🏛️ 1. Project Architecture Overview

| Dimension | Details |
| :--- | :--- |
| **Project Title** | Apex Imperial University — Modern College Website & Multi-Role Enterprise Portal |
| **Developer** | Sunny Kumar (Roll: `24BCS12641`) |
| **Frontend Stack** | HTML5, CSS3 Glassmorphism, JavaScript, Bootstrap 5.3, Font Awesome 6, AOS Animations, Chart.js 4.x |
| **Backend Stack** | PHP 8+ (Clean MVC architecture, procedural security wrappers, session authorization) |
| **Database** | MySQL 8 / MariaDB (`college_website` database, 17 normalized relational tables) |
| **Visual Design** | Ivy-League Navy Blue (`#0a192f`), Pure White (`#ffffff`), and Royal Gold (`#d4af37`) |
| **User Roles** | **Admin**, **Faculty**, and **Student** (Role-Based Access Control) |
| **Public Website** | Home, About Us, Academics, Online Admissions & Tracking, Placements, Contact & Campus Visit |

---

## ⚡ 2. Quick Setup & Run Instructions

### Step 1: Start XAMPP Services
1. Open the **XAMPP Control Panel**.
2. Click **Start** for **Apache** and **MySQL** (both green).

### Step 2: Open in Your Browser
- Open your browser (Chrome or Edge) and visit:
  ```
  http://localhost/StudentPortal/
  ```
  *(or if running via PHP CLI: `C:\xampp\php\php.exe -S localhost:8000` & visit `http://localhost:8000`)*

### Step 3: Fast-Fill Demo Credentials (Zero Typing Required)
Every login form features **1-Click Fast-Fill Pills** so your evaluator or teacher ("mam") can immediately test all 3 roles:
- 🛡️ **Administrator**: `admin@gmail.com` | `admin123`
- 👨‍🏫 **Faculty**: `faculty@gmail.com` | `faculty123`
- 🎓 **Student**: `student@gmail.com` | `student123`

*(You can also use the floating **⚡ Portal Quick Access** pill in the bottom-right corner of any public page to jump directly into any role!)*

---

## 🌟 3. The 5-Minute "Teacher Impressing" Presentation Flow

Follow this structured walkthrough when presenting your project to your teacher/examiner:

### ⏱️ Minute 1: The Modern International University Website
- **Start at `index.php`**:
  - Show the **Live Announcement Ticker** at the top with campus updates.
  - Highlight the **Hero Section**: Show the Playfair Display typography, Navy & Gold luxury theme, and live counter statistics (15,000+ Scholars, 98% Placement, ₹44.5 LPA Highest Package).
  - Scroll through the **Legacy Overview**, **Schools & Programs**, **Upcoming Events**, **Campus Facilities**, **Student Testimonials**, and the **Photo Gallery**.
- **Show `admissions.php`**:
  - Point out the 4-step eligibility roadmap.
  - Demonstrate the **Live Application Status Tracker**: Enter `ADM-2026-1001` to instantly see live verification status.
  - Show the **Online Admission Enquiry Form**: Fill a quick dummy entry — show how it generates a unique application ID and saves to the MySQL database.
- **Show `placements.php`**:
  - Show recruiter logos (Google, Microsoft, Amazon, Deloitte), student success stories, and salary graphs.
- **Show `contact.php`**:
  - Show interactive inquiry submission form connected directly to `contact_messages` table.

### ⏱️ Minute 2: The Executive Administrator Dashboard
- Click **"Portal Quick Access" &rarr; Admin** (or login with `admin@gmail.com`):
  - **KPI Cards**: Total Students, Faculty, Degree Programs, Admissions.
  - **Dual Interactive Charts**:
    - *Admissions Growth Trend Line Chart* (Chart.js)
    - *Departmental Student Distribution Doughnut Chart*
  - **Online Admissions Manager (`manage_admissions.php`)**:
    - Click **Review Application** on any candidate to inspect details.
    - Click **Approve** or **Reject** with 1-click status update.
  - **Academic Structure Managers**:
    - Show **Departments** (`manage_departments.php`), **Courses** (`manage_courses.php`), and **Subjects** (`manage_subjects.php`).
  - **Exam Results & Grades Manager (`manage_results.php` & `manage_grades.php`)**:
    - Show the dedicated tabs for **Semester Exam Results** and **Assignment Continuous Grades**.
    - Click **Publish Exam Result**: Show how letter grades (O, A+, A, etc.) and Pass/Fail statuses auto-calculate based on marks.

### ⏱️ Minute 3: Faculty Portal (Teaching & Grading Workflow)
- Switch to **Faculty** (`faculty@gmail.com` - Dr. Rajesh Sharma):
  - Show the **Teaching Dashboard** with active courses and assigned student metrics.
  - **Create Assignment**: Create a coursework task with deadline and attachments.
  - **Grade Submissions**: Open student submissions, enter numerical marks and constructive feedback.
  - **Mark Attendance (`mark_attendance.php`)**: Show the date picker and 1-click attendance marker for classes.
  - **Publish Exam Results (`manage_results.php`)**: Input semester exam marks.
  - **Weekly Teaching Timetable (`timetable.php`)**: Clean day-by-day lecture schedule.

### ⏱️ Minute 4: Student Learning Portal
- Switch to **Student** (`student@gmail.com` - Sunny Kumar):
  - Show the **Student Dashboard**: Greeting banner, enrolled course (B.Tech CSE), attendance meter, and recent notices.
  - **My Degree & Courses (`courses.php`)**: Enrolled curriculum, credits, and HOD details.
  - **Weekly Class Timetable (`timetable.php`)**: Mon&ndash;Sat lecture schedule with room numbers.
  - **Assignments & My Submissions (`view_assignments.php` & `my_submissions.php`)**: Submit answers, view grades and instructor feedback.
  - **Exam Results Grade Card (`view_grades.php` / `results.php`)**:
    - Show the **SGPA calculation (e.g. 9.47 / 10.0)** and aggregate percentage banner.
    - Show the **Tab Switcher**: Official Semester Grade Card vs. Continuous Assignment Evaluations.
    - Click **Print Grade Sheet**: Shows clean print-ready transcript layout!
  - **Tuition Fees & Invoices (`fees.php`)**: Invoice ledger, payment status pills, and **Print Official Fee Receipt**.

### ⏱️ Minute 5: Security Architecture & Database Integrity
- Explain:
  - Role-Based Access Control (`config/auth.php` & `check_role()`).
  - Passwords hashed with standard `password_hash()` (Bcrypt).
  - SQL sanitization with `escape()` and integer typecasting `(int)$_POST[...]`.
  - Normalized 17-table MySQL schema with foreign keys and indexes.

---

## 💡 4. Top Viva Questions & Impressive Answers

### Q1: "How did you design and normalize the database?"
> **Answer:** *"Mam, the system uses a 3rd Normal Form (3NF) relational database named `college_website` with 17 normalized tables. Relationships are strictly maintained: `students` and `faculty` reference the primary `users` authentication table via foreign keys. Academic courses belong to departments, subjects map to courses and faculty, and attendance, assignments, fees, results, and timetable records tie back to specific students and courses."*

### Q2: "How is security implemented in the backend?"
> **Answer:** *"I implemented defense-in-depth across the PHP backend:
> 1. **Authentication:** Passwords use PHP's native `password_hash()` with BCRYPT algorithms and are verified using `password_verify()`.
> 2. **Authorization (RBAC):** Every internal page calls `check_role('admin'|'faculty'|'student')`. If an unauthorized user attempts direct URL tampering, they are safely redirected with a flash error.
> 3. **SQL Injection Prevention:** Every user input is sanitized using `escape()` (`mysqli_real_escape_string()`), and numerical IDs are explicitly cast using `(int)`.
> 4. **XSS Protection:** Dynamic outputs rendered into HTML are wrapped in `htmlspecialchars()`."*

### Q3: "Can the website be deployed to production?"
> **Answer:** *"Yes, mam. The system has zero hardcoded base paths, uses standard relative directory resolution, supports environment-configured database connections via `config/db.php`, and uses lightweight production CDNs for Bootstrap, FontAwesome, AOS, and Chart.js."*

---

## 📁 5. Directory & Module Reference

```
StudentPortal/
├── admin/                     # Administrator Portal (17 Modules)
│   ├── dashboard.php          # Executive analytics with Chart.js
│   ├── manage_admissions.php  # Online admission approvals
│   ├── manage_students.php    # Student CRUD
│   ├── manage_faculty.php     # Faculty roster CRUD
│   ├── manage_departments.php # Academic departments
│   ├── manage_courses.php     # Degree programs
│   ├── manage_subjects.php    # Curriculum subjects
│   ├── manage_results.php     # Semester exam results publisher
│   ├── manage_grades.php      # Continuous assignment grades
│   ├── manage_attendance.php  # University attendance audit
│   ├── manage_events.php      # Campus events CRUD
│   ├── manage_placements.php  # Placements & recruiters
│   ├── manage_gallery.php     # Campus photo gallery
│   └── manage_contacts.php    # Public contact inquiries
├── student/                   # Student Learning Hub (11 Modules)
│   ├── dashboard.php          # Student home & quick metrics
│   ├── courses.php            # Degree details & curriculum
│   ├── timetable.php          # Weekly class schedule
│   ├── view_assignments.php   # View and download assignments
│   ├── submit_assignment.php  # File upload & solution submit
│   ├── my_submissions.php     # Evaluated work & scores
│   ├── view_grades.php        # Semester results & Grade Sheet
│   ├── view_attendance.php    # Attendance percentage meter
│   ├── fees.php               # Invoices & printable fee receipts
│   └── profile.php            # Profile & photo update
├── faculty/                   # Faculty Workstation (11 Modules)
│   ├── dashboard.php          # Faculty overview
│   ├── create_assignment.php  # Publish assignments
│   ├── view_submissions.php   # Review student submissions
│   ├── grade_assignment.php   # Enter marks & feedback
│   ├── manage_results.php     # Enter semester exam marks
│   ├── mark_attendance.php    # Class attendance register
│   ├── timetable.php          # Teaching lecture timetable
│   └── upload_material.php    # Share courseware & slides
├── assets/                    # Presentation Assets & Styles
│   ├── css/public.css         # Navy & Gold public theme
│   ├── css/dashboard.css      # Glassmorphic portal design
│   ├── css/style.css          # Auth & login layout
│   └── js/public.js           # Animated counters & smooth scroll
├── config/
│   ├── db.php                 # MySQLi database wrapper
│   └── auth.php               # Session role security
├── database/
│   └── college_website.sql    # Complete SQL dump with sample data
├── index.php                  # Public Home Page
├── about.php                  # College History & Leadership
├── academics.php              # Degree Programs & Departments
├── admissions.php             # Online Application & Status Tracker
├── placements.php             # Recruiters & Placed Students
├── contact.php                # Campus Map & Enquiry Form
├── login.php                  # Fast-Fill Sign In
└── register.php               # Account Registration
```

---
*Created with pride for Academic Submission 2026. Ready for full live evaluation.*
