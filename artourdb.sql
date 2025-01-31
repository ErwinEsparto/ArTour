-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 31, 2025 at 11:22 AM
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
(15, 53, 'Game'),
(39, 63, 'Game'),
(40, 63, 'Digital'),
(41, 63, 'None');

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `chatId` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `imageName` varchar(255) DEFAULT NULL,
  `messengerId` int(11) NOT NULL,
  `receiverId` int(11) NOT NULL,
  `messageDate` datetime DEFAULT NULL,
  `readStatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`chatId`, `message`, `imageName`, `messengerId`, `receiverId`, `messageDate`, `readStatus`) VALUES
(6, 'Doggo', 'dog_01312025_1738318867.jpg', 1, 4, '2025-01-31 18:21:07', 1),
(7, 'Wow', NULL, 4, 1, '2025-01-31 18:21:25', 1);

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
(1, 'Cute', 2, 49, '2025-01-30 20:21:42'),
(2, 'Sheesh', 4, 47, '2025-01-30 20:29:28'),
(3, '🔥', 5, 34, '2025-01-30 22:36:39'),
(4, 'Woah', 1, 43, '2025-01-30 23:15:29');

-- --------------------------------------------------------

--
-- Table structure for table `follow`
--

CREATE TABLE `follow` (
  `followId` int(11) NOT NULL,
  `followerId` int(11) NOT NULL,
  `followStatus` int(11) NOT NULL,
  `followedId` int(11) NOT NULL,
  `lastMessageDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `follow`
--

INSERT INTO `follow` (`followId`, `followerId`, `followStatus`, `followedId`, `lastMessageDate`) VALUES
(1, 1, 1, 2, NULL),
(3, 1, 1, 4, '2025-01-31 18:21:25'),
(4, 2, 1, 1, NULL),
(7, 5, 1, 1, NULL),
(8, 4, 1, 1, '2025-01-31 18:21:25');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `imageId` int(11) NOT NULL,
  `imageName` varchar(255) NOT NULL,
  `imageDescription` varchar(255) NOT NULL,
  `uploadDate` datetime NOT NULL,
  `deleteStatus` int(11) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`imageId`, `imageName`, `imageDescription`, `uploadDate`, `deleteStatus`, `userId`) VALUES
(24, 'abstract.jpg', 'Hmm', '2024-11-07 23:45:52', 0, 4),
(25, 'digital.jpg', '...', '2024-11-07 23:46:24', 0, 4),
(26, 'architecture.jpg', '(づ ◕‿◕ )づ', '2024-11-07 23:46:59', 0, 5),
(28, 'nature.jpg', '(⌐■_■)', '2024-11-07 23:48:01', 0, 5),
(29, 'painting.jpg', 'Amazing', '2024-11-07 23:48:48', 0, 2),
(31, 'photography.jpg', 'Impressive', '2024-11-07 23:49:27', 0, 2),
(34, 'sculpture.jpg', '🗿', '2025-01-30 23:18:57', 0, 1),
(43, 'drawing.jpg', 'Wow', '2024-11-08 22:43:03', 0, 2),
(47, 'game.jpg', 'Red Dead Redemption 2', '2025-01-30 16:42:16', 0, 1),
(49, 'dog.jpg', 'Honey Bee &lt;3', '2025-01-30 19:13:10', 0, 1),
(63, 'malenia_01302025_1738252199.jpg', 'Elden Ring', '2025-01-30 23:49:59', 0, 1);

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
(1, 31, 1, 1),
(2, 28, 1, 1),
(3, 25, 1, 1),
(7, 47, 1, 5),
(8, 49, 1, 2),
(9, 34, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notificationId` int(11) NOT NULL,
  `notifierId` int(11) NOT NULL,
  `notifType` int(11) NOT NULL,
  `imageId` int(11) DEFAULT NULL,
  `notifiedId` int(11) NOT NULL,
  `notifyDate` datetime NOT NULL,
  `readStatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notificationId`, `notifierId`, `notifType`, `imageId`, `notifiedId`, `notifyDate`, `readStatus`) VALUES
(1, 1, 1, 31, 2, '2025-01-30 20:20:38', 0),
(2, 1, 3, NULL, 2, '2025-01-30 20:20:40', 0),
(3, 1, 1, 28, 5, '2025-01-30 20:21:01', 0),
(4, 1, 3, NULL, 5, '2025-01-30 20:21:02', 0),
(5, 1, 1, 25, 4, '2025-01-30 20:21:09', 0),
(6, 1, 3, NULL, 4, '2025-01-30 20:21:10', 0),
(7, 2, 9, NULL, 2, '2025-01-30 20:21:34', 1),
(10, 2, 2, 49, 1, '2025-01-30 20:21:42', 1),
(11, 5, 9, NULL, 5, '2025-01-30 20:21:54', 1),
(14, 1, 9, NULL, 1, '2025-01-30 20:22:06', 1),
(15, 4, 9, NULL, 4, '2025-01-30 20:29:23', 1),
(17, 1, 9, NULL, 1, '2025-01-30 20:29:37', 1),
(18, 1, 10, NULL, 5, '2025-01-30 20:57:28', 0),
(19, 5, 9, NULL, 5, '2025-01-30 21:14:53', 1),
(24, 1, 9, NULL, 1, '2025-01-30 21:15:10', 1),
(25, 5, 9, NULL, 5, '2025-01-30 21:25:03', 1),
(28, 1, 9, NULL, 1, '2025-01-30 21:25:17', 1),
(29, 5, 9, NULL, 5, '2025-01-30 21:25:28', 1),
(30, 5, 1, 47, 1, '2025-01-30 21:25:31', 1),
(31, 5, 3, NULL, 1, '2025-01-30 21:25:32', 1),
(32, 1, 9, NULL, 1, '2025-01-30 21:25:40', 1),
(33, 2, 9, NULL, 2, '2025-01-30 22:32:03', 1),
(36, 1, 9, NULL, 1, '2025-01-30 22:32:15', 1),
(37, 4, 9, NULL, 4, '2025-01-30 22:35:56', 1),
(38, 4, 1, 34, 1, '2025-01-30 22:36:09', 0),
(39, 4, 3, NULL, 1, '2025-01-30 22:36:12', 0),
(40, 5, 9, NULL, 5, '2025-01-30 22:36:23', 1),
(41, 5, 2, 34, 1, '2025-01-30 22:36:39', 1),
(42, 1, 9, NULL, 1, '2025-01-30 22:36:49', 1),
(43, 1, 2, 43, 2, '2025-01-30 23:15:29', 0),
(44, 1, 7, 34, 1, '2025-01-30 23:18:57', 1),
(45, 1, 12, 63, 1, '2025-01-30 23:49:59', 1),
(47, 2, 9, NULL, 2, '2025-01-30 23:58:53', 1),
(49, 1, 9, NULL, 1, '2025-01-30 23:59:09', 1),
(52, 1, 9, NULL, 1, '2025-01-31 00:55:09', 1),
(53, 1, 9, NULL, 1, '2025-01-31 11:24:26', 1),
(54, 2, 9, NULL, 2, '2025-01-31 12:53:23', 1),
(55, 5, 9, NULL, 5, '2025-01-31 12:55:06', 1),
(56, 5, 2, 29, 2, '2025-01-31 13:13:53', 0),
(57, 5, 2, 29, 2, '2025-01-31 13:15:07', 0),
(58, 1, 9, NULL, 1, '2025-01-31 13:22:24', 1),
(59, 2, 9, NULL, 2, '2025-01-31 13:25:39', 1),
(60, 13, 9, NULL, 13, '2025-01-31 13:33:15', 1),
(61, 1, 9, NULL, 1, '2025-01-31 13:37:58', 1),
(62, 5, 9, NULL, 5, '2025-01-31 13:38:25', 1),
(63, 13, 9, NULL, 13, '2025-01-31 13:39:27', 1),
(64, 1, 9, NULL, 1, '2025-01-31 13:41:12', 1),
(65, 13, 9, NULL, 13, '2025-01-31 13:47:34', 1),
(66, 13, 9, NULL, 13, '2025-01-31 13:48:23', 1),
(67, 1, 9, NULL, 1, '2025-01-31 13:49:32', 1),
(68, 13, 9, NULL, 13, '2025-01-31 13:51:11', 1),
(69, 1, 9, NULL, 1, '2025-01-31 13:51:41', 1),
(70, 1, 9, NULL, 1, '2025-01-31 13:53:42', 1),
(71, 13, 9, NULL, 13, '2025-01-31 13:54:07', 1),
(72, 1, 9, NULL, 1, '2025-01-31 13:56:51', 1),
(73, 1, 9, NULL, 1, '2025-01-31 14:00:47', 1),
(74, 1, 9, NULL, 1, '2025-01-31 14:03:35', 1),
(75, 1, 9, NULL, 1, '2025-01-31 14:04:50', 1),
(76, 1, 9, NULL, 1, '2025-01-31 14:06:42', 1),
(77, 1, 9, NULL, 1, '2025-01-31 14:08:20', 1),
(78, 1, 9, NULL, 1, '2025-01-31 14:09:53', 1),
(79, 5, 9, NULL, 5, '2025-01-31 14:13:10', 1),
(80, 13, 9, NULL, 13, '2025-01-31 14:19:34', 1),
(81, 1, 9, NULL, 1, '2025-01-31 15:46:19', 1),
(82, 13, 9, NULL, 13, '2025-01-31 15:53:44', 1),
(83, 1, 9, NULL, 1, '2025-01-31 15:55:11', 1),
(84, 13, 9, NULL, 13, '2025-01-31 16:47:38', 1),
(85, 13, 9, NULL, 13, '2025-01-31 16:48:22', 1),
(86, 1, 9, NULL, 1, '2025-01-31 16:49:40', 1),
(87, 1, 9, NULL, 1, '2025-01-31 16:53:27', 1),
(88, 1, 9, NULL, 1, '2025-01-31 17:00:54', 1),
(89, 1, 9, NULL, 1, '2025-01-31 17:03:06', 1),
(90, 1, 9, NULL, 1, '2025-01-31 17:03:29', 1),
(91, 1, 9, NULL, 1, '2025-01-31 17:04:11', 1),
(92, 1, 9, NULL, 1, '2025-01-31 17:04:42', 1),
(93, 1, 9, NULL, 1, '2025-01-31 17:05:07', 1),
(94, 1, 9, NULL, 1, '2025-01-31 17:06:11', 1),
(95, 1, 9, NULL, 1, '2025-01-31 17:06:56', 1),
(96, 1, 9, NULL, 1, '2025-01-31 17:07:56', 1),
(97, 1, 9, NULL, 1, '2025-01-31 17:09:06', 1),
(98, 13, 9, NULL, 13, '2025-01-31 17:10:27', 1),
(99, 1, 9, NULL, 1, '2025-01-31 17:11:24', 1),
(100, 1, 9, NULL, 1, '2025-01-31 17:12:06', 1),
(101, 1, 9, NULL, 1, '2025-01-31 17:12:32', 1),
(102, 13, 9, NULL, 13, '2025-01-31 17:14:20', 1),
(103, 1, 9, NULL, 1, '2025-01-31 17:16:05', 1),
(104, 1, 9, NULL, 1, '2025-01-31 17:21:32', 1),
(106, 13, 9, NULL, 13, '2025-01-31 17:26:40', 1),
(107, 1, 9, NULL, 1, '2025-01-31 17:29:46', 1),
(111, 4, 9, NULL, 4, '2025-01-31 18:14:20', 1),
(112, 1, 9, NULL, 1, '2025-01-31 18:16:02', 1),
(114, 4, 4, NULL, 1, '2025-01-31 18:21:25', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `reportId` int(11) NOT NULL,
  `reportedId` int(11) NOT NULL,
  `reportReason` varchar(255) NOT NULL,
  `imageId` int(11) DEFAULT NULL,
  `reportType` int(11) NOT NULL,
  `reporterId` int(11) NOT NULL,
  `reportDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`reportId`, `reportedId`, `reportReason`, `imageId`, `reportType`, `reporterId`, `reportDate`) VALUES
(20, 2, 'Stealing Artwork', NULL, 2, 1, '2025-01-31 14:12:57'),
(21, 2, 'Inappropriate Post', 29, 1, 5, '2025-01-31 14:13:23');

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
  `activeStatus` int(11) NOT NULL,
  `resetToken` varchar(64) DEFAULT NULL,
  `resetTokenExpire` datetime DEFAULT NULL,
  `verifyStatus` int(11) NOT NULL,
  `verifyToken` varchar(64) DEFAULT NULL,
  `verifyTokenExpire` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`profileId`, `profileType`, `profileName`, `profilePassword`, `profileDescription`, `profileAddress`, `profileEmail`, `profileNumber`, `profileFacebook`, `profileInstagram`, `profileX`, `profilePicture`, `dateCreated`, `activeStatus`, `resetToken`, `resetTokenExpire`, `verifyStatus`, `verifyToken`, `verifyTokenExpire`) VALUES
(1, 2, 'Erwin Esparto', '$2y$10$vfx5vdOd68Dq9A/1rR.rue7qTr16tmbRZC1G.amnPJsehAl01hcqO', 'Time is gold when you&#039;re watching...', 'Philippines', 'kingpsycho15@gmail.com', '09123456789', 'Erwin Esparto', 'irwennn', 'Irwen', 'Erwin Esparto_01302025_1738235216.jpg', '2024-11-05', 1, NULL, NULL, 1, NULL, NULL),
(2, 2, 'Jaspher Baet', '$2y$10$vfx5vdOd68Dq9A/1rR.rue7qTr16tmbRZC1G.amnPJsehAl01hcqO', 'Nothing lasts forever.', 'USA', 'jasbaet09@gmail.com', '09876543211', 'Jaspher Baet', 'Jaskuno', 'Jaskuno', 'baet.jpg', '2024-11-06', 1, NULL, NULL, 1, NULL, NULL),
(4, 2, 'Kenneth Odgien', '$2y$10$vfx5vdOd68Dq9A/1rR.rue7qTr16tmbRZC1G.amnPJsehAl01hcqO', '', 'Canada', 'kennethodgien@gmail.com', '09567123811', '', '', '', 'odgien.png', '2024-11-06', 1, NULL, NULL, 1, NULL, NULL),
(5, 2, 'Lilac Goodrich', '$2y$10$vfx5vdOd68Dq9A/1rR.rue7qTr16tmbRZC1G.amnPJsehAl01hcqO', '', 'Japan', 'lilacgoodrich@gmail.com', '09189381289', '', '', '', 'goodrich.jpg', '2024-11-06', 1, NULL, NULL, 1, NULL, NULL),
(13, 1, 'ArTour', '$2y$10$4HNmfJkRXk2dbIAehNfrWuvmi02vmx7iPOuIz4cXF/l8mXfYaS8vi', '', 'Philippines', 'artour@gmail.com', '09912039102', 'ArTour', 'ArTour', 'ArTour', 'default.jpg', '2025-01-23', 1, NULL, NULL, 1, NULL, NULL),
(21, 2, 'Aurora Lights', '$2y$10$4unJsY6R.4tTYo4.ET38K.h6VCMEMo8rNIAswWkZIx/pM4zyxouKC', '', 'Philippines', 'auroralightsbsit@gmail.com', '09001312039', 'Not Available', 'Not Available', 'Not Available', 'default.jpg', '2025-01-31', 1, NULL, NULL, 1, NULL, NULL);

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notificationId`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`reportId`);

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
  MODIFY `categoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `chatId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `commentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `follow`
--
ALTER TABLE `follow`
  MODIFY `followId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `imageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `likeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notificationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `reportId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `profileId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
