-- Student Portal Complete Database Schema
-- Compatible with MySQL 5.7+, MySQL 8.0+, MariaDB 10+

CREATE DATABASE IF NOT EXISTS `student_portal` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `student_portal`;

-- Drop existing tables in reverse dependency order
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `study_materials`;
DROP TABLE IF EXISTS `grades`;
DROP TABLE IF EXISTS `attendance`;
DROP TABLE IF EXISTS `submissions`;
DROP TABLE IF EXISTS `assignments`;
DROP TABLE IF EXISTS `subjects`;
DROP TABLE IF EXISTS `faculty`;
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `notices`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Users Table
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'faculty', 'student') NOT NULL,
  `profile_picture` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Students Table
CREATE TABLE `students` (
  `student_id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `roll_no` VARCHAR(50) NOT NULL,
  `department_id` INT DEFAULT 1,
  `course_id` INT DEFAULT 1,
  `course` VARCHAR(100) NOT NULL DEFAULT 'BCA',
  `semester` INT NOT NULL DEFAULT 1,
  `profile_picture` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (`user_id`),
  CONSTRAINT `fk_student_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Faculty Table
CREATE TABLE `faculty` (
  `faculty_id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `department` VARCHAR(100) NOT NULL DEFAULT 'Computer Science',
  `designation` VARCHAR(100) NOT NULL DEFAULT 'Assistant Professor',
  `profile_picture` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (`user_id`),
  CONSTRAINT `fk_faculty_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Subjects Table
CREATE TABLE `subjects` (
  `subject_id` INT AUTO_INCREMENT PRIMARY KEY,
  `subject_name` VARCHAR(100) NOT NULL,
  `subject_code` VARCHAR(50) NOT NULL UNIQUE,
  `faculty_id` INT DEFAULT NULL,
  `course` VARCHAR(100) DEFAULT 'BCA',
  `semester` INT DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Assignments Table
CREATE TABLE `assignments` (
  `assignment_id` INT AUTO_INCREMENT PRIMARY KEY,
  `subject_id` INT DEFAULT NULL,
  `faculty_id` INT DEFAULT NULL,
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT NOT NULL,
  `deadline` DATE NOT NULL,
  `file_path` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Submissions Table
CREATE TABLE `submissions` (
  `submission_id` INT AUTO_INCREMENT PRIMARY KEY,
  `assignment_id` INT NOT NULL,
  `student_id` INT NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `status` VARCHAR(50) DEFAULT 'Submitted',
  `marks` INT DEFAULT NULL,
  `remarks` TEXT DEFAULT NULL,
  INDEX (`assignment_id`),
  INDEX (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Attendance Table
CREATE TABLE `attendance` (
  `attendance_id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `subject_id` INT NOT NULL,
  `course_id` INT DEFAULT 1,
  `attendance_date` DATE NOT NULL,
  `date` DATE DEFAULT NULL,
  `status` ENUM('Present', 'Absent', 'Late', 'Excused') NOT NULL DEFAULT 'Present',
  `marked_by` INT DEFAULT NULL,
  `remarks` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (`student_id`),
  INDEX (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Grades Table
CREATE TABLE `grades` (
  `grade_id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `assignment_id` INT DEFAULT NULL,
  `subject_id` INT DEFAULT NULL,
  `marks` INT NOT NULL,
  `remarks` TEXT DEFAULT NULL,
  `graded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (`student_id`),
  INDEX (`assignment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. Notices Table
CREATE TABLE `notices` (
  `notice_id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT NOT NULL,
  `posted_by` VARCHAR(100) DEFAULT 'Admin',
  `posted_on` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. Study Materials Table
CREATE TABLE `study_materials` (
  `material_id` INT AUTO_INCREMENT PRIMARY KEY,
  `subject_id` INT DEFAULT NULL,
  `faculty_id` INT DEFAULT NULL,
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
