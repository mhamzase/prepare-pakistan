-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2021 at 02:37 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prepare-pakistan`
--

-- --------------------------------------------------------

--
-- Table structure for table `chapters`
--

CREATE TABLE `chapters` (
  `id` int(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `chapter_file` varchar(500) NOT NULL,
  `subject_id` int(20) NOT NULL,
  `user_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `chapters`
--

INSERT INTO `chapters` (`id`, `name`, `chapter_file`, `subject_id`, `user_id`) VALUES
(36, 'chapter 1 - intro to computer', 'chapter-files/98b297950041a42470269d56260243a1.sample.pdf', 28, 1),
(37, 'chapter 2 - what is computer hardware?', 'chapter-files/d07e70efcfab08731a97e7b91be644de.sample.pdf', 28, 1),
(38, 'chapter 1 - intro to physics', 'chapter-files/ca75910166da03ff9d4655a0338e6b09.sample.pdf', 29, 1),
(39, 'chapter 1 - intro to chesmistry', 'chapter-files/4a47d2983c8bd392b120b627e0e1cab4.sample.pdf', 27, 1),
(40, 'chapter 2 - explain about physics?', 'chapter-files/b55ec28c52d5f6205684a473a2193564.sample.pdf', 29, 1),
(41, 'chapter 1 - intro to phy', 'chapter-files/36660e59856b4de58a219bcf4e27eba3.Financial Management - MGT201 Mcqs.pdf', 31, 12);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(20) NOT NULL,
  `question` varchar(255) NOT NULL,
  `option1` varchar(50) NOT NULL,
  `option2` varchar(50) NOT NULL,
  `option3` varchar(50) NOT NULL,
  `option4` varchar(50) NOT NULL,
  `correct_answer` varchar(50) NOT NULL,
  `quiz_id` int(20) NOT NULL,
  `user_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question`, `option1`, `option2`, `option3`, `option4`, `correct_answer`, `quiz_id`, `user_id`) VALUES
(19, 'what is a computer?', 'digital machine', 'none', 'calculator', 'control system', 'digital machine', 19, 1),
(20, 'pc stand for', 'personal computer', 'p laptop', 'none', 'both', 'personal computer', 19, 1),
(21, 'what is hardware?', 'physical thing', 'virtual thing', 'both', 'none', 'physical thing', 20, 1),
(22, 'what is first three letters of physics?', 'phi', 'phe', 'phy', 'none', 'phy', 21, 1),
(23, 'what is chemistry?', 'chemical solutions', 'chemical instruction', 'either', 'none', 'chemical solutions', 22, 1),
(24, 'dummy quiz ', 'a', 'b', 'c', 'd', 'b', 19, 1),
(25, 'the computer is derived from word?', 'compute', 'comp', 'campsir', 'none', 'compute', 19, 1),
(26, 'An electronic tool that allows information to be input, processed, and output:', 'Operating system', 'Motherboard', 'CPU', 'Computer', 'Computer', 19, 1),
(27, 'A program that controls a computer\'s basic functions:', 'Hard Drive', 'Motherboard', 'Operating System', 'CPU', 'Operating System', 19, 1),
(28, 'A small picture that represents a folder, program or other things:', 'Desktop', 'Icon', 'Graphics', 'none', 'Icon', 19, 1),
(29, 'A name for the short term memory of the computer that is lost when the computer is turned off:', 'CPU', 'Hardware', 'RAM', 'Processor', 'RAM', 19, 1),
(31, 'ASDFASDFASDFASDF', 'asdf', 'asdfasdfasdfASDF', 'ASDFASDFSSSS', 'ASDF', 'ASDFSSSS', 23, 0);

-- --------------------------------------------------------

--
-- Table structure for table `quizes`
--

CREATE TABLE `quizes` (
  `id` int(20) NOT NULL,
  `chapter_id` int(20) NOT NULL,
  `user_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quizes`
--

INSERT INTO `quizes` (`id`, `chapter_id`, `user_id`) VALUES
(19, 36, 1),
(20, 37, 1),
(21, 38, 1),
(22, 39, 1),
(23, 41, 0);

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(20) NOT NULL,
  `quiz_id` int(20) NOT NULL,
  `total_marks` int(20) NOT NULL,
  `obtained_marks` int(50) NOT NULL,
  `user_id` int(10) NOT NULL,
  `status` varchar(50) NOT NULL,
  `taken_date` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `test_id` int(20) NOT NULL,
  `user_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `test_id`, `user_id`) VALUES
(27, 'Chemistry', 39, 1),
(28, 'Computer Science', 39, 1),
(29, 'Physics', 39, 1),
(31, 'Physics', 41, 12);

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `id` int(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `test_file` varchar(255) NOT NULL,
  `user_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`id`, `name`, `test_file`, `user_id`) VALUES
(39, 'CSS Screening Test', 'test-files/2bcab9d935d219641434683dd9d18a03.sample.pdf', 1),
(41, 'IQ Online Test sheet', '', 12);

-- --------------------------------------------------------

--
-- Table structure for table `test_results`
--

CREATE TABLE `test_results` (
  `id` int(20) NOT NULL,
  `test_id` int(20) NOT NULL,
  `obtained_marks` varchar(50) NOT NULL,
  `total_marks` varchar(50) NOT NULL,
  `user_id` int(10) NOT NULL,
  `status` varchar(20) NOT NULL,
  `taken_date` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(5) NOT NULL,
  `username` varchar(50) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(64) NOT NULL,
  `type` int(2) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `bio` varchar(500) NOT NULL,
  `street` varchar(500) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(20) NOT NULL,
  `zipcode` varchar(20) NOT NULL,
  `token` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `fullname`, `email`, `password`, `type`, `phone`, `bio`, `street`, `city`, `state`, `zipcode`, `token`) VALUES
(1, 'admin', '', 'admin@gmail.com', '$2y$10$US.c6iVyo1kMQQzdSI.3muzvHFl4leSdntbNvwrhno5GH2G.8k9Ya', 0, '', '', '', '', '', '', ''),
(12, 'mrhaimi', 'Muhammad Hamza Shabbir', 'mhamzasulehri143@gmail.com', '$2y$10$D6eZvM6XqFeIstgwTP/Y6eGiVUbOjOeLhRxp8foJlQQedWJevuOea', 2, '03042445911', 'I am a software engineer ', 'Ward # 5 Muhalla Sulehri Town Tehsil & P/O Zafarwal District Narowal Punjab', 'Zafarwal', 'Punjab', '51670', 'b5657208927e51811aefc00d9eab39b4'),
(13, 'aliraza2', '', 'aliraza@gmail.com', '$2y$10$gIRN5g79NYtF2EiVVhKjae.4iLll8JC9bwGAol.dhYw2kyYYke9OO', 1, '', '', '', '', '', '', 'c91f76fccc51d5a89564958054c32472'),
(14, 'javeed', '', 'javeed@yahoo.com', '$2y$10$.e25k4l23KvsWWn.FSdxDeM1/u4Xr16WavxCY7Yk/Vyp71EkcRL2u', 1, '', '', '', '', '', '', '7fdd1fd206f644f8847e3b5c0581d948');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chapters`
--
ALTER TABLE `chapters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `quizes`
--
ALTER TABLE `quizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chapter_id` (`chapter_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_id` (`test_id`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_results`
--
ALTER TABLE `test_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_id` (`test_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chapters`
--
ALTER TABLE `chapters`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `quizes`
--
ALTER TABLE `quizes`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `test_results`
--
ALTER TABLE `test_results`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chapters`
--
ALTER TABLE `chapters`
  ADD CONSTRAINT `chapters_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quizes`
--
ALTER TABLE `quizes`
  ADD CONSTRAINT `quizes_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `test_results`
--
ALTER TABLE `test_results`
  ADD CONSTRAINT `test_results_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `test_results_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
