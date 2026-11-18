-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: college_website
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `college_website`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `college_website` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `college_website`;

--
-- Table structure for table `admissions`
--

DROP TABLE IF EXISTS `admissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admissions` (
  `admission_id` int(11) NOT NULL AUTO_INCREMENT,
  `application_no` varchar(40) NOT NULL,
  `student_name` varchar(120) NOT NULL,
  `email` varchar(120) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `course_id` int(11) NOT NULL,
  `marks_12th` decimal(5,2) DEFAULT NULL,
  `prev_school` varchar(200) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('Pending','Under Review','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `notes` text DEFAULT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`admission_id`),
  UNIQUE KEY `application_no` (`application_no`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `fk_adm_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admissions`
--

LOCK TABLES `admissions` WRITE;
/*!40000 ALTER TABLE `admissions` DISABLE KEYS */;
INSERT INTO `admissions` VALUES (1,'ADM-2026-8910','Aarav Mehta','aarav.mehta@gmail.com','+91 98451 12345',1,91.50,'Delhi Public School, R.K. Puram','New Delhi, India','Approved','Merit scholarship awarded: 25% fee concession.','2026-11-17 10:43:57'),(2,'ADM-2026-8911','Ishita Sharma','ishita.sharma@gmail.com','+91 98452 23456',2,84.20,'Kendriya Vidyalaya No. 1','Chandigarh, India','Approved','Awaiting original 12th board certificate verification.','2026-11-17 10:43:57'),(3,'ADM-2026-8912','Rohan Gupta','rohan.gupta@gmail.com','+91 98453 34567',4,78.00,'St. Xavier High School','Mumbai, India','Under Review','Interview scheduled for upcoming Wednesday.','2026-11-17 10:43:57'),(4,'ADM-2026-8913','Tanvi Reddy','tanvi.reddy@gmail.com','+91 98454 45678',1,94.80,'Hyderabad Public School','Hyderabad, India','Approved','Top ranker in state entrance test.','2026-11-17 10:43:57'),(5,'ADM-2026-7742','Sunny kumar','sunnykr851212@gmail.com','+916207754322',1,75.00,'BSEB','NH-05, Ludhiana, Highway, Chandigarh State, Punjab','Approved','','2026-11-17 10:59:52');
/*!40000 ALTER TABLE `admissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assignments`
--

DROP TABLE IF EXISTS `assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assignments` (
  `assignment_id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) DEFAULT 1,
  `course_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `deadline` date NOT NULL,
  `max_marks` int(11) DEFAULT 100,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`assignment_id`),
  KEY `course_id` (`course_id`),
  KEY `faculty_id` (`faculty_id`),
  CONSTRAINT `fk_asgn_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_asgn_fac` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assignments`
--

LOCK TABLES `assignments` WRITE;
/*!40000 ALTER TABLE `assignments` DISABLE KEYS */;
INSERT INTO `assignments` VALUES (1,1,2,1,'Enterprise Relational Schema & 3NF Normalization','Design a scalable university database schema, eliminate transitive dependencies, and write complex JOIN queries.','2026-11-24',100,'sql_assignment.pdf','2026-11-17 10:43:57'),(2,1,2,1,'PHP Full-Stack Session Authentication Module','Implement session-based login, role access guards, prepared statements, and user registration.','2026-11-29',100,'php_crud_brief.pdf','2026-11-17 10:43:57'),(3,1,1,2,'Distributed Cloud Architecture & Microservices','Deploy a containerized microservice application with load balancing and auto-scaling simulation.','2026-11-27',100,'cloud_arch.pdf','2026-11-17 10:43:57');
/*!40000 ALTER TABLE `assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT 1,
  `course_id` int(11) NOT NULL,
  `attendance_date` date DEFAULT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent','Late') NOT NULL DEFAULT 'Present',
  `marked_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`attendance_id`),
  KEY `student_id` (`student_id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `fk_att_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendance`
--

LOCK TABLES `attendance` WRITE;
/*!40000 ALTER TABLE `attendance` DISABLE KEYS */;
INSERT INTO `attendance` VALUES (1,1,1,2,'2026-11-11','2026-11-11','Present',NULL,'2026-11-17 10:43:57'),(2,1,1,2,'2026-11-12','2026-11-12','Present',NULL,'2026-11-17 10:43:57'),(3,1,1,2,'2026-11-13','2026-11-13','Present',NULL,'2026-11-17 10:43:57'),(4,1,1,2,'2026-11-14','2026-11-14','Present',NULL,'2026-11-17 10:43:57'),(5,1,1,2,'2026-11-15','2026-11-15','Present',NULL,'2026-11-17 10:43:57'),(6,1,1,2,'2026-11-16','2026-11-16','Present',NULL,'2026-11-17 10:43:57'),(7,1,1,2,'2026-11-17','2026-11-17','Present',NULL,'2026-11-17 10:43:57'),(8,2,1,2,'2026-11-17','2026-11-17','Present',NULL,'2026-11-17 10:43:57'),(9,3,1,1,'2026-11-17','2026-11-17','Absent',NULL,'2026-11-17 10:43:57'),(10,3,2,1,'2026-11-16','2026-11-16','Absent',1,'2026-11-17 11:02:26'),(11,3,3,1,'2026-11-15','2026-11-15','Present',1,'2026-11-17 11:02:26'),(12,3,4,1,'2026-11-14','2026-11-14','Present',1,'2026-11-17 11:02:26'),(13,3,1,1,'2026-11-13','2026-11-13','Present',1,'2026-11-17 11:02:26'),(14,3,2,1,'2026-11-12','2026-11-12','Present',1,'2026-11-17 11:02:26'),(15,3,3,1,'2026-11-11','2026-11-11','Present',1,'2026-11-17 11:02:26'),(16,3,4,1,'2026-11-10','2026-11-10','Absent',1,'2026-11-17 11:02:26'),(17,3,1,1,'2026-11-09','2026-11-09','Present',1,'2026-11-17 11:02:26'),(18,3,2,1,'2026-11-08','2026-11-08','Present',1,'2026-11-17 11:02:26'),(19,3,3,1,'2026-11-07','2026-11-07','Present',1,'2026-11-17 11:02:26'),(20,3,4,1,'2026-11-06','2026-11-06','Present',1,'2026-11-17 11:02:26'),(21,3,1,1,'2026-11-05','2026-11-05','Present',1,'2026-11-17 11:02:26'),(22,3,2,1,'2026-11-04','2026-11-04','Absent',1,'2026-11-17 11:02:26'),(23,3,3,1,'2026-11-03','2026-11-03','Present',1,'2026-11-17 11:02:26'),(24,1,2,2,'2026-11-16','2026-11-16','Present',1,'2026-11-17 11:02:26'),(25,1,3,2,'2026-11-15','2026-11-15','Present',1,'2026-11-17 11:02:26'),(26,1,4,2,'2026-11-14','2026-11-14','Absent',1,'2026-11-17 11:02:26'),(27,1,1,2,'2026-11-13','2026-11-13','Present',1,'2026-11-17 11:02:26'),(28,1,2,2,'2026-11-12','2026-11-12','Present',1,'2026-11-17 11:02:26'),(29,1,3,2,'2026-11-11','2026-11-11','Present',1,'2026-11-17 11:02:26'),(30,1,4,2,'2026-11-10','2026-11-10','Present',1,'2026-11-17 11:02:26'),(31,1,1,2,'2026-11-09','2026-11-09','Present',1,'2026-11-17 11:02:26'),(32,1,2,2,'2026-11-08','2026-11-08','Absent',1,'2026-11-17 11:02:26'),(33,1,3,2,'2026-11-07','2026-11-07','Present',1,'2026-11-17 11:02:26'),(34,1,4,2,'2026-11-06','2026-11-06','Present',1,'2026-11-17 11:02:26'),(35,1,1,2,'2026-11-05','2026-11-05','Present',1,'2026-11-17 11:02:26'),(36,1,2,2,'2026-11-04','2026-11-04','Present',1,'2026-11-17 11:02:26'),(37,1,3,2,'2026-11-03','2026-11-03','Present',1,'2026-11-17 11:02:26'),(38,2,2,2,'2026-11-16','2026-11-16','Present',1,'2026-11-17 11:02:26'),(39,2,3,2,'2026-11-15','2026-11-15','Absent',1,'2026-11-17 11:02:26'),(40,2,4,2,'2026-11-14','2026-11-14','Present',1,'2026-11-17 11:02:26'),(41,2,1,2,'2026-11-13','2026-11-13','Present',1,'2026-11-17 11:02:26'),(42,2,2,2,'2026-11-12','2026-11-12','Present',1,'2026-11-17 11:02:26'),(43,2,3,2,'2026-11-11','2026-11-11','Present',1,'2026-11-17 11:02:26'),(44,2,4,2,'2026-11-10','2026-11-10','Present',1,'2026-11-17 11:02:26'),(45,2,1,2,'2026-11-09','2026-11-09','Absent',1,'2026-11-17 11:02:26'),(46,2,2,2,'2026-11-08','2026-11-08','Present',1,'2026-11-17 11:02:26'),(47,2,3,2,'2026-11-07','2026-11-07','Present',1,'2026-11-17 11:02:26'),(48,2,4,2,'2026-11-06','2026-11-06','Present',1,'2026-11-17 11:02:26'),(49,2,1,2,'2026-11-05','2026-11-05','Present',1,'2026-11-17 11:02:26'),(50,2,2,2,'2026-11-04','2026-11-04','Present',1,'2026-11-17 11:02:26'),(51,2,3,2,'2026-11-03','2026-11-03','Absent',1,'2026-11-17 11:02:26'),(52,4,2,3,'2026-11-16','2026-11-16','Present',1,'2026-11-17 11:02:26'),(53,4,3,3,'2026-11-15','2026-11-15','Present',1,'2026-11-17 11:02:26'),(54,4,4,3,'2026-11-14','2026-11-14','Present',1,'2026-11-17 11:02:26'),(55,4,1,3,'2026-11-13','2026-11-13','Present',1,'2026-11-17 11:02:26'),(56,4,2,3,'2026-11-12','2026-11-12','Present',1,'2026-11-17 11:02:26'),(57,4,3,3,'2026-11-11','2026-11-11','Absent',1,'2026-11-17 11:02:26'),(58,4,4,3,'2026-11-10','2026-11-10','Present',1,'2026-11-17 11:02:26'),(59,4,1,3,'2026-11-09','2026-11-09','Present',1,'2026-11-17 11:02:27'),(60,4,2,3,'2026-11-08','2026-11-08','Present',1,'2026-11-17 11:02:27'),(61,4,3,3,'2026-11-07','2026-11-07','Present',1,'2026-11-17 11:02:27'),(62,4,4,3,'2026-11-06','2026-11-06','Present',1,'2026-11-17 11:02:27'),(63,4,1,3,'2026-11-05','2026-11-05','Absent',1,'2026-11-17 11:02:27'),(64,4,2,3,'2026-11-04','2026-11-04','Present',1,'2026-11-17 11:02:27'),(65,4,3,3,'2026-11-03','2026-11-03','Present',1,'2026-11-17 11:02:27');
/*!40000 ALTER TABLE `attendance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `email` varchar(120) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `status` enum('Unread','Replied','Closed') DEFAULT 'Unread',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
INSERT INTO `contact_messages` VALUES (1,'Vikram Singhania','vikram.s@gmail.com','+91 98989 89898','Admission query for B.Tech CSE 2026','Hello Admissions Team, I would like to inquire about the direct admission criteria and lateral entry options for B.Tech Computer Science.','Replied','2026-11-14 10:43:57'),(2,'Meenakshi Iyer','meenakshi.iyer@gmail.com','+91 97777 66666','Hostel accommodation & campus visit request','Dear Administration, We are planning to visit the university campus this Friday. Kindly confirm if guided campus tours are available for prospective students.','Unread','2026-11-16 10:43:57');
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `code` varchar(30) NOT NULL,
  `degree_level` enum('Undergraduate','Postgraduate','Doctorate','Diploma') NOT NULL DEFAULT 'Undergraduate',
  `duration` varchar(50) NOT NULL DEFAULT '3 Years',
  `semester_count` int(11) NOT NULL DEFAULT 6,
  `fees_per_year` decimal(10,2) NOT NULL DEFAULT 65000.00,
  `eligibility` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `course_name` varchar(150) DEFAULT NULL,
  `course_code` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`course_id`),
  UNIQUE KEY `code` (`code`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `fk_course_dept` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (1,1,'Bachelor of Technology in Computer Science & Engineering','BTECH-CSE','Undergraduate','4 Years',8,120000.00,'10+2 with PCM >= 60%','Rigorous curriculum covering data structures, OS, databases, AI, machine learning, and cloud computing.','2026-11-17 10:43:57','Bachelor of Technology in Computer Science & Engineering','BTECH-CSE'),(2,1,'Bachelor of Computer Applications','BCA','Undergraduate','3 Years',6,75000.00,'10+2 with Mathematics/CS >= 50%','Comprehensive software development, web engineering, PHP, Python, and mobile application engineering.','2026-11-17 10:43:57','Bachelor of Computer Applications','BCA'),(3,2,'Bachelor of Business Administration','BBA','Undergraduate','3 Years',6,85000.00,'10+2 any stream >= 50%','Focus on strategic management, marketing, financial accounting, and human resource development.','2026-11-17 10:43:57','Bachelor of Business Administration','BBA'),(4,2,'Master of Business Administration','MBA','Postgraduate','2 Years',4,150000.00,'Graduation >= 55% + Entrance','Specializations in FinTech, Global Supply Chain, Digital Marketing, and Business Analytics.','2026-11-17 10:43:57','Master of Business Administration','MBA'),(5,3,'Master of Computer Applications','MCA','Postgraduate','2 Years',4,95000.00,'BCA / B.Sc. IT >= 55%','Advanced enterprise computing, microservices, cloud native architecture, and cybersecurity.','2026-11-17 10:43:57','Master of Computer Applications','MCA'),(6,4,'B.Tech in Mechanical Engineering & Automation','BTECH-MECH','Undergraduate','4 Years',8,110000.00,'10+2 with PCM >= 60%','Core mechanical design, electric vehicles, industrial IoT, and additive manufacturing.','2026-11-17 10:43:57','B.Tech in Mechanical Engineering & Automation','BTECH-MECH');
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `code` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  `head_of_dept` varchar(120) DEFAULT NULL,
  `icon` varchar(50) DEFAULT 'fa-laptop-code',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`department_id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'School of Computer Science & Engineering','CSE','Pioneering cutting-edge computer systems, software engineering, AI, and cybersecurity.','Dr. Rajesh Sharma','fa-laptop-code','2026-11-17 10:43:57'),(2,'Department of Business Administration & Management','BBA','Fostering future corporate leaders, entrepreneurs, and global management thinkers.','Dr. Sunita Deshmukh','fa-briefcase','2026-11-17 10:43:57'),(3,'School of Information Technology & Data Science','IT','Specializing in cloud computing, big data analytics, and internet systems.','Prof. Ananya Verma','fa-database','2026-11-17 10:43:57'),(4,'Department of Mechanical & Robotics Engineering','MECH','Advanced thermodynamics, robotics, CAD/CAM, and automated manufacturing.','Dr. Vikram Malhotra','fa-gears','2026-11-17 10:43:57'),(5,'Department of Humanities, Arts & Journalism','HAJ','Cultivating creative expressions, media ethics, communication, and social studies.','Prof. Neha Sen','fa-feather-pointed','2026-11-17 10:43:57');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `event_date` date NOT NULL,
  `event_time` varchar(50) DEFAULT '10:00 AM - 04:00 PM',
  `location` varchar(150) NOT NULL DEFAULT 'Main University Auditorium',
  `image` varchar(255) DEFAULT 'event_default.jpg',
  `organizer` varchar(120) DEFAULT 'Student Council',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,'InnovateTech Hackathon 2026','A 36-hour non-stop coding hackathon where students build AI solutions for healthcare, education, and smart cities.','2026-11-29','09:00 AM - 09:00 PM','Central Computing Center','event_hackathon.jpg','Google Developer Student Club','2026-11-17 10:43:57'),(2,'Convocation Ceremony Class of 2025','Celebrating the graduation and conferment of degrees upon undergraduate and postgraduate scholars.','2026-12-12','10:00 AM - 02:00 PM','Grand University Convocation Hall','event_convocation.jpg','Office of the Vice-Chancellor','2026-11-17 10:43:57'),(3,'Global Corporate Leadership Summit','Keynote addresses by Fortune 500 CEOs and leaders discussing emerging career trends in FinTech and Cloud Computing.','2026-12-27','11:00 AM - 05:00 PM','Seminar Hall Block B','event_summit.jpg','Corporate Relations Cell','2026-11-17 10:43:57');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faculty`
--

DROP TABLE IF EXISTS `faculty`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faculty` (
  `faculty_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `department` varchar(100) DEFAULT 'Computer Science',
  `designation` varchar(100) NOT NULL DEFAULT 'Assistant Professor',
  `qualification` varchar(150) NOT NULL DEFAULT 'Ph.D. / M.Tech',
  `specialization` varchar(200) DEFAULT 'Computer Networks, AI',
  `office` varchar(100) DEFAULT 'Block A, Room 304',
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`faculty_id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `user_id_2` (`user_id`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `fk_fac_dept` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE SET NULL,
  CONSTRAINT `fk_fac_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faculty`
--

LOCK TABLES `faculty` WRITE;
/*!40000 ALTER TABLE `faculty` DISABLE KEYS */;
INSERT INTO `faculty` VALUES (1,2,1,'Computer Science','Professor & Dean Academics','Ph.D. in Computer Science (IIT Delhi)','Relational Databases, Distributed Systems & AI','Academic Block A, Suite 401',NULL,'2026-11-17 10:43:57'),(2,3,3,'Computer Science','Associate Professor','Ph.D. in Information Systems','Cloud Computing, Web Services & Big Data','IT Tower, Room 210',NULL,'2026-11-17 10:43:57'),(3,4,2,'Computer Science','Professor & HOD','Ph.D. in Management (IIM Ahmedabad)','Strategic Leadership & Corporate Governance','Management Wing, Room 102',NULL,'2026-11-17 10:43:57');
/*!40000 ALTER TABLE `faculty` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fees`
--

DROP TABLE IF EXISTS `fees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fees` (
  `fee_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `fee_type` varchar(100) DEFAULT 'Tuition & Academic Fee',
  `total_amount` decimal(10,2) NOT NULL DEFAULT 45000.00,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT 45000.00,
  `due_date` date NOT NULL,
  `status` enum('Paid','Pending','Overdue') NOT NULL DEFAULT 'Paid',
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  PRIMARY KEY (`fee_id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `fk_fee_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fees`
--

LOCK TABLES `fees` WRITE;
/*!40000 ALTER TABLE `fees` DISABLE KEYS */;
INSERT INTO `fees` VALUES (1,1,3,'Semester 3 Academic Tuition & Lab Fee',37500.00,37500.00,'2025-08-15','Paid','TXN-EDU-9812401','2025-08-10'),(2,1,4,'Semester 4 Academic Tuition & Lab Fee',37500.00,37500.00,'2026-02-15','Paid','TXN-EDU-9819832','2026-02-08'),(3,2,4,'Semester 4 Academic Tuition & Lab Fee',37500.00,37500.00,'2026-02-15','Paid','TXN-EDU-9819955','2026-02-12'),(4,3,4,'Semester 4 Engineering Tuition Fee',60000.00,30000.00,'2026-03-31','Pending','TXN-EDU-9820101','2026-02-14');
/*!40000 ALTER TABLE `fees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery`
--

DROP TABLE IF EXISTS `gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery` (
  `gallery_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `category` enum('Campus','Events','Labs','Sports','Library','Auditorium') DEFAULT 'Campus',
  `image_url` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`gallery_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery`
--

LOCK TABLES `gallery` WRITE;
/*!40000 ALTER TABLE `gallery` DISABLE KEYS */;
INSERT INTO `gallery` VALUES (1,'State-of-the-art Central University Library','Library','https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=1200&q=80','Holding over 150,000 volumes, international e-journals, and silent research pods.','2026-11-17 10:43:57'),(2,'Advanced Computing & AI Laboratory','Labs','https://images.unsplash.com/photo-1581092921461-eab62e97a780?auto=format&fit=crop&w=1200&q=80','Equipped with NVIDIA GPU workstations for deep learning and high-performance computing.','2026-11-17 10:43:57'),(3,'Campus Aerial View & Iconic Heritage Clock Tower','Campus','https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=1200&q=80','Spread across 120 lush green acres with smart academic complexes and sports stadiums.','2026-11-17 10:43:57'),(4,'Grand University Auditorium','Auditorium','https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1200&q=80','Acoustically treated auditorium with 2,500 seating capacity for international summits.','2026-11-17 10:43:57'),(5,'Olympic-Standard Sports Complex','Sports','https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1200&q=80','Indoor badminton courts, synthetic athletic track, and Olympic-size swimming pool.','2026-11-17 10:43:57');
/*!40000 ALTER TABLE `gallery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grades`
--

DROP TABLE IF EXISTS `grades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grades` (
  `grade_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `marks` int(11) NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `graded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`grade_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grades`
--

LOCK TABLES `grades` WRITE;
/*!40000 ALTER TABLE `grades` DISABLE KEYS */;
INSERT INTO `grades` VALUES (1,1,1,1,92,'Outstanding work! Clean code and detailed analysis.','2026-11-17 11:02:26'),(2,1,2,1,85,'Very good effort. Edge cases handled well.','2026-11-17 11:02:26'),(3,1,3,1,78,'Good attempt. Code readability could be improved.','2026-11-17 11:02:26'),(4,2,1,1,95,'Exemplary submission! Full marks awarded.','2026-11-17 11:02:26'),(5,2,2,1,88,'Well-documented solution with excellent test coverage.','2026-11-17 11:02:26'),(6,2,3,1,92,'Outstanding work! Clean code and detailed analysis.','2026-11-17 11:02:26'),(7,3,1,1,85,'Very good effort. Edge cases handled well.','2026-11-17 11:02:26'),(8,3,2,1,78,'Good attempt. Code readability could be improved.','2026-11-17 11:02:26'),(9,3,3,1,95,'Exemplary submission! Full marks awarded.','2026-11-17 11:02:26'),(10,4,1,1,88,'Well-documented solution with excellent test coverage.','2026-11-17 11:02:26'),(11,4,2,1,92,'Outstanding work! Clean code and detailed analysis.','2026-11-17 11:02:26'),(12,4,3,1,85,'Very good effort. Edge cases handled well.','2026-11-17 11:02:26');
/*!40000 ALTER TABLE `grades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notices`
--

DROP TABLE IF EXISTS `notices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notices` (
  `notice_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `description` text DEFAULT NULL,
  `category` enum('Academic','Examination','Admission','Sports','General') DEFAULT 'General',
  `target_role` enum('all','students','faculty') DEFAULT 'all',
  `posted_by` varchar(120) DEFAULT 'Dean Office',
  `posted_on` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`notice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notices`
--

LOCK TABLES `notices` WRITE;
/*!40000 ALTER TABLE `notices` DISABLE KEYS */;
INSERT INTO `notices` VALUES (1,'Admissions Open for Academic Session 2026–2027','Applications are invited for Undergraduate & Postgraduate programs in Engineering, Management, and Computing. Apply online through the college portal.','Applications are invited for Undergraduate & Postgraduate programs in Engineering, Management, and Computing. Apply online through the college portal.','Admission','all','Office of Registrar','2026-11-17 10:43:57'),(2,'Mid-Semester Examination Datesheet Released','The theory and practical examinations for Even Semesters (Sem 2, 4, 6) will commence from the 10th of next month. Detailed timetable is posted on the notice board.','The theory and practical examinations for Even Semesters (Sem 2, 4, 6) will commence from the 10th of next month. Detailed timetable is posted on the notice board.','Examination','students','Controller of Examinations','2026-11-15 10:43:57'),(3,'Annual International Conference on AI & Sustainable Tech','Join global researchers, industry pioneers, and academicians for keynote sessions, paper presentations, and tech demonstrations in Auditorium 1.','Join global researchers, industry pioneers, and academicians for keynote sessions, paper presentations, and tech demonstrations in Auditorium 1.','Academic','all','Research & Innovation Cell','2026-11-13 10:43:57'),(4,'Inter-University Sports Meet & Cultural Gala 2026','Registrations are open for Football, Cricket, Badminton, Classical Music, and Hackathon events. Certificates and cash prizes will be awarded.','Registrations are open for Football, Cricket, Badminton, Classical Music, and Hackathon events. Certificates and cash prizes will be awarded.','Sports','students','Dean Student Welfare','2026-11-11 10:43:57');
/*!40000 ALTER TABLE `notices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `placements`
--

DROP TABLE IF EXISTS `placements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `placements` (
  `placement_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_name` varchar(120) NOT NULL,
  `roll_no` varchar(50) NOT NULL,
  `company_name` varchar(120) NOT NULL,
  `package_lpa` decimal(5,2) NOT NULL,
  `role` varchar(100) NOT NULL,
  `batch_year` int(11) NOT NULL DEFAULT 2024,
  `company_logo` varchar(255) DEFAULT 'default_company.png',
  `student_image` varchar(255) DEFAULT 'default_student.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`placement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `placements`
--

LOCK TABLES `placements` WRITE;
/*!40000 ALTER TABLE `placements` DISABLE KEYS */;
INSERT INTO `placements` VALUES (1,'Ayush Varma','22BCS10012','Microsoft',44.50,'Software Development Engineer',2025,'microsoft.png','default.png','2026-11-17 10:43:57'),(2,'Simran Kaur','22BCS10045','Amazon Web Services',38.00,'Cloud Solutions Architect',2025,'amazon.png','default.png','2026-11-17 10:43:57'),(3,'Devansh Joshi','22BBA10018','Deloitte',18.50,'Management Consultant',2025,'deloitte.png','default.png','2026-11-17 10:43:57'),(4,'Rhea Mukherjee','22BCS10089','Google Cloud',42.00,'Frontend Systems Engineer',2025,'google.png','default.png','2026-11-17 10:43:57'),(5,'Aditya Choudhury','22BCS10102','Oracle',24.00,'Database Systems Engineer',2025,'oracle.png','default.png','2026-11-17 10:43:57');
/*!40000 ALTER TABLE `placements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `results` (
  `result_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `subject_name` varchar(150) NOT NULL,
  `exam_type` varchar(50) DEFAULT 'End Semester Examination',
  `total_marks` int(11) NOT NULL DEFAULT 100,
  `marks_obtained` int(11) NOT NULL,
  `grade` varchar(10) NOT NULL DEFAULT 'A',
  `status` enum('Pass','Fail') DEFAULT 'Pass',
  `published_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`result_id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `fk_res_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `results`
--

LOCK TABLES `results` WRITE;
/*!40000 ALTER TABLE `results` DISABLE KEYS */;
INSERT INTO `results` VALUES (1,1,2,3,'Database Management Systems','End Semester Examination',100,92,'A+','Pass','2026-11-17 10:43:57'),(2,1,2,3,'Object Oriented Programming with Java','End Semester Examination',100,88,'A','Pass','2026-11-17 10:43:57'),(3,1,2,3,'Computer System Architecture','End Semester Examination',100,85,'A','Pass','2026-11-17 10:43:57'),(4,1,2,3,'Web Development Technologies','End Semester Examination',100,95,'O','Pass','2026-11-17 10:43:57'),(5,2,2,3,'Database Management Systems','End Semester Examination',100,90,'A+','Pass','2026-11-17 10:43:57');
/*!40000 ALTER TABLE `results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `student_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `roll_no` varchar(50) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `course` varchar(100) DEFAULT 'BCA',
  `semester` int(11) NOT NULL DEFAULT 1,
  `admission_year` int(11) NOT NULL DEFAULT 2024,
  `gender` enum('Male','Female','Other') DEFAULT 'Male',
  `dob` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('Active','Graduated','Suspended') DEFAULT 'Active',
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `roll_no` (`roll_no`),
  KEY `user_id_2` (`user_id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `fk_stu_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE SET NULL,
  CONSTRAINT `fk_stu_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (1,5,'24BCS12641',1,2,'BCA',4,2024,'Male','2004-05-15','Flat 402, Royal Palms Enclave, Tech Park Road','Active',NULL,'2026-11-17 10:43:57'),(2,6,'24BCS12642',1,2,'BCA',4,2024,'Female','2004-08-20','House 12B, Green Valley Residency, University Campus','Active',NULL,'2026-11-17 10:43:57'),(3,7,'24BCS12643',1,1,'BCA',4,2024,'Male','2003-11-10','Plot 88, Sunrise Apartments, Sector 14','Active',NULL,'2026-11-17 10:43:57'),(4,8,'24BCS12644',2,3,'BCA',2,2025,'Female','2005-02-28','Villa 5, Golden Orchid Heights','Active',NULL,'2026-11-17 10:43:57');
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `study_materials`
--

DROP TABLE IF EXISTS `study_materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `study_materials` (
  `material_id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`material_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `study_materials`
--

LOCK TABLES `study_materials` WRITE;
/*!40000 ALTER TABLE `study_materials` DISABLE KEYS */;
INSERT INTO `study_materials` VALUES (1,1,1,'DSA Unit 1 - Trees, Heaps & Graphs','Comprehensive lecture presentation and complexity cheat sheet covering balanced BSTs and Dijkstra algorithm.','dsa_unit1_slides.pdf','2026-11-17 11:02:26'),(2,2,1,'DBMS - Normalization & SQL Queries','In-depth guide on 1NF to BCNF normalization with 50 practice SQL query sets and execution plans.','dbms_normalization.pdf','2026-11-17 11:02:26'),(3,3,2,'Full-Stack Modern Web Architectures','Interactive course slides on MVC architecture, RESTful API design, and asynchronous JS patterns.','web_arch_guide.pdf','2026-11-17 11:02:26'),(4,4,2,'Operating Systems - Concurrency & Deadlocks','Semaphore implementations, mutex locks, Bankers algorithm walkthrough, and Linux process states.','os_concurrency.pdf','2026-11-17 11:02:26'),(5,5,3,'AI & Machine Learning Foundations','Supervised learning regressions, neural networks backpropagation derivation, and scikit-learn notebooks.','ai_ml_foundations.pdf','2026-11-17 11:02:26');
/*!40000 ALTER TABLE `study_materials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(150) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `course` varchar(100) DEFAULT 'B.Tech CSE',
  `semester` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subjects`
--

LOCK TABLES `subjects` WRITE;
/*!40000 ALTER TABLE `subjects` DISABLE KEYS */;
INSERT INTO `subjects` VALUES (1,'Data Structures & Algorithms','CS301',1,'B.Tech CSE',3,'2026-11-17 11:02:26'),(2,'Database Management Systems','CS302',1,'B.Tech CSE',3,'2026-11-17 11:02:26'),(3,'Web Technologies & Frameworks','CS401',2,'B.Tech CSE',4,'2026-11-17 11:02:26'),(4,'Operating Systems & Architecture','CS402',2,'B.Tech CSE',4,'2026-11-17 11:02:26'),(5,'Artificial Intelligence & ML','CS501',3,'B.Tech CSE',5,'2026-11-17 11:02:26'),(6,'Software Engineering & Agile','CS502',3,'B.Tech CSE',5,'2026-11-17 11:02:26'),(7,'Computer Networks & Security','CS601',1,'B.Tech CSE',6,'2026-11-17 11:02:26'),(8,'Cloud Computing & DevOps','CS602',2,'B.Tech CSE',6,'2026-11-17 11:02:26');
/*!40000 ALTER TABLE `subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `submissions`
--

DROP TABLE IF EXISTS `submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `submissions` (
  `submission_id` int(11) NOT NULL AUTO_INCREMENT,
  `assignment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `marks` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` enum('Submitted','Graded','Late') DEFAULT 'Submitted',
  PRIMARY KEY (`submission_id`),
  KEY `assignment_id` (`assignment_id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `fk_sub_asgn` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`assignment_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_sub_stu` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `submissions`
--

LOCK TABLES `submissions` WRITE;
/*!40000 ALTER TABLE `submissions` DISABLE KEYS */;
INSERT INTO `submissions` VALUES (1,1,1,'sunny_sql_joins_solution.pdf','2026-11-15 10:43:57',94,'Superb ER diagram design and flawless normalization proof!','Superb ER diagram design and flawless normalization proof!','Graded'),(2,1,2,'priya_sql_solution.pdf','2026-11-16 10:43:57',92,'Clean script formatting and comprehensive query explanation.','Clean script formatting and comprehensive query explanation.','Graded');
/*!40000 ALTER TABLE `submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timetable`
--

DROP TABLE IF EXISTS `timetable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timetable` (
  `timetable_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `semester` int(11) NOT NULL DEFAULT 1,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') NOT NULL,
  `time_slot` varchar(50) NOT NULL,
  `subject_name` varchar(120) NOT NULL,
  `room_no` varchar(50) NOT NULL,
  `faculty_name` varchar(120) NOT NULL,
  PRIMARY KEY (`timetable_id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `fk_time_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timetable`
--

LOCK TABLES `timetable` WRITE;
/*!40000 ALTER TABLE `timetable` DISABLE KEYS */;
INSERT INTO `timetable` VALUES (1,2,4,'Monday','09:00 AM - 10:00 AM','Database Systems (CS401)','Lecture Hall 101','Dr. Rajesh Sharma'),(2,2,4,'Monday','10:00 AM - 11:00 AM','Web Engineering (CS402)','Computer Lab 3','Prof. Ananya Verma'),(3,2,4,'Monday','11:15 AM - 12:15 PM','Software Engineering (CS403)','Lecture Hall 101','Dr. Rajesh Sharma'),(4,2,4,'Tuesday','09:00 AM - 10:00 AM','Operating Systems (CS404)','Lecture Hall 102','Prof. Ananya Verma'),(5,2,4,'Tuesday','10:00 AM - 12:00 PM','Web Engineering Practical Lab','Software Lab 2','Dr. Rajesh Sharma'),(6,2,4,'Wednesday','09:00 AM - 10:00 AM','Database Systems (CS401)','Lecture Hall 101','Dr. Rajesh Sharma'),(7,2,4,'Thursday','10:00 AM - 11:00 AM','Computer Networks (CS405)','Lecture Hall 103','Prof. Ananya Verma'),(8,2,4,'Friday','02:00 PM - 04:00 PM','Project Work & Seminar','Seminar Hall 1','Faculty Panel');
/*!40000 ALTER TABLE `timetable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','faculty','student') NOT NULL DEFAULT 'student',
  `profile_picture` varchar(255) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'System Administrator','admin@gmail.com','admin123','admin','default.png','+91 98765 43210','2026-11-17 10:43:57'),(2,'Dr. Rajesh Sharma','faculty@gmail.com','faculty123','faculty','default.png','+91 98111 22334','2026-11-17 10:43:57'),(3,'Prof. Ananya Verma','ananya.verma@college.edu','faculty123','faculty','default.png','+91 98222 33445','2026-11-17 10:43:57'),(4,'Dr. Sunita Deshmukh','sunita.deshmukh@college.edu','faculty123','faculty','default.png','+91 98333 44556','2026-11-17 10:43:57'),(5,'Sunny Kumar','student@gmail.com','student123','student','default.png','+91 91234 56789','2026-11-17 10:43:57'),(6,'Priya Singh','priya.singh@student.edu','student123','student','default.png','+91 92345 67890','2026-11-17 10:43:57'),(7,'Rahul Patel','rahul.patel@student.edu','student123','student','default.png','+91 93456 78901','2026-11-17 10:43:57'),(8,'Sneha Kapoor','sneha.kapoor@student.edu','student123','student','default.png','+91 94567 89012','2026-11-17 10:43:57');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-17 16:38:41
