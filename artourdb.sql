-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2024 at 05:22 PM
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
-- Database: `artourdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `imageId` int(11) NOT NULL,
  `category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`imageId`, `category`) VALUES
(24, 'Abstract'),
(25, 'Digital'),
(26, 'Architecture'),
(28, 'Nature'),
(29, 'Painting'),
(31, 'Photography'),
(34, 'Sculpture'),
(43, 'Animal'),
(43, 'Nature'),
(43, 'Drawing'),
(47, 'Game'),
(47, 'Digital'),
(49, 'Animal'),
(49, 'Photography');

-- --------------------------------------------------------

--
-- Table structure for table `follow`
--

CREATE TABLE `follow` (
  `followId` int(11) NOT NULL,
  `followerId` int(11) NOT NULL,
  `followStatus` int(11) NOT NULL,
  `followedId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `follow`
--

INSERT INTO `follow` (`followId`, `followerId`, `followStatus`, `followedId`) VALUES
(4, 1, 1, 4),
(6, 1, 1, 5),
(12, 5, 1, 4),
(13, 4, 1, 2),
(14, 2, 1, 1),
(15, 5, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `imageId` int(11) NOT NULL,
  `imageName` varchar(255) NOT NULL,
  `imageDescription` varchar(255) NOT NULL,
  `uploadDate` datetime NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`imageId`, `imageName`, `imageDescription`, `uploadDate`, `userId`) VALUES
(24, 'abstract.jpg', 'Hmm', '2024-11-07 23:45:52', 4),
(25, 'digital.jpg', '...', '2024-11-07 23:46:24', 4),
(26, 'architecture.jpg', '(づ ◕‿◕ )づ', '2024-11-07 23:46:59', 5),
(28, 'nature.jpg', '(⌐■_■)', '2024-11-07 23:48:01', 5),
(29, 'painting.jpg', 'Amazing', '2024-11-07 23:48:48', 2),
(31, 'photography.jpg', 'Impressive', '2024-11-07 23:49:27', 2),
(34, 'sculpture.jpg', '💪', '2024-11-07 23:51:20', 1),
(43, 'drawing.jpg', 'Wow', '2024-11-08 22:43:03', 2),
(47, 'game.jpg', '', '2024-11-08 22:52:35', 1),
(49, 'dog.jpg', '&lt;3', '2024-11-08 23:22:55', 1);

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `likeId` int(11) NOT NULL,
  `imageId` int(11) NOT NULL,
  `likeStatus` int(11) NOT NULL,
  `profileId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`likeId`, `imageId`, `likeStatus`, `profileId`) VALUES
(13, 31, 1, 1),
(14, 26, 1, 1),
(15, 28, 1, 1),
(22, 43, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `profileId` int(11) NOT NULL,
  `profileName` varchar(255) NOT NULL,
  `profilePassword` varchar(255) NOT NULL,
  `profileDescription` varchar(255) NOT NULL,
  `profileAddress` varchar(255) NOT NULL,
  `profileEmail` varchar(255) NOT NULL,
  `profileNumber` varchar(11) NOT NULL,
  `profileFacebook` varchar(255) NOT NULL,
  `profileInstagram` varchar(255) NOT NULL,
  `profileX` varchar(255) NOT NULL,
  `profilePicture` varchar(255) NOT NULL,
  `dateCreated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`profileId`, `profileName`, `profilePassword`, `profileDescription`, `profileAddress`, `profileEmail`, `profileNumber`, `profileFacebook`, `profileInstagram`, `profileX`, `profilePicture`, `dateCreated`) VALUES
(1, 'Erwin Esparto', '123', 'Time is gold when you&#039;re watching...', 'Philippines', 'erwinesparto@gmail.com', '09123456789', 'Erwin Esparto', 'irwennn', 'Irwen', 'erwin.jpg', '2024-11-05'),
(2, 'Jaspher Baet', '111', 'Nothing lasts forever.', 'USA', 'jaspherbaet@gmail.com', '09876543211', 'Jaspher Baet', 'Jaskuno', 'Jaskuno', 'baet.jpg', '2024-11-06'),
(4, 'Kenneth Odgien', '321', '', 'Canada', 'kennethodgien@gmail.com', '09567123811', '', '', '', 'odgien.png', '2024-11-06'),
(5, 'Lilac Goodrich', '213', '', 'Japan', 'lilacgoodrich@gmail.com', '09189381289', '', '', '', 'goodrich.jpg', '2024-11-06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `follow`
--
ALTER TABLE `follow`
  ADD PRIMARY KEY (`followId`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`imageId`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`likeId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`profileId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `follow`
--
ALTER TABLE `follow`
  MODIFY `followId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `imageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `likeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `profileId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
