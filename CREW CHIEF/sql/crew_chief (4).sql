-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 16, 2026 at 06:43 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crew_chief`
--

-- --------------------------------------------------------

--
-- Table structure for table `lap_times`
--

CREATE TABLE `lap_times` (
  `lapID` int(11) NOT NULL COMMENT 'Unique ID for the recorded lap',
  `sessionID` int(11) NOT NULL,
  `lapNumber` int(11) NOT NULL,
  `lapTime` decimal(6,3) NOT NULL,
  `isBestLap` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lists`
--

CREATE TABLE `lists` (
  `listID` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `listTitle` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lists`
--

INSERT INTO `lists` (`listID`, `eventID`, `listTitle`, `category`) VALUES
(1, 1, 'Complete before Gold Rush', 'cooling system');

-- --------------------------------------------------------

--
-- Table structure for table `list_items`
--

CREATE TABLE `list_items` (
  `itemID` int(11) NOT NULL,
  `listID` int(11) NOT NULL,
  `itemName` varchar(300) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `isCompleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `list_items`
--

INSERT INTO `list_items` (`itemID`, `listID`, `itemName`, `quantity`, `isCompleted`) VALUES
(1, 1, 'mount Rad', 1, 0),
(2, 1, 'paint quarter panel', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pit_adjustments`
--

CREATE TABLE `pit_adjustments` (
  `adjustmentID` int(11) NOT NULL COMMENT 'Unique ID for the adjustment note',
  `eventID` int(11) NOT NULL,
  `sessionID` int(11) NOT NULL,
  `timeLogged` timestamp NOT NULL DEFAULT current_timestamp(),
  `changeMade` text NOT NULL,
  `carHandlingNotes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `profileID` int(50) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `teamName` varchar(50) NOT NULL,
  `emailAddress` varchar(50) NOT NULL,
  `carName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `race_events`
--

CREATE TABLE `race_events` (
  `eventID` int(50) NOT NULL,
  `profileID` int(11) DEFAULT NULL,
  `eventName` varchar(100) NOT NULL,
  `trackName` varchar(100) NOT NULL,
  `eventDate` date NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `race_events`
--

INSERT INTO `race_events` (`eventID`, `profileID`, `eventName`, `trackName`, `eventDate`, `notes`) VALUES
(1, 1, 'Gold Rush 126', 'Flamboro Speedway', '2026-06-30', '50 lap feature'),
(3, NULL, 'RIP TIDE 2026', 'Sauble Speedway', '2026-08-30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `userID` int(11) NOT NULL,
  `userName` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `emailAddress` varchar(100) NOT NULL,
  `failedAttempts` int(11) NOT NULL DEFAULT 0,
  `lastFailedLogin` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`userID`, `userName`, `password`, `emailAddress`, `failedAttempts`, `lastFailedLogin`) VALUES
(1, 'Kyle Larson', '$2y$10$ULImRBWDSVAe/SXZ6n3.fe7lalX8yygZuS.XhgcWAUqGJQNgLEFSK', 'klarson@hendricks.com', 0, NULL),
(2, 'JR Hanley', '$2y$10$fo2Ztp/rRlR03TZF9XtNY.23kVixW8McyJIMdI9U5IuJ.2NKgCUFK', 'jrhanley@hanleyent.com', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `sessionID` int(11) NOT NULL COMMENT 'Unique ID for the session',
  `eventID` int(11) NOT NULL,
  `sessionName` varchar(50) NOT NULL,
  `trackTemp` decimal(4,1) DEFAULT NULL,
  `airTemp` decimal(4,1) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `setups`
--

CREATE TABLE `setups` (
  `setupID` int(11) NOT NULL COMMENT 'Unique ID for the setup baseline',
  `eventID` int(11) NOT NULL COMMENT 'Foreign key linking to race_events.eventID',
  `tireSizeLF` varchar(20) DEFAULT NULL,
  `tireSizeRF` varchar(20) DEFAULT NULL,
  `tireSizeLR` varchar(20) DEFAULT NULL,
  `tireSizeRR` varchar(20) DEFAULT NULL,
  `tirePressureLF` decimal(4,1) DEFAULT NULL COMMENT 'cold',
  `tirePressureRF` decimal(4,1) DEFAULT NULL COMMENT 'cold',
  `tirePressureLR` decimal(4,1) DEFAULT NULL COMMENT 'cold',
  `tirePressureRR` decimal(4,1) DEFAULT NULL,
  `frontStagger` decimal(4,2) DEFAULT NULL,
  `rearStagger` decimal(4,2) DEFAULT NULL,
  `weightLF` decimal(6,1) DEFAULT NULL,
  `weightRF` decimal(6,1) DEFAULT NULL,
  `weightLR` decimal(6,1) DEFAULT NULL,
  `weightRR` decimal(6,1) DEFAULT NULL,
  `leftWeight` decimal(4,1) DEFAULT NULL,
  `totalWeight` decimal(6,1) DEFAULT NULL,
  `crossWeight` decimal(4,1) DEFAULT NULL,
  `gearRatio` varchar(20) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `setups`
--

INSERT INTO `setups` (`setupID`, `eventID`, `tireSizeLF`, `tireSizeRF`, `tireSizeLR`, `tireSizeRR`, `tirePressureLF`, `tirePressureRF`, `tirePressureLR`, `tirePressureRR`, `frontStagger`, `rearStagger`, `weightLF`, `weightRF`, `weightLR`, `weightRR`, `leftWeight`, `totalWeight`, `crossWeight`, `gearRatio`, `notes`) VALUES
(1, 3, '86.5', '87.5', '80', '87', 33.0, 30.0, 15.0, 30.0, 1.00, 7.00, 759.0, 735.0, 865.0, 650.0, 54.0, NULL, 53.0, NULL, 'Tire set #029'),
(3, 1, '86.5', '86', '83', '91', 22.0, 22.0, 22.0, 22.0, 0.50, 8.00, 502.0, 502.0, 502.0, 502.0, 50.0, NULL, 50.0, NULL, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lap_times`
--
ALTER TABLE `lap_times`
  ADD PRIMARY KEY (`lapID`),
  ADD KEY `sessionID` (`sessionID`);

--
-- Indexes for table `lists`
--
ALTER TABLE `lists`
  ADD PRIMARY KEY (`listID`),
  ADD KEY `eventID` (`eventID`);

--
-- Indexes for table `list_items`
--
ALTER TABLE `list_items`
  ADD PRIMARY KEY (`itemID`),
  ADD KEY `listID` (`listID`);

--
-- Indexes for table `pit_adjustments`
--
ALTER TABLE `pit_adjustments`
  ADD PRIMARY KEY (`adjustmentID`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`profileID`),
  ADD UNIQUE KEY `emailAddress` (`emailAddress`);

--
-- Indexes for table `race_events`
--
ALTER TABLE `race_events`
  ADD PRIMARY KEY (`eventID`),
  ADD KEY `profileID` (`profileID`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `userName` (`userName`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`sessionID`),
  ADD KEY `eventID` (`eventID`);

--
-- Indexes for table `setups`
--
ALTER TABLE `setups`
  ADD PRIMARY KEY (`setupID`),
  ADD KEY `eventID` (`eventID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lists`
--
ALTER TABLE `lists`
  MODIFY `listID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `list_items`
--
ALTER TABLE `list_items`
  MODIFY `itemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pit_adjustments`
--
ALTER TABLE `pit_adjustments`
  MODIFY `adjustmentID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for the adjustment note';

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `profileID` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `race_events`
--
ALTER TABLE `race_events`
  MODIFY `eventID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `sessionID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for the session';

--
-- AUTO_INCREMENT for table `setups`
--
ALTER TABLE `setups`
  MODIFY `setupID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for the setup baseline', AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
