-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2019 at 10:23 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id8325910_vending_machine`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `Acc_ID` int(10) NOT NULL,
  `AL_ID` int(1) NOT NULL,
  `User_Name` varchar(30) NOT NULL,
  `OTP` varchar(6) NOT NULL,
  `Password` varchar(10) NOT NULL,
  `Email` varchar(500) NOT NULL,
  `registration_token` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`Acc_ID`, `AL_ID`, `User_Name`, `OTP`, `Password`, `Email`, `registration_token`) VALUES
(2, 1, 'admin', '', 'admin', 'tapaymarjay@gmail.com', NULL),
(3, 2, 'cashier', '', 'cashier', 'cashier@gmail.com', NULL),
(4, 3, 'marjaygab', '123456', 'user1234', 'marjay_gabriel_tapay@dlsl.edu.ph', NULL),
(5, 3, 'lorenjoyce', '', 'user1234', 'loren_joyce_mendoza@dlsl.edu.ph', NULL),
(6, 3, 'vienlara', '', 'user1234', 'steven_daniel_lara@dlsl.edu.ph', NULL),
(18, 3, 'eloisalecaroz', '', 'user1234', 'eloisa.lecaroz@dlsl.edu.ph', NULL),
(19, 0, 'sophielara', '', 'user1234', 'sophielara@dlsl.edu.ph', NULL),
(20, 0, 'sophielara', '', 'user1234', 'sophielara@dlsl.edu.ph', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `acc_admin`
--

CREATE TABLE `acc_admin` (
  `Acc_ID` int(10) NOT NULL,
  `Acc_Admin_ID` int(10) NOT NULL,
  `ID_Number` varchar(20) NOT NULL,
  `First_Name` varchar(200) NOT NULL,
  `Last_Name` varchar(200) NOT NULL,
  `Balance` int(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `acc_admin`
--

INSERT INTO `acc_admin` (`Acc_ID`, `Acc_Admin_ID`, `ID_Number`, `First_Name`, `Last_Name`, `Balance`) VALUES
(2, 1, '9792436013', 'Juan', 'De La Cruz', 0);

-- --------------------------------------------------------

--
-- Table structure for table `acc_cashier`
--

CREATE TABLE `acc_cashier` (
  `Acc_ID` int(10) NOT NULL,
  `Acc_Cashier_ID` int(10) NOT NULL,
  `ID_Number` varchar(20) NOT NULL,
  `First_Name` varchar(200) NOT NULL,
  `Last_Name` varchar(200) NOT NULL,
  `Balance` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `acc_cashier`
--

INSERT INTO `acc_cashier` (`Acc_ID`, `Acc_Cashier_ID`, `ID_Number`, `First_Name`, `Last_Name`, `Balance`) VALUES
(3, 1, '8706540219', 'Juana', 'Cruz', 0);

-- --------------------------------------------------------

--
-- Table structure for table `acc_images`
--

CREATE TABLE `acc_images` (
  `Image_ID` int(200) NOT NULL,
  `Acc_ID` int(200) NOT NULL,
  `Image_Path` varchar(2000) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `acc_images`
--

INSERT INTO `acc_images` (`Image_ID`, `Acc_ID`, `Image_Path`) VALUES
(5, 4, '/user_images/USER_4.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `acc_levels`
--

CREATE TABLE `acc_levels` (
  `AL_ID` int(11) NOT NULL,
  `Access_Level` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `acc_levels`
--

INSERT INTO `acc_levels` (`AL_ID`, `Access_Level`) VALUES
(1, 'ADMIN'),
(2, 'CASHIER'),
(3, 'USER'),
(4, 'TEMP_USER');

-- --------------------------------------------------------

--
-- Table structure for table `acc_temp`
--

CREATE TABLE `acc_temp` (
  `Acc_ID` int(11) NOT NULL,
  `Acc_Temp_ID` int(10) NOT NULL,
  `OTP` varchar(6) NOT NULL,
  `Balance` decimal(15,2) NOT NULL,
  `Purchase_Date` datetime NOT NULL,
  `Expiry_Date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `acc_users`
--

CREATE TABLE `acc_users` (
  `Acc_ID` int(10) NOT NULL,
  `Acc_User_ID` int(10) NOT NULL,
  `ID_Number` varchar(10) NOT NULL,
  `First_Name` varchar(200) NOT NULL,
  `Last_Name` varchar(200) NOT NULL,
  `Phone` varchar(11) NOT NULL,
  `Balance` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `acc_users`
--

INSERT INTO `acc_users` (`Acc_ID`, `Acc_User_ID`, `ID_Number`, `First_Name`, `Last_Name`, `Phone`, `Balance`) VALUES
(4, 1, '2014141511', 'Majie', 'Tapay', 'undefined', '300.00'),
(5, 2, '2014151301', 'Loren Joyce', 'Mendoza', '09954842070', '200.00'),
(6, 3, '2014123456', 'Steven Daniel', 'Lara', '09266172058', '56.70'),
(18, 5, '2014157689', 'Eloisa', 'Lecaroz', '', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `machine_unit`
--

CREATE TABLE `machine_unit` (
  `MU_ID` int(10) NOT NULL,
  `Model Number` varchar(20) NOT NULL,
  `MAC_ADD` varchar(17) NOT NULL,
  `Machine_Location` varchar(100) NOT NULL,
  `Date of Purchase` date NOT NULL,
  `Last_Maintenance_Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `machine_unit`
--

INSERT INTO `machine_unit` (`MU_ID`, `Model Number`, `MAC_ADD`, `Machine_Location`, `Date of Purchase`, `Last_Maintenance_Date`) VALUES
(1, 'ABC123', 'F1-A0-19-6B-5C-04', 'Mabini Building', '2018-08-13', '2018-12-01'),
(2, 'EFG456', '81-37-12-83-2C-16', 'Chez Rafael Building', '2018-09-02', '2018-12-01');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `Notif_ID` int(11) NOT NULL,
  `Notif_Title` varchar(500) NOT NULL,
  `Notif_Desc` varchar(1000) NOT NULL,
  `Time_Posted` time NOT NULL,
  `Date_Posted` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`Notif_ID`, `Notif_Title`, `Notif_Desc`, `Time_Posted`, `Date_Posted`) VALUES
(1, 'ReQuench Alert', 'Machine #1 Needs Refill. Thank you!', '13:08:50', '2019-01-24'),
(2, 'Browser Closed', 'User has closed his/her window', '13:24:41', '2019-01-24');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_history`
--

CREATE TABLE `purchase_history` (
  `Purchase_ID` int(10) NOT NULL,
  `Acc_ID` int(10) NOT NULL,
  `Amount` decimal(15,2) NOT NULL,
  `Price_Computed` decimal(15,2) NOT NULL,
  `Time` time NOT NULL,
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_history`
--

INSERT INTO `purchase_history` (`Purchase_ID`, `Acc_ID`, `Amount`, `Price_Computed`, `Time`, `Date`) VALUES
(3, 4, '200.00', '8.00', '12:00:00', '2019-01-30'),
(4, 4, '100.00', '4.00', '14:00:00', '2019-02-01'),
(5, 4, '100.00', '4.00', '17:00:00', '2019-02-01');

-- --------------------------------------------------------

--
-- Table structure for table `seen_notifs`
--

CREATE TABLE `seen_notifs` (
  `Seen_ID` int(11) NOT NULL,
  `Notif_ID` int(11) NOT NULL,
  `Acc_ID` int(11) NOT NULL,
  `Time_Seen` time NOT NULL,
  `Date_Seen` date NOT NULL,
  `Status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `WI_ID` int(10) NOT NULL,
  `MU_ID` int(10) NOT NULL,
  `Current_Water_Level` int(10) NOT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL,
  `Temperature` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_history`
--

CREATE TABLE `transaction_history` (
  `Transaction_ID` int(10) NOT NULL,
  `Acc_ID` int(10) NOT NULL,
  `MU_ID` int(10) NOT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL,
  `Amount` decimal(15,2) NOT NULL,
  `Temperature` varchar(5) NOT NULL,
  `Price_Computed` decimal(15,2) NOT NULL,
  `Water_Level_Before` decimal(15,2) NOT NULL,
  `Water_Level_After` decimal(15,2) NOT NULL,
  `Remaining_Balance` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction_history`
--

INSERT INTO `transaction_history` (`Transaction_ID`, `Acc_ID`, `MU_ID`, `Date`, `Time`, `Amount`, `Temperature`, `Price_Computed`, `Water_Level_Before`, `Water_Level_After`, `Remaining_Balance`) VALUES
(16, 4, 2, '2019-01-28', '12:00:00', '10.00', 'COLD', '0.40', '1000.00', '990.00', 190),
(17, 4, 2, '2019-01-31', '15:00:00', '10.00', 'COLD', '0.40', '990.00', '980.00', 180),
(18, 4, 2, '2019-02-01', '16:00:00', '10.00', 'COLD', '0.40', '980.00', '970.00', 270),
(19, 4, 2, '2019-02-01', '18:00:00', '70.00', 'COLD', '2.80', '970.00', '900.00', 300);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`Acc_ID`);

--
-- Indexes for table `acc_admin`
--
ALTER TABLE `acc_admin`
  ADD PRIMARY KEY (`Acc_Admin_ID`);

--
-- Indexes for table `acc_cashier`
--
ALTER TABLE `acc_cashier`
  ADD PRIMARY KEY (`Acc_Cashier_ID`);

--
-- Indexes for table `acc_images`
--
ALTER TABLE `acc_images`
  ADD PRIMARY KEY (`Image_ID`);

--
-- Indexes for table `acc_levels`
--
ALTER TABLE `acc_levels`
  ADD PRIMARY KEY (`AL_ID`);

--
-- Indexes for table `acc_temp`
--
ALTER TABLE `acc_temp`
  ADD PRIMARY KEY (`Acc_Temp_ID`);

--
-- Indexes for table `acc_users`
--
ALTER TABLE `acc_users`
  ADD PRIMARY KEY (`Acc_User_ID`);

--
-- Indexes for table `machine_unit`
--
ALTER TABLE `machine_unit`
  ADD PRIMARY KEY (`MU_ID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`Notif_ID`);

--
-- Indexes for table `purchase_history`
--
ALTER TABLE `purchase_history`
  ADD PRIMARY KEY (`Purchase_ID`);

--
-- Indexes for table `seen_notifs`
--
ALTER TABLE `seen_notifs`
  ADD PRIMARY KEY (`Seen_ID`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`WI_ID`);

--
-- Indexes for table `transaction_history`
--
ALTER TABLE `transaction_history`
  ADD PRIMARY KEY (`Transaction_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `Acc_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `acc_admin`
--
ALTER TABLE `acc_admin`
  MODIFY `Acc_Admin_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `acc_cashier`
--
ALTER TABLE `acc_cashier`
  MODIFY `Acc_Cashier_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `acc_images`
--
ALTER TABLE `acc_images`
  MODIFY `Image_ID` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `acc_levels`
--
ALTER TABLE `acc_levels`
  MODIFY `AL_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `acc_temp`
--
ALTER TABLE `acc_temp`
  MODIFY `Acc_Temp_ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_users`
--
ALTER TABLE `acc_users`
  MODIFY `Acc_User_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `machine_unit`
--
ALTER TABLE `machine_unit`
  MODIFY `MU_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `Notif_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase_history`
--
ALTER TABLE `purchase_history`
  MODIFY `Purchase_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `seen_notifs`
--
ALTER TABLE `seen_notifs`
  MODIFY `Seen_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction_history`
--
ALTER TABLE `transaction_history`
  MODIFY `Transaction_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
