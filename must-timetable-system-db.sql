-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2025 at 04:47 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `must-timetable-system-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `timetables_created` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `admin_id`, `timetables_created`) VALUES
(1, 'Software Engineering', 4, 1),
(2, 'Computer Science', 2, 0),
(3, 'Information Technology', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `session` varchar(10) NOT NULL,
  `subject_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `feedback` text NOT NULL,
  `profile_pic` varchar(255) DEFAULT 'default-avatar.jpg',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `requested_by` int(11) NOT NULL,
  `reason` text NOT NULL,
  `requested_time` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_name` varchar(100) NOT NULL,
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_name`, `department_id`) VALUES
(2, 'LT 2', 1),
(4, 'D & T LAB', 1),
(5, 'A & D LAB', 1),
(6, 'ROOM 2', 1),
(7, 'ROOM 3', 1),
(9, 'GP LAB', 1),
(10, 'ARFA KAREEM LAB', 1),
(11, 'LT 1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `section` varchar(10) NOT NULL,
  `duration` varchar(10) NOT NULL,
  `same_time` enum('yes','no') NOT NULL DEFAULT 'no',
  `days_per_week` int(11) NOT NULL DEFAULT '1',
  `class_type` enum('Theory','Lab') NOT NULL DEFAULT 'Theory',
  `preferred_days` text NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `year` varchar(4) NOT NULL DEFAULT '2025',
  `semester` varchar(10) NOT NULL,
  `session` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `department_id`, `subject_name`, `teacher_id`, `section`, `duration`, `same_time`, `days_per_week`, `class_type`, `preferred_days`, `room_id`, `year`, `semester`, `session`) VALUES
(1, 0, 'Formal Methods(Theory)', 14, '', '', 'no', 1, 'Theory', '', NULL, '2025', '', NULL),
(2, 0, 'SDA (Theory)', 15, '', '', 'no', 1, 'Theory', '', NULL, '2025', '', NULL),
(3, 0, 'SDA (Lab)', 17, '', '', 'no', 1, 'Theory', '', NULL, '2025', '', NULL),
(4, 0, 'WEB', 14, '', '', 'no', 1, 'Theory', '', NULL, '2025', '', NULL),
(5, 0, 'Formal Methods', 25, 'A', '1.5', 'no', 2, 'Theory', 'Monday, Wednesday', NULL, '2025', '', NULL),
(6, 0, 'Formal Methods', 25, 'B', '1.5', 'no', 2, 'Theory', 'Monday, Wednesday', NULL, '2025', '', NULL),
(7, 0, 'SDA', 30, 'B', '3', 'no', 1, 'Lab', 'Tuesday', NULL, '2025', '', NULL),
(51, 1, 'DIP', 27, 'A', '2', 'no', 2, 'Theory', 'Monday, Friday', 6, '2025', '', '21-25'),
(52, 1, 'DIP', 27, 'A', '1.5', 'no', 2, 'Theory', 'Tuesday, Friday', 7, '2025', 'Fall', '21-25'),
(53, 1, 'DIP', 27, 'B', '1.5', 'no', 2, 'Theory', 'Wednesday, Friday', 7, '2025', 'Fall', '21-25'),
(54, 1, 'DIP', 26, 'A', '3', 'no', 1, 'Lab', 'Thursday', 4, '2025', 'Fall', '21-25'),
(55, 1, 'DIP', 26, 'B', '3', 'no', 1, 'Lab', 'Friday', 5, '2025', 'Fall', '21-25'),
(56, 1, 'FP', 30, 'A', '2', 'yes', 1, 'Theory', 'Tuesday', 2, '2025', 'Fall', '21-25'),
(57, 1, 'FP', 30, 'B', '2', 'yes', 1, 'Theory', 'Tuesday', 2, '2025', 'Fall', '21-25'),
(58, 1, 'Formal Methods', 26, 'A', '1.5', 'no', 2, 'Theory', 'Tuesday, Friday', 9, '2025', 'Fall', '22-26'),
(59, 1, 'Formal Methods', 26, 'B', '1.5', 'no', 2, 'Theory', 'Monday, Thursday', 2, '2025', 'Fall', '22-26'),
(64, 1, 'OOP', 31, 'A', '3', 'yes', 1, 'Lab', 'Tuesday, Friday', 2, '2025', 'Fall', '23-27'),
(65, 1, 'OOP', 31, 'B', '3', 'yes', 1, 'Lab', 'Tuesday, Friday', 2, '2025', 'Fall', '23-27'),
(68, 1, 'SDA', 29, 'A', '3', 'yes', 1, 'Lab', 'Wednesday', 5, '2025', 'Fall', '22-26'),
(69, 1, 'SDA', 29, 'B', '3', 'yes', 1, 'Lab', 'Wednesday', 5, '2025', 'Fall', '22-26'),
(70, 1, 'FM', 33, 'A', '1.5', 'yes', 1, 'Theory', 'Wednesday', 11, '2025', '', '24-28'),
(71, 1, 'FM', 33, 'B', '1.5', 'yes', 1, 'Theory', 'Wednesday', 11, '2025', '', '24-28');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `available_time` text,
  `available_days` text,
  `name` varchar(255) NOT NULL,
  `contact_no` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_course`
--

CREATE TABLE `teacher_course` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `feedback` text NOT NULL,
  `profile_pic` varchar(255) DEFAULT 'default-avatar.jpg',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `feedback`, `profile_pic`, `created_at`) VALUES
(1, 'Ali Khan', 'Great system! It made scheduling so easy.', 'public/images/user1.jpg', '2025-02-09 14:24:57'),
(2, 'Ayesha Ahmed', 'Very helpful platform for students and teachers.', 'public/images/user2.jpg', '2025-02-09 14:24:57'),
(3, 'Noor', 'Make it more better.', 'https://th.bing.com/th?q=Gender-Neutral+Icon+Avatar&w=120&h=120&c=1&rs=1&qlt=90&cb=1&pid=InlineBlock&mkt=en-WW&cc=PK&setlang=en&adlt=moderate&t=1&mw=247', '2025-03-03 12:06:21'),
(4, 'Engr. Ahmed', 'This system is easy to navigate.', 'https://th.bing.com/th?q=Gender-Neutral+Icon+Avatar&w=120&h=120&c=1&rs=1&qlt=90&cb=1&pid=InlineBlock&mkt=en-WW&cc=PK&setlang=en&adlt=moderate&t=1&mw=247', '2025-03-03 12:07:54'),
(5, 'Hamna', 'Great Effort.', 'https://th.bing.com/th?q=Gender-Neutral+Icon+Avatar&w=120&h=120&c=1&rs=1&qlt=90&cb=1&pid=InlineBlock&mkt=en-WW&cc=PK&setlang=en&adlt=moderate&t=1&mw=247', '2025-03-03 12:08:37'),
(6, 'Muqadas Meherban', 'The system is intuitive and easy to navigate.', 'https://th.bing.com/th?q=Gender-Neutral+Icon+Avatar&w=120&h=120&c=1&rs=1&qlt=90&cb=1&pid=InlineBlock&mkt=en-WW&cc=PK&setlang=en&adlt=moderate&t=1&mw=247', '2025-03-05 06:53:15');

-- --------------------------------------------------------

--
-- Table structure for table `timetables`
--

CREATE TABLE `timetables` (
  `id` int(11) NOT NULL,
  `session` varchar(20) NOT NULL,
  `section` varchar(10) NOT NULL,
  `day` varchar(20) NOT NULL,
  `time_slot` varchar(20) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `semester` varchar(10) NOT NULL,
  `generated_date` datetime NOT NULL,
  `is_confirmed` tinyint(1) DEFAULT '0',
  `year` varchar(4) NOT NULL DEFAULT '2025',
  `duration` float NOT NULL DEFAULT '1.5',
  `class_type` varchar(10) DEFAULT 'Theory'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timetables`
--

INSERT INTO `timetables` (`id`, `session`, `section`, `day`, `time_slot`, `subject_id`, `teacher_id`, `room_id`, `department_id`, `semester`, `generated_date`, `is_confirmed`, `year`, `duration`, `class_type`) VALUES
(2505, '21-25', 'A', 'Friday', '10:00-11:30', 51, 27, 6, 1, 'Fall', '2025-03-06 09:03:05', 1, '2025', 1.5, 'Theory'),
(2506, '21-25', 'A', 'Monday', '11:30-13:00', 51, 27, 6, 1, 'Fall', '2025-03-06 09:03:05', 1, '2025', 1.5, 'Theory'),
(2507, '21-25', 'A', 'Tuesday', '15:00-16:30', 52, 27, 7, 1, 'Fall', '2025-03-06 09:03:05', 1, '2025', 1.5, 'Theory'),
(2508, '21-25', 'A', 'Friday', '11:30-13:00', 52, 27, 7, 1, 'Fall', '2025-03-06 09:03:05', 1, '2025', 1.5, 'Theory'),
(2509, '21-25', 'B', 'Wednesday', '11:30-13:00', 53, 27, 7, 1, 'Fall', '2025-03-06 09:03:05', 1, '2025', 1.5, 'Theory'),
(2510, '21-25', 'B', 'Friday', '08:30-10:00', 53, 27, 7, 1, 'Fall', '2025-03-06 09:03:05', 1, '2025', 1.5, 'Theory'),
(2511, '21-25', 'A', 'Thursday', '08:30-10:00', 54, 26, 4, 1, 'Fall', '2025-03-06 09:03:05', 1, '2025', 1.5, 'Theory'),
(2512, '21-25', 'B', 'Friday', '11:30-13:00', 55, 26, 5, 1, 'Fall', '2025-03-06 09:03:05', 1, '2025', 1.5, 'Theory'),
(2513, '21-25', 'A', 'Tuesday', '10:00-11:30', 56, 30, 2, 1, 'Fall', '2025-03-06 09:03:05', 1, '2025', 1.5, 'Theory'),
(2514, '21-25', 'B', 'Tuesday', '10:00-11:30', 57, 30, 2, 1, 'Fall', '2025-03-06 09:03:05', 1, '2025', 1.5, 'Theory'),
(2515, '22-26', 'A', 'Tuesday', '15:00-16:30', 58, 26, 9, 1, 'Fall', '2025-03-06 09:03:05', 1, '2025', 1.5, 'Theory'),
(2516, '22-26', 'A', 'Friday', '15:00-16:30', 58, 26, 9, 1, 'Fall', '2025-03-06 09:03:05', 1, '2025', 1.5, 'Theory'),
(2517, '22-26', 'B', 'Monday', '08:30-10:00', 59, 26, 2, 1, 'Fall', '2025-03-06 09:03:05', 1, '2025', 1.5, 'Theory'),
(2518, '22-26', 'B', 'Thursday', '10:00-11:30', 59, 26, 2, 1, 'Fall', '2025-03-06 09:03:05', 1, '2025', 1.5, 'Theory'),
(2519, '23-27', 'A', 'Tuesday', '13:30-15:00', 64, 31, 2, 1, 'Fall', '2025-03-06 09:03:05', 1, '2025', 1.5, 'Theory'),
(2520, '23-27', 'B', 'Friday', '10:00-11:30', 65, 31, 2, 1, 'Fall', '2025-03-06 09:03:05', 1, '2025', 1.5, 'Theory'),
(2521, '22-26', 'A', 'Wednesday', '13:30-15:00', 68, 29, 5, 1, 'Fall', '2025-03-06 09:03:05', 1, '2025', 1.5, 'Theory'),
(2522, '22-26', 'B', 'Wednesday', '13:30-15:00', 69, 29, 5, 1, 'Fall', '2025-03-06 09:03:05', 1, '2025', 1.5, 'Theory'),
(2523, '24-28', 'A', 'Wednesday', '10:00-11:30', 70, 33, 11, 1, 'Fall', '2025-03-06 09:03:05', 1, '2025', 1.5, 'Theory'),
(2524, '24-28', 'B', 'Wednesday', '08:30-10:00', 71, 33, 11, 1, 'Fall', '2025-03-06 09:03:05', 1, '2025', 1.5, 'Theory'),
(2525, '21-25', 'A', 'Monday', '10:00-11:30', 51, 27, 6, 1, 'Spring', '2025-03-06 09:19:18', 0, '2025', 1.5, 'Theory'),
(2526, '21-25', 'A', 'Friday', '10:00-11:30', 51, 27, 6, 1, 'Spring', '2025-03-06 09:19:18', 0, '2025', 1.5, 'Theory'),
(2527, '21-25', 'A', 'Tuesday', '11:30-13:00', 52, 27, 7, 1, 'Spring', '2025-03-06 09:19:18', 0, '2025', 1.5, 'Theory'),
(2528, '21-25', 'A', 'Friday', '08:30-10:00', 52, 27, 7, 1, 'Spring', '2025-03-06 09:19:18', 0, '2025', 1.5, 'Theory'),
(2529, '21-25', 'B', 'Friday', '08:30-10:00', 53, 27, 7, 1, 'Spring', '2025-03-06 09:19:18', 0, '2025', 1.5, 'Theory'),
(2530, '21-25', 'B', 'Wednesday', '11:30-13:00', 53, 27, 7, 1, 'Spring', '2025-03-06 09:19:18', 0, '2025', 1.5, 'Theory'),
(2531, '21-25', 'A', 'Thursday', '13:30-15:00', 54, 26, 4, 1, 'Spring', '2025-03-06 09:19:18', 0, '2025', 1.5, 'Theory'),
(2532, '21-25', 'B', 'Friday', '15:00-16:30', 55, 26, 5, 1, 'Spring', '2025-03-06 09:19:18', 0, '2025', 1.5, 'Theory'),
(2533, '21-25', 'A', 'Tuesday', '11:30-13:00', 56, 30, 2, 1, 'Spring', '2025-03-06 09:19:18', 0, '2025', 1.5, 'Theory'),
(2534, '21-25', 'B', 'Tuesday', '08:30-10:00', 57, 30, 2, 1, 'Spring', '2025-03-06 09:19:18', 0, '2025', 1.5, 'Theory'),
(2535, '22-26', 'A', 'Friday', '13:30-15:00', 58, 26, 9, 1, 'Spring', '2025-03-06 09:19:18', 0, '2025', 1.5, 'Theory'),
(2536, '22-26', 'A', 'Tuesday', '13:30-15:00', 58, 26, 9, 1, 'Spring', '2025-03-06 09:19:18', 0, '2025', 1.5, 'Theory'),
(2537, '22-26', 'B', 'Thursday', '08:30-10:00', 59, 26, 2, 1, 'Spring', '2025-03-06 09:19:18', 0, '2025', 1.5, 'Theory'),
(2538, '22-26', 'B', 'Monday', '08:30-10:00', 59, 26, 2, 1, 'Spring', '2025-03-06 09:19:18', 0, '2025', 1.5, 'Theory'),
(2539, '23-27', 'A', 'Friday', '13:30-15:00', 64, 31, 2, 1, 'Spring', '2025-03-06 09:19:18', 0, '2025', 1.5, 'Theory'),
(2540, '23-27', 'B', 'Tuesday', '11:30-13:00', 65, 31, 2, 1, 'Spring', '2025-03-06 09:19:18', 0, '2025', 1.5, 'Theory'),
(2541, '22-26', 'A', 'Wednesday', '15:00-16:30', 68, 29, 5, 1, 'Spring', '2025-03-06 09:19:18', 0, '2025', 1.5, 'Theory'),
(2542, '22-26', 'B', 'Wednesday', '11:30-13:00', 69, 29, 5, 1, 'Spring', '2025-03-06 09:19:18', 0, '2025', 1.5, 'Theory'),
(2543, '24-28', 'A', 'Wednesday', '10:00-11:30', 70, 33, 11, 1, 'Spring', '2025-03-06 09:19:18', 0, '2025', 1.5, 'Theory'),
(2544, '24-28', 'B', 'Wednesday', '10:00-11:30', 71, 33, 11, 1, 'Spring', '2025-03-06 09:19:18', 0, '2025', 1.5, 'Theory');

-- --------------------------------------------------------

--
-- Table structure for table `timetable_details`
--

CREATE TABLE `timetable_details` (
  `id` int(11) NOT NULL,
  `timetable_id` int(11) NOT NULL,
  `course` varchar(255) NOT NULL,
  `time_slot` varchar(255) NOT NULL,
  `room` varchar(255) NOT NULL,
  `day` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `timetable_requests`
--

CREATE TABLE `timetable_requests` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `requested_time` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `teacher_name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `department_id` int(11) NOT NULL,
  `timetable_id` int(11) NOT NULL,
  `timetables_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timetable_requests`
--

INSERT INTO `timetable_requests` (`id`, `teacher_id`, `reason`, `requested_time`, `status`, `created_at`, `teacher_name`, `subject`, `department_id`, `timetable_id`, `timetables_id`) VALUES
(1, 12, 'I wont be able to teach on this time.', '8;30 to 9:30', 'pending', '2025-02-09 15:59:38', '', '', 0, 0, 0),
(2, 28, 'Not available', '8:30 to 10:00', 'pending', '2025-03-02 21:08:31', '', '', 0, 0, 0),
(3, 12, 'please change my time slot for session 22-26 (subject FM)', '8:30 to 10:00', 'pending', '2025-03-06 08:01:10', '', '', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','admin','teacher','student') NOT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT 'default-avatar.jpg',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `contact_number` varchar(15) DEFAULT NULL,
  `specialty` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `roll_no` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `contact`, `profile_pic`, `created_at`, `contact_number`, `specialty`, `department_id`, `is_active`, `roll_no`) VALUES
(1, 'Super Admin', 'superadmin@must.edu.pk', '202cb962ac59075b964b07152d234b70', 'superadmin', '03496670892', 'MUST LOGO.jpg', '2025-02-09 13:20:07', NULL, NULL, NULL, 1, NULL),
(2, 'Admin CS', 'admin.cs@must.edu.pk', '250cf8b51c773f3f8dc8b4be867a9a02', 'admin', NULL, 'default-avatar.jpg', '2025-02-09 13:20:08', NULL, NULL, 2, 1, NULL),
(3, 'Admin IT', 'admin.it@must.edu.pk', '250cf8b51c773f3f8dc8b4be867a9a02', 'admin', '03496627891', 'default-avatar.jpg', '2025-02-09 13:20:08', NULL, NULL, 3, 1, NULL),
(4, 'Admin SE', 'admin.se@must.edu.pk', '250cf8b51c773f3f8dc8b4be867a9a02', 'admin', '03496627891', 'default-avatar.jpg', '2025-02-09 13:20:08', NULL, NULL, 1, 1, NULL),
(5, 'Admin EE', 'admin.ee@must.edu.pk', '250cf8b51c773f3f8dc8b4be867a9a02', 'admin', NULL, 'default-avatar.jpg', '2025-02-09 13:20:08', NULL, NULL, NULL, 1, NULL),
(6, 'Admin ME', 'admin.me@must.edu.pk', '250cf8b51c773f3f8dc8b4be867a9a02', 'admin', NULL, 'default-avatar.jpg', '2025-02-09 13:20:08', NULL, NULL, NULL, 1, NULL),
(7, 'Admin Civil', 'admin.civil@must.edu.pk', '250cf8b51c773f3f8dc8b4be867a9a02', 'admin', NULL, 'default-avatar.jpg', '2025-02-09 13:20:08', NULL, NULL, NULL, 1, NULL),
(8, 'Admin BBA', 'admin.bba@must.edu.pk', '250cf8b51c773f3f8dc8b4be867a9a02', 'admin', NULL, 'default-avatar.jpg', '2025-02-09 13:20:08', NULL, NULL, NULL, 1, NULL),
(9, 'Admin Physics', 'admin.physics@must.edu.pk', '250cf8b51c773f3f8dc8b4be867a9a02', 'admin', NULL, 'default-avatar.jpg', '2025-02-09 13:20:08', NULL, NULL, NULL, 1, NULL),
(10, 'Admin Math', 'admin.math@must.edu.pk', '250cf8b51c773f3f8dc8b4be867a9a02', 'admin', NULL, 'default-avatar.jpg', '2025-02-09 13:20:08', NULL, NULL, NULL, 1, NULL),
(11, 'Admin BioTech', 'admin.biotech@must.edu.pk', '250cf8b51c773f3f8dc8b4be867a9a02', 'admin', NULL, 'default-avatar.jpg', '2025-02-09 13:20:08', NULL, NULL, NULL, 0, NULL),
(12, 'Saman Amin', 'saman@must.edu.pk', '68053af2923e00204c3ca7c6a3150cf7', 'teacher', '03455678882', 'default-avatar.jpg', '2025-02-09 14:27:03', NULL, NULL, NULL, 1, NULL),
(13, 'Eman', 'eman@must.edu.pk', '81dc9bdb52d04dc20036dbd8313ed055', 'student', NULL, 'default-avatar.jpg', '2025-02-09 18:35:25', NULL, NULL, NULL, 1, NULL),
(14, 'Engr. SamiUllah', 'sami@must.edu.pk', '68053af2923e00204c3ca7c6a3150cf7', 'teacher', NULL, 'default-avatar.jpg', '2025-02-09 19:21:12', NULL, NULL, NULL, 1, NULL),
(15, 'Saba Zafar', 'saba@must.edu.pk', '68053af2923e00204c3ca7c6a3150cf7', 'teacher', NULL, 'default-avatar.jpg', '2025-02-09 19:21:41', NULL, NULL, NULL, 1, NULL),
(16, 'Engr.Areeb', 'areeb@must.edu.pk', '68053af2923e00204c3ca7c6a3150cf7', 'teacher', NULL, 'default-avatar.jpg', '2025-02-09 19:22:11', NULL, NULL, NULL, 1, NULL),
(17, 'Engr. Rubab', 'rubab@must.edu.pk', '68053af2923e00204c3ca7c6a3150cf7', 'teacher', NULL, 'default-avatar.jpg', '2025-02-09 19:22:54', NULL, NULL, NULL, 1, NULL),
(18, 'Engr. Umair', 'umair@must.edu.pk', '68053af2923e00204c3ca7c6a3150cf7', 'teacher', NULL, 'default-avatar.jpg', '2025-02-09 19:23:27', NULL, NULL, NULL, 1, NULL),
(19, 'Engr. Asim', 'asim@must.edu.pk', '68053af2923e00204c3ca7c6a3150cf7', 'teacher', NULL, 'default-avatar.jpg', '2025-02-09 19:24:15', NULL, NULL, NULL, 1, NULL),
(20, 'Engr. Hafeez', 'hafeez@must.edu.pk', '68053af2923e00204c3ca7c6a3150cf7', 'teacher', NULL, 'default-avatar.jpg', '2025-02-09 19:24:47', NULL, NULL, NULL, 1, NULL),
(21, 'Engr. Sehrish', 'sehrish@must.edu.pk', '250cf8b51c773f3f8dc8b4be867a9a02', 'teacher', NULL, 'default-avatar.jpg', '2025-02-09 19:25:32', NULL, NULL, NULL, 1, NULL),
(22, 'Engr. Shamila', 'shamila@must.edu.pk', '68053af2923e00204c3ca7c6a3150cf7', 'teacher', NULL, 'default-avatar.jpg', '2025-02-28 18:22:57', NULL, NULL, NULL, 1, NULL),
(23, 'Dr. Nouman', 'nouman@must.edu.pk', '250cf8b51c773f3f8dc8b4be867a9a02', 'teacher', NULL, 'default-avatar.jpg', '2025-03-01 09:30:41', NULL, NULL, NULL, 1, NULL),
(24, 'Dr. Shamila', 'shamila.se@must.edu.pk', '68053af2923e00204c3ca7c6a3150cf7', 'teacher', NULL, 'default-avatar.jpg', '2025-03-01 10:11:50', NULL, NULL, 1, 1, NULL),
(25, 'Engr. SamiUllah', 'sami.se@must.edu.pk', '68053af2923e00204c3ca7c6a3150cf7', 'teacher', NULL, 'default-avatar.jpg', '2025-03-01 10:12:25', NULL, NULL, 1, 1, NULL),
(26, 'Engr. SamiUllah', 'samiullah.se@must.edu.pk', '68053af2923e00204c3ca7c6a3150cf7', 'teacher', NULL, 'default-avatar.jpg', '2025-03-01 12:26:53', NULL, NULL, 1, 1, NULL),
(27, 'Dr. Nouman', 'nouman.se@must.edu.pk', '68053af2923e00204c3ca7c6a3150cf7', 'teacher', NULL, 'default-avatar.jpg', '2025-03-01 12:27:39', NULL, NULL, 1, 1, NULL),
(28, 'Engr.Saman Fatima', 'saman.se@must.edu.pk', '68053af2923e00204c3ca7c6a3150cf7', 'teacher', NULL, 'default-avatar.jpg', '2025-03-01 12:28:18', NULL, NULL, 1, 1, NULL),
(29, 'Engr. Saba Zafar', 'saba.se@must.edu.pk', '68053af2923e00204c3ca7c6a3150cf7', 'teacher', NULL, 'default-avatar.jpg', '2025-03-01 12:28:44', NULL, NULL, 1, 1, NULL),
(30, 'Engr. Rubab Tassawar', 'rubab.se@must.edu.pk', '68053af2923e00204c3ca7c6a3150cf7', 'teacher', NULL, 'default-avatar.jpg', '2025-03-01 12:29:25', NULL, NULL, 1, 1, NULL),
(31, 'Engr. Sherish', 'sehrish.se@must.edu.pk', '68053af2923e00204c3ca7c6a3150cf7', 'teacher', NULL, 'default-avatar.jpg', '2025-03-01 12:29:55', NULL, NULL, 1, 1, NULL),
(32, 'Engr. Umair', 'umair.se@must.edu.pk', '68053af2923e00204c3ca7c6a3150cf7', 'teacher', NULL, 'default-avatar.jpg', '2025-03-01 12:30:17', NULL, NULL, 1, 1, NULL),
(33, 'Engr. Asim', 'asim.se@must.edu.pk', '68053af2923e00204c3ca7c6a3150cf7', 'teacher', NULL, 'default-avatar.jpg', '2025-03-01 12:30:47', NULL, NULL, 1, 1, NULL),
(34, 'Admin BE', 'admin.eng@must.edu.pk', '250cf8b51c773f3f8dc8b4be867a9a02', 'admin', NULL, 'default-avatar.jpg', '2025-03-02 20:26:07', NULL, NULL, NULL, 0, NULL),
(35, 'Hamna', 'hamna@must.edu.pk', '81dc9bdb52d04dc20036dbd8313ed055', 'student', '0344678862', 'default-avatar.jpg', '2025-03-03 11:06:49', NULL, NULL, NULL, 1, NULL),
(36, 'Maryam', 'maryam@must.edu.pk', '81dc9bdb52d04dc20036dbd8313ed055', 'student', NULL, 'default-avatar.jpg', '2025-03-05 06:54:01', NULL, NULL, NULL, 1, NULL),
(37, 'Ali', 'ali@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'student', '03496627891', 'default-avatar.jpg', '2025-03-05 06:55:16', NULL, NULL, NULL, 1, NULL),
(38, 'Ali ', 'ali@must.edu.pk', '68053af2923e00204c3ca7c6a3150cf7', 'teacher', NULL, 'default-avatar.jpg', '2025-03-05 08:00:43', NULL, NULL, 2, 1, NULL),
(39, 'Muqadas Meherban', 'muqadas@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'student', NULL, 'default-avatar.jpg', '2025-03-05 17:03:25', NULL, NULL, NULL, 1, 'FA22-BSE-020'),
(40, 'mina', 'mina@must.edu.pk', '25041b7d9ab70b4f8fed6c2100bc8b87', 'student', NULL, 'default-avatar.jpg', '2025-03-05 17:15:29', NULL, NULL, NULL, 1, 'FA22-BSE-099'),
(41, 'JCHU', 'admin@emust.edu.pk', '81dc9bdb52d04dc20036dbd8313ed055', 'student', NULL, 'default-avatar.jpg', '2025-03-05 17:17:49', NULL, NULL, NULL, 1, 'HVSD'),
(42, 'Ahmed Ali', 'ahmed@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'student', NULL, 'default-avatar.jpg', '2025-03-05 17:24:18', NULL, NULL, NULL, 1, 'FA22-BSE-036'),
(43, 'Admin LLB', 'admin.llb@must.edu.pk', '250cf8b51c773f3f8dc8b4be867a9a02', 'admin', '', 'default-avatar.jpg', '2025-03-05 19:10:25', NULL, NULL, NULL, 1, NULL),
(47, 'Admin CE', 'admin.ce@must.edu.pk', '250cf8b51c773f3f8dc8b4be867a9a02', 'admin', '', 'default-avatar.jpg', '2025-03-05 19:14:58', NULL, NULL, NULL, 1, NULL),
(48, 'Admic cse', 'admin.cse@must.edu.pk', '250cf8b51c773f3f8dc8b4be867a9a02', 'admin', '', 'default-avatar.jpg', '2025-03-05 19:26:02', NULL, NULL, NULL, 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_id` (`admin_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requested_by` (`requested_by`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `teacher_course`
--
ALTER TABLE `teacher_course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timetables`
--
ALTER TABLE `timetables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `timetable_details`
--
ALTER TABLE `timetable_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `timetable_id` (`timetable_id`);

--
-- Indexes for table `timetable_requests`
--
ALTER TABLE `timetable_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `department_id` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teacher_course`
--
ALTER TABLE `teacher_course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `timetables`
--
ALTER TABLE `timetables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2545;

--
-- AUTO_INCREMENT for table `timetable_details`
--
ALTER TABLE `timetable_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timetable_requests`
--
ALTER TABLE `timetable_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teachers_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_course`
--
ALTER TABLE `teacher_course`
  ADD CONSTRAINT `teacher_course_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_course_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `timetables`
--
ALTER TABLE `timetables`
  ADD CONSTRAINT `timetables_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `timetables_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `timetables_ibfk_3` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `timetables_ibfk_4` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `timetable_details`
--
ALTER TABLE `timetable_details`
  ADD CONSTRAINT `timetable_details_ibfk_1` FOREIGN KEY (`timetable_id`) REFERENCES `timetables` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `timetable_requests`
--
ALTER TABLE `timetable_requests`
  ADD CONSTRAINT `timetable_requests_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
