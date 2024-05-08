-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2024 at 10:31 AM
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
-- Database: `investorscontactsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins_information`
--

CREATE TABLE `admins_information` (
  `Admin_ID` int(11) NOT NULL,
  `Email_Address` varchar(50) NOT NULL,
  `Password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins_information`
--

INSERT INTO `admins_information` (`Admin_ID`, `Email_Address`, `Password`) VALUES
(1, 'david@gmail.com', 'david123');

-- --------------------------------------------------------

--
-- Table structure for table `investors_businesses`
--

CREATE TABLE `investors_businesses` (
  `Business_ID` int(11) NOT NULL,
  `Business_Name` varchar(255) NOT NULL,
  `Investor_Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `investors_businesses`
--

INSERT INTO `investors_businesses` (`Business_ID`, `Business_Name`, `Investor_Name`) VALUES
(1, 'Selling Houses', 'GISA'),
(2, 'Cargo Derively', 'Frank'),
(3, 'Hotel Owner', 'IRADUKUNDA Fils'),
(4, 'Cars Importing', 'Jospin'),
(6, 'silling Electronics', 'David');

-- --------------------------------------------------------

--
-- Table structure for table `members_contacts_info`
--

CREATE TABLE `members_contacts_info` (
  `Member_ID` int(255) NOT NULL,
  `Phone_Number` varchar(20) NOT NULL,
  `Members_Full_Names` varchar(30) NOT NULL,
  `Shares` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members_contacts_info`
--

INSERT INTO `members_contacts_info` (`Member_ID`, `Phone_Number`, `Members_Full_Names`, `Shares`) VALUES
(7, '0794005956', 'IRADUKUNDA Fils', 13000000),
(8, '0796745632', 'Jospin', 2000000),
(9, '0784884096', 'GISA', 3000000),
(11, '0783074468', 'Rugwiro', 1200000),
(12, '0783082356', 'Frank', 1250000),
(13, '079865345', 'David', 12000);

-- --------------------------------------------------------

--
-- Table structure for table `users_information`
--

CREATE TABLE `users_information` (
  `Users_ID` int(255) NOT NULL,
  `Phone_Number` varchar(10) NOT NULL,
  `Password` varchar(30) NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_information`
--

INSERT INTO `users_information` (`Users_ID`, `Phone_Number`, `Password`, `is_approved`) VALUES
(1, '0783773086', 'David123', 1),
(3, '0783082354', '564filsdavid', 1),
(4, '0784432886', '12345678', 1),
(5, '0784884095', 'Filsdavid123', 1),
(7, '0795227092', 'RAFIKI123', 0),
(8, '0794017693', 'IRADUKUNDA', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins_information`
--
ALTER TABLE `admins_information`
  ADD PRIMARY KEY (`Admin_ID`),
  ADD UNIQUE KEY `Password` (`Password`);

--
-- Indexes for table `investors_businesses`
--
ALTER TABLE `investors_businesses`
  ADD PRIMARY KEY (`Business_ID`);

--
-- Indexes for table `members_contacts_info`
--
ALTER TABLE `members_contacts_info`
  ADD PRIMARY KEY (`Member_ID`),
  ADD UNIQUE KEY `Phone_Number` (`Phone_Number`,`Members_Full_Names`);

--
-- Indexes for table `users_information`
--
ALTER TABLE `users_information`
  ADD PRIMARY KEY (`Users_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins_information`
--
ALTER TABLE `admins_information`
  MODIFY `Admin_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `investors_businesses`
--
ALTER TABLE `investors_businesses`
  MODIFY `Business_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `members_contacts_info`
--
ALTER TABLE `members_contacts_info`
  MODIFY `Member_ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users_information`
--
ALTER TABLE `users_information`
  MODIFY `Users_ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
