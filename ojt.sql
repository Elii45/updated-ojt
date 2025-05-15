-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2025 at 01:53 AM
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
(1, 'De Guzman, Krizza Mhei A.', 'Provincial Information Technology Office (PITO)', 'Administrative Aide VI', '2025-04-08', 5, '2025-04-08', 5),
(2, 'Bartolo, John Daniel A.', 'Provincial Information Technology Office (PITO)', 'Admin Aide II', '2025-04-11', 8, '2025-04-11', 8),
(3, 'aa', 'Office of the Governor (GO)', 'aa', '2025-05-12', 2, '2025-05-12', 1),
(4, 'aa', 'Office of the Governor (GO)', 'A', '2025-05-12', 1, '2025-05-12', 1),
(5, 'aa', 'Office of the Governor (GO)', 'aa', '2025-05-13', 1, '2025-05-13', 1),
(6, 'aa', 'Office of the Governor (GO)', 'aa', '2025-05-13', 1, '2025-05-13', 1),
(7, 'aa', 'Office of the Governor (GO)', 'aa', '2025-05-13', 1, '2025-05-13', 1),
(8, 'aa', 'Office of the Governor (GO)', 'aa', '2025-05-13', 1, '2025-05-13', 1),
(9, 'aa', 'Office of the Governor (GO)', 'aa', '2025-05-13', 1, '2025-05-13', 1);

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
  `salary` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employeedetails`
--

INSERT INTO `employeedetails` (`id`, `office`, `last_name`, `first_name`, `middle_name`, `filing_date`, `position`, `salary`) VALUES
(39, 'Office of the Governor (GO)', 'aa', 'aa', 'aa', '2025-05-13', 'aa', '0'),
(40, 'Office of the Governor (GO)', 'bb', 'bb', 'bb', '2025-05-14', 'aa', '0'),
(41, 'Office of the Governor - Personal Staff', '', '', '', '0000-00-00', '', '0'),
(42, 'Office of the Governor - Personal Staff', '', '', '', '0000-00-00', '', '0'),
(63, 'Office of the Governor (GO)', 'aa', 'aa', 'aa', '2025-05-15', 'aa', '100');

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
  `other_days` int(11) DEFAULT NULL,
  `other_specify` varchar(255) NOT NULL,
  `disapproved_reason` varchar(255) DEFAULT NULL,
  `vacation_total_earned` float DEFAULT 0,
  `sick_total_earned` float DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leaveapproval`
--

INSERT INTO `leaveapproval` (`id`, `employee_id`, `leave_id`, `as_of`, `vacation_leave_balance`, `vacation_less_application`, `sick_leave_balance`, `sick_less_application`, `days_with_pay`, `days_without_pay`, `other_days`, `other_specify`, `disapproved_reason`, `vacation_total_earned`, `sick_total_earned`) VALUES
(29, 39, 35, '2025-05-13', 0.00, 0.00, 0.00, 0.00, 0, 0, NULL, '', NULL, 0, 0),
(30, 40, 36, '2025-05-14', 0.00, 0.00, 0.00, 0.00, 0, 0, NULL, '', NULL, 0, 0),
(31, 63, 39, '2025-05-15', 1.00, 1.00, 1.00, 1.00, 1, 1, NULL, '', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `leavedetails`
--

CREATE TABLE `leavedetails` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `leave_type` varchar(255) NOT NULL,
  `leave_type_others` varchar(255) DEFAULT NULL,
  `detail_type` varchar(255) NOT NULL,
  `detail_description` varchar(255) DEFAULT NULL,
  `working_days` int(11) NOT NULL,
  `inclusive_dates` varchar(255) NOT NULL,
  `commutation` enum('notRequested','requested') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leavedetails`
--

INSERT INTO `leavedetails` (`id`, `employee_id`, `leave_type`, `leave_type_others`, `detail_type`, `detail_description`, `working_days`, `inclusive_dates`, `commutation`) VALUES
(36, 40, 'vacationLeave', NULL, 'withinPhilippines', 'aa', 0, '', 'notRequested'),
(37, 41, 'vacationLeave', NULL, 'withinPhilippines', 'aa', 0, '', 'notRequested'),
(38, 42, 'vacationLeave', NULL, 'withinPhilippines', 'aa', 0, '', 'notRequested'),
(39, 63, 'vacationLeave', NULL, 'abroad', 'aa', 5, '2025-05-05,2025-05-06,2025-05-07,2025-05-08,2025-05-09', 'notRequested');

-- --------------------------------------------------------

--
-- Table structure for table `leave_type`
--

CREATE TABLE `leave_type` (
  `id` int(11) NOT NULL,
  `leave_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_type`
--

INSERT INTO `leave_type` (`id`, `leave_name`, `description`, `remarks`) VALUES
(1, 'Vacation Leave', 'Sec. 51, Rule XVI, Omnibus Rules Implementing E.O No.292', 'VL'),
(2, 'Mandatory/Forced Leave', 'Sec. 2, Rule XVI, Omnibus Rules Implementing E.O No.292', 'FL for Approval'),
(3, 'Sick Leave', 'Sec. 43, Rule SVI, Omnibus Rules Implementing E.O No.292', 'SL'),
(4, 'Maternity Leave ', 'R.A. No. 11210/IRR issued by CSC, DOLE and SSS', 'ML'),
(5, 'Paternity Leave', 'R.A. No. 8187/CSC MC No. 71, s. 1998, as amended', 'PL'),
(6, 'Special Privilege Leave', 'Sec. 21, Rule XVI, Omnibus Rules Implementing E.O No.292', 'SPL'),
(7, 'Solo Parent Leave', 'RA No. 8972/CSC MC No. 8, s. 2004', 'SPL'),
(8, 'Study Leave', 'Sec. 68, Rule XVI, Omnibus Rules Implementing E.O No.292', 'SL'),
(9, '10-Day VAWC Leave', 'RA No. 9262/CSC MC No. 15 s. 2005', 'VL'),
(10, 'Rehabilitation Privilege', 'Sec. 55, Rule XVI, Omnibus Rules Implementing E.O No.292', 'RP'),
(11, 'Special Leave Benefits for Women', 'RA No. 9710/CSC No. 25, s. 2010', 'SLBW'),
(12, 'Special Emergency Leave', 'CSC MC No. 2, s. 2012, as amended', 'SEL'),
(13, 'Adoption Leave', 'CSC MC No. 2, s. 2012, as amended', 'AL'),
(14, 'Others', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `leave_type_detail`
--

CREATE TABLE `leave_type_detail` (
  `id` int(11) NOT NULL,
  `leave_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_type_detail`
--

INSERT INTO `leave_type_detail` (`id`, `leave_name`) VALUES
(1, 'Within Philippines'),
(2, 'Abroad'),
(3, 'Hospital'),
(4, 'Out Patient'),
(5, 'Benefits for Women'),
(6, 'Completion of Master\'s Degree'),
(7, 'BAR/Board Examination Review'),
(8, 'Monetization of Leave Credits'),
(9, 'Terminal Leave');

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
(1, 1, '2025-04-10', 'Malolos', 'Meeting', 'April 8-12 2025, April 15-19 2025, April 22 2025, April 26 2025', '12:00:00', '10:00 AM', 'PITO Office'),
(2, 1, '2025-04-08', 'Malolos', 'Meeting', 'April 8-9 2025, April 11-12 2025', '2:00 PM', '3:00 PM', 'Abiol, Tristan P.'),
(3, 1, '2025-04-08', 'Malolos', 'Meeting', 'April 8-9 2025, April 11-12 2025', '10:00 PM', '11:00 PM', 'PITO Office'),
(4, 1, '2025-04-08', 'Plaridel', 'Meeting', 'April 8-9 2025, April 11-12 2025, April 17 2025', '2:00 PM', '2:00 PM', 'Mendoza, Baby Angela R.'),
(5, 1, '2025-04-11', 'Malolos', 'Meeting', 'April 6 2025, April 9 2025, April 12 2025', '12:00 PM', '11:45 AM', 'Abiol, Tristan P.');

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
-- Table structure for table `pito_office`
--

CREATE TABLE `pito_office` (
  `employee_id` int(11) NOT NULL,
  `employee_lastName` varchar(255) NOT NULL,
  `employee_firstName` varchar(255) NOT NULL,
  `employee_middleName` varchar(255) NOT NULL,
  `position` varchar(100) NOT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `email_address` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pito_office`
--

INSERT INTO `pito_office` (`employee_id`, `employee_lastName`, `employee_firstName`, `employee_middleName`, `position`, `mobile_number`, `email_address`) VALUES
(1, 'Abiol', 'Tristan', 'P.', 'Computer Programmer III', '09420303612', 't_abiol@yahoo.com'),
(2, 'Bartolo', 'John Daniel', 'A.', 'Admin Aide II', '09501138555', 'bartsdaniel22@gmail.com'),
(3, 'Cervantes', 'Joseph', 'S.', 'CP II', '09058373331', 'josephcervantes.pgb@gmail.com'),
(4, 'Crisostomo', 'Emmanuel', 'C.', 'DEMO II', '09178967299', 'mnycris@gmail.com'),
(5, 'Cue', 'Karlo Antonio', 'B.', 'DEMO I', '09335566543', 'karloluna23@gmail.com'),
(6, 'De Guzman', 'Krizza Mhei', 'A.', 'Administrative Aide VI', '09613676136', 'krizzadeguzman31@gmail.com'),
(7, 'Guitierrez', 'Arnel', 'S.', 'CP III', '09226319826', 'pgb.pito.az@gmail.com'),
(8, 'Mendoza', 'Baby Angela', 'R.', 'CP III', '09153822952', 'angela.mendoza1127@gmail.com'),
(9, 'Navarro', 'Frederick', 'P.', 'DEMO II', '09321326387', 'gatts1511@gmail.com'),
(10, 'Nicolas', 'James Patrick', 'S.', 'DEMO I', '09958232310', 'jpatricknicolas@gmail.com'),
(11, 'Ochoa', 'Joseph Gary', 'G.', 'CP III', '09436059207', 'joegarochoa@gmail.com'),
(12, 'Owera', 'Mary Christian Joy', 'C.', 'DEMO I', '09335433882', 'ceejayowera12@gmail.com'),
(13, 'Palad', 'Arthur', 'L.', 'CP III', '09158489357', 'ants_bug@yahoo.com'),
(14, 'Perez', 'Ronalyn', 'C.', 'IT OFFICER II', '09174645119', 'rcperez828@gmail.com'),
(15, 'Sacdalan', 'Bryan Allen', 'S.', 'DEMO I', '09499377005', 'bryanallensacdalan555@gmail.com'),
(16, 'Santiago', 'Christian Dave', 'T.', 'DEMO I', '09613354694', 'christiandavesantiago7@gmail.com'),
(17, 'Santiago', 'Sandy', 'I.', 'CP III', '09234657337', 'zahndee@yahoo.com'),
(18, 'Santos', 'Joe Allann', 'S.', 'DEMO I', '09433915177', 'sajsantos7@gmail.com'),
(19, 'Santos', 'Paul Andrew', 'R.', 'CP III', '09326204981', 'paulandrewsantos@gmail.com'),
(20, 'Tolentino', 'Gilbert', 'T.', 'DEMO II', '09067859854', 'gilberttolentino04112o@gmail.com'),
(21, 'Tolentino', 'Joseph', 'C.', 'DEMO II', '09166417402', 'pito.kuyajoseph@gmail.com'),
(22, 'Valerio', 'Rhea Liza', 'R.', 'Department Head\r\n', '09162837328', 'pgbulacandco@gmail.com'),
(23, 'Manahan', 'Jose Fernando', 'G.', 'Puno, PHRMO', NULL, NULL),
(24, 'Constantino', 'Antonia', 'V.', 'Provincial Administrator', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `header_image_path` varchar(255) DEFAULT NULL,
  `footer_image_path` varchar(255) DEFAULT NULL,
  `text_size` int(11) DEFAULT NULL,
  `footer_text` varchar(255) DEFAULT NULL,
  `body_image_path` varchar(255) DEFAULT NULL,
  `body_bg_position_x` varchar(50) DEFAULT NULL,
  `body_bg_position_y` varchar(50) DEFAULT NULL,
  `body_bg_size_mode` varchar(50) DEFAULT NULL,
  `body_bg_custom_width` int(11) DEFAULT NULL,
  `body_bg_custom_height` int(11) DEFAULT NULL,
  `footer_bg_position_x` varchar(50) DEFAULT NULL,
  `footer_bg_position_y` varchar(50) DEFAULT NULL,
  `footer_bg_size_mode` varchar(50) DEFAULT NULL,
  `footer_bg_custom_width` int(11) DEFAULT NULL,
  `footer_bg_custom_height` int(11) DEFAULT NULL,
  `header_bg_position_x` varchar(50) DEFAULT NULL,
  `header_bg_position_y` varchar(50) DEFAULT NULL,
  `header_bg_size_mode` varchar(50) DEFAULT NULL,
  `header_bg_custom_width` int(11) DEFAULT NULL,
  `header_bg_custom_height` int(11) DEFAULT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'leave'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `header_image_path`, `footer_image_path`, `text_size`, `footer_text`, `body_image_path`, `body_bg_position_x`, `body_bg_position_y`, `body_bg_size_mode`, `body_bg_custom_width`, `body_bg_custom_height`, `footer_bg_position_x`, `footer_bg_position_y`, `footer_bg_size_mode`, `footer_bg_custom_width`, `footer_bg_custom_height`, `header_bg_position_x`, `header_bg_position_y`, `header_bg_size_mode`, `header_bg_custom_width`, `header_bg_custom_height`, `type`) VALUES
(1, '/assets/header_locator_1.png', NULL, 0, '', NULL, 'center', 'center', 'cover', NULL, NULL, 'center', 'center', 'contain', 600, NULL, 'center', 'center', 'contain', 300, 100, 'locator'),
(2, '/assets/leave_header_2.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'center', 'center', 'contain', 300, 100, 'leave');

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
-- Indexes for table `leave_type`
--
ALTER TABLE `leave_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_type_detail`
--
ALTER TABLE `leave_type_detail`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `pito_office`
--
ALTER TABLE `pito_office`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type` (`type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cto_requests`
--
ALTER TABLE `cto_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `employeedetails`
--
ALTER TABLE `employeedetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `leaveapproval`
--
ALTER TABLE `leaveapproval`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `leavedetails`
--
ALTER TABLE `leavedetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `leave_type`
--
ALTER TABLE `leave_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `leave_type_detail`
--
ALTER TABLE `leave_type_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `locator_slip`
--
ALTER TABLE `locator_slip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `offices`
--
ALTER TABLE `offices`
  MODIFY `office_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `pito_office`
--
ALTER TABLE `pito_office`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leaveapproval`
--
ALTER TABLE `leaveapproval`
  ADD CONSTRAINT `leaveapproval_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employeedetails` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leavedetails`
--
ALTER TABLE `leavedetails`
  ADD CONSTRAINT `fk_leavedetails_employee` FOREIGN KEY (`employee_id`) REFERENCES `employeedetails` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
