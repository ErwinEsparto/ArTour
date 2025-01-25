-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 25, 2025 at 01:49 AM
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
  `categoryId` int(11) NOT NULL,
  `imageId` int(11) NOT NULL,
  `category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categoryId`, `imageId`, `category`) VALUES
(1, 24, 'Abstract'),
(2, 25, 'Digital'),
(3, 26, 'Architecture'),
(4, 28, 'Nature'),
(5, 29, 'Painting'),
(6, 31, 'Photography'),
(7, 34, 'Sculpture'),
(8, 43, 'Animal'),
(9, 43, 'Nature'),
(10, 43, 'Drawing'),
(11, 47, 'Game'),
(12, 47, 'Digital'),
(13, 49, 'Animal'),
(14, 49, 'Photography'),
(15, 53, 'Game');

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `chatId` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `messengerId` int(11) NOT NULL,
  `receiverId` int(11) NOT NULL,
  `messageDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`chatId`, `message`, `messengerId`, `receiverId`, `messageDate`) VALUES
(1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus felis metus, vehicula ac dignissim sit amet, commodo et libero. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 2, 1, '2025-01-18 16:03:18'),
(2, 'Hello World', 1, 4, '2025-01-17 12:03:09');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `commentId` int(11) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `commentorId` int(11) NOT NULL,
  `postId` int(11) NOT NULL,
  `dateCommented` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`commentId`, `comment`, `commentorId`, `postId`, `dateCommented`) VALUES
(1, 'Wow', 2, 49, '2025-01-18 17:25:52'),
(2, 'Amazing', 1, 49, '2025-01-19 17:25:52');

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
(12, 5, 1, 4),
(13, 4, 1, 2),
(15, 5, 1, 1),
(22, 4, 1, 1),
(26, 1, 1, 2),
(27, 1, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `imageId` int(11) NOT NULL,
  `imageName` varchar(255) NOT NULL,
  `imageDescription` varchar(255) NOT NULL,
  `uploadDate` datetime NOT NULL,
  `reportStatus` int(11) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`imageId`, `imageName`, `imageDescription`, `uploadDate`, `reportStatus`, `userId`) VALUES
(24, 'abstract.jpg', 'Hmm', '2024-11-07 23:45:52', 0, 4),
(25, 'digital.jpg', '...', '2024-11-07 23:46:24', 1, 4),
(26, 'architecture.jpg', '(づ ◕‿◕ )づ', '2024-11-07 23:46:59', 0, 5),
(28, 'nature.jpg', '(⌐■_■)', '2024-11-07 23:48:01', 0, 5),
(29, 'painting.jpg', 'Amazing', '2024-11-07 23:48:48', 0, 2),
(31, 'photography.jpg', 'Impressive', '2024-11-07 23:49:27', 0, 2),
(34, 'sculpture.jpg', '💪', '2024-11-07 23:51:20', 0, 1),
(43, 'drawing.jpg', 'Wow', '2024-11-08 22:43:03', 0, 2),
(47, 'game.jpg', '', '2024-11-08 22:52:35', 0, 1),
(49, 'dog.jpg', '&lt;3', '2024-11-08 23:22:55', 0, 1);

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
(22, 43, 1, 1),
(25, 49, 1, 5),
(26, 47, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `profileId` int(11) NOT NULL,
  `profileType` int(11) NOT NULL,
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
  `dateCreated` date NOT NULL,
  `resetToken` varchar(64) DEFAULT NULL,
  `resetTokenExpire` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`profileId`, `profileType`, `profileName`, `profilePassword`, `profileDescription`, `profileAddress`, `profileEmail`, `profileNumber`, `profileFacebook`, `profileInstagram`, `profileX`, `profilePicture`, `dateCreated`, `resetToken`, `resetTokenExpire`) VALUES
(1, 2, 'Erwin Esparto', '$2y$10$vfx5vdOd68Dq9A/1rR.rue7qTr16tmbRZC1G.amnPJsehAl01hcqO', 'Time is gold when you&#039;re watching...', 'Philippines', 'kingpsycho15@gmail.com', '09123456789', 'Erwin Esparto', 'irwennn', 'Irwen', 'omen_12012024_1733029099.jpg', '2024-11-05', '5ee36d76d16ccc35ad2116ada31ad800cba86087acb44e7717d916f649b23370', '2025-01-03 17:25:52'),
(2, 2, 'Jaspher Baet', '$2y$10$vfx5vdOd68Dq9A/1rR.rue7qTr16tmbRZC1G.amnPJsehAl01hcqO', 'Nothing lasts forever.', 'USA', 'jasbaet09@gmail.com', '09876543211', 'Jaspher Baet', 'Jaskuno', 'Jaskuno', 'baet.jpg', '2024-11-06', NULL, NULL),
(4, 2, 'Kenneth Odgien', '$2y$10$vfx5vdOd68Dq9A/1rR.rue7qTr16tmbRZC1G.amnPJsehAl01hcqO', '', 'Canada', 'kennethodgien@gmail.com', '09567123811', '', '', '', 'odgien.png', '2024-11-06', NULL, NULL),
(5, 2, 'Lilac Goodrich', '$2y$10$vfx5vdOd68Dq9A/1rR.rue7qTr16tmbRZC1G.amnPJsehAl01hcqO', '', 'Japan', 'lilacgoodrich@gmail.com', '09189381289', '', '', '', 'goodrich.jpg', '2024-11-06', NULL, NULL),
(13, 1, 'ArTour', '$2y$10$4HNmfJkRXk2dbIAehNfrWuvmi02vmx7iPOuIz4cXF/l8mXfYaS8vi', '', 'Philippines', 'artour@gmail.com', '09912039102', 'ArTour', 'ArTour', 'ArTour', 'default.jpg', '2025-01-23', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoryId`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`chatId`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`commentId`);

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
  ADD PRIMARY KEY (`profileId`),
  ADD UNIQUE KEY `resetToken` (`resetToken`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `chatId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `commentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `follow`
--
ALTER TABLE `follow`
  MODIFY `followId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `imageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `likeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `profileId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
