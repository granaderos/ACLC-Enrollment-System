-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 21, 2017 at 07:28 AM
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
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `curriculum` varchar(16) DEFAULT NULL,
  `division` varchar(16) DEFAULT NULL,
  `sectionMax` int(2) DEFAULT NULL,
  `sectionMin` int(2) DEFAULT NULL,
  `year` int(1) DEFAULT NULL,
  `sem` int(1) DEFAULT NULL,
  `sy` varchar(16) DEFAULT NULL,
  `preregStatus` int(1) DEFAULT NULL,
  `pEncodingStatus` int(1) DEFAULT NULL,
  `mEncodingStatus` int(1) DEFAULT NULL,
  `pfEncodingStatus` int(1) DEFAULT NULL,
  `fEncodingStatus` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`curriculum`, `division`, `sectionMax`, `sectionMin`, `year`, `sem`, `sy`, `preregStatus`, `pEncodingStatus`, `mEncodingStatus`, `pfEncodingStatus`, `fEncodingStatus`) VALUES
('1001', 'Semestral', 45, 15, 1, 1, '2016-2017', 1, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `courseCode` varchar(8) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `units` int(1) DEFAULT NULL,
  `labUnits` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`courseCode`, `description`, `units`, `labUnits`) VALUES
('BA331B', 'Perform contact center services', 4, 5),
('BA332A', 'Journalize transactions', 4, 1),
('BA333A', 'Post transactions (17 hrs)', 5, 1),
('BA334A', 'Prepare trial balance (34 hrs)', 5, 1),
('BA335A', 'Prepare financial reports (Lec: 34 hrs; Lab: 51 hrs)', 5, 1),
('BA336B', 'Review internal control systems', 3, 0),
('BA337A', 'Perform events management services (Lec: 17 hrs; Lab: 51 hrs)', 3, 1),
('BA341B', 'Process and maintain workplace information (17 hrs)', 3, 0),
('BA342A', 'Organize schedules, business travel & associated itinerary (Lec: 34 hrs)', 3, 1),
('BA700B', 'On-The-Job Training (OJT)', 3, 0),
('CS101', 'Comp Fundamentals w/ Business Applications Software', 3, 1),
('CS101A', 'Computer Fundamentals with MS Application', 3, 1),
('CS102', 'Introduction to Information Systems', 3, 1),
('CS103', 'Internet Technologies ', 3, 1),
('CS301', 'Introduction to Programming (C Language)', 3, 1),
('CS302', 'Computer Programming 1 (C++)', 3, 1),
('CS303 ', 'Digital Designs ', 3, 1),
('CS305', 'Computer Organization and Assembly Language ', 3, 1),
('dsds', 'dsds', 3, 0),
('fgbfgh', 'gfhgfhfg', 3, 0),
('GE 521A', 'Physical Education 1', 2, 0),
('GE111', 'Communication Skills 1', 3, 0),
('GE111A', 'Communication Skills 1', 3, 0),
('GE112', 'Communication Skills 2', 3, 0),
('GE113', 'Speech Communication 1', 3, 0),
('GE116', 'Technical, Scientific and Business English ', 3, 0),
('GE121', 'Komunikasyon sa Akademikong Filipino', 3, 0),
('GE121A', 'Komunikasyon sa Akademikong Filipino', 3, 0),
('GE122', 'Pagbasa at Pagsulat Tungo sa Pananaliksik', 3, 0),
('GE141', 'Introduction to Philosophy with Logic', 3, 0),
('GE141A', 'Introduction to Philosophy with Logic', 3, 0),
('GE211', 'College Algebra', 3, 0),
('GE211A', 'College Algebra', 3, 0),
('GE212', 'Trigonometry', 3, 0),
('GE213', 'Statistics and Probability', 3, 0),
('GE313', 'Physical Science ', 3, 0),
('GE411', 'General Psychology', 3, 0),
('GE411A', 'General Psychology with Drug Education', 3, 0),
('GE413', 'Phil. History, Politics, Governance & Constitution', 3, 0),
('GE511', 'Euthenics 1', 1, 0),
('GE511A', 'Euthenics 1', 1, 0),
('GE512 ', 'Euthenics 2', 1, 0),
('GE521', 'Physical Education 1', 2, 0),
('GE521A', 'Physical Education 1', 2, 0),
('GE522B', 'Physical Education 2', 2, 0),
('GE531', 'National Service Training Program 1', 3, 0),
('GE531A', 'National Service Training Program 1', 3, 0),
('GE532', 'National Service Training Program 2', 3, 0),
('TC311A', 'Lead workplace communication', 3, 0),
('TC312B', 'Lead small teams (17 hrs)', 3, 0),
('TC313B', 'Develop and practice negotiation skills (17 hrs)', 3, 0),
('TC314A', 'Solve problems related to work activities (17 hrs)', 3, 0),
('TC315A', 'Use mathematical concepts and techniques', 3, 0),
('TC316A', 'Use relevant technologies (17 hrs)', 3, 0),
('TC321B', 'Maintain an effective relationship with clients (46 hrs)', 3, 0),
('TC322B', 'Manage own performance (5 hrs)', 3, 0),
('TC421A', 'Apply quality standards (17 hrs)', 3, 0),
('TC422A', 'Perform Computer Operations', 2, 1);

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
(69, 'ACT', 'GE111', 1, 1, '1001'),
(70, 'ACT', 'GE141', 1, 1, '1001'),
(71, 'ACT', 'GE411', 1, 1, '1001'),
(72, 'ACT', 'GE211', 1, 1, '1001'),
(73, 'ACT', 'CS101', 1, 1, '1001'),
(74, 'ACT', 'GE511', 1, 1, '1001'),
(75, 'ACT', 'GE112', 1, 2, '1001'),
(76, 'ACT', 'GE121', 1, 2, '1001'),
(77, 'ACT', 'GE212', 1, 2, '1001'),
(78, 'ACT', 'CS102', 1, 2, '1001'),
(79, 'ACT', 'CS301', 1, 2, '1001'),
(80, 'ACT', 'GE413', 1, 2, '1001'),
(81, 'BSIS', 'GE111A', 1, 1, '1001'),
(82, 'BSIS', 'GE121A', 1, 1, '1001'),
(83, 'BSIS', 'GE141A', 1, 1, '1001'),
(84, 'BSIS', 'GE411A', 1, 1, '1001'),
(85, 'BSIS', 'GE211A', 1, 1, '1001'),
(86, 'BSIS', 'CS101A', 1, 1, '1001'),
(87, 'BSIS', 'GE511A', 1, 1, '1001'),
(88, 'BSIS', 'GE521A', 1, 1, '1001'),
(89, 'BSIS', 'GE531A', 1, 1, '1001'),
(90, 'BSIS', 'fgbfgh', 1, 1, '1001'),
(91, 'BSIS', 'dsds', 1, 1, '1001'),
(92, 'BOAS', 'TC311A', 1, 1, '1001'),
(93, 'BOAS', 'TC315A', 1, 1, '1001'),
(94, 'BOAS', 'BA332A', 1, 1, '1001'),
(95, 'BOAS', 'TC422A', 1, 1, '1001'),
(96, 'BOAS', 'TC314A', 1, 1, '1001'),
(97, 'BOAS', 'TC316A', 1, 1, '1001'),
(98, 'BOAS', 'TC421A', 1, 1, '1001'),
(99, 'BOAS', 'GE 521A', 1, 1, '1001'),
(100, 'BOAS', 'TC321B', 1, 2, '1001'),
(101, 'BOAS', 'TC322B', 1, 2, '1001'),
(102, 'BOAS', 'BA331B', 1, 2, '1001'),
(103, 'BOAS', 'TC312B', 1, 2, '1001'),
(104, 'BOAS', 'TC313B', 1, 2, '1001'),
(105, 'BOAS', 'BA341B', 1, 2, '1001'),
(106, 'BOAS', 'GE522B', 1, 2, '1001'),
(107, 'BOAS', 'BA337A', 2, 1, '1001'),
(108, 'BOAS', 'BA342A', 2, 1, '1001'),
(109, 'BOAS', 'BA333A', 2, 1, '1001'),
(110, 'BOAS', 'BA334A', 2, 1, '1001'),
(111, 'BOAS', 'BA335A', 2, 1, '1001'),
(112, 'BOAS', 'BA336B', 2, 2, '1001'),
(113, 'BOAS', 'BA700B', 2, 2, '1001');

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

--
-- Dumping data for table `coursestoprogramstrimestral`
--

INSERT INTO `coursestoprogramstrimestral` (`ctptId`, `progCode`, `courseCode`, `year`, `semester`, `curriculumYear`) VALUES
(1, 'ACT', 'GE111', 1, 1, '1001'),
(2, 'ACT', 'GE141', 1, 1, '1001'),
(3, 'ACT', 'GE411', 1, 1, '1001'),
(4, 'ACT', 'GE211', 1, 1, '1001'),
(5, 'ACT', 'CS101', 1, 1, '1001'),
(6, 'ACT', 'GE511', 1, 1, '1001'),
(7, 'BSCS', 'GE111', 1, 1, '1001'),
(8, 'BSCS', 'GE141', 1, 1, '1001'),
(9, 'BSCS', 'GE411 ', 1, 1, '1001'),
(10, 'BSCS', 'GE211', 1, 1, '1001'),
(11, 'BSCS', 'CS101', 1, 1, '1001'),
(12, 'BSCS', 'GE511', 1, 1, '1001'),
(13, 'BSCS', 'GE112', 1, 2, '1001'),
(14, 'BSCS', 'GE121', 1, 2, '1001'),
(15, 'BSCS', 'GE212', 1, 2, '1001'),
(16, 'BSCS', 'CS102', 1, 2, '1001'),
(17, 'BSCS', 'CS301', 1, 2, '1001'),
(18, 'BSCS', 'GE512 ', 1, 2, '1001'),
(19, 'BSCS', 'GE531', 1, 2, '1001'),
(20, 'BSCS', 'GE113', 1, 3, '1001'),
(21, 'BSCS', 'GE122', 1, 3, '1001'),
(22, 'BSCS', 'GE213', 1, 3, '1001'),
(23, 'BSCS', 'CS302', 1, 3, '1001'),
(24, 'BSCS', 'CS303 ', 1, 3, '1001'),
(25, 'BSCS', 'GE521', 1, 3, '1001'),
(26, 'BSCS', 'GE532', 1, 3, '1001');

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
(2, '1001', 'This is for old curriculum.'),
(3, '1002', 'This is for new curriculum.');

-- --------------------------------------------------------

--
-- Table structure for table `days`
--

CREATE TABLE `days` (
  `id` int(11) NOT NULL,
  `day` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `days`
--

INSERT INTO `days` (`id`, `day`) VALUES
(1, 'Sunday'),
(2, 'Monday'),
(3, 'Tuesday'),
(4, 'Wednesday'),
(5, 'Thursday'),
(6, 'Friday'),
(7, 'Saturday');

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
-- Table structure for table `miscellaneous`
--

CREATE TABLE `miscellaneous` (
  `miscId` int(11) NOT NULL,
  `misc` varchar(255) DEFAULT NULL,
  `amount` varchar(16) DEFAULT NULL,
  `toWhom` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `miscellaneous`
--

INSERT INTO `miscellaneous` (`miscId`, `misc`, `amount`, `toWhom`) VALUES
(1, 'Computer Lab.', '1000', 'all'),
(2, 'Student Council Fee', '35', 'all'),
(3, 'Air Conditioning', '800', 'all'),
(4, 'Guidance and Counseling Fee', '400', 'all'),
(5, 'Medical and Dental Fee', '580', 'all'),
(7, 'gdgdfg', '3', 'all'),
(9, 'Internet Fee', '526', 'ACT'),
(10, 'hjhgjgh', '878', 'all');

-- --------------------------------------------------------

--
-- Table structure for table `preregistration`
--

CREATE TABLE `preregistration` (
  `preId` int(11) NOT NULL,
  `studentId` int(11) DEFAULT NULL,
  `courseCode` varchar(8) DEFAULT NULL,
  `sectionId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `preregistration`
--

INSERT INTO `preregistration` (`preId`, `studentId`, `courseCode`, `sectionId`) VALUES
(1, 170001, 'GE411', 0),
(2, 170001, 'GE211', 0),
(3, 170001, 'CS101', 0),
(4, 170001, 'GE511', 0),
(5, 170001, 'GE111', 0),
(6, 170001, 'GE141', 0);

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
('GE112', 'GE111'),
('CS102', 'CS101'),
('GE512 ', 'GE511'),
('GE113', 'GE112'),
('GE122', 'GE121'),
('GE213', 'GE211'),
('CS302', 'CS301'),
('CS303 ', 'CS101'),
('GE521', 'GE111'),
('GE521', ''),
('GE532', 'GE531'),
('GE313', 'GE211'),
('CS103', 'CS101'),
('CS305', 'GE111'),
('CS305', '');

-- --------------------------------------------------------

--
-- Table structure for table `professorSchedule`
--

CREATE TABLE `professorSchedule` (
  `psId` int(11) NOT NULL,
  `profId` int(11) DEFAULT NULL,
  `sectionId` int(11) DEFAULT NULL,
  `courseCode` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `professorSchedule`
--

INSERT INTO `professorSchedule` (`psId`, `profId`, `sectionId`, `courseCode`) VALUES
(5, 26, 1, 'GE111'),
(7, 26, 2, 'GE111'),
(8, 30, 3, 'TC311A'),
(26, 31, 3, 'TC315A'),
(27, 30, 3, 'BA332A'),
(28, 26, 3, 'TC422A');

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
('ACT', 'Associate in Computer Technology'),
('BOAS', 'Diploma in Business and Office Administration Services'),
('BOIS', 'Diploma in Business and Office Information Services'),
('BSCS', 'Bachelor of Science in Computer Science'),
('BSHRM', 'Bachelor of Science in Hotel and Restaurant Management'),
('BSIS', 'Bachelor of Science in Information Systems'),
('BSIT', 'Bachelor of Science in Information Technology'),
('BSTM', 'Bachelor of Science in Tourism Management'),
('DSD', 'Diploma in Software Development'),
('HRS', 'Diploma in Hotel and Restaurant Services'),
('NTT', 'Diploma in Networking and Telecommunications Technology'),
('TS', 'Diploma in Tourism Services');

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
(7, '2x2 ID Picture', 'students'),
(8, 'NSO Birth Certificate', 'students'),
(9, 'Form 137', 'newStudent'),
(10, 'Good Moral', 'students');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `scheduleId` int(11) NOT NULL,
  `sectionId` int(11) DEFAULT NULL,
  `courseCode` varchar(16) DEFAULT NULL,
  `timeStart` varchar(8) DEFAULT NULL,
  `timeEnd` varchar(16) DEFAULT NULL,
  `day` varchar(9) DEFAULT NULL,
  `room` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`scheduleId`, `sectionId`, `courseCode`, `timeStart`, `timeEnd`, `day`, `room`) VALUES
(54, 1, 'GE111', '07:00 AM', '07:30 AM', 'Monday', 'RM1001'),
(55, 2, 'GE111', '07:00 AM', '07:30 AM', 'Monday', 'RM1001'),
(56, 2, 'GE111', '07:00 AM', '07:30 AM', 'Wednesday', 'RM1001'),
(57, 2, 'GE111', '07:00 AM', '07:30 AM', 'Friday', 'RM1001'),
(58, 2, 'GE111', '08:30 AM', '10:00 AM', 'Monday', 'RM1001'),
(59, 2, 'GE141', '07:00 AM', '07:30 AM', 'Monday', 'RM1001'),
(60, 2, 'GE141', '07:00 AM', '07:30 AM', 'Wednesday', 'RM1001'),
(61, 2, 'GE141', '08:30 AM', '10:30 AM', 'Monday', 'RM1001'),
(62, 3, 'TC311A', '07:00 AM', '09:30 AM', 'Monday', 'RM1001'),
(63, 3, 'TC311A', '11:30 AM', '03:30 PM', 'Monday', 'RM1001'),
(64, 3, 'TC315A', '07:30 AM', '09:30 AM', 'Tuesday', 'RM1001'),
(65, 3, 'BA332A', '07:30 AM', '09:30 AM', 'Wednesday', 'RM1001'),
(66, 3, 'BA332A', '07:30 AM', '09:30 AM', 'Thursday', 'RM1001'),
(67, 3, 'TC422A', '07:30 AM', '09:30 AM', 'Sunday', 'RM2002'),
(68, 3, 'TC422A', '11:00 AM', '06:30 PM', 'Sunday', 'RM2002'),
(69, 3, 'TC314A', '07:30 AM', '02:30 PM', 'Friday', 'RM2002'),
(70, 3, 'TC314A', '07:30 AM', '08:00 AM', 'Friday', 'RM1002');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `sectionId` int(11) NOT NULL,
  `sectionCode` varchar(50) DEFAULT NULL,
  `programCode` varchar(50) DEFAULT NULL,
  `sy` varchar(16) DEFAULT NULL,
  `year` int(1) DEFAULT NULL,
  `semester` int(1) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `type` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`sectionId`, `sectionCode`, `programCode`, `sy`, `year`, `semester`, `status`, `type`) VALUES
(1, 'ACT11KA1', 'ACT', '2016-2017', 1, 1, 1, 'block'),
(2, 'ACTKB1', 'ACT', '2016-2017', 1, 1, 1, 'block'),
(3, 'BOAS11KB1', 'BOAS', '2016-2017', 1, 1, 0, 'block');

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
(1, 'Perpinosa', 'Marejean', 'Granaderos', 'granaderos', '*1FD919BADCA00EA6E1517C48061B5D0654470198', 'admin', 0),
(26, 'Marlon', 'Francisco', 'Arcilla', 'marlonmarlon', '*1FD919BADCA00EA6E1517C48061B5D0654470198', 'instructor', 0),
(27, 'Kenneth', 'Francisco', 'Santos', 'kennethkenneth', '*D71495E5EFA02F21DA9F8DBCF20B86ACF1AF2ACD', 'dean', 0),
(28, 'Vincent', 'Francisco', 'Espinosa', 'vincentvincent', '*D1BB2CC6E11BD610B0E7DA0A469D104EFC93D74A', 'registrar', 0),
(29, 'Adrian', 'Francisco', 'Acevedo', 'adrianadrian', '*0D919BF732D1C120EC757AB72022F31896A54185', 'cashier', 0),
(30, 'Dave Michael', 'Francisco', 'Martinez', 'michaelmichael', '*36AFA01280877DF11F08EBC7D6E2F1D161355A96', 'instructor', 0),
(31, 'Joemahrie', 'Francisco', 'Sivila', 'joamahriejoamahrie', '*15B8BA19FAA2F96C64D787D1DE4D9B2406D0BEFA', 'instructor', 0),
(32, 'Nature', 'Sprinjg', 'Green', 'naturespring', '*CE07DFED4F06C9A17295D3C0E94CC845F1E2997C', 'HR', 0);

-- --------------------------------------------------------

--
-- Table structure for table `studentBalance`
--

CREATE TABLE `studentBalance` (
  `sbId` int(11) NOT NULL,
  `studentId` int(11) DEFAULT NULL,
  `balance` double DEFAULT NULL,
  `amountToPay` double DEFAULT NULL,
  `sy` varchar(16) DEFAULT NULL,
  `sem` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `studentBalance`
--

INSERT INTO `studentBalance` (`sbId`, `studentId`, `balance`, `amountToPay`, `sy`, `sem`) VALUES
(75, 170001, -2981, 19398165.296, '2016-2017', 1),
(76, 170009, 0, 18622, '2016-2017', 1);

-- --------------------------------------------------------

--
-- Table structure for table `studentgrades`
--

CREATE TABLE `studentgrades` (
  `studentId` int(11) DEFAULT NULL,
  `courseCode` varchar(8) DEFAULT NULL,
  `sy` varchar(16) DEFAULT NULL,
  `year` int(1) DEFAULT NULL,
  `sem` int(1) DEFAULT NULL,
  `pGrade` varchar(5) DEFAULT NULL,
  `mGrade` varchar(5) DEFAULT NULL,
  `pfGrade` varchar(5) DEFAULT NULL,
  `fGrade` varchar(5) DEFAULT NULL,
  `sgId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `studentgrades`
--

INSERT INTO `studentgrades` (`studentId`, `courseCode`, `sy`, `year`, `sem`, `pGrade`, `mGrade`, `pfGrade`, `fGrade`, `sgId`) VALUES
(170001, 'GE111', '2016-2017', 1, 1, '0', '0', '0', '0', 19),
(170001, 'GE141', '2016-2017', 1, 1, '0', '0', '0', '0', 20),
(170001, 'GE411', '2016-2017', 1, 1, '0', '0', '0', '0', 21),
(170001, 'GE211', '2016-2017', 1, 1, '0', '0', '0', '0', 22),
(170001, 'CS101', '2016-2017', 1, 1, '0', '0', '0', '0', 23),
(170001, 'GE511', '2016-2017', 1, 1, '0', '0', '0', '0', 24),
(170009, 'GE111', '2016-2017', 1, 1, '0', '0', '0', '0', 25),
(170009, 'GE141', '2016-2017', 1, 1, '0', '0', '0', '0', 26),
(170009, 'GE411', '2016-2017', 1, 1, '0', '0', '0', '0', 27),
(170009, 'GE211', '2016-2017', 1, 1, '0', '0', '0', '0', 28),
(170009, 'CS101', '2016-2017', 1, 1, '0', '0', '0', '0', 29),
(170009, 'GE511', '2016-2017', 1, 1, '0', '0', '0', '0', 30);

-- --------------------------------------------------------

--
-- Table structure for table `studentPhoto`
--

CREATE TABLE `studentPhoto` (
  `photoId` int(11) NOT NULL,
  `studentId` int(11) DEFAULT NULL,
  `photoName` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `studentPhoto`
--

INSERT INTO `studentPhoto` (`photoId`, `studentId`, `photoName`) VALUES
(1, 170001, '170001.jpg'),
(2, 170009, 'mj.png');

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
(170001, 10, 1, NULL, '2017-03-14'),
(170001, 7, 1, NULL, '2017-03-16'),
(170009, 7, 1, NULL, '2017-03-17'),
(170009, 8, 1, NULL, '2017-03-17');

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
  `emailAddress` varchar(50) DEFAULT NULL,
  `contactNumber` varchar(11) DEFAULT NULL,
  `nationality` varchar(20) DEFAULT NULL,
  `birthday` varchar(16) DEFAULT NULL,
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
  `password` varchar(225) DEFAULT NULL,
  `gfName` varchar(16) DEFAULT NULL,
  `gmName` varchar(16) DEFAULT NULL,
  `glName` varchar(16) DEFAULT NULL,
  `gRelationship` varchar(16) DEFAULT NULL,
  `gAddress` varchar(16) DEFAULT NULL,
  `gContactNumber` varchar(16) DEFAULT NULL,
  `preregStatus` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `studentId`, `lastname`, `firstname`, `middlename`, `address`, `emailAddress`, `contactNumber`, `nationality`, `birthday`, `gender`, `placeOfBirth`, `secondarySchool`, `secDateGraduated`, `schoolLastAttended`, `schoolLastAttendedDateAttended`, `programDateCompleted`, `yearLevel`, `progCode`, `curriculum`, `dateEnrolled`, `type`, `username`, `password`, `gfName`, `gmName`, `glName`, `gRelationship`, `gAddress`, `gContactNumber`, `preregStatus`) VALUES
(8, '170001', 'Lalim', 'Ryan Matthew', 'Cordero', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'ACT', '1001', '2017-03-14', 'newStudent', 'LalimRo', '*5F1F70F43E51312CDD1D1782BB517E6FA514D3BF', NULL, NULL, NULL, NULL, NULL, NULL, -1),
(9, '170009', 'Juanillo', 'Melissa', 'Picones', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'ACT', '1001', '2017-03-17', 'newStudent', 'JuanilloMs', '*6030C5EEB202787E9D2D3A6725888597D2A6324B', NULL, NULL, NULL, NULL, NULL, NULL, -1);

-- --------------------------------------------------------

--
-- Table structure for table `studentSchedule`
--

CREATE TABLE `studentSchedule` (
  `ssId` int(11) NOT NULL,
  `studentId` int(11) DEFAULT NULL,
  `courseCode` varchar(16) DEFAULT NULL,
  `sectionId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `studentSchedule`
--

INSERT INTO `studentSchedule` (`ssId`, `studentId`, `courseCode`, `sectionId`) VALUES
(19, 170001, 'GE111', 1),
(20, 170001, 'GE141', 1),
(21, 170001, 'GE411', 1),
(22, 170001, 'GE211', 1),
(23, 170001, 'CS101', 1),
(24, 170001, 'GE511', 1),
(25, 177777, 'GE211', 1),
(26, 170009, 'GE111', 2),
(27, 170009, 'GE141', 2),
(28, 170009, 'GE411', 2),
(29, 170009, 'GE211', 2),
(30, 170009, 'CS101', 2),
(31, 170009, 'GE511', 2);

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
('BSCS', 'GE116', 2, 1, '1001'),
('BSCS', 'GE313', 2, 1, '1001'),
('BSCS', 'CS103', 2, 1, '1001'),
('BSCS', 'CS305', 2, 1, '1001');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transId` int(11) NOT NULL,
  `studentId` int(11) DEFAULT NULL,
  `paymentFor` varchar(64) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `datePaid` varchar(64) DEFAULT NULL,
  `sy` varchar(32) DEFAULT NULL,
  `sem` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transId`, `studentId`, `paymentFor`, `amount`, `datePaid`, `sy`, `sem`) VALUES
(1, 170001, 'downPayment', 3000, '2017-03-15', '2016-2017', 1),
(2, 170009, 'downPayment', 3000, '2017-03-17', '2016-2017', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tuitionFee`
--

CREATE TABLE `tuitionFee` (
  `tfId` int(11) NOT NULL,
  `feePerUnit` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tuitionFee`
--

INSERT INTO `tuitionFee` (`tfId`, `feePerUnit`) VALUES
(1, 1000);

--
-- Indexes for dumped tables
--

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
-- Indexes for table `days`
--
ALTER TABLE `days`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gradingsystem`
--
ALTER TABLE `gradingsystem`
  ADD PRIMARY KEY (`gradeId`);

--
-- Indexes for table `miscellaneous`
--
ALTER TABLE `miscellaneous`
  ADD PRIMARY KEY (`miscId`);

--
-- Indexes for table `preregistration`
--
ALTER TABLE `preregistration`
  ADD PRIMARY KEY (`preId`);

--
-- Indexes for table `prerequisites`
--
ALTER TABLE `prerequisites`
  ADD KEY `prerequisitesFKcourseCode` (`courseCode`),
  ADD KEY `prerequisitesFKprereqCourse` (`preReqCourse`);

--
-- Indexes for table `professorSchedule`
--
ALTER TABLE `professorSchedule`
  ADD PRIMARY KEY (`psId`);

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
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`scheduleId`);

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
-- Indexes for table `studentBalance`
--
ALTER TABLE `studentBalance`
  ADD PRIMARY KEY (`sbId`);

--
-- Indexes for table `studentgrades`
--
ALTER TABLE `studentgrades`
  ADD PRIMARY KEY (`sgId`),
  ADD KEY `sgTs` (`studentId`);

--
-- Indexes for table `studentPhoto`
--
ALTER TABLE `studentPhoto`
  ADD PRIMARY KEY (`photoId`);

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
-- Indexes for table `studentSchedule`
--
ALTER TABLE `studentSchedule`
  ADD PRIMARY KEY (`ssId`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transId`);

--
-- Indexes for table `tuitionFee`
--
ALTER TABLE `tuitionFee`
  ADD PRIMARY KEY (`tfId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `coursestoprogramssemestral`
--
ALTER TABLE `coursestoprogramssemestral`
  MODIFY `ctpsId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;
--
-- AUTO_INCREMENT for table `coursestoprogramstrimestral`
--
ALTER TABLE `coursestoprogramstrimestral`
  MODIFY `ctptId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `curriculum`
--
ALTER TABLE `curriculum`
  MODIFY `curId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `days`
--
ALTER TABLE `days`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `gradingsystem`
--
ALTER TABLE `gradingsystem`
  MODIFY `gradeId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `miscellaneous`
--
ALTER TABLE `miscellaneous`
  MODIFY `miscId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `preregistration`
--
ALTER TABLE `preregistration`
  MODIFY `preId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `professorSchedule`
--
ALTER TABLE `professorSchedule`
  MODIFY `psId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `requirements`
--
ALTER TABLE `requirements`
  MODIFY `requirementId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `scheduleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;
--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `sectionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staffId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `studentBalance`
--
ALTER TABLE `studentBalance`
  MODIFY `sbId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;
--
-- AUTO_INCREMENT for table `studentgrades`
--
ALTER TABLE `studentgrades`
  MODIFY `sgId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `studentPhoto`
--
ALTER TABLE `studentPhoto`
  MODIFY `photoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `studentSchedule`
--
ALTER TABLE `studentSchedule`
  MODIFY `ssId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tuitionFee`
--
ALTER TABLE `tuitionFee`
  MODIFY `tfId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
