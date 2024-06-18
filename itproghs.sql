-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2024 at 12:39 PM
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
-- Database: `grocerease`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phoneNumber` varchar(13) NOT NULL,
  `password` varchar(200) NOT NULL,
  `profilePicture` mediumblob NOT NULL,
  `admin_checker` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`firstName`, `lastName`, `email`, `phoneNumber`, `password`, `profilePicture`, `admin_checker`) VALUES
('Jerome', 'Victoria', 'jerome.victoriasiy@gmail.com', '+639665004905', '$2y$10$jaEeFyDpfX1rBwvITfVz7u6SZ/iMnNDZekpHNoWlkz1dpk9px1PfO', 0x75706c6f6164732f3132332e6a7067, 1),
('Barry', 'Ran', 'harry@gmail.com', '+639665004905', '$2y$10$wEwOQT3EsEhLpQmOpgJtCuwSBhBmBkSLqtixOjGfLerOEnWAUBr.e', 0x75706c6f6164732f3132332e6a7067, 0),
('Garry', 'Ray', 'garry@gmail.com', '+639665004905', '$2y$10$0H7gah3WrBX57VIvkZKQn.tmR/gC0zhrZl33iVXE87dAmWcvogmBa', 0x75706c6f6164732f3132332e6a7067, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
