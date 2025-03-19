-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Mar 19, 2025 at 09:30 AM
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
(1, 1, '2025-03-19', 'Bulacan', 'Meeting', 'Mar 17-Mar 18, Mar 20-Mar 21', '04:22 PM', '05:22 PM', 'Abiol, Tristan P., Cervantes, Joseph S.');

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
(22, 'Valerio', 'Rhea Liza', 'R.', 'Department Head\r\n', '09162837328', 'pgbulacandco@gmail.com');

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
-- Indexes for table `pito_office`
--
ALTER TABLE `pito_office`
  ADD PRIMARY KEY (`employee_id`);

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
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employeedetails`
--
ALTER TABLE `employeedetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leaveapproval`
--
ALTER TABLE `leaveapproval`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `leavedetails`
--
ALTER TABLE `leavedetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `locator_slip`
--
ALTER TABLE `locator_slip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `offices`
--
ALTER TABLE `offices`
  MODIFY `office_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `pito_office`
--
ALTER TABLE `pito_office`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

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
