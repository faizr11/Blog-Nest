-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2025 at 10:59 AM
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
-- Database: `db_theblognest`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `penulis_id` int(11) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tanggal_publikasi` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `judul`, `isi`, `penulis_id`, `gambar`, `tanggal_publikasi`, `created_at`) VALUES
(1, 'Getting Started with HTML & CSS', 'Learn the fundamentals of HTML and CSS to build modern, responsive websites from scratch.', 1, 'https://img.freepik.com/free-vector/hand-drawn-kawaii-coloring-book-illustration_23-2149738644.jpg', '2025-05-20', '2025-05-20 15:24:09'),
(2, 'React Hooks Explained', 'A comprehensive guide to understanding and using React Hooks effectively in your projects.', 2, NULL, '2025-05-21', '2025-05-21 01:46:39'),
(3, 'Belajar PHP Dasar', 'Artikel ini membahas dasar-dasar bahasa pemrograman PHP.', 1, 'images/php-dasar.jpg', '2025-05-01', '2025-05-21 02:31:40'),
(4, 'Pengenalan UI/UX', 'UI/UX sangat penting dalam proses pengembangan aplikasi modern.', 2, 'images/uiux.jpg', '2025-05-05', '2025-05-21 02:31:40'),
(5, 'Apa Itu Database Relasional?', 'Penjelasan lengkap tentang database relasional dan contohnya.', 1, 'images/database.jpg', '2025-05-10', '2025-05-21 02:31:40');

-- --------------------------------------------------------

--
-- Table structure for table `article_category`
--

CREATE TABLE `article_category` (
  `artikel_id` int(11) NOT NULL,
  `kategori_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `article_category`
--

INSERT INTO `article_category` (`artikel_id`, `kategori_id`) VALUES
(1, 166),
(1, 168),
(2, 128),
(3, 122),
(4, 149),
(5, 122),
(5, 163);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `nama`) VALUES
(117, 'Python'),
(118, 'JavaScript'),
(119, 'TypeScript'),
(120, 'Java'),
(121, 'C'),
(122, 'C++'),
(123, 'C#'),
(124, 'PHP'),
(125, 'Go'),
(126, 'Ruby'),
(127, 'Swift'),
(128, 'Kotlin'),
(129, 'Rust'),
(130, 'Dart'),
(131, 'Scala'),
(132, 'Perl'),
(133, 'Haskell'),
(134, 'Lua'),
(135, 'Objective-C'),
(136, 'Elixir'),
(137, 'R'),
(138, 'MATLAB'),
(139, 'Julia'),
(140, 'Visual Basic'),
(141, 'Assembly'),
(142, 'Shell'),
(143, 'Groovy'),
(144, 'F#'),
(145, 'Erlang'),
(146, 'COBOL'),
(147, 'Fortran'),
(148, 'VB.NET'),
(149, 'Bash'),
(150, 'Crystal'),
(151, 'Nim'),
(152, 'Lisp'),
(153, 'Clojure'),
(154, 'OCaml'),
(155, 'Ada'),
(156, 'Pascal'),
(157, 'VHDL'),
(158, 'Solidity'),
(159, 'Zig'),
(160, 'Prolog'),
(161, 'Smalltalk'),
(162, 'UI/UX'),
(163, 'Database'),
(164, 'Networking'),
(165, 'Security'),
(166, 'DevOps'),
(167, 'Cloud'),
(168, 'AI'),
(169, 'Machine Learning'),
(170, 'Big Data'),
(171, 'Mobile Development'),
(172, 'Web Development'),
(173, 'Software Testing'),
(174, 'Embedded Systems');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('penulis','admin') DEFAULT 'penulis',
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `foto_profil` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `status`, `foto_profil`, `created_at`, `updated_at`) VALUES
(1, 'coba', 'coba@gmail.com', '01b307acba4f54f55aafc33bb06bbbf6ca803e9a', 'penulis', 'aktif', NULL, '2025-05-19 08:04:51', '2025-05-19 09:27:01'),
(2, 'test@gmail.com', 'test@gmail.com', '$2y$10$BTM1MWap.pGEp1E/X7EVL.BKEZFP/iiJ.1Hckmn4bvmmpN/4x2CNa', 'penulis', 'aktif', NULL, '2025-05-19 09:37:30', '2025-05-19 09:37:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penulis_id` (`penulis_id`);

--
-- Indexes for table `article_category`
--
ALTER TABLE `article_category`
  ADD PRIMARY KEY (`artikel_id`,`kategori_id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`penulis_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `article_category`
--
ALTER TABLE `article_category`
  ADD CONSTRAINT `article_category_ibfk_1` FOREIGN KEY (`artikel_id`) REFERENCES `articles` (`id`),
  ADD CONSTRAINT `article_category_ibfk_2` FOREIGN KEY (`kategori_id`) REFERENCES `category` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
