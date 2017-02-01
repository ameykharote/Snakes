-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2016 at 11:52 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `snakes`
--

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE IF NOT EXISTS `scores` (
  `email_id` varchar(100) NOT NULL,
  `fname` varchar(30) NOT NULL,
  `score` int(4) NOT NULL,
  `mega_point` varchar(20) NOT NULL,
  `played` int(10) NOT NULL,
  PRIMARY KEY (`email_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `scores`
--

INSERT INTO `scores` (`email_id`, `fname`, `score`, `mega_point`, `played`) VALUES
('akashgund@gmail.com', 'Akash', 32, 'No Megapoints', 1),
('ameykharote@gmail.com', 'Amey', 29, '', 0),
('ninoshkapinto@gmail.com', 'Ninoshka', 25, '', 0),
('nishalabraham@gmail.com', 'Nishcal', 31, 'No Megapoints', 0),
('ronfer@gmail.com', 'agin', 31, '', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
