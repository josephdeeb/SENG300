-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2019 at 10:59 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `seng300`
--
CREATE DATABASE IF NOT EXISTS `seng300` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `seng300`;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `journalName` varchar(255) NOT NULL,
  `reviewer` varchar(55) NOT NULL,
  `line` int(11) NOT NULL,
  `comment` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`journalName`, `reviewer`, `line`, `comment`) VALUES
('asg1.pdf', 'ben', 5, 'do better');

-- --------------------------------------------------------

--
-- Table structure for table `journals`
--

CREATE TABLE `journals` (
  `name` varchar(255) NOT NULL,
  `submitter` varchar(55) NOT NULL,
  `location` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `version` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `journals`
--

INSERT INTO `journals` (`name`, `submitter`, `location`, `status`, `version`) VALUES
('A2.pdf', 'submitter', 'journals/A2.pdf', 0, 0),
('A4.pdf', 'joseph', 'journals/A4.pdf', 0, 0),
('A5.pdf', 'submitter', 'journals/A5.pdf', 2, 0),
('asg1.pdf', 'submitter', 'journals/asg1.pdf', 1, 0),
('HIPODiagram.pdf', 'submitter', 'journals/HIPODiagram.pdf', 0, 0),
('proj_relation_model.pdf', 'submitter', 'journals/proj_relation_model.pdf', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `revisions`
--

CREATE TABLE `revisions` (
  `originalName` varchar(255) NOT NULL,
  `revisionName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `revisions`
--

INSERT INTO `revisions` (`originalName`, `revisionName`) VALUES
('A5.pdf', 'A5(3).pdf');

-- --------------------------------------------------------

--
-- Table structure for table `subprefs`
--

CREATE TABLE `subprefs` (
  `journalName` varchar(255) NOT NULL,
  `reviewer` varchar(55) NOT NULL,
  `preferred` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subprefs`
--

INSERT INTO `subprefs` (`journalName`, `reviewer`, `preferred`) VALUES
('A2.pdf', 'ben', 1),
('A2.pdf', 'reviewer', 0),
('proj_relation_model.pdf', 'joseph', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userName` varchar(55) NOT NULL,
  `password` varchar(55) NOT NULL,
  `firstName` varchar(55) NOT NULL,
  `lastName` varchar(55) NOT NULL,
  `type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userName`, `password`, `firstName`, `lastName`, `type`) VALUES
('ben', 'password', 'ben', 's', 2),
('editor', 'password', 'editor', 'man', 3),
('joseph', 'password', 'jo', 'seph', 1),
('reviewer', 'password', 'review', 'er', 2),
('submitter', 'password', 'sub', 'mitter', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD KEY `comments_ibfk_1` (`journalName`),
  ADD KEY `comments_ibfk_2` (`reviewer`);

--
-- Indexes for table `journals`
--
ALTER TABLE `journals`
  ADD PRIMARY KEY (`name`),
  ADD KEY `journals_ibfk_1` (`submitter`);

--
-- Indexes for table `revisions`
--
ALTER TABLE `revisions`
  ADD PRIMARY KEY (`revisionName`),
  ADD KEY `revisions_ibfk_1` (`originalName`);

--
-- Indexes for table `subprefs`
--
ALTER TABLE `subprefs`
  ADD PRIMARY KEY (`journalName`,`reviewer`),
  ADD KEY `subpreferences_ibfk_1` (`reviewer`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userName`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`journalName`) REFERENCES `journals` (`name`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`reviewer`) REFERENCES `users` (`userName`);

--
-- Constraints for table `journals`
--
ALTER TABLE `journals`
  ADD CONSTRAINT `journals_ibfk_1` FOREIGN KEY (`submitter`) REFERENCES `users` (`userName`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `revisions`
--
ALTER TABLE `revisions`
  ADD CONSTRAINT `revisions_ibfk_1` FOREIGN KEY (`originalName`) REFERENCES `journals` (`name`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `subprefs`
--
ALTER TABLE `subprefs`
  ADD CONSTRAINT `subprefs_ibfk_1` FOREIGN KEY (`reviewer`) REFERENCES `users` (`userName`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
