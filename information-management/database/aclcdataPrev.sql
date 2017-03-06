-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2016 at 11:51 AM
-- Server version: 5.6.26
-- PHP Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aclcdata`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `accId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`accId`, `userId`, `username`, `password`, `type`) VALUES
(2, 1, 'regMarejean', '*5EA2ECA27CC3619D6235B79337DCA62E4478BA5D', 'registrar');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE IF NOT EXISTS `courses` (
  `courseCode` varchar(8) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `units` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`courseCode`, `description`, `units`) VALUES
('CS101A', 'Computer Fundamentals with MS Application', 3),
('GE111A', 'Communication Skills 1', 3),
('GE121A', 'Komunikasyon sa Akademikong Filipino', 3),
('GE141A', 'Introduction to Philosophy with Logic', 3),
('GE211A', 'College Algebra', 3),
('GE411A', 'General Psychology with Drug Education', 3),
('GE511A', 'Euthenics 1', 1),
('GE521A', 'Physical Education 1', 2),
('GE531A', 'National Service Training Program 1', 3),
('IT 303', 'fdkljsfkdhs ', 3);

-- --------------------------------------------------------

--
-- Table structure for table `coursestoprogramssemestral`
--

CREATE TABLE IF NOT EXISTS `coursestoprogramssemestral` (
  `ctpsId` int(11) NOT NULL,
  `progCode` varchar(8) DEFAULT NULL,
  `courseCode` varchar(8) DEFAULT NULL,
  `year` int(1) DEFAULT NULL,
  `semester` int(1) DEFAULT NULL,
  `curriculumYear` varchar(10) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `coursestoprogramssemestral`
--

INSERT INTO `coursestoprogramssemestral` (`ctpsId`, `progCode`, `courseCode`, `year`, `semester`, `curriculumYear`) VALUES
(1, 'BSCS', 'GE111A', 1, 1, '2015'),
(2, 'BSCS', 'GE121A', 1, 1, '2015'),
(3, 'BSCS', 'GE141A', 1, 1, '2015'),
(4, 'BSCS', 'GE411A', 1, 1, '2015'),
(5, 'BSCS', 'GE211A', 1, 1, '2015'),
(6, 'BSCS', 'CS101A', 1, 1, '2015'),
(7, 'BSCS', 'GE511A', 1, 1, '2015'),
(8, 'BSCS', 'GE521A', 1, 1, '2015'),
(9, 'BSCS', 'GE531A', 1, 1, '2015'),
(10, 'CS IT', 'IT 303', 1, 1, '2015');

-- --------------------------------------------------------

--
-- Table structure for table `coursestoprogramstrimestral`
--

CREATE TABLE IF NOT EXISTS `coursestoprogramstrimestral` (
  `ctptId` int(11) NOT NULL,
  `progCode` varchar(8) DEFAULT NULL,
  `courseCode` varchar(8) DEFAULT NULL,
  `year` int(1) DEFAULT NULL,
  `semester` int(1) DEFAULT NULL,
  `curriculumYear` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `curriculum`
--

CREATE TABLE IF NOT EXISTS `curriculum` (
  `curId` int(11) NOT NULL,
  `curriculum` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gradingsystem`
--

CREATE TABLE IF NOT EXISTS `gradingsystem` (
  `gradeId` int(11) NOT NULL,
  `gradeRange` varchar(20) DEFAULT NULL,
  `description` varchar(20) DEFAULT NULL,
  `remark` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `prerequisites`
--

CREATE TABLE IF NOT EXISTS `prerequisites` (
  `courseCode` varchar(8) DEFAULT NULL,
  `preReqCourse` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `professors`
--

CREATE TABLE IF NOT EXISTS `professors` (
  `profId` int(11) NOT NULL,
  `lastname` varchar(20) DEFAULT NULL,
  `firstname` varchar(20) DEFAULT NULL,
  `middlename` varchar(20) DEFAULT NULL,
  `status` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `proftocourses`
--

CREATE TABLE IF NOT EXISTS `proftocourses` (
  `profId` int(11) DEFAULT NULL,
  `courseCode` varchar(10) DEFAULT NULL,
  `sectionId` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE IF NOT EXISTS `programs` (
  `progCode` varchar(8) NOT NULL DEFAULT '',
  `description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`progCode`, `description`) VALUES
('CS IT', 'Bachelor of Science in information Technology');

-- --------------------------------------------------------

--
-- Table structure for table `requirements`
--

CREATE TABLE IF NOT EXISTS `requirements` (
  `requirementId` int(11) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `requiree` varchar(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `requirements`
--

INSERT INTO `requirements` (`requirementId`, `description`, `requiree`) VALUES
(1, 'NSO', 'Students'),
(2, 'Form 137 (High School Card)', 'New Students'),
(3, 'NSO', 'Students'),
(4, 'True Copy of Grades', 'Students');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE IF NOT EXISTS `sections` (
  `sectionId` int(11) NOT NULL,
  `sectionCode` varchar(50) DEFAULT NULL,
  `programCode` varchar(50) DEFAULT NULL,
  `year` int(1) DEFAULT NULL,
  `semester` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `studentcourses`
--

CREATE TABLE IF NOT EXISTS `studentcourses` (
  `studentId` int(11) DEFAULT NULL,
  `courseCode` varchar(8) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `studentgrades`
--

CREATE TABLE IF NOT EXISTS `studentgrades` (
  `studentId` int(11) DEFAULT NULL,
  `courseCode` varchar(8) DEFAULT NULL,
  `year` int(1) DEFAULT NULL,
  `period` varchar(10) DEFAULT NULL,
  `grade` double DEFAULT NULL,
  `gradeDesc` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `studentpaymentdetails`
--

CREATE TABLE IF NOT EXISTS `studentpaymentdetails` (
  `studentId` int(11) DEFAULT NULL,
  `ofYear` int(1) DEFAULT NULL,
  `semester` int(1) DEFAULT NULL,
  `totalFee` double DEFAULT NULL,
  `amountPaid` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `studentrequirements`
--

CREATE TABLE IF NOT EXISTS `studentrequirements` (
  `studentId` int(11) DEFAULT NULL,
  `reqId` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `studentId` int(11) NOT NULL,
  `lastname` varchar(15) DEFAULT NULL,
  `firstname` varchar(15) DEFAULT NULL,
  `middlename` varchar(15) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `contactNumber` varchar(11) DEFAULT NULL,
  `nationality` varchar(20) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` varchar(6) DEFAULT NULL,
  `placeOfBirth` varchar(20) DEFAULT NULL,
  `secondarySchool` varchar(50) DEFAULT NULL,
  `secDateGraduated` varchar(30) DEFAULT NULL,
  `schoolLastAttended` varchar(50) DEFAULT NULL,
  `schoolLastAttendedDateAttended` varchar(30) DEFAULT NULL,
  `programDateCompleted` varchar(30) DEFAULT NULL,
  `yearLevel` int(1) DEFAULT NULL,
  `progCode` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `studenttosection`
--

CREATE TABLE IF NOT EXISTS `studenttosection` (
  `studentId` int(11) DEFAULT NULL,
  `sectionId` int(11) DEFAULT NULL,
  `year` int(1) DEFAULT NULL,
  `semester` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`accId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`courseCode`);

--
-- Indexes for table `coursestoprogramssemestral`
--
ALTER TABLE `coursestoprogramssemestral`
  ADD PRIMARY KEY (`ctpsId`),
  ADD KEY `coursestoprogramssemestralFK` (`progCode`);

--
-- Indexes for table `coursestoprogramstrimestral`
--
ALTER TABLE `coursestoprogramstrimestral`
  ADD PRIMARY KEY (`ctptId`),
  ADD KEY `coursestoprogramstrimestralFK` (`progCode`);

--
-- Indexes for table `curriculum`
--
ALTER TABLE `curriculum`
  ADD PRIMARY KEY (`curId`);

--
-- Indexes for table `gradingsystem`
--
ALTER TABLE `gradingsystem`
  ADD PRIMARY KEY (`gradeId`);

--
-- Indexes for table `prerequisites`
--
ALTER TABLE `prerequisites`
  ADD KEY `prerequisitesFKcourseCode` (`courseCode`),
  ADD KEY `prerequisitesFKprereqCourse` (`preReqCourse`);

--
-- Indexes for table `professors`
--
ALTER TABLE `professors`
  ADD PRIMARY KEY (`profId`);

--
-- Indexes for table `proftocourses`
--
ALTER TABLE `proftocourses`
  ADD KEY `profId` (`profId`),
  ADD KEY `courseCode` (`courseCode`),
  ADD KEY `sectionId` (`sectionId`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`progCode`);

--
-- Indexes for table `requirements`
--
ALTER TABLE `requirements`
  ADD PRIMARY KEY (`requirementId`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`sectionId`),
  ADD KEY `programCode` (`programCode`);

--
-- Indexes for table `studentcourses`
--
ALTER TABLE `studentcourses`
  ADD KEY `studentcoursesFKcourseCode` (`courseCode`),
  ADD KEY `studentcoursesFKstudentId` (`studentId`);

--
-- Indexes for table `studentgrades`
--
ALTER TABLE `studentgrades`
  ADD KEY `sgTs` (`studentId`),
  ADD KEY `studentgradesFK` (`gradeDesc`);

--
-- Indexes for table `studentpaymentdetails`
--
ALTER TABLE `studentpaymentdetails`
  ADD KEY `studentPaymentDetailsFK` (`studentId`);

--
-- Indexes for table `studentrequirements`
--
ALTER TABLE `studentrequirements`
  ADD KEY `studentrequirementsFKstudentId` (`studentId`),
  ADD KEY `studentRequirementsFKreqId` (`reqId`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`studentId`);

--
-- Indexes for table `studenttosection`
--
ALTER TABLE `studenttosection`
  ADD KEY `studentId` (`studentId`),
  ADD KEY `sectionId` (`sectionId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `accId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `coursestoprogramssemestral`
--
ALTER TABLE `coursestoprogramssemestral`
  MODIFY `ctpsId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `coursestoprogramstrimestral`
--
ALTER TABLE `coursestoprogramstrimestral`
  MODIFY `ctptId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `curriculum`
--
ALTER TABLE `curriculum`
  MODIFY `curId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gradingsystem`
--
ALTER TABLE `gradingsystem`
  MODIFY `gradeId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `professors`
--
ALTER TABLE `professors`
  MODIFY `profId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `requirements`
--
ALTER TABLE `requirements`
  MODIFY `requirementId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `sectionId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `studentId` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
