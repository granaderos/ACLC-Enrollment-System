-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 14, 2017 at 07:37 AM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 7.0.9

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

CREATE TABLE `accounts` (
  `accId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`accId`, `userId`, `username`, `password`, `type`) VALUES
(2, 1, 'regMarejean', '*5EA2ECA27CC3619D6235B79337DCA62E4478BA5D', 'registrar');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `courseCode` varchar(8) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `units` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`courseCode`, `description`, `units`) VALUES
('a', 'a', 3),
('bn', 'bn', 3),
('bnv', 'nbv', 3),
('bvc', 'bvc', 3),
('bvn', 'nbv', 5555),
('cv', 'cv', 3),
('cvcv', 'cv', 3),
('cvvv', 'cvvv', 33),
('dfg', 'df', 4),
('f', 'f', 3),
('fd', 'fd', 3),
('fddfdffd', 'fdsfdsdsfdsfdsfds', 43),
('fdffdsfd', 'dsfdsfdsfds', 4),
('gfdgd', 'gdfg', 3),
('gfg', '3', 3),
('ghgf', 'hgfh', 3),
('ghgh', 'tytr', 3),
('ghjhgmnh', 'hgjhgmnbmnb', 3),
('hg', 'bv', 3),
('hg3', 'hgfds', 33),
('hgjh', 'jhgj', 3),
('jk', 'dfdfds', 2),
('mjnmmnb', 'mnbmnb', 3),
('mnb', 'mnb', 3),
('nb', 'mnb', 3),
('qwe', 'qwewqe', 3),
('qwqwqwq', 'qwqwqwqw', 3),
('tr', 'tr', 3),
('uytuyt', 'uytuyt', 2),
('vb', 'vb', 3),
('vd', 'vc', 3),
('vdc', 'vc', 3),
('wqewqqwe', 'wewqewe', 3);

-- --------------------------------------------------------

--
-- Table structure for table `coursestoprogramssemestral`
--

CREATE TABLE `coursestoprogramssemestral` (
  `ctpsId` int(11) NOT NULL,
  `progCode` varchar(8) DEFAULT NULL,
  `courseCode` varchar(8) DEFAULT NULL,
  `year` int(1) DEFAULT NULL,
  `semester` int(1) DEFAULT NULL,
  `curriculumYear` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `coursestoprogramssemestral`
--

INSERT INTO `coursestoprogramssemestral` (`ctpsId`, `progCode`, `courseCode`, `year`, `semester`, `curriculumYear`) VALUES
(1, 'BSA', 'qwe', 1, 1, '3847820138'),
(2, 'BSA', 'qwe', 1, 1, '3847820138'),
(3, 'BSA', 'cv', 1, 1, '3847820138'),
(4, 'BSA', 'cvcv', 1, 1, '3847820138'),
(5, 'BSA', 'cvvv', 1, 1, '3847820138'),
(6, 'BSA', 'jk', 1, 1, '3847820138'),
(7, 'BSA', 'uytuyt', 1, 1, '3847820138'),
(8, 'BSA', 'wqewqqwe', 1, 1, '3847820138'),
(9, 'BSA', 'qwqwqwq', 1, 1, '3847820138'),
(10, 'BSA', 'vb', 1, 1, '3847820138'),
(11, 'BSA', 'bn', 1, 1, '3847820138'),
(12, 'BSA', 'ghgh', 1, 1, '3847820138'),
(13, 'BSA', 'hgjh', 1, 2, '3847820138'),
(14, 'BSA', 'ghjhgmnh', 2, 1, '3847820138'),
(15, 'BSA', 'nb', 2, 2, '3847820138'),
(16, 'BSA', 'bvn', 3, 1, '3847820138'),
(17, 'BSA', 'ghgf', 3, 2, '3847820138'),
(18, 'BSA', 'mjnmmnb', 4, 1, '3847820138'),
(19, 'BSA', 'mnb', 4, 2, '3847820138');

-- --------------------------------------------------------

--
-- Table structure for table `coursestoprogramstrimestral`
--

CREATE TABLE `coursestoprogramstrimestral` (
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

CREATE TABLE `curriculum` (
  `curId` int(11) NOT NULL,
  `curriculum` varchar(10) DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `curriculum`
--

INSERT INTO `curriculum` (`curId`, `curriculum`, `description`) VALUES
(3, '3847820138', 'a1c28b430f374e8452cb6eef88e42b0c'),
(4, 'wew', 'erew');

-- --------------------------------------------------------

--
-- Table structure for table `gradingsystem`
--

CREATE TABLE `gradingsystem` (
  `gradeId` int(11) NOT NULL,
  `gradeRange` varchar(20) DEFAULT NULL,
  `description` varchar(20) DEFAULT NULL,
  `remark` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `prerequisites`
--

CREATE TABLE `prerequisites` (
  `courseCode` varchar(8) DEFAULT NULL,
  `preReqCourse` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prerequisites`
--

INSERT INTO `prerequisites` (`courseCode`, `preReqCourse`) VALUES
('rewr', 'erew'),
('fsdf', 'fdsf'),
('fsdf', ' dfdf'),
('cv', 'fdsf'),
('gjhgjgh', 'gfdg'),
('', 'q'),
('rte', 'juku'),
('eqw', 'qwewq'),
('eqw', ' q'),
('cc', 'aa'),
('dd', 'bbb'),
('v', 'x');

-- --------------------------------------------------------

--
-- Table structure for table `professors`
--

CREATE TABLE `professors` (
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

CREATE TABLE `proftocourses` (
  `profId` int(11) DEFAULT NULL,
  `courseCode` varchar(10) DEFAULT NULL,
  `sectionId` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `progCode` varchar(8) NOT NULL DEFAULT '',
  `description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`progCode`, `description`) VALUES
('BSA', 'Bachelor of Science in Acountancy'),
('CBE', 'Business'),
('CS', 'Computer Science'),
('educ', 'education'),
('EE', 'Electrical Engineering'),
('IT', 'Bachelor of Science in information Technology');

-- --------------------------------------------------------

--
-- Table structure for table `requirements`
--

CREATE TABLE `requirements` (
  `requirementId` int(11) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `requiree` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `requirements`
--

INSERT INTO `requirements` (`requirementId`, `description`, `requiree`) VALUES
(2, 'Form 137 (High School Card)', 'New Students'),
(4, 'True Copy of  Grades', 'Students'),
(5, 'NSO', 'New Students');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `sectionId` int(11) NOT NULL,
  `sectionCode` varchar(50) DEFAULT NULL,
  `programCode` varchar(50) DEFAULT NULL,
  `year` int(1) DEFAULT NULL,
  `semester` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staffId` int(11) NOT NULL,
  `lastName` varchar(20) DEFAULT NULL,
  `firstName` varchar(20) DEFAULT NULL,
  `middleName` varchar(20) DEFAULT NULL,
  `userName` varchar(20) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staffId`, `lastName`, `firstName`, `middleName`, `userName`, `password`, `type`, `status`) VALUES
(1, 'Perpinosa', 'Marejean', 'Granaderos', 'granaderos', 'granaderos', 'admin', 0),
(11, '9c0da782865abeb03abc', '51aca628cbc7bb656eeb', '5cc4c1ee957526d0249e', 'bec7ce8e77a74884dcfc', '92b1c9ac9736b82597d9e21db74af9db', '07f8bcc8ac', 0),
(12, 'c69a09446009c500b136', 'e355ec3b68d0fffc37d1', 'e7ec4c603ee70851f526', '7d1b1c142003efd0867a', '7d0d3d5c796a4c745eec6449f26e8474', '3df9a4e363', 0),
(13, '3df9a4e363f0f5f580bb', '3df9a4e363f0f5f580bb', '3df9a4e363f0f5f580bb', '5940569cd1d60781f856', '1ad833308a4abef5c1a7460e81134cef', '5940569cd1', 0),
(14, 'Registrar', 'Registrar', 'Registrar', 'registrar2', 'f4473c40', 'registrar', 0);

-- --------------------------------------------------------

--
-- Table structure for table `studentcourses`
--

CREATE TABLE `studentcourses` (
  `studentId` int(11) DEFAULT NULL,
  `courseCode` varchar(8) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `studentgrades`
--

CREATE TABLE `studentgrades` (
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

CREATE TABLE `studentpaymentdetails` (
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

CREATE TABLE `studentrequirements` (
  `studentId` int(11) DEFAULT NULL,
  `reqId` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `file` varchar(225) DEFAULT NULL,
  `dateSubmitted` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `studentrequirements`
--

INSERT INTO `studentrequirements` (`studentId`, `reqId`, `status`, `file`, `dateSubmitted`) VALUES
(170002, 2, 1, NULL, '2017-02-08'),
(170002, 4, 1, NULL, '2017-02-08'),
(170002, 5, 1, NULL, '2017-02-08');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `studentId` varchar(15) DEFAULT NULL,
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
  `progCode` varchar(8) DEFAULT NULL,
  `curriculum` varchar(15) DEFAULT NULL,
  `dateEnrolled` date DEFAULT NULL,
  `type` varchar(15) DEFAULT NULL,
  `username` varchar(25) DEFAULT NULL,
  `password` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `studentId`, `lastname`, `firstname`, `middlename`, `address`, `contactNumber`, `nationality`, `birthday`, `gender`, `placeOfBirth`, `secondarySchool`, `secDateGraduated`, `schoolLastAttended`, `schoolLastAttendedDateAttended`, `programDateCompleted`, `yearLevel`, `progCode`, `curriculum`, `dateEnrolled`, `type`, `username`, `password`) VALUES
(1, '170001', 'Perpinosa', 'Marejean', 'Granaderos', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'BSA', '3847820138', '2017-02-08', 'newStudent', 'PerpinosaMs', '3fe4505c'),
(2, '170002', 'De Castro', 'Derwin', 'Padillo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'BSA', '3847820138', '2017-02-08', 'newStudent', 'De_CastroDo', '3fe4505c');

-- --------------------------------------------------------

--
-- Table structure for table `studenttosection`
--

CREATE TABLE `studenttosection` (
  `studentId` int(11) DEFAULT NULL,
  `sectionId` int(11) DEFAULT NULL,
  `year` int(1) DEFAULT NULL,
  `semester` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tempCoursesToProgram`
--

CREATE TABLE `tempCoursesToProgram` (
  `progCode` varchar(10) DEFAULT NULL,
  `courseCode` varchar(10) DEFAULT NULL,
  `year` int(1) DEFAULT NULL,
  `semester` int(1) DEFAULT NULL,
  `curriculum` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tempCoursesToProgram`
--

INSERT INTO `tempCoursesToProgram` (`progCode`, `courseCode`, `year`, `semester`, `curriculum`) VALUES
('BSA', 'fdffdsfd', 1, 1, NULL),
('BSA', 'fddfdffdsf', 1, 1, '3847820138'),
('BSA', 'dfg', 1, 1, '3847820138'),
('BSA', 'bn', 1, 1, NULL),
('BSA', 'tr', 1, 1, '3847820138'),
('BSA', 'f', 1, 1, '3847820138'),
('BSA', 'hg3', 1, 1, '3847820138'),
('BSA', 'bnv', 1, 1, '3847820138'),
('BSA', 'fd', 1, 1, '3847820138'),
('BSA', 'vdc', 1, 1, '3847820138'),
('BSA', 'vd', 1, 1, '3847820138'),
('BSA', 'bvc', 1, 1, '3847820138'),
('BSA', 'gfg', 1, 1, '3847820138'),
('BSA', 'gfdgd', 1, 1, '3847820138'),
('BSA', 'hg', 1, 1, '3847820138'),
('BSA', 'a', 1, 1, '3847820138');

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
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staffId`);

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
  ADD PRIMARY KEY (`id`);

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
  MODIFY `accId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `coursestoprogramssemestral`
--
ALTER TABLE `coursestoprogramssemestral`
  MODIFY `ctpsId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `coursestoprogramstrimestral`
--
ALTER TABLE `coursestoprogramstrimestral`
  MODIFY `ctptId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `curriculum`
--
ALTER TABLE `curriculum`
  MODIFY `curId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
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
  MODIFY `requirementId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `sectionId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staffId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
