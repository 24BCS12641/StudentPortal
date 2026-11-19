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
INSERT INTO `assignments` VALUES 
(1,1,1,1,'Enterprise Relational Schema & 3NF Normalization','Design a scalable university database schema, eliminate transitive dependencies, and write complex JOIN queries.','2026-11-28',100,'sql_assignment.pdf','2026-11-17 10:43:57'),
(2,3,2,2,'PHP Full-Stack Session Authentication Module','Implement session-based login, role access guards, prepared statements, and user registration.','2026-11-30',100,'php_crud_brief.pdf','2026-11-17 10:43:57'),
(3,5,1,9,'Distributed Neural Network Training in PyTorch','Train a ResNet-50 image classifier with distributed data parallel and evaluate precision-recall curves.','2026-12-05',100,'dl_pytorch.pdf','2026-11-17 10:43:57'),
(4,9,3,3,'Corporate Valuation & Discounted Cash Flow Analysis','Calculate WACC and DCF valuation model for a Fortune 500 company using 5-year historical balance sheets.','2026-12-02',100,'corporate_valuation.pdf','2026-11-17 10:43:57'),
(5,13,6,4,'EV Battery Thermal Management Simulation','Perform finite element thermal analysis of a 400V lithium-ion battery pack under fast-charging conditions.','2026-12-08',100,'ev_thermal.pdf','2026-11-17 10:43:57'),
(6,16,8,6,'FPGA Implementation of FFT Algorithm','Design a 64-point Radix-2 FFT processor in Verilog and simulate using ModelSim testbench.','2026-12-04',100,'fft_fpga.pdf','2026-11-17 10:43:57'),
(7,19,11,9,'Transformer Architecture from Scratch','Implement Multi-Head Self Attention and Positional Encodings in Python without high-level wrappers.','2026-12-10',100,'transformer_lab.pdf','2026-11-17 10:43:57'),
(8,20,12,10,'Malware Memory Forensics with Volatility 3','Analyze a memory dump from an infected server to isolate injected DLLs, C2 beacons, and hook points.','2026-12-07',100,'forensics_lab.pdf','2026-11-17 10:43:57'),
(9,21,13,11,'Aerodynamic Airfoil Lift & Drag CFD Study','Simulate NACA 0012 airfoil at subsonic and transonic speeds using OpenFOAM and compare pressure contours.','2026-12-12',100,'cfd_airfoil.pdf','2026-11-17 10:43:57'),
(10,26,18,16,'Moot Court Memorial: Fundamental Rights in Digital Age','Draft petitioner and respondent memorials on whether algorithmic facial recognition infringes Article 21.','2026-12-15',100,'moot_court_brief.pdf','2026-11-17 10:43:57'),
(11,27,19,17,'HPLC Chromatographic Assay of Paracetamol','Formulate calibration curves and quantify active pharmaceutical ingredient purity using reverse-phase HPLC.','2026-12-06',100,'hplc_protocol.pdf','2026-11-17 10:43:57'),
(12,28,20,18,'Biophilic Net-Zero Campus Pavilion Design','Submit architectural CAD sheets, daylight factor simulation, and BIM structural schedule for a 500 sqm pavilion.','2026-12-18',100,'bim_pavilion.pdf','2026-11-17 10:43:57');
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
INSERT INTO `attendance` VALUES 
(1,1,1,2,'2026-11-10','2026-11-10','Present',2,'2026-11-17 10:43:57'),
(2,1,1,2,'2026-11-11','2026-11-11','Present',2,'2026-11-17 10:43:57'),
(3,1,1,2,'2026-11-12','2026-11-12','Present',2,'2026-11-17 10:43:57'),
(4,1,1,2,'2026-11-13','2026-11-13','Late',2,'2026-11-17 10:43:57'),
(5,1,1,2,'2026-11-14','2026-11-14','Present',2,'2026-11-17 10:43:57'),
(6,1,1,2,'2026-11-17','2026-11-17','Absent',2,'2026-11-17 10:43:57'),
(7,1,1,2,'2026-11-18','2026-11-18','Present',2,'2026-11-17 10:43:57'),
(8,2,1,2,'2026-11-10','2026-11-10','Present',2,'2026-11-17 10:43:57'),
(9,2,1,2,'2026-11-11','2026-11-11','Present',2,'2026-11-17 10:43:57'),
(10,2,1,2,'2026-11-12','2026-11-12','Late',2,'2026-11-17 10:43:57'),
(11,2,1,2,'2026-11-13','2026-11-13','Present',2,'2026-11-17 10:43:57'),
(12,2,1,2,'2026-11-14','2026-11-14','Absent',2,'2026-11-17 10:43:57'),
(13,2,1,2,'2026-11-17','2026-11-17','Present',2,'2026-11-17 10:43:57'),
(14,2,1,2,'2026-11-18','2026-11-18','Present',2,'2026-11-17 10:43:57'),
(15,3,1,2,'2026-11-10','2026-11-10','Present',2,'2026-11-17 10:43:57'),
(16,3,1,2,'2026-11-11','2026-11-11','Late',2,'2026-11-17 10:43:57'),
(17,3,1,2,'2026-11-12','2026-11-12','Present',2,'2026-11-17 10:43:57'),
(18,3,1,2,'2026-11-13','2026-11-13','Absent',2,'2026-11-17 10:43:57'),
(19,3,1,2,'2026-11-14','2026-11-14','Present',2,'2026-11-17 10:43:57'),
(20,3,1,2,'2026-11-17','2026-11-17','Present',2,'2026-11-17 10:43:57'),
(21,3,1,2,'2026-11-18','2026-11-18','Present',2,'2026-11-17 10:43:57'),
(22,4,1,2,'2026-11-10','2026-11-10','Late',2,'2026-11-17 10:43:57'),
(23,4,1,2,'2026-11-11','2026-11-11','Present',2,'2026-11-17 10:43:57'),
(24,4,1,2,'2026-11-12','2026-11-12','Absent',2,'2026-11-17 10:43:57'),
(25,4,1,2,'2026-11-13','2026-11-13','Present',2,'2026-11-17 10:43:57'),
(26,4,1,2,'2026-11-14','2026-11-14','Present',2,'2026-11-17 10:43:57'),
(27,4,1,2,'2026-11-17','2026-11-17','Present',2,'2026-11-17 10:43:57'),
(28,4,1,2,'2026-11-18','2026-11-18','Present',2,'2026-11-17 10:43:57'),
(29,5,1,2,'2026-11-10','2026-11-10','Present',2,'2026-11-17 10:43:57'),
(30,5,1,2,'2026-11-11','2026-11-11','Absent',2,'2026-11-17 10:43:57'),
(31,5,1,2,'2026-11-12','2026-11-12','Present',2,'2026-11-17 10:43:57'),
(32,5,1,2,'2026-11-13','2026-11-13','Present',2,'2026-11-17 10:43:57'),
(33,5,1,2,'2026-11-14','2026-11-14','Present',2,'2026-11-17 10:43:57'),
(34,5,1,2,'2026-11-17','2026-11-17','Present',2,'2026-11-17 10:43:57'),
(35,5,1,2,'2026-11-18','2026-11-18','Late',2,'2026-11-17 10:43:57'),
(36,6,1,2,'2026-11-10','2026-11-10','Absent',2,'2026-11-17 10:43:57'),
(37,6,1,2,'2026-11-11','2026-11-11','Present',2,'2026-11-17 10:43:57'),
(38,6,1,2,'2026-11-12','2026-11-12','Present',2,'2026-11-17 10:43:57'),
(39,6,1,2,'2026-11-13','2026-11-13','Present',2,'2026-11-17 10:43:57'),
(40,6,1,2,'2026-11-14','2026-11-14','Present',2,'2026-11-17 10:43:57'),
(41,6,1,2,'2026-11-17','2026-11-17','Late',2,'2026-11-17 10:43:57'),
(42,6,1,2,'2026-11-18','2026-11-18','Present',2,'2026-11-17 10:43:57'),
(43,7,1,2,'2026-11-10','2026-11-10','Present',2,'2026-11-17 10:43:57'),
(44,7,1,2,'2026-11-11','2026-11-11','Present',2,'2026-11-17 10:43:57'),
(45,7,1,2,'2026-11-12','2026-11-12','Present',2,'2026-11-17 10:43:57'),
(46,7,1,2,'2026-11-13','2026-11-13','Present',2,'2026-11-17 10:43:57'),
(47,7,1,2,'2026-11-14','2026-11-14','Late',2,'2026-11-17 10:43:57'),
(48,7,1,2,'2026-11-17','2026-11-17','Present',2,'2026-11-17 10:43:57'),
(49,7,1,2,'2026-11-18','2026-11-18','Absent',2,'2026-11-17 10:43:57'),
(50,8,1,2,'2026-11-10','2026-11-10','Present',2,'2026-11-17 10:43:57'),
(51,8,1,2,'2026-11-11','2026-11-11','Present',2,'2026-11-17 10:43:57'),
(52,8,1,2,'2026-11-12','2026-11-12','Present',2,'2026-11-17 10:43:57'),
(53,8,1,2,'2026-11-13','2026-11-13','Late',2,'2026-11-17 10:43:57'),
(54,8,1,2,'2026-11-14','2026-11-14','Present',2,'2026-11-17 10:43:57'),
(55,8,1,2,'2026-11-17','2026-11-17','Absent',2,'2026-11-17 10:43:57'),
(56,8,1,2,'2026-11-18','2026-11-18','Present',2,'2026-11-17 10:43:57'),
(57,9,1,2,'2026-11-10','2026-11-10','Present',2,'2026-11-17 10:43:57'),
(58,9,1,2,'2026-11-11','2026-11-11','Present',2,'2026-11-17 10:43:57'),
(59,9,1,2,'2026-11-12','2026-11-12','Late',2,'2026-11-17 10:43:57'),
(60,9,1,2,'2026-11-13','2026-11-13','Present',2,'2026-11-17 10:43:57'),
(61,9,1,2,'2026-11-14','2026-11-14','Absent',2,'2026-11-17 10:43:57'),
(62,9,1,2,'2026-11-17','2026-11-17','Present',2,'2026-11-17 10:43:57'),
(63,9,1,2,'2026-11-18','2026-11-18','Present',2,'2026-11-17 10:43:57'),
(64,10,1,2,'2026-11-10','2026-11-10','Present',2,'2026-11-17 10:43:57'),
(65,10,1,2,'2026-11-11','2026-11-11','Late',2,'2026-11-17 10:43:57'),
(66,10,1,2,'2026-11-12','2026-11-12','Present',2,'2026-11-17 10:43:57'),
(67,10,1,2,'2026-11-13','2026-11-13','Absent',2,'2026-11-17 10:43:57'),
(68,10,1,2,'2026-11-14','2026-11-14','Present',2,'2026-11-17 10:43:57'),
(69,10,1,2,'2026-11-17','2026-11-17','Present',2,'2026-11-17 10:43:57'),
(70,10,1,2,'2026-11-18','2026-11-18','Present',2,'2026-11-17 10:43:57'),
(71,11,1,2,'2026-11-10','2026-11-10','Late',2,'2026-11-17 10:43:57'),
(72,11,1,2,'2026-11-11','2026-11-11','Present',2,'2026-11-17 10:43:57'),
(73,11,1,2,'2026-11-12','2026-11-12','Absent',2,'2026-11-17 10:43:57'),
(74,11,1,2,'2026-11-13','2026-11-13','Present',2,'2026-11-17 10:43:57'),
(75,11,1,2,'2026-11-14','2026-11-14','Present',2,'2026-11-17 10:43:57'),
(76,11,1,2,'2026-11-17','2026-11-17','Present',2,'2026-11-17 10:43:57'),
(77,11,1,2,'2026-11-18','2026-11-18','Present',2,'2026-11-17 10:43:57'),
(78,12,1,2,'2026-11-10','2026-11-10','Present',2,'2026-11-17 10:43:57'),
(79,12,1,2,'2026-11-11','2026-11-11','Absent',2,'2026-11-17 10:43:57'),
(80,12,1,2,'2026-11-12','2026-11-12','Present',2,'2026-11-17 10:43:57'),
(81,12,1,2,'2026-11-13','2026-11-13','Present',2,'2026-11-17 10:43:57'),
(82,12,1,2,'2026-11-14','2026-11-14','Present',2,'2026-11-17 10:43:57'),
(83,12,1,2,'2026-11-17','2026-11-17','Present',2,'2026-11-17 10:43:57'),
(84,12,1,2,'2026-11-18','2026-11-18','Late',2,'2026-11-17 10:43:57'),
(85,13,1,2,'2026-11-10','2026-11-10','Absent',2,'2026-11-17 10:43:57'),
(86,13,1,2,'2026-11-11','2026-11-11','Present',2,'2026-11-17 10:43:57'),
(87,13,1,2,'2026-11-12','2026-11-12','Present',2,'2026-11-17 10:43:57'),
(88,13,1,2,'2026-11-13','2026-11-13','Present',2,'2026-11-17 10:43:57'),
(89,13,1,2,'2026-11-14','2026-11-14','Present',2,'2026-11-17 10:43:57'),
(90,13,1,2,'2026-11-17','2026-11-17','Late',2,'2026-11-17 10:43:57'),
(91,13,1,2,'2026-11-18','2026-11-18','Present',2,'2026-11-17 10:43:57'),
(92,14,1,2,'2026-11-10','2026-11-10','Present',2,'2026-11-17 10:43:57'),
(93,14,1,2,'2026-11-11','2026-11-11','Present',2,'2026-11-17 10:43:57'),
(94,14,1,2,'2026-11-12','2026-11-12','Present',2,'2026-11-17 10:43:57'),
(95,14,1,2,'2026-11-13','2026-11-13','Present',2,'2026-11-17 10:43:57'),
(96,14,1,2,'2026-11-14','2026-11-14','Late',2,'2026-11-17 10:43:57'),
(97,14,1,2,'2026-11-17','2026-11-17','Present',2,'2026-11-17 10:43:57'),
(98,14,1,2,'2026-11-18','2026-11-18','Absent',2,'2026-11-17 10:43:57'),
(99,15,1,2,'2026-11-10','2026-11-10','Present',2,'2026-11-17 10:43:57'),
(100,15,1,2,'2026-11-11','2026-11-11','Present',2,'2026-11-17 10:43:57'),
(101,15,1,2,'2026-11-12','2026-11-12','Present',2,'2026-11-17 10:43:57'),
(102,15,1,2,'2026-11-13','2026-11-13','Late',2,'2026-11-17 10:43:57'),
(103,15,1,2,'2026-11-14','2026-11-14','Present',2,'2026-11-17 10:43:57'),
(104,15,1,2,'2026-11-17','2026-11-17','Absent',2,'2026-11-17 10:43:57'),
(105,15,1,2,'2026-11-18','2026-11-18','Present',2,'2026-11-17 10:43:57'),
(106,16,1,2,'2026-11-10','2026-11-10','Present',2,'2026-11-17 10:43:57'),
(107,16,1,2,'2026-11-11','2026-11-11','Present',2,'2026-11-17 10:43:57'),
(108,16,1,2,'2026-11-12','2026-11-12','Late',2,'2026-11-17 10:43:57'),
(109,16,1,2,'2026-11-13','2026-11-13','Present',2,'2026-11-17 10:43:57'),
(110,16,1,2,'2026-11-14','2026-11-14','Absent',2,'2026-11-17 10:43:57'),
(111,16,1,2,'2026-11-17','2026-11-17','Present',2,'2026-11-17 10:43:57'),
(112,16,1,2,'2026-11-18','2026-11-18','Present',2,'2026-11-17 10:43:57'),
(113,17,1,2,'2026-11-10','2026-11-10','Present',2,'2026-11-17 10:43:57'),
(114,17,1,2,'2026-11-11','2026-11-11','Late',2,'2026-11-17 10:43:57'),
(115,17,1,2,'2026-11-12','2026-11-12','Present',2,'2026-11-17 10:43:57'),
(116,17,1,2,'2026-11-13','2026-11-13','Absent',2,'2026-11-17 10:43:57'),
(117,17,1,2,'2026-11-14','2026-11-14','Present',2,'2026-11-17 10:43:57'),
(118,17,1,2,'2026-11-17','2026-11-17','Present',2,'2026-11-17 10:43:57'),
(119,17,1,2,'2026-11-18','2026-11-18','Present',2,'2026-11-17 10:43:57'),
(120,18,1,2,'2026-11-10','2026-11-10','Late',2,'2026-11-17 10:43:57'),
(121,18,1,2,'2026-11-11','2026-11-11','Present',2,'2026-11-17 10:43:57'),
(122,18,1,2,'2026-11-12','2026-11-12','Absent',2,'2026-11-17 10:43:57'),
(123,18,1,2,'2026-11-13','2026-11-13','Present',2,'2026-11-17 10:43:57'),
(124,18,1,2,'2026-11-14','2026-11-14','Present',2,'2026-11-17 10:43:57'),
(125,18,1,2,'2026-11-17','2026-11-17','Present',2,'2026-11-17 10:43:57'),
(126,18,1,2,'2026-11-18','2026-11-18','Present',2,'2026-11-17 10:43:57'),
(127,19,1,2,'2026-11-10','2026-11-10','Present',2,'2026-11-17 10:43:57'),
(128,19,1,2,'2026-11-11','2026-11-11','Absent',2,'2026-11-17 10:43:57'),
(129,19,1,2,'2026-11-12','2026-11-12','Present',2,'2026-11-17 10:43:57'),
(130,19,1,2,'2026-11-13','2026-11-13','Present',2,'2026-11-17 10:43:57'),
(131,19,1,2,'2026-11-14','2026-11-14','Present',2,'2026-11-17 10:43:57'),
(132,19,1,2,'2026-11-17','2026-11-17','Present',2,'2026-11-17 10:43:57'),
(133,19,1,2,'2026-11-18','2026-11-18','Late',2,'2026-11-17 10:43:57'),
(134,20,1,2,'2026-11-10','2026-11-10','Absent',2,'2026-11-17 10:43:57'),
(135,20,1,2,'2026-11-11','2026-11-11','Present',2,'2026-11-17 10:43:57'),
(136,20,1,2,'2026-11-12','2026-11-12','Present',2,'2026-11-17 10:43:57'),
(137,20,1,2,'2026-11-13','2026-11-13','Present',2,'2026-11-17 10:43:57'),
(138,20,1,2,'2026-11-14','2026-11-14','Present',2,'2026-11-17 10:43:57'),
(139,20,1,2,'2026-11-17','2026-11-17','Late',2,'2026-11-17 10:43:57'),
(140,20,1,2,'2026-11-18','2026-11-18','Present',2,'2026-11-17 10:43:57');
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
INSERT INTO `courses` VALUES 
(1,1,'Bachelor of Technology in Computer Science & Engineering','BTECH-CSE','Undergraduate','4 Years',8,120000,'10+2 with PCM >= 60%','Rigorous curriculum covering data structures, OS, databases, AI, machine learning, and cloud computing.','2026-11-17 10:43:57','Bachelor of Technology in Computer Science & Engineering','BTECH-CSE'),
(2,1,'Bachelor of Computer Applications','BCA','Undergraduate','3 Years',6,75000,'10+2 with Mathematics/CS >= 50%','Comprehensive software development, web engineering, PHP, Python, and mobile application engineering.','2026-11-17 10:43:57','Bachelor of Computer Applications','BCA'),
(3,2,'Bachelor of Business Administration','BBA','Undergraduate','3 Years',6,85000,'10+2 any stream >= 50%','Focus on strategic management, marketing, financial accounting, and human resource development.','2026-11-17 10:43:57','Bachelor of Business Administration','BBA'),
(4,2,'Master of Business Administration','MBA','Postgraduate','2 Years',4,150000,'Graduation >= 55% + Entrance','Specializations in FinTech, Global Supply Chain, Digital Marketing, and Business Analytics.','2026-11-17 10:43:57','Master of Business Administration','MBA'),
(5,3,'Master of Computer Applications','MCA','Postgraduate','2 Years',4,95000,'BCA / B.Sc. IT >= 55%','Advanced enterprise computing, microservices, cloud native architecture, and cybersecurity.','2026-11-17 10:43:57','Master of Computer Applications','MCA'),
(6,4,'B.Tech in Mechanical Engineering & Automation','BTECH-MECH','Undergraduate','4 Years',8,110000,'10+2 with PCM >= 60%','Core mechanical design, electric vehicles, industrial IoT, and additive manufacturing.','2026-11-17 10:43:57','B.Tech in Mechanical Engineering & Automation','BTECH-MECH'),
(7,5,'Bachelor of Arts in Journalism & Liberal Arts','BA-HAJ','Undergraduate','3 Years',6,60000,'10+2 any stream >= 50%','Media ethics, investigative journalism, digital publishing, and creative literature.','2026-11-17 10:43:57','Bachelor of Arts in Journalism & Liberal Arts','BA-HAJ'),
(8,6,'B.Tech in Electronics & Communication Engineering','BTECH-ECE','Undergraduate','4 Years',8,115000,'10+2 with PCM >= 60%','Microprocessor systems, 5G wireless networks, antenna design, and embedded IoT.','2026-11-17 10:43:57','B.Tech in Electronics & Communication Engineering','BTECH-ECE'),
(9,7,'B.Tech in Electrical & Electronics Engineering','BTECH-EEE','Undergraduate','4 Years',8,110000,'10+2 with PCM >= 60%','Smart grid technologies, renewable energy converters, power electronics, and drives.','2026-11-17 10:43:57','B.Tech in Electrical & Electronics Engineering','BTECH-EEE'),
(10,8,'B.Tech in Civil & Infrastructure Engineering','BTECH-CIVIL','Undergraduate','4 Years',8,105000,'10+2 with PCM >= 60%','Structural mechanics, earthquake engineering, geotechnical surveying, and green concrete.','2026-11-17 10:43:57','B.Tech in Civil & Infrastructure Engineering','BTECH-CIVIL'),
(11,9,'B.Tech in Artificial Intelligence & Machine Learning','BTECH-AIDS','Undergraduate','4 Years',8,130000,'10+2 with PCM >= 65%','Deep learning, LLMs, computer vision, reinforcement learning, and AI ethics.','2026-11-17 10:43:57','B.Tech in Artificial Intelligence & Machine Learning','BTECH-AIDS'),
(12,10,'B.Tech in Cybersecurity & Digital Forensics','BTECH-CYBER','Undergraduate','4 Years',8,125000,'10+2 with PCM >= 60%','Offensive security, incident response, network traffic auditing, and cloud protection.','2026-11-17 10:43:57','B.Tech in Cybersecurity & Digital Forensics','BTECH-CYBER'),
(13,11,'B.Tech in Aerospace & Aeronautical Engineering','BTECH-AERO','Undergraduate','4 Years',8,135000,'10+2 with PCM >= 65%','Flight mechanics, aerospace propulsion, space vehicle dynamics, and supersonic aerodynamics.','2026-11-17 10:43:57','B.Tech in Aerospace & Aeronautical Engineering','BTECH-AERO'),
(14,12,'B.Tech in Biotechnology & Bioinformatics','BTECH-BIOTECH','Undergraduate','4 Years',8,115000,'10+2 with PCB/PCM >= 60%','Genetic engineering, recombinant DNA, immunotechnology, and bioprocess engineering.','2026-11-17 10:43:57','B.Tech in Biotechnology & Bioinformatics','BTECH-BIOTECH'),
(15,13,'B.Tech in Chemical & Petroleum Engineering','BTECH-CHE','Undergraduate','4 Years',8,110000,'10+2 with PCM >= 60%','Transport phenomena, chemical reaction kinetics, petroleum refining, and polymer synthesis.','2026-11-17 10:43:57','B.Tech in Chemical & Petroleum Engineering','BTECH-CHE'),
(16,14,'Bachelor of Commerce (Honours) in Fintech','BCOM-HONS','Undergraduate','3 Years',6,70000,'10+2 Commerce/Math >= 50%','Financial modeling, international taxation, cryptocurrency economics, and corporate auditing.','2026-11-17 10:43:57','Bachelor of Commerce (Honours) in Fintech','BCOM-HONS'),
(17,15,'Bachelor of Science in Economics & Analytics','BSC-ECON','Undergraduate','3 Years',6,75000,'10+2 with Math/Stats >= 55%','Econometric modeling, public policy analytics, financial derivatives, and game theory.','2026-11-17 10:43:57','Bachelor of Science in Economics & Analytics','BSC-ECON'),
(18,16,'Integrated Bachelor of Arts & Bachelor of Laws','BA-LLB','Undergraduate','5 Years',10,140000,'10+2 any stream >= 50% + Entrance','Constitutional jurisprudence, cyber crime law, corporate arbitration, and international law.','2026-11-17 10:43:57','Integrated Bachelor of Arts & Bachelor of Laws','BA-LLB'),
(19,17,'Bachelor of Pharmacy (B.Pharm)','BPHARM','Undergraduate','4 Years',8,95000,'10+2 with PCB/PCM >= 55%','Pharmacokinetics, medicinal chemistry, clinical trials, and drug dosage forms.','2026-11-17 10:43:57','Bachelor of Pharmacy (B.Pharm)','BPHARM'),
(20,18,'Bachelor of Architecture (B.Arch)','BARCH','Undergraduate','5 Years',10,125000,'10+2 with Math >= 50% + NATA','Architectural drafting, sustainable spatial planning, structural design, and BIM.','2026-11-17 10:43:57','Bachelor of Architecture (B.Arch)','BARCH'),
(21,19,'B.Sc in Clinical & Cognitive Psychology','BSC-PSYCH','Undergraduate','3 Years',6,65000,'10+2 any stream >= 50%','Neuropsychology, developmental therapies, statistical research methods, and clinical assessment.','2026-11-17 10:43:57','B.Sc in Clinical & Cognitive Psychology','BSC-PSYCH'),
(22,20,'B.Sc (Honours) in Mathematics & Computing','BSC-MATH','Undergraduate','3 Years',6,60000,'10+2 with Mathematics >= 60%','Abstract algebra, real analysis, cryptography algorithms, and numerical computing.','2026-11-17 10:43:57','B.Sc (Honours) in Mathematics & Computing','BSC-MATH'),
(23,21,'B.Sc (Honours) in Physics & Quantum Science','BSC-PHY','Undergraduate','3 Years',6,60000,'10+2 with Physics & Math >= 60%','Quantum mechanics, electrodynamics, solid-state physics, and nuclear instrumentation.','2026-11-17 10:43:57','B.Sc (Honours) in Physics & Quantum Science','BSC-PHY'),
(24,22,'B.Sc (Honours) in Chemistry & Materials','BSC-CHEM','Undergraduate','3 Years',6,60000,'10+2 with Chemistry >= 55%','Organic synthesis, coordination chemistry, spectroscopy, and nanomaterials synthesis.','2026-11-17 10:43:57','B.Sc (Honours) in Chemistry & Materials','BSC-CHEM'),
(25,23,'B.Sc (Honours) in Agricultural Sciences','BSC-AGRI','Undergraduate','4 Years',8,80000,'10+2 with Science >= 50%','Horticulture, smart irrigation robotics, crop genetics, and agribusiness enterprise.','2026-11-17 10:43:57','B.Sc (Honours) in Agricultural Sciences','BSC-AGRI'),
(26,24,'B.Sc in Environmental Science & Climate Ecology','BSC-ENV','Undergraduate','3 Years',6,65000,'10+2 with Science >= 50%','Atmospheric modeling, waste-to-energy technologies, GIS mapping, and environmental law.','2026-11-17 10:43:57','B.Sc in Environmental Science & Climate Ecology','BSC-ENV'),
(27,25,'Bachelor of Journalism & Digital Media','BJMC','Undergraduate','3 Years',6,75000,'10+2 any stream >= 50%','Broadcast journalism, video editing, newsroom reporting, and interactive media.','2026-11-17 10:43:57','Bachelor of Journalism & Digital Media','BJMC'),
(28,26,'Bachelor of Design in Animation & Game Art','BDES','Undergraduate','4 Years',8,110000,'10+2 any stream >= 50% + Portfolio','Character modeling, real-time render pipelines, motion capture, and level design.','2026-11-17 10:43:57','Bachelor of Design in Animation & Game Art','BDES'),
(29,27,'Bachelor of Hotel & Hospitality Administration','BHM','Undergraduate','3 Years',6,85000,'10+2 any stream >= 50%','Food & beverage operations, luxury front office, culinary gastronomy, and event planning.','2026-11-17 10:43:57','Bachelor of Hotel & Hospitality Administration','BHM'),
(30,28,'Bachelor of Science in Nursing (B.Sc Nursing)','BSC-NURSE','Undergraduate','4 Years',8,90000,'10+2 with PCB >= 50%','Critical patient triage, surgical nursing, pharmacology, and community healthcare.','2026-11-17 10:43:57','Bachelor of Science in Nursing (B.Sc Nursing)','BSC-NURSE'),
(31,29,'Bachelor of Physical Education & Sports Analytics','BPED','Undergraduate','3 Years',6,55000,'10+2 with Physical Ed/Sports','Kinesiology, sports coaching methods, injury rehabilitation, and athletic nutrition.','2026-11-17 10:43:57','Bachelor of Physical Education & Sports Analytics','BPED'),
(32,30,'Bachelor of Arts in English Literature & Rhetoric','BA-ENG','Undergraduate','3 Years',6,50000,'10+2 any stream >= 50%','Literary analysis, creative writing, linguistics, and comparative world drama.','2026-11-17 10:43:57','Bachelor of Arts in English Literature & Rhetoric','BA-ENG');
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
INSERT INTO `departments` VALUES 
(1,'School of Computer Science & Engineering','CSE','Pioneering cutting-edge computer systems, software engineering, AI, and cybersecurity.','Dr. Rajesh Sharma','fa-laptop-code','2026-11-17 10:43:57'),
(2,'Department of Business Administration & Management','BBA','Fostering future corporate leaders, entrepreneurs, and global management thinkers.','Dr. Sunita Deshmukh','fa-briefcase','2026-11-17 10:43:57'),
(3,'School of Information Technology & Data Science','IT','Specializing in cloud computing, big data analytics, and internet systems.','Prof. Ananya Verma','fa-database','2026-11-17 10:43:57'),
(4,'Department of Mechanical & Robotics Engineering','MECH','Advanced thermodynamics, robotics, CAD/CAM, and automated manufacturing.','Dr. Vikram Malhotra','fa-gears','2026-11-17 10:43:57'),
(5,'Department of Humanities, Arts & Journalism','HAJ','Cultivating creative expressions, media ethics, communication, and social studies.','Prof. Neha Sen','fa-feather-pointed','2026-11-17 10:43:57'),
(6,'Department of Electronics & Communication Engineering','ECE','Next-generation VLSI, signal processing, embedded systems, and wireless networks.','Dr. Arvind Swaminathan','fa-microchip','2026-11-17 10:43:57'),
(7,'Department of Electrical & Electronics Engineering','EEE','Power grids, renewable energy, smart systems, and high-voltage transmission.','Dr. Meenakshi Sundaram','fa-bolt','2026-11-17 10:43:57'),
(8,'Department of Civil & Structural Engineering','CIVIL','Smart infrastructure, sustainable construction, bridges, and environmental geotechnics.','Dr. Harish Chandra','fa-bridge','2026-11-17 10:43:57'),
(9,'Department of Artificial Intelligence & Machine Learning','AIDS','Deep learning architectures, neural networks, natural language processing, and computer vision.','Dr. Siddharth Nair','fa-brain','2026-11-17 10:43:57'),
(10,'Department of Cybersecurity & Digital Forensics','CYBER','Information assurance, ethical hacking, malware analysis, cryptography, and network defense.','Dr. Kavita Raman','fa-shield-halved','2026-11-17 10:43:57'),
(11,'Department of Aerospace & Aeronautical Engineering','AERO','Aerodynamics, propulsion systems, satellite launch vehicles, and avionics technology.','Dr. K. Radhakrishnan','fa-plane-up','2026-11-17 10:43:57'),
(12,'Department of Biotechnology & Genetic Engineering','BIOTECH','Molecular biology, genomic editing, biopharmaceutical manufacturing, and bioinformatics.','Dr. Shalini Mukherjee','fa-dna','2026-11-17 10:43:57'),
(13,'Department of Chemical & Petroleum Engineering','CHE','Petrochemical processes, polymers, industrial synthesis, and green chemistry systems.','Dr. Pradeep Joshi','fa-flask-vial','2026-11-17 10:43:57'),
(14,'Department of Commerce, Banking & Fintech','COMMERCE','Corporate finance, algorithmic trading, global banking regulations, and auditing standards.','Prof. Rakesh Jhunjhunwala','fa-coins','2026-11-17 10:43:57'),
(15,'Department of Economics & Global Public Policy','ECON','Macroeconomic modeling, development economics, monetary policy, and econometrics.','Dr. Amartya Banerjee','fa-chart-line','2026-11-17 10:43:57'),
(16,'School of Legal Studies & Constitutional Jurisprudence','LAW','Corporate law, cyber jurisprudence, international arbitration, and human rights advocacy.','Prof. Fali Nariman','fa-scale-balanced','2026-11-17 10:43:57'),
(17,'School of Pharmaceutical Sciences & Drug Discovery','PHARM','Pharmacology, clinical trials, novel drug delivery formulations, and medicinal chemistry.','Dr. Swati Piramal','fa-capsules','2026-11-17 10:43:57'),
(18,'School of Architecture, Urban Design & Planning','ARCH','Sustainable habitats, landscape architecture, BIM modeling, and urban master planning.','Prof. B.V. Doshi','fa-compass-drafting','2026-11-17 10:43:57'),
(19,'Department of Psychology & Cognitive Neuroscience','PSYCH','Clinical behavioral therapies, neuro-imaging, organizational psychology, and counseling.','Dr. Vandana Shiva','fa-head-side-virus','2026-11-17 10:43:57'),
(20,'Department of Mathematics & Computational Statistics','MATH','Number theory, applied probability, stochastic calculus, and mathematical modeling.','Dr. Manjul Bhargava','fa-square-root-variable','2026-11-17 10:43:57'),
(21,'Department of Applied Physics & Quantum Nanotechnology','PHY','Quantum optics, condensed matter physics, superconducting devices, and nanostructures.','Dr. Ashoke Sen','fa-atom','2026-11-17 10:43:57'),
(22,'Department of Chemistry & Materials Science','CHEM','Supramolecular catalysts, smart nanomaterials, crystallographic analysis, and green polymers.','Dr. C.N.R. Rao','fa-vial','2026-11-17 10:43:57'),
(23,'School of Agricultural Sciences & Agritech Innovation','AGRI','Precision agronomy, soil health science, drone telemetry, and sustainable food security.','Dr. M.S. Swaminathan','fa-wheat-awn','2026-11-17 10:43:57'),
(24,'Department of Environmental Sciences & Sustainability','ENV','Climate resilience, carbon sequestration, renewable hydrology, and ecological conservation.','Dr. Sunita Narain','fa-leaf','2026-11-17 10:43:57'),
(25,'School of Media Studies, Film & Digital Broadcasting','MEDIA','Digital film production, investigative journalism, podcasting, and streaming media tech.','Prof. Prannoy Roy','fa-video','2026-11-17 10:43:57'),
(26,'Department of Animation, VFX & Interactive Game Design','DESIGN','3D asset modeling, procedural shaders, VR environments, and Unreal Engine development.','Prof. Satyajit Sen','fa-palette','2026-11-17 10:43:57'),
(27,'Department of Hospitality, Culinary Arts & Tourism','HOSP','Luxury hotel operations, international gourmet culinary techniques, and resort management.','Chef Sanjeev Oberoi','fa-utensils','2026-11-17 10:43:57'),
(28,'School of Nursing & Healthcare Management','NURSE','Advanced acute clinical care, epidemiology, patient safety protocols, and health informatics.','Dr. Florence Thomas','fa-user-nurse','2026-11-17 10:43:57'),
(29,'Department of Sports Science, Kinesiology & Athletics','SPORTS','Athletic biomechanics, exercise physiology, sports rehabilitation, and peak fitness analytics.','Coach Pullela Gopichand','fa-person-running','2026-11-17 10:43:57'),
(30,'Department of English & World Literature','ENG','Post-colonial narratives, critical literary theory, linguistics, and global dramatic arts.','Dr. Gayatri Spivak','fa-book-bookmark','2026-11-17 10:43:57'),
(31,'School of Educational Sciences & Modern Pedagogy','EDU','Instructional design, blended learning psychology, STEM curricula, and educational leadership.','Dr. Krishna Kumar','fa-chalkboard','2026-11-17 10:43:57'),
(32,'Department of Performing Arts, Indian Classical & World Music','MUSIC','Classical acoustic theory, ethnomusicology, audio engineering, and orchestration.','Ustad Zakir Hussain','fa-music','2026-11-17 10:43:57');
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
INSERT INTO `faculty` VALUES 
(1,2,1,'Computer Science','Professor & Dean Academics','Ph.D. in Computer Science (IIT Delhi)','Relational Databases, Distributed Systems & AI','Academic Block A, Suite 401',NULL,'2026-11-17 10:43:57'),
(2,3,3,'Information Technology','Associate Professor','Ph.D. in Information Systems','Cloud Computing, Web Services & Big Data','IT Tower, Room 210',NULL,'2026-11-17 10:43:57'),
(3,4,2,'Business Administration','Professor & HOD','Ph.D. in Management (IIM Ahmedabad)','Strategic Leadership & Corporate Governance','Management Wing, Room 102',NULL,'2026-11-17 10:43:57'),
(4,9,4,'Mechanical Engineering','Professor & HOD','Ph.D. in Robotics (IIT Bombay)','Mechatronics, Electric Vehicles & CAD/CAM','Engineering Workshop Block, Room 105',NULL,'2026-11-17 10:43:57'),
(5,10,5,'Humanities & Journalism','Associate Professor','Ph.D. in Mass Communication (JNU)','Media Ethics, Investigative Journalism & Cultural Studies','Humanities Block, Room 204',NULL,'2026-11-17 10:43:57'),
(6,11,6,'Electronics Engineering','Professor & HOD','Ph.D. in VLSI Design (IIT Madras)','Embedded Systems, 5G Wireless & Signal Processing','Technology Center, Room 312',NULL,'2026-11-17 10:43:57'),
(7,12,7,'Electrical Engineering','Professor & HOD','Ph.D. in Power Systems (IIT Roorkee)','Smart Grid Architecture & Renewable Energy','Power Systems Wing, Room 201',NULL,'2026-11-17 10:43:57'),
(8,13,8,'Civil Engineering','Associate Professor','Ph.D. in Structural Engineering (IIT Kharagpur)','Earthquake-Resistant Structures & Concrete Tech','Civil Engineering Block, Room 118',NULL,'2026-11-17 10:43:57'),
(9,14,9,'Artificial Intelligence','Professor & HOD','Ph.D. in Machine Learning (IISc Bangalore)','Deep Learning, Natural Language Processing & LLMs','AI Research Lab, Room 402',NULL,'2026-11-17 10:43:57'),
(10,15,10,'Cybersecurity','Associate Professor','Ph.D. in Cyber Defense (Carnegie Mellon)','Ethical Hacking, Cryptography & Threat Hunting','Cyber Security Operations Center, Room 205',NULL,'2026-11-17 10:43:57'),
(11,16,11,'Aerospace Engineering','Distinguished Professor','Ph.D. in Aeronautics (ISRO / MIT)','Rocket Propulsion, Flight Dynamics & Hypersonic Flow','Aeronautics Hangar, Suite 101',NULL,'2026-11-17 10:43:57'),
(12,17,12,'Biotechnology','Professor & HOD','Ph.D. in Molecular Genetics (Oxford)','CRISPR Gene Editing, Bioinformatics & Biopharma','Bio-Research Complex, Room 303',NULL,'2026-11-17 10:43:57'),
(13,18,13,'Chemical Engineering','Associate Professor','Ph.D. in Chemical Process Engineering (IIT Kanpur)','Petrochemical Refining, Polymers & Catalysis','Chemical Labs, Room 112',NULL,'2026-11-17 10:43:57'),
(14,19,14,'Commerce & Finance','Professor of Practice','FCA, Ph.D. in FinTech & Derivatives','Algorithmic Trading, Corporate Taxation & Mergers','Finance Lab, Room 215',NULL,'2026-11-17 10:43:57'),
(15,20,15,'Economics','Professor & HOD','Ph.D. in Development Economics (LSE)','Econometrics, Game Theory & Public Policy Design','Social Sciences Tower, Room 305',NULL,'2026-11-17 10:43:57'),
(16,21,16,'Legal Studies','Senior Professor & Dean','LL.D. in Constitutional Jurisprudence (Harvard)','Constitutional Law, Corporate Litigation & Human Rights','Law Moot Court Building, Room 101',NULL,'2026-11-17 10:43:57'),
(17,22,17,'Pharmacy','Professor & Dean','Ph.D. in Pharmacology (NIPER)','Novel Drug Delivery Systems & Clinical Trials','Pharmacy Complex, Room 202',NULL,'2026-11-17 10:43:57'),
(18,23,18,'Architecture','Chief Architecture Fellow','M.Arch, Pritzker Fellow (CEPT)','Urban Master Planning & Biophilic Architecture','Design Studios, Room 401',NULL,'2026-11-17 10:43:57'),
(19,24,19,'Psychology','Professor & HOD','Ph.D. in Cognitive Neuropsychology (NIMHANS)','Behavioral Therapy, Neuro-Imaging & Mental Health','Psychology Clinic, Room 108',NULL,'2026-11-17 10:43:57'),
(20,25,20,'Mathematics','Visiting Professor','Ph.D. in Number Theory (Princeton)','Algebraic Geometry, Probability & Cryptography','Ramanujan Mathematical Center, Room 310',NULL,'2026-11-17 10:43:57'),
(21,26,21,'Applied Physics','Professor of Physics','Ph.D. in Quantum Mechanics (TIFR)','Superconductivity, Quantum Computing & Photonics','C.V. Raman Physics Lab, Room 208',NULL,'2026-11-17 10:43:57'),
(22,27,22,'Chemistry','Honorary Chair Professor','Ph.D. in Solid State Chemistry (UC Berkeley)','Nanomaterials, Green Catalysis & Polymer Science','Chemistry Research Center, Room 104',NULL,'2026-11-17 10:43:57'),
(23,28,23,'Agricultural Sciences','Professor & HOD','Ph.D. in Plant Genetics (IARI)','Agrigenomics, Precision Farming & Soil Health','Agronomy Experimental Field Office 1',NULL,'2026-11-17 10:43:57'),
(24,29,24,'Environmental Science','Professor & HOD','Ph.D. in Climate Dynamics (Cambridge)','Carbon Sequestration, Hydrology & Ecological Policy','Ecology Center, Room 201',NULL,'2026-11-17 10:43:57'),
(25,30,25,'Media Studies','Professor of Broadcasting','M.A. in Visual Journalism (Columbia)','Documentary Production, News Broadcasting & Podcasting','University Media Studio, Room 103',NULL,'2026-11-17 10:43:57'),
(26,31,26,'Animation & Design','Associate Professor','M.Des in Animation Art (NID)','Virtual Production, Unreal Engine & 3D Character Art','Animation Studio B, Room 220',NULL,'2026-11-17 10:43:57'),
(27,32,27,'Hospitality & Tourism','Master Professor of Culinary','Master Chef Distinction (Le Cordon Bleu)','International Cuisine, Hotel Administration & Oenology','Culinary Arts Kitchen 1',NULL,'2026-11-17 10:43:57'),
(28,33,28,'Nursing & Health','Clinical Professor & HOD','Ph.D. in Nursing Practice (Johns Hopkins)','Critical ICU Care, Emergency Response & Hospital Safety','Clinical Simulation Center, Room 102',NULL,'2026-11-17 10:43:57'),
(29,34,29,'Sports Science','Director of Athletics','M.P.Ed, Olympic Performance Coach','Kinesiology, Peak Conditioning & Athletic Physiology','University Sports Arena, Room 101',NULL,'2026-11-17 10:43:57'),
(30,35,30,'English Literature','Professor of Humanities','Ph.D. in English Literature (Columbia)','Modernist Poetry, Comparative Rhetoric & World Drama','Literature Library, Room 207',NULL,'2026-11-17 10:43:57'),
(31,36,31,'Educational Sciences','Professor & HOD','Ph.D. in Educational Pedagogy (Delhi University)','Cognitive Instructional Design & STEM Curriculum','Education Wing, Room 301',NULL,'2026-11-17 10:43:57'),
(32,37,32,'Music & Performing Arts','Chair of Performing Arts','Sangeet Natak Akademi Fellow','Percussion Acoustics, Ethnomusicology & Orchestra','Performing Arts Auditorium Studio 1',NULL,'2026-11-17 10:43:57');
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
INSERT INTO `fees` VALUES 
(1,1,2,'Semester 2 Academic & Lab Tuition',40000,40000,'2026-03-15','Paid','TXN-EDU-9801001','2026-02-02'),
(2,2,3,'Semester 3 Academic & Lab Tuition',45000,45000,'2026-03-15','Paid','TXN-EDU-9801002','2026-02-03'),
(3,3,4,'Semester 4 Academic & Lab Tuition',50000,50000,'2026-03-15','Paid','TXN-EDU-9801003','2026-02-04'),
(4,4,5,'Semester 5 Academic & Lab Tuition',55000,55000,'2026-03-15','Paid','TXN-EDU-9801004','2026-02-05'),
(5,5,6,'Semester 6 Academic & Lab Tuition',35000,17500,'2026-03-15','Pending','TXN-EDU-9801005','2026-02-06'),
(6,6,1,'Semester 1 Academic & Lab Tuition',40000,40000,'2026-03-15','Paid','TXN-EDU-9801006','2026-02-07'),
(7,7,2,'Semester 2 Academic & Lab Tuition',45000,45000,'2026-03-15','Paid','TXN-EDU-9801007','2026-02-08'),
(8,8,3,'Semester 3 Academic & Lab Tuition',50000,50000,'2026-03-15','Paid','TXN-EDU-9801008','2026-02-09'),
(9,9,4,'Semester 4 Academic & Lab Tuition',55000,55000,'2026-03-15','Paid','TXN-EDU-9801009','2026-02-10'),
(10,10,5,'Semester 5 Academic & Lab Tuition',35000,17500,'2026-03-15','Pending','TXN-EDU-9801010','2026-02-11'),
(11,11,6,'Semester 6 Academic & Lab Tuition',40000,40000,'2026-03-15','Paid','TXN-EDU-9801011','2026-02-12'),
(12,12,1,'Semester 1 Academic & Lab Tuition',45000,45000,'2026-03-15','Paid','TXN-EDU-9801012','2026-02-13'),
(13,13,2,'Semester 2 Academic & Lab Tuition',50000,50000,'2026-03-15','Paid','TXN-EDU-9801013','2026-02-14'),
(14,14,3,'Semester 3 Academic & Lab Tuition',55000,55000,'2026-03-15','Paid','TXN-EDU-9801014','2026-02-15'),
(15,15,4,'Semester 4 Academic & Lab Tuition',35000,17500,'2026-03-15','Pending','TXN-EDU-9801015','2026-02-16'),
(16,16,5,'Semester 5 Academic & Lab Tuition',40000,40000,'2026-03-15','Paid','TXN-EDU-9801016','2026-02-17'),
(17,17,6,'Semester 6 Academic & Lab Tuition',45000,45000,'2026-03-15','Paid','TXN-EDU-9801017','2026-02-18'),
(18,18,1,'Semester 1 Academic & Lab Tuition',50000,50000,'2026-03-15','Paid','TXN-EDU-9801018','2026-02-19'),
(19,19,2,'Semester 2 Academic & Lab Tuition',55000,55000,'2026-03-15','Paid','TXN-EDU-9801019','2026-02-20'),
(20,20,3,'Semester 3 Academic & Lab Tuition',35000,17500,'2026-03-15','Pending','TXN-EDU-9801020','2026-02-21'),
(21,21,4,'Semester 4 Academic & Lab Tuition',40000,40000,'2026-03-15','Paid','TXN-EDU-9801021','2026-02-22'),
(22,22,5,'Semester 5 Academic & Lab Tuition',45000,45000,'2026-03-15','Paid','TXN-EDU-9801022','2026-02-23'),
(23,23,6,'Semester 6 Academic & Lab Tuition',50000,50000,'2026-03-15','Paid','TXN-EDU-9801023','2026-02-24'),
(24,24,1,'Semester 1 Academic & Lab Tuition',55000,55000,'2026-03-15','Paid','TXN-EDU-9801024','2026-02-25'),
(25,25,2,'Semester 2 Academic & Lab Tuition',35000,17500,'2026-03-15','Pending','TXN-EDU-9801025','2026-02-01'),
(26,26,3,'Semester 3 Academic & Lab Tuition',40000,40000,'2026-03-15','Paid','TXN-EDU-9801026','2026-02-02'),
(27,27,4,'Semester 4 Academic & Lab Tuition',45000,45000,'2026-03-15','Paid','TXN-EDU-9801027','2026-02-03'),
(28,28,5,'Semester 5 Academic & Lab Tuition',50000,50000,'2026-03-15','Paid','TXN-EDU-9801028','2026-02-04'),
(29,29,6,'Semester 6 Academic & Lab Tuition',55000,55000,'2026-03-15','Paid','TXN-EDU-9801029','2026-02-05'),
(30,30,1,'Semester 1 Academic & Lab Tuition',35000,17500,'2026-03-15','Pending','TXN-EDU-9801030','2026-02-06'),
(31,31,2,'Semester 2 Academic & Lab Tuition',40000,40000,'2026-03-15','Paid','TXN-EDU-9801031','2026-02-07'),
(32,32,3,'Semester 3 Academic & Lab Tuition',45000,45000,'2026-03-15','Paid','TXN-EDU-9801032','2026-02-08'),
(33,33,4,'Semester 4 Academic & Lab Tuition',50000,50000,'2026-03-15','Paid','TXN-EDU-9801033','2026-02-09'),
(34,34,5,'Semester 5 Academic & Lab Tuition',55000,55000,'2026-03-15','Paid','TXN-EDU-9801034','2026-02-10'),
(35,35,6,'Semester 6 Academic & Lab Tuition',35000,17500,'2026-03-15','Pending','TXN-EDU-9801035','2026-02-11');
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
INSERT INTO `placements` VALUES 
(1,'Aarav Mehta','23BCS10001','Google',44.5,'Software Engineer L4',2024,'default_company.png','default_student.png','2026-11-17 10:43:57'),
(2,'Sneha Reddy','23BCS10015','Microsoft',38,'Cloud Solutions Engineer',2024,'default_company.png','default_student.png','2026-11-17 10:43:57'),
(3,'Rohan Kapoor','23BCS10042','Amazon',32,'SDE-1',2024,'default_company.png','default_student.png','2026-11-17 10:43:57'),
(4,'Ishita Jain','23BCS10078','Goldman Sachs',28.5,'Technology Analyst',2024,'default_company.png','default_student.png','2026-11-17 10:43:57'),
(5,'Arjun Nair','23MCA20010','Adobe',24,'Full Stack Developer',2024,'default_company.png','default_student.png','2026-11-17 10:43:57'),
(6,'Kavya Sharma','23BCA20025','Flipkart',18.5,'Backend Engineer',2024,'default_company.png','default_student.png','2026-11-17 10:43:57'),
(7,'Deepak Yadav','23BCS10090','TCS Digital',11,'Systems Engineer',2024,'default_company.png','default_student.png','2026-11-17 10:43:57'),
(8,'Meera Patel','23BBA30012','Deloitte',9.5,'Business Analyst',2024,'default_company.png','default_student.png','2026-11-17 10:43:57'),
(9,'Vikram Joshi','23MEC40015','Tesla India',26,'Robotics Automation Engineer',2024,'default_company.png','default_student.png','2026-11-17 10:43:57'),
(10,'Tanvi Rao','23ECE60020','Qualcomm',29,'Hardware System Engineer',2024,'default_company.png','default_student.png','2026-11-17 10:43:57'),
(11,'Abhinav Singh','23AID90005','NVIDIA',36,'AI Research Engineer',2024,'default_company.png','default_student.png','2026-11-17 10:43:57'),
(12,'Pooja Hegde','23CYB10012','Palo Alto Networks',25,'Security Operations Analyst',2024,'default_company.png','default_student.png','2026-11-17 10:43:57'),
(13,'Nikhil Bhatt','23AER11008','ISRO (VSSC)',18,'Scientist / Engineer SC',2024,'default_company.png','default_student.png','2026-11-17 10:43:57'),
(14,'Ananya Sen','23LAW16010','Shardul Amarchand Mangaldas',20,'Corporate Legal Associate',2024,'default_company.png','default_student.png','2026-11-17 10:43:57'),
(15,'Siddharth Roy','23BPH17015','Pfizer Global',16.5,'Clinical Formulation Scientist',2024,'default_company.png','default_student.png','2026-11-17 10:43:57');
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
INSERT INTO `results` VALUES 
(1,1,2,3,'Database Management Systems','End Semester Examination',100,92,'A+','Pass','2026-11-17 10:43:57'),
(2,1,2,3,'Object Oriented Programming with Java','End Semester Examination',100,88,'A','Pass','2026-11-17 10:43:57'),
(3,1,2,3,'Computer System Architecture','End Semester Examination',100,85,'A','Pass','2026-11-17 10:43:57'),
(4,1,2,3,'Web Development Technologies','End Semester Examination',100,95,'O','Pass','2026-11-17 10:43:57'),
(5,2,2,3,'Database Management Systems','End Semester Examination',100,90,'A+','Pass','2026-11-17 10:43:57'),
(6,2,2,3,'Web Development Technologies','End Semester Examination',100,89,'A','Pass','2026-11-17 10:43:57'),
(7,3,1,3,'Data Structures & Algorithms','End Semester Examination',100,91,'A+','Pass','2026-11-17 10:43:57'),
(8,4,3,2,'Financial Accounting & Reporting','End Semester Examination',100,84,'A','Pass','2026-11-17 10:43:57'),
(9,5,1,2,'Operating Systems Concepts','End Semester Examination',100,86,'A','Pass','2026-11-17 10:43:57'),
(10,6,1,2,'Discrete Mathematics','End Semester Examination',100,94,'A+','Pass','2026-11-17 10:43:57'),
(11,7,3,3,'Marketing Management','End Semester Examination',100,82,'A','Pass','2026-11-17 10:43:57'),
(12,8,4,1,'Managerial Economics','End Semester Examination',100,93,'A+','Pass','2026-11-17 10:43:57'),
(13,9,5,1,'Advanced Database Architectures','End Semester Examination',100,87,'A','Pass','2026-11-17 10:43:57'),
(14,11,6,5,'Thermodynamics & Heat Transfer','End Semester Examination',100,89,'A','Pass','2026-11-17 10:43:57'),
(15,13,8,3,'Analog & Digital Communications','End Semester Examination',100,91,'A+','Pass','2026-11-17 10:43:57'),
(16,14,9,3,'Electric Machines & Drives','End Semester Examination',100,85,'A','Pass','2026-11-17 10:43:57'),
(17,15,10,5,'Geotechnical Engineering','End Semester Examination',100,83,'A','Pass','2026-11-17 10:43:57'),
(18,16,11,1,'Foundations of Artificial Intelligence','End Semester Examination',100,97,'O','Pass','2026-11-17 10:43:57'),
(19,17,12,3,'Applied Cryptography & PKI','End Semester Examination',100,92,'A+','Pass','2026-11-17 10:43:57'),
(20,18,13,4,'Aerodynamics & Wing Design','End Semester Examination',100,94,'A+','Pass','2026-11-17 10:43:57');
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
INSERT INTO `students` VALUES 
(1,5,'24BCS12641',1,2,'BCA',4,2024,'Male','2004-05-15','Flat 402, Royal Palms Enclave, Tech Park Road','Active',NULL,'2026-11-17 10:43:57'),
(2,6,'24BCS12642',1,2,'BCA',4,2024,'Female','2004-08-20','House 12B, Green Valley Residency, Campus View','Active',NULL,'2026-11-17 10:43:57'),
(3,7,'24BCS12643',1,1,'BTECH-CSE',4,2024,'Male','2003-11-10','Plot 88, Sunrise Apartments, Sector 14','Active',NULL,'2026-11-17 10:43:57'),
(4,8,'24BCS12644',2,3,'BBA',2,2025,'Female','2005-02-28','Villa 5, Golden Orchid Heights, Civil Lines','Active',NULL,'2026-11-17 10:43:57'),
(5,38,'24CSE101',1,1,'BTECH-CSE',3,2024,'Male','2004-04-12','Hostel Block A, Room 204, Campus','Active',NULL,'2026-11-17 10:43:57'),
(6,39,'24CSE102',1,1,'BTECH-CSE',3,2024,'Female','2004-09-18','Tower 3, Cyber Greens, IT Highway','Active',NULL,'2026-11-17 10:43:57'),
(7,40,'24BBA201',2,3,'BBA',4,2024,'Male','2003-12-05','14 Rosewood Lane, Model Town','Active',NULL,'2026-11-17 10:43:57'),
(8,41,'24MBA202',2,4,'MBA',2,2025,'Female','2002-07-22','Penthouse 11, Silver Oak Enclave','Active',NULL,'2026-11-17 10:43:57'),
(9,42,'24MCA301',3,5,'MCA',2,2025,'Male','2003-03-30','Sector 21B, Urban Estate, Chandigarh','Active',NULL,'2026-11-17 10:43:57'),
(10,43,'24MCA302',3,5,'MCA',4,2024,'Female','2002-11-14','House 45, DLF Phase 2, Gurgaon','Active',NULL,'2026-11-17 10:43:57'),
(11,44,'24MEC401',4,6,'BTECH-MECH',6,2023,'Male','2003-01-25','Plot 102, Industrial Estate Road','Active',NULL,'2026-11-17 10:43:57'),
(12,45,'24HAJ501',5,7,'BA-HAJ',2,2025,'Female','2005-06-19','Flat 304, Media Enclave, Sector 62','Active',NULL,'2026-11-17 10:43:57'),
(13,46,'24ECE601',6,8,'BTECH-ECE',4,2024,'Male','2004-02-14','House 77, Circuit City, Bengaluru','Active',NULL,'2026-11-17 10:43:57'),
(14,47,'24EEE701',7,9,'BTECH-EEE',4,2024,'Female','2004-10-08','88 Power Grid Colony, Hyderabad','Active',NULL,'2026-11-17 10:43:57'),
(15,48,'24CIV801',8,10,'BTECH-CIVIL',6,2023,'Male','2003-05-17','Civil Lines, Near University Gate 2','Active',NULL,'2026-11-17 10:43:57'),
(16,49,'24AID901',9,11,'BTECH-AIDS',2,2025,'Female','2005-08-11','Hostel Block B, Room 112, Campus','Active',NULL,'2026-11-17 10:43:57'),
(17,50,'24CYB101',10,12,'BTECH-CYBER',4,2024,'Male','2004-01-29','Silicon Towers, Phase 7, Mohali','Active',NULL,'2026-11-17 10:43:57'),
(18,51,'24AER111',11,13,'BTECH-AERO',5,2023,'Female','2003-08-04','Airport Road, Viman Nagar, Pune','Active',NULL,'2026-11-17 10:43:57'),
(19,52,'24BIO121',12,14,'BTECH-BIOTECH',3,2024,'Male','2004-12-16','Genome Valley, Block 4, Hyderabad','Active',NULL,'2026-11-17 10:43:57'),
(20,53,'24CHE131',13,15,'BTECH-CHE',5,2023,'Female','2003-04-20','Refinery Township, Sector 8, Vadodara','Active',NULL,'2026-11-17 10:43:57'),
(21,54,'24COM141',14,16,'BCOM-HONS',3,2024,'Male','2004-07-09','Dalal Street Residency, Mumbai','Active',NULL,'2026-11-17 10:43:57'),
(22,55,'24ECO151',15,17,'BSC-ECON',2,2025,'Female','2005-03-14','Chanakyapuri, Diplomatic Enclave, Delhi','Active',NULL,'2026-11-17 10:43:57'),
(23,56,'24LAW161',16,18,'BA-LLB',6,2023,'Male','2002-10-31','High Court Enclave, Sector 1, Chandigarh','Active',NULL,'2026-11-17 10:43:57'),
(24,57,'24PHA171',17,19,'BPHARM',4,2024,'Female','2004-06-27','Pharma City, Baddi, Himachal Pradesh','Active',NULL,'2026-11-17 10:43:57'),
(25,58,'24ARC181',18,20,'BARCH',6,2023,'Male','2003-09-03','Studio Quarter, Heritage Zone, Jaipur','Active',NULL,'2026-11-17 10:43:57'),
(26,59,'24PSY191',19,21,'BSC-PSYCH',2,2025,'Female','2005-11-20','Lake View Gardens, Bhopal','Active',NULL,'2026-11-17 10:43:57'),
(27,60,'24MAT201',20,22,'BSC-MATH',4,2024,'Male','2004-03-08','Ramanujan Hostel, Campus Block C','Active',NULL,'2026-11-17 10:43:57'),
(28,61,'24PHY211',21,23,'BSC-PHY',4,2024,'Female','2004-05-24','Cosmic Heights, Observatory Hill, Nainital','Active',NULL,'2026-11-17 10:43:57'),
(29,62,'24CHM221',22,24,'BSC-CHEM',2,2025,'Male','2005-01-19','Crystal Palace Apartments, Indore','Active',NULL,'2026-11-17 10:43:57'),
(30,63,'24AGR231',23,25,'BSC-AGRI',4,2024,'Female','2004-08-02','Farm Research Hub, PAU Road, Ludhiana','Active',NULL,'2026-11-17 10:43:57'),
(31,64,'24ENV241',24,26,'BSC-ENV',3,2024,'Male','2004-06-11','Eco Forest Valley, Dehradun','Active',NULL,'2026-11-17 10:43:57'),
(32,65,'24MED251',25,27,'BJMC',2,2025,'Female','2005-04-07','Broadcasting Colony, Film City, Noida','Active',NULL,'2026-11-17 10:43:57'),
(33,66,'24DES261',26,28,'BDES',4,2024,'Male','2004-02-22','Design District, Koramangala, Bengaluru','Active',NULL,'2026-11-17 10:43:57'),
(34,67,'24HOS271',27,29,'BHM',3,2024,'Male','2004-07-17','Grand Heritage Boulevard, Udaipur','Active',NULL,'2026-11-17 10:43:57'),
(35,68,'24NUR281',28,30,'BSC-NURSE',5,2023,'Female','2003-12-12','Medical Enclave, AIIMS Campus Road','Active',NULL,'2026-11-17 10:43:57');
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
INSERT INTO `subjects` VALUES 
(1,'Data Structures & Algorithms','CS301',1,'B.Tech CSE',3,'2026-11-17 11:02:26'),
(2,'Database Management Systems','CS302',1,'B.Tech CSE',3,'2026-11-17 11:02:26'),
(3,'Web Technologies & Frameworks','CS401',2,'BCA',4,'2026-11-17 11:02:26'),
(4,'Operating Systems & Architecture','CS402',2,'BCA',4,'2026-11-17 11:02:26'),
(5,'Artificial Intelligence & ML','CS501',9,'B.Tech CSE',5,'2026-11-17 11:02:26'),
(6,'Software Engineering & Agile','CS502',1,'B.Tech CSE',5,'2026-11-17 11:02:26'),
(7,'Computer Networks & Security','CS601',2,'BCA',4,'2026-11-17 11:02:26'),
(8,'Cloud Computing & DevOps','CS602',2,'B.Tech CSE',6,'2026-11-17 11:02:26'),
(9,'Strategic Financial Management','BBA301',3,'BBA',3,'2026-11-17 11:02:26'),
(10,'Organizational Behavior & HR','BBA302',3,'BBA',3,'2026-11-17 11:02:26'),
(11,'Global Supply Chain Strategy','MBA201',3,'MBA',2,'2026-11-17 11:02:26'),
(12,'Enterprise Java & Spring Boot','MCA201',2,'MCA',2,'2026-11-17 11:02:26'),
(13,'Automotive Systems & EV Tech','MEC601',4,'BTECH-MECH',6,'2026-11-17 11:02:26'),
(14,'CAD/CAM & Industrial Robotics','MEC602',4,'BTECH-MECH',6,'2026-11-17 11:02:26'),
(15,'Media Ethics & Investigative Journalism','HAJ201',5,'BA-HAJ',2,'2026-11-17 11:02:26'),
(16,'Digital Signal Processing','ECE401',6,'BTECH-ECE',4,'2026-11-17 11:02:26'),
(17,'Power Grid Infrastructure','EEE401',7,'BTECH-EEE',4,'2026-11-17 11:02:26'),
(18,'Structural Mechanics & Concrete','CIV601',8,'BTECH-CIVIL',6,'2026-11-17 11:02:26'),
(19,'Deep Neural Networks & NLP','AID201',9,'BTECH-AIDS',2,'2026-11-17 11:02:26'),
(20,'Network Penetration Testing','CYB401',10,'BTECH-CYBER',4,'2026-11-17 11:02:26'),
(21,'Rocket Propulsion Systems','AER501',11,'BTECH-AERO',5,'2026-11-17 11:02:26'),
(22,'Recombinant DNA Technology','BIO301',12,'BTECH-BIOTECH',3,'2026-11-17 11:02:26'),
(23,'Petroleum Refining Processes','CHE501',13,'BTECH-CHE',5,'2026-11-17 11:02:26'),
(24,'Corporate Taxation & Auditing','COM301',14,'BCOM-HONS',3,'2026-11-17 11:02:26'),
(25,'Econometric Modeling','ECO201',15,'BSC-ECON',2,'2026-11-17 11:02:26'),
(26,'Constitutional Law & Rights','LAW601',16,'BA-LLB',6,'2026-11-17 11:02:26'),
(27,'Clinical Pharmacology','PHA401',17,'BPHARM',4,'2026-11-17 11:02:26'),
(28,'Urban Sustainable Planning','ARC601',18,'BARCH',6,'2026-11-17 11:02:26'),
(29,'Cognitive Neuroscience','PSY201',19,'BSC-PSYCH',2,'2026-11-17 11:02:26'),
(30,'Differential Geometry & Stats','MAT401',20,'BSC-MATH',4,'2026-11-17 11:02:26'),
(31,'Quantum Electrodynamics','PHY401',21,'BSC-PHY',4,'2026-11-17 11:02:26'),
(32,'Organic Supramolecular Chemistry','CHM201',22,'BSC-CHEM',2,'2026-11-17 11:02:26');
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
INSERT INTO `submissions` VALUES 
(1,1,1,'sunny_sql_joins_solution.pdf','2026-11-20 10:43:57',94,'Superb ER diagram design and flawless normalization proof!','Superb ER diagram design and flawless normalization proof!','Graded'),
(2,1,2,'priya_sql_solution.pdf','2026-11-21 11:15:20',92,'Clean script formatting and comprehensive query explanation.','Clean script formatting and comprehensive query explanation.','Graded'),
(3,2,1,'sunny_php_project.zip','2026-11-22 14:30:00',96,'Outstanding MVC layout, CSRF protection, and responsive UI.','Outstanding MVC layout, CSRF protection, and responsive UI.','Graded'),
(4,2,2,'priya_auth_module.zip','2026-11-23 09:45:00',90,'Well-commented auth guards and database error handlers.','Well-commented auth guards and database error handlers.','Graded'),
(5,3,5,'rohan_resnet_solution.pdf','2026-11-24 16:20:00',88,'Good convergence logs, minor epoch overfitting noted.','Good convergence logs, minor epoch overfitting noted.','Graded'),
(6,4,7,'vikram_dcf_model.xlsx','2026-11-25 12:00:00',91,'Accurate WACC calculation and sensitivity matrices.','Accurate WACC calculation and sensitivity matrices.','Graded'),
(7,5,11,'karan_ansys_simulation.pdf','2026-11-26 15:10:00',93,'Excellent heat dissipation contours and cooling inlet design.','Excellent heat dissipation contours and cooling inlet design.','Graded'),
(8,7,16,'shreya_transformer_code.py','2026-11-27 18:00:00',98,'Brilliant matrix broadcast implementation for self-attention!','Brilliant matrix broadcast implementation for self-attention!','Graded'),
(9,8,17,'kunal_volatility_report.pdf','2026-11-27 20:30:00',95,'Accurate C2 IP attribution and PID injection recovery.','Accurate C2 IP attribution and PID injection recovery.','Graded'),
(10,10,23,'manav_moot_memorial.pdf','2026-11-28 11:00:00',89,'Persuasive constitutional arguments and judicial precedent citations.','Persuasive constitutional arguments and judicial precedent citations.','Graded');
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
INSERT INTO `timetable` VALUES 
(1,2,4,'Monday','09:00 AM - 10:00 AM','Database Systems (CS401)','Lecture Hall 101','Dr. Rajesh Sharma'),
(2,2,4,'Monday','10:00 AM - 11:00 AM','Web Engineering (CS402)','Computer Lab 3','Prof. Ananya Verma'),
(3,2,4,'Monday','11:15 AM - 12:15 PM','Software Engineering (CS403)','Lecture Hall 101','Dr. Rajesh Sharma'),
(4,2,4,'Tuesday','09:00 AM - 10:00 AM','Operating Systems (CS404)','Lecture Hall 102','Prof. Ananya Verma'),
(5,2,4,'Tuesday','10:00 AM - 12:00 PM','Web Engineering Practical Lab','Software Lab 2','Dr. Rajesh Sharma'),
(6,2,4,'Wednesday','09:00 AM - 10:00 AM','Database Systems (CS401)','Lecture Hall 101','Dr. Rajesh Sharma'),
(7,2,4,'Thursday','10:00 AM - 11:00 AM','Computer Networks (CS405)','Lecture Hall 103','Prof. Ananya Verma'),
(8,2,4,'Friday','02:00 PM - 04:00 PM','Capstone Project Seminar','Seminar Hall 1','Faculty Panel'),
(9,1,3,'Monday','09:00 AM - 10:00 AM','Data Structures & Algorithms','Engineering LH 201','Dr. Rajesh Sharma'),
(10,1,3,'Wednesday','11:00 AM - 01:00 PM','DSA Implementation Lab','AI Computing Lab 1','Dr. Siddharth Nair'),
(11,3,2,'Tuesday','10:00 AM - 11:00 AM','Strategic Financial Accounting','Management Hall B','Dr. Sunita Deshmukh'),
(12,6,6,'Thursday','02:00 PM - 04:00 PM','Robotics & Automation Lab','Mechatronics Lab','Dr. Vikram Malhotra'),
(13,8,4,'Wednesday','09:00 AM - 10:00 AM','VLSI Architecture & Verilog','VLSI Design Center','Dr. Arvind Swaminathan'),
(14,11,2,'Friday','10:00 AM - 12:00 PM','Deep Learning Neural Networks','GPU Supercluster Lab','Dr. Siddharth Nair'),
(15,12,4,'Monday','01:00 PM - 03:00 PM','Offensive Cyber Security Lab','Cyber Defense SOC','Dr. Kavita Raman'),
(16,18,6,'Thursday','10:00 AM - 01:00 PM','Constitutional Law Moot Court','Main Moot Courtroom','Prof. Fali Nariman');
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
INSERT INTO `users` VALUES 
(1,'System Administrator','admin@gmail.com','admin123','admin','default.png','+91 98765 43210','2026-11-17 10:43:57'),
(2,'Dr. Rajesh Sharma','faculty@gmail.com','faculty123','faculty','default.png','+91 98111 22334','2026-11-17 10:43:57'),
(3,'Prof. Ananya Verma','ananya.verma@college.edu','faculty123','faculty','default.png','+91 98222 33445','2026-11-17 10:43:57'),
(4,'Dr. Sunita Deshmukh','sunita.deshmukh@college.edu','faculty123','faculty','default.png','+91 98333 44556','2026-11-17 10:43:57'),
(5,'Sunny Kumar','student@gmail.com','student123','student','default.png','+91 91234 56789','2026-11-17 10:43:57'),
(6,'Priya Singh','priya.singh@student.edu','student123','student','default.png','+91 92345 67890','2026-11-17 10:43:57'),
(7,'Rahul Patel','rahul.patel@student.edu','student123','student','default.png','+91 93456 78901','2026-11-17 10:43:57'),
(8,'Sneha Kapoor','sneha.kapoor@student.edu','student123','student','default.png','+91 94567 89012','2026-11-17 10:43:57'),
(9,'Dr. Vikram Malhotra','vikram.malhotra@college.edu','faculty123','faculty','default.png','+91 98101 10001','2026-11-17 10:43:57'),
(10,'Prof. Neha Sen','neha.sen@college.edu','faculty123','faculty','default.png','+91 98101 10002','2026-11-17 10:43:57'),
(11,'Dr. Arvind Swaminathan','arvind.swaminathan@college.edu','faculty123','faculty','default.png','+91 98101 10003','2026-11-17 10:43:57'),
(12,'Dr. Meenakshi Sundaram','meenakshi.sundaram@college.edu','faculty123','faculty','default.png','+91 98101 10004','2026-11-17 10:43:57'),
(13,'Dr. Harish Chandra','harish.chandra@college.edu','faculty123','faculty','default.png','+91 98101 10005','2026-11-17 10:43:57'),
(14,'Dr. Siddharth Nair','siddharth.nair@college.edu','faculty123','faculty','default.png','+91 98101 10006','2026-11-17 10:43:57'),
(15,'Dr. Kavita Raman','kavita.raman@college.edu','faculty123','faculty','default.png','+91 98101 10007','2026-11-17 10:43:57'),
(16,'Dr. K. Radhakrishnan','radhakrishnan@college.edu','faculty123','faculty','default.png','+91 98101 10008','2026-11-17 10:43:57'),
(17,'Dr. Shalini Mukherjee','shalini.mukherjee@college.edu','faculty123','faculty','default.png','+91 98101 10009','2026-11-17 10:43:57'),
(18,'Dr. Pradeep Joshi','pradeep.joshi@college.edu','faculty123','faculty','default.png','+91 98101 10010','2026-11-17 10:43:57'),
(19,'Prof. Rakesh Jhunjhunwala','rakesh.finance@college.edu','faculty123','faculty','default.png','+91 98101 10011','2026-11-17 10:43:57'),
(20,'Dr. Amartya Banerjee','amartya.banerjee@college.edu','faculty123','faculty','default.png','+91 98101 10012','2026-11-17 10:43:57'),
(21,'Prof. Fali Nariman','fali.nariman@college.edu','faculty123','faculty','default.png','+91 98101 10013','2026-11-17 10:43:57'),
(22,'Dr. Swati Piramal','swati.piramal@college.edu','faculty123','faculty','default.png','+91 98101 10014','2026-11-17 10:43:57'),
(23,'Prof. B.V. Doshi','bv.doshi@college.edu','faculty123','faculty','default.png','+91 98101 10015','2026-11-17 10:43:57'),
(24,'Dr. Vandana Shiva','vandana.shiva@college.edu','faculty123','faculty','default.png','+91 98101 10016','2026-11-17 10:43:57'),
(25,'Dr. Manjul Bhargava','manjul.bhargava@college.edu','faculty123','faculty','default.png','+91 98101 10017','2026-11-17 10:43:57'),
(26,'Dr. Ashoke Sen','ashoke.sen@college.edu','faculty123','faculty','default.png','+91 98101 10018','2026-11-17 10:43:57'),
(27,'Dr. C.N.R. Rao','cnr.rao@college.edu','faculty123','faculty','default.png','+91 98101 10019','2026-11-17 10:43:57'),
(28,'Dr. M.S. Swaminathan','ms.swaminathan@college.edu','faculty123','faculty','default.png','+91 98101 10020','2026-11-17 10:43:57'),
(29,'Dr. Sunita Narain','sunita.narain@college.edu','faculty123','faculty','default.png','+91 98101 10021','2026-11-17 10:43:57'),
(30,'Prof. Prannoy Roy','prannoy.roy@college.edu','faculty123','faculty','default.png','+91 98101 10022','2026-11-17 10:43:57'),
(31,'Prof. Satyajit Sen','satyajit.sen@college.edu','faculty123','faculty','default.png','+91 98101 10023','2026-11-17 10:43:57'),
(32,'Chef Sanjeev Oberoi','sanjeev.oberoi@college.edu','faculty123','faculty','default.png','+91 98101 10024','2026-11-17 10:43:57'),
(33,'Dr. Florence Thomas','florence.thomas@college.edu','faculty123','faculty','default.png','+91 98101 10025','2026-11-17 10:43:57'),
(34,'Coach Pullela Gopichand','pullela.gopichand@college.edu','faculty123','faculty','default.png','+91 98101 10026','2026-11-17 10:43:57'),
(35,'Dr. Gayatri Spivak','gayatri.spivak@college.edu','faculty123','faculty','default.png','+91 98101 10027','2026-11-17 10:43:57'),
(36,'Dr. Krishna Kumar','krishna.kumar@college.edu','faculty123','faculty','default.png','+91 98101 10028','2026-11-17 10:43:57'),
(37,'Ustad Zakir Hussain','zakir.hussain@college.edu','faculty123','faculty','default.png','+91 98101 10029','2026-11-17 10:43:57'),
(38,'Rohan Sharma','rohan.sharma@student.edu','student123','student','default.png','+91 97100 20001','2026-11-17 10:43:57'),
(39,'Ananya Gupta','ananya.gupta@student.edu','student123','student','default.png','+91 97100 20002','2026-11-17 10:43:57'),
(40,'Vikram Singh','vikram.singh@student.edu','student123','student','default.png','+91 97100 20003','2026-11-17 10:43:57'),
(41,'Neha Patel','neha.patel@student.edu','student123','student','default.png','+91 97100 20004','2026-11-17 10:43:57'),
(42,'Amit Joshi','amit.joshi@student.edu','student123','student','default.png','+91 97100 20005','2026-11-17 10:43:57'),
(43,'Pooja Reddy','pooja.reddy@student.edu','student123','student','default.png','+91 97100 20006','2026-11-17 10:43:57'),
(44,'Karan Verma','karan.verma@student.edu','student123','student','default.png','+91 97100 20007','2026-11-17 10:43:57'),
(45,'Meera Iyer','meera.iyer@student.edu','student123','student','default.png','+91 97100 20008','2026-11-17 10:43:57'),
(46,'Aditya Saxena','aditya.saxena@student.edu','student123','student','default.png','+91 97100 20009','2026-11-17 10:43:57'),
(47,'Divya Nair','divya.nair@student.edu','student123','student','default.png','+91 97100 20010','2026-11-17 10:43:57'),
(48,'Harsh Vardhan','harsh.vardhan@student.edu','student123','student','default.png','+91 97100 20011','2026-11-17 10:43:57'),
(49,'Shreya Ghoshal','shreya.ghoshal@student.edu','student123','student','default.png','+91 97100 20012','2026-11-17 10:43:57'),
(50,'Kunal Bhatia','kunal.bhatia@student.edu','student123','student','default.png','+91 97100 20013','2026-11-17 10:43:57'),
(51,'Tanvi Desai','tanvi.desai@student.edu','student123','student','default.png','+91 97100 20014','2026-11-17 10:43:57'),
(52,'Varun Kashyap','varun.kashyap@student.edu','student123','student','default.png','+91 97100 20015','2026-11-17 10:43:57'),
(53,'Ritu Choudhary','ritu.choudhary@student.edu','student123','student','default.png','+91 97100 20016','2026-11-17 10:43:57'),
(54,'Abhishek Roy','abhishek.roy@student.edu','student123','student','default.png','+91 97100 20017','2026-11-17 10:43:57'),
(55,'Simran Kaur','simran.kaur@student.edu','student123','student','default.png','+91 97100 20018','2026-11-17 10:43:57'),
(56,'Manav Trivedi','manav.trivedi@student.edu','student123','student','default.png','+91 97100 20019','2026-11-17 10:43:57'),
(57,'Ayesha Siddiqui','ayesha.siddiqui@student.edu','student123','student','default.png','+91 97100 20020','2026-11-17 10:43:57'),
(58,'Nikhil Kulkarni','nikhil.kulkarni@student.edu','student123','student','default.png','+91 97100 20021','2026-11-17 10:43:57'),
(59,'Pallavi Sen','pallavi.sen@student.edu','student123','student','default.png','+91 97100 20022','2026-11-17 10:43:57'),
(60,'Gaurav Mathur','gaurav.mathur@student.edu','student123','student','default.png','+91 97100 20023','2026-11-17 10:43:57'),
(61,'Nandini Menon','nandini.menon@student.edu','student123','student','default.png','+91 97100 20024','2026-11-17 10:43:57'),
(62,'Rohit Pandey','rohit.pandey@student.edu','student123','student','default.png','+91 97100 20025','2026-11-17 10:43:57'),
(63,'Deepa Pillai','deepa.pillai@student.edu','student123','student','default.png','+91 97100 20026','2026-11-17 10:43:57'),
(64,'Mayank Agarwal','mayank.agarwal@student.edu','student123','student','default.png','+91 97100 20027','2026-11-17 10:43:57'),
(65,'Natasha Das','natasha.das@student.edu','student123','student','default.png','+91 97100 20028','2026-11-17 10:43:57'),
(66,'Chetan Anand','chetan.anand@student.edu','student123','student','default.png','+91 97100 20029','2026-11-17 10:43:57'),
(67,'Alok Mishra','alok.mishra@student.edu','student123','student','default.png','+91 97100 20030','2026-11-17 10:43:57'),
(68,'Barkha Dutt','barkha.dutt@student.edu','student123','student','default.png','+91 97100 20031','2026-11-17 10:43:57');
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
