-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Mar 11, 2025 at 09:26 AM
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
(484, 'user', 'Office of the Vice Governor - Personal Staff', 'user', '2025-03-11', 1, '2025-03-11', 1);

-- --------------------------------------------------------

--
-- Table structure for table `employee_info`
--

CREATE TABLE `employee_info` (
  `id` int(11) NOT NULL,
  `office` varchar(255) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `filing_date` date NOT NULL,
  `position` varchar(100) NOT NULL,
  `salary` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_approval`
--

CREATE TABLE `leave_approval` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `as_of` date NOT NULL,
  `vacation_total_earned` int(11) DEFAULT NULL,
  `sick_total_earned` int(11) DEFAULT NULL,
  `vacation_less_application` int(11) DEFAULT NULL,
  `sick_less_application` int(11) DEFAULT NULL,
  `vacation_balance` int(11) DEFAULT NULL,
  `sick_balance` int(11) DEFAULT NULL,
  `recommendation` varchar(255) DEFAULT NULL,
  `approval_status` varchar(255) DEFAULT NULL,
  `days_with_pay` int(11) DEFAULT NULL,
  `days_without_pay` int(11) DEFAULT NULL,
  `others_specify` varchar(255) DEFAULT NULL,
  `disapproved_to` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_details`
--

CREATE TABLE `leave_details` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `type_of_leave` varchar(255) DEFAULT NULL,
  `vacation_within_philippines` varchar(255) DEFAULT NULL,
  `vacation_abroad` varchar(255) DEFAULT NULL,
  `sick_hospital` varchar(255) DEFAULT NULL,
  `sick_out_patient` varchar(255) DEFAULT NULL,
  `special_leave_women` varchar(255) DEFAULT NULL,
  `study_leave_master` tinyint(1) DEFAULT NULL,
  `study_leave_exam` tinyint(1) DEFAULT NULL,
  `monetization` tinyint(1) DEFAULT NULL,
  `terminal_leave` tinyint(1) DEFAULT NULL,
  `working_days` int(11) NOT NULL,
  `inclusive_dates` date NOT NULL,
  `commutation` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(19, 1, '0000-00-00', 'Plaridel', 'Wala', '', '01:00 AM', '01:00 AM', 'Marie');

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
-- Indexes for table `employee_info`
--
ALTER TABLE `employee_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_approval`
--
ALTER TABLE `leave_approval`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `leave_details`
--
ALTER TABLE `leave_details`
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
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=485;

--
-- AUTO_INCREMENT for table `employee_info`
--
ALTER TABLE `employee_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=605;

--
-- AUTO_INCREMENT for table `leave_approval`
--
ALTER TABLE `leave_approval`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_details`
--
ALTER TABLE `leave_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locator_slip`
--
ALTER TABLE `locator_slip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
-- Constraints for table `leave_approval`
--
ALTER TABLE `leave_approval`
  ADD CONSTRAINT `leave_approval_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee_info` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_details`
--
ALTER TABLE `leave_details`
  ADD CONSTRAINT `leave_details_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee_info` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
