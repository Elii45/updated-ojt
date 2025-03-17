-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Mar 17, 2025 at 09:52 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ojt`
--

-- --------------------------------------------------------

--
-- Table structure for table `cto_requests`
--

CREATE TABLE `cto_requests` (
  `request_id` int(11) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `office` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `filing_date` date NOT NULL,
  `earned_hours` int(11) NOT NULL,
  `cto_date` date NOT NULL,
  `applied_hours` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cto_requests`
--

INSERT INTO `cto_requests` (`request_id`, `employee_name`, `office`, `position`, `filing_date`, `earned_hours`, `cto_date`, `applied_hours`) VALUES
(483, 'Chabelita', 'Office of the Governor (GO)', 'Intern', '2025-03-11', 5, '2025-03-11', 2),
(484, 'user', 'Office of the Vice Governor - Personal Staff', 'user', '2025-03-11', 1, '2025-03-11', 1),
(485, '1', 'Office of the Secretary to the Sangguniang Panlalawigan (OSSP)', '1', '2025-03-12', 1, '2025-03-12', 1),
(486, 'bb', 'Office of the Vice Governor (VGO)', 'bb', '2025-03-12', 1, '2025-03-12', 1);

-- --------------------------------------------------------

--
-- Table structure for table `employeedetails`
--

CREATE TABLE `employeedetails` (
  `id` int(11) NOT NULL,
  `office` varchar(255) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `filing_date` date NOT NULL,
  `position` varchar(255) NOT NULL,
  `salary` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employeedetails`
--

INSERT INTO `employeedetails` (`id`, `office`, `last_name`, `first_name`, `middle_name`, `filing_date`, `position`, `salary`) VALUES
(1, 'HR Department', 'Doe', 'John', 'A', '2025-03-10', 'HR Manager', 50000.00),
(108, '4', 'Dela Cruz', 'renz', 'culalic', '2025-03-17', 'Intern', 0.00),
(109, '20', 'Dela Cruz', 'Chabelita', 'Pita', '2025-03-17', 'Intern', 0.00),
(110, '1', 'bb', 'bb', 'bb', '2025-03-17', 'bb', 0.00),
(111, 'Office of the Governor (GO)', 'bb', 'bb', 'bb', '2025-03-17', 'bb', 0.00),
(112, 'Office of the Governor - Personal Staff', 'bb', 'bb', 'bb', '2025-03-17', 'bb', 0.00),
(113, 'Office of the Governor - Personal Staff', 'bb', 'bb', 'bb', '2025-03-17', 'bb', 0.00),
(114, 'Office of the Governor - Personal Staff', 'bb', 'bb', 'bb', '2025-03-17', 'bb', 0.00),
(115, 'Office of the Governor - Personal Staff', 'bb', 'bb', 'bb', '2025-03-17', 'bb', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `leaveapproval`
--

CREATE TABLE `leaveapproval` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `leave_id` int(11) NOT NULL,
  `as_of` date NOT NULL,
  `vacation_leave_balance` decimal(10,2) NOT NULL,
  `vacation_less_application` decimal(10,2) NOT NULL,
  `sick_leave_balance` decimal(10,2) NOT NULL,
  `sick_less_application` decimal(10,2) NOT NULL,
  `days_with_pay` int(11) NOT NULL,
  `days_without_pay` int(11) NOT NULL,
  `disapproved_days` int(11) DEFAULT NULL,
  `disapproved_reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leaveapproval`
--

INSERT INTO `leaveapproval` (`id`, `employee_id`, `leave_id`, `as_of`, `vacation_leave_balance`, `vacation_less_application`, `sick_leave_balance`, `sick_less_application`, `days_with_pay`, `days_without_pay`, `disapproved_days`, `disapproved_reason`) VALUES
(1, 1, 1, '2025-03-14', 10.00, 2.00, 8.00, 1.00, 5, 2, NULL, NULL),
(84, 108, 108, '2025-03-17', 2.00, 1.00, 1.00, 2.00, 3, 3, NULL, NULL),
(85, 109, 109, '2025-03-17', 0.00, 0.00, 1.00, 0.00, 0, 1, NULL, NULL),
(86, 110, 110, '2025-03-17', 0.00, 0.00, 0.00, 0.00, 0, 0, NULL, NULL),
(87, 115, 115, '2025-03-17', 0.00, 0.00, 0.00, 0.00, 0, 0, NULL, 'asdasd');

-- --------------------------------------------------------

--
-- Table structure for table `leavedetails`
--

CREATE TABLE `leavedetails` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `leave_type` enum('sickLeave','forceLeave','vacationLeave','maternityLeave','paternityLeave','specialPrivilege','studyLeave','vawcLeave','rehabPriv','specialLeave','specialEmergency','others') NOT NULL,
  `leave_type_others` varchar(255) DEFAULT NULL,
  `detail_type` enum('withinPhilippines','abroad','hospital','outPatient','leaveWomen','completion','exam','monetization','terminal') DEFAULT NULL,
  `detail_description` varchar(255) DEFAULT NULL,
  `working_days` int(11) NOT NULL,
  `inclusive_dates` date NOT NULL,
  `commutation` enum('notRequested','requested') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leavedetails`
--

INSERT INTO `leavedetails` (`id`, `employee_id`, `leave_type`, `leave_type_others`, `detail_type`, `detail_description`, `working_days`, `inclusive_dates`, `commutation`) VALUES
(1, 1, 'sickLeave', NULL, 'hospital', 'Flu', 5, '2025-03-10', 'requested'),
(108, 108, 'vacationLeave', NULL, 'withinPhilippines', 'Siargao', 12, '2025-03-17', 'requested'),
(109, 109, 'sickLeave', NULL, 'hospital', 'Lagnat', 3, '2025-03-17', 'requested'),
(110, 110, 'vacationLeave', NULL, 'withinPhilippines', 'bb', 0, '2025-03-17', 'notRequested'),
(111, 111, 'vacationLeave', NULL, 'withinPhilippines', 'bb', 0, '2025-03-17', 'notRequested'),
(112, 112, 'vacationLeave', NULL, 'withinPhilippines', 'bb', 0, '2025-03-17', 'notRequested'),
(113, 113, 'vacationLeave', NULL, 'withinPhilippines', 'bb', 0, '2025-03-17', 'notRequested'),
(114, 114, 'vacationLeave', NULL, 'withinPhilippines', 'bb', 0, '2025-03-17', 'notRequested'),
(115, 115, 'vacationLeave', NULL, 'withinPhilippines', 'bb', 0, '2025-03-17', 'requested');

-- --------------------------------------------------------

--
-- Table structure for table `locator_slip`
--

CREATE TABLE `locator_slip` (
  `id` int(11) NOT NULL,
  `official` tinyint(1) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `inclusive_dates` varchar(255) DEFAULT NULL,
  `time_of_departure` varchar(20) DEFAULT NULL,
  `time_of_arrival` varchar(20) DEFAULT NULL,
  `requested_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locator_slip`
--

INSERT INTO `locator_slip` (`id`, `official`, `date`, `destination`, `purpose`, `inclusive_dates`, `time_of_departure`, `time_of_arrival`, `requested_by`) VALUES
(2, 0, '2024-02-28', 'Provincial Office', 'Submit Reports', '2024-02-28', '08:30 AM', '02:30 PM', 'Robert White'),
(5, 0, '2024-03-03', 'Government Office', 'Sign Documents', '2024-03-03', '11:00 AM', '01:00 PM', 'Jessica Taylor'),
(6, 0, '2024-03-04', 'Health Department', 'Health and Safety Inspection', '2024-03-04', '09:15 AM', '03:45 PM', 'Daniel Clark'),
(10, 1, '2025-02-26', 'Malolos', 'Tambay', '2025-02-26', '03:23 PM', '04:23 PM', 'Chabelita\r\n'),
(11, 0, '2025-02-02', 'Lugam', 'Tambay', '2025-02-09', '08:24 PM', '10:24 PM', 'user 6'),
(12, 1, '2025-02-27', 'Malolos', 'Tambay', '2025-03-01', '12:52 PM', '12:52 PM', 'Chabelita zghdhfhhzsgdzd'),
(13, 0, '2025-02-11', 'Malolos', 'Tambay', '2025-02-27', '11:53 AM', '11:53 PM', 'aaaa'),
(14, 1, '2025-03-04', 'aaaaaa', 'Tambay', '2025-03-12', '11:58 AM', '11:57 AM', 'user1\r\n'),
(15, 1, '2025-03-20', 'Malolos', 'Tambay', '2025-02-27', '04:38 PM', '08:37 PM', 'user 2\r\n'),
(16, 1, '2025-03-04', 'Malolos', 'Tambay', '2025-03-10', '09:37 AM', '12:36 PM', 'user3\r\n'),
(17, 1, '0000-00-00', 'Malolos', 'Tambay', '', '01:00 AM', '01:00 AM', 'Chabelita'),
(18, 0, '2024-02-28', 'Provincial Office', 'Submit Reports', '2024-02-28', '08:30 AM', '02:30 PM', 'Robert White'),
(19, 1, '0000-00-00', 'Plaridel', 'Wala', '', '01:00 AM', '01:00 AM', 'Marie'),
(26, 1, '2025-03-12', 'dd', 'dd', '2025-03-12', '09:16 AM', '09:19 AM', 'dd'),
(27, 1, '2025-03-12', 'cc', 'cc', '2025-03-12', '09:19 AM', '09:19 AM', 'cc'),
(28, 0, '2025-03-01', 'Location', 'Agenda', '2025-03-01', '06:19 PM', '09:19 PM', 'User'),
(29, 1, '2025-03-12', 'aaaaa', 'aaaaaaa', '2025-03-12', '09:25 AM', '09:25 AM', 'aaaaaaa'),
(30, 1, '2025-03-12', '2', '2', '2025-03-27', '02:22 PM', '10:23 AM', '2'),
(31, 0, '2025-03-12', '1', '1', '2025-03-12', '02:01 PM', '02:01 AM', '1'),
(32, 1, '2025-03-12', 'Malolos', 'Tambay', '2025-03-12', '02:19 PM', '02:19 PM', 'Marie'),
(33, 1, '2025-03-12', 'Malolos', 'Tambay', '2025-03-12', '02:19 PM', '02:19 PM', 'Marie'),
(34, 1, '2025-03-25', 'aaaaaa', 'Tambay', '2025-03-06', '03:12 AM', '03:12 AM', 'Chabelita');

-- --------------------------------------------------------

--
-- Table structure for table `offices`
--

CREATE TABLE `offices` (
  `office_ID` int(11) NOT NULL,
  `office_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offices`
--

INSERT INTO `offices` (`office_ID`, `office_name`) VALUES
(1, 'Office of the Governor (GO)'),
(2, 'Office of the Governor - Personal Staff'),
(3, 'Office of the Vice Governor (VGO)'),
(4, 'Office of the Vice Governor - Personal Staff'),
(5, 'Office of the Secretary to the Sangguniang Panlalawigan (OSSP)'),
(6, 'Bulacan Environment and Natural Resources Office (BENRO)'),
(7, 'Bulacan Polytechnic College (BPC)'),
(8, 'Provincial Accounting Office'),
(9, 'Provincial Administrator\'s Office (PA\'s Office)'),
(10, 'Provincial Agriculture Office (PAO)'),
(11, 'Provincial Assessor\'s Office'),
(12, 'Provincial Budget Office (PBO)'),
(13, 'Provincial Civil Security and Jail Management Office (PCSJMO)'),
(14, 'Provincial Cooperative and Enterprise Development Office (PCEDO)'),
(15, 'Provincial Disaster Risk Reduction and Management Office (PDRRMO)'),
(16, 'Provincial Engineer\'s Office (PEO)'),
(17, 'Provincial General Services Office (PGSO)'),
(18, 'Provincial History, Arts, Culture, and Tourism Office (PHACTO)'),
(19, 'Provincial Human Resource Management Office (PHRMO)'),
(20, 'Provincial Information Technology Office (PITO)'),
(21, 'Provincial Legal Office (PLO)'),
(22, 'Provincial Planning and Development Office (PPDO)'),
(23, 'Provincial Public Affairs Office (PPAO)'),
(24, 'Provincial Public Employment Service Office (PPESO)'),
(25, 'Provincial Public Health Office (PPHO)'),
(26, 'Provincial Social Welfare and Development Office (PSWDO)'),
(27, 'Provincial Treasurer\'s Office (PTO)'),
(28, 'Provincial Veterinary Office (PVO)'),
(29, 'Provincial Youth and Sports Development Office (PYSDO)');

-- --------------------------------------------------------

--
-- Table structure for table `remarks`
--

CREATE TABLE `remarks` (
  `id` int(11) NOT NULL,
  `remark_text` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `remarks`
--

INSERT INTO `remarks` (`id`, `remark_text`) VALUES
(1, 'SPL'),
(2, 'FL for approval'),
(3, 'approved'),
(4, 'declined');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cto_requests`
--
ALTER TABLE `cto_requests`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `employeedetails`
--
ALTER TABLE `employeedetails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leaveapproval`
--
ALTER TABLE `leaveapproval`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `leave_id` (`leave_id`);

--
-- Indexes for table `leavedetails`
--
ALTER TABLE `leavedetails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `locator_slip`
--
ALTER TABLE `locator_slip`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offices`
--
ALTER TABLE `offices`
  ADD PRIMARY KEY (`office_ID`);

--
-- Indexes for table `remarks`
--
ALTER TABLE `remarks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cto_requests`
--
ALTER TABLE `cto_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=487;

--
-- AUTO_INCREMENT for table `employeedetails`
--
ALTER TABLE `employeedetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `leaveapproval`
--
ALTER TABLE `leaveapproval`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `leavedetails`
--
ALTER TABLE `leavedetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `locator_slip`
--
ALTER TABLE `locator_slip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `offices`
--
ALTER TABLE `offices`
  MODIFY `office_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `remarks`
--
ALTER TABLE `remarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leaveapproval`
--
ALTER TABLE `leaveapproval`
  ADD CONSTRAINT `leaveapproval_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employeedetails` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leaveapproval_ibfk_2` FOREIGN KEY (`leave_id`) REFERENCES `leavedetails` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leavedetails`
--
ALTER TABLE `leavedetails`
  ADD CONSTRAINT `leavedetails_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employeedetails` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
