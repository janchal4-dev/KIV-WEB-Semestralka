-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Sob 22. lis 2025, 21:13
-- Verze serveru: 10.4.32-MariaDB
-- Verze PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `konference`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `post`
--

CREATE TABLE `post` (
  `id_post` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `abstract` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `author_id` bigint(20) UNSIGNED NOT NULL,
  `status_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `date_uploaded` datetime NOT NULL DEFAULT current_timestamp(),
  `date_changed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `post`
--

INSERT INTO `post` (`id_post`, `name`, `abstract`, `file_path`, `author_id`, `status_id`, `date_uploaded`, `date_changed`) VALUES
(4, 'Baf', NULL, 'pdf_6922009287f3f4.70330173.pdf', 18, 2, '2025-11-22 19:27:30', '2025-11-22 19:27:48'),
(5, 'admin', '<p>dwddw</p>\r\n\r\n<p><strong>wepefk</strong></p>\r\n', 'pdf_69221274056b75.20024354.pdf', 18, 2, '2025-11-22 20:43:48', '2025-11-22 20:50:21'),
(6, 'Třetí článek', '<p>Je to <strong>bezva</strong></p>\r\n', 'pdf_692216279c6187.96656472.pdf', 18, 2, '2025-11-22 20:59:35', '2025-11-22 20:59:56');

-- --------------------------------------------------------

--
-- Struktura tabulky `post_reviewer`
--

CREATE TABLE `post_reviewer` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `reviewer_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `post_reviewer`
--

INSERT INTO `post_reviewer` (`id`, `post_id`, `reviewer_id`, `assigned_at`) VALUES
(46, 3, 6, '2025-11-21 21:20:10'),
(47, 3, 5, '2025-11-21 21:20:21'),
(48, 4, 15, '2025-11-22 19:27:57'),
(49, 4, 16, '2025-11-22 19:50:36'),
(50, 6, 16, '2025-11-22 21:00:00'),
(51, 6, 15, '2025-11-22 21:07:01');

-- --------------------------------------------------------

--
-- Struktura tabulky `post_status`
--

CREATE TABLE `post_status` (
  `id_status` bigint(20) UNSIGNED NOT NULL,
  `status_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `post_status`
--

INSERT INTO `post_status` (`id_status`, `status_name`) VALUES
(1, 'Čeká na schválení'),
(2, 'Schválen'),
(3, 'Zamítnut');

-- --------------------------------------------------------

--
-- Struktura tabulky `review`
--

CREATE TABLE `review` (
  `id_review` bigint(20) UNSIGNED NOT NULL,
  `rev_quality` int(11) NOT NULL,
  `rev_language` int(11) NOT NULL,
  `rev_originality` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `review`
--

INSERT INTO `review` (`id_review`, `rev_quality`, `rev_language`, `rev_originality`, `comment`, `date_created`, `published`, `post_id`, `user_id`, `status_id`) VALUES
(8, 5, 4, 2, '', '2025-11-21 21:20:44', 3, 3, 5, 1),
(9, 5, 4, 3, '', '2025-11-22 20:20:02', 2, 4, 15, 1),
(10, 2, 3, 4, '<p>fhgh</p>\r\n', '2025-11-22 20:12:07', 2, 4, 16, 1),
(11, 5, 3, 5, '<p>veojojd</p>\r\n\r\n<p>ihieh</p>\r\n\r\n<p><strong>ihdwid</strong></p>\r\n', '2025-11-22 21:00:55', 2, 6, 16, 1),
(12, 5, 5, 5, '<p><strong>Je to prostě bezva</strong></p>\r\n\r\n<ul>\r\n	<li><strong>Ë</strong></li>\r\n</ul>\r\n', '2025-11-22 21:07:43', 0, 6, 15, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `review_status`
--

CREATE TABLE `review_status` (
  `id_status` bigint(20) UNSIGNED NOT NULL,
  `status_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `review_status`
--

INSERT INTO `review_status` (`id_status`, `status_name`) VALUES
(1, 'Čeká na schválení'),
(2, 'Schváleno'),
(3, 'Zamítnuto');

-- --------------------------------------------------------

--
-- Struktura tabulky `roles`
--

CREATE TABLE `roles` (
  `id_role` bigint(20) UNSIGNED NOT NULL,
  `role_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `roles`
--

INSERT INTO `roles` (`id_role`, `role_name`) VALUES
(1, 'SuperAdmin'),
(2, 'Admin'),
(3, 'Recenzent'),
(4, 'Autor');

-- --------------------------------------------------------

--
-- Struktura tabulky `status`
--

CREATE TABLE `status` (
  `id_status` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `status`
--

INSERT INTO `status` (`id_status`, `name`) VALUES
(1, 'Čeká na schválení'),
(2, 'Přiděleno recenzentům'),
(3, 'Recenze dokončeny'),
(4, 'Schváleno'),
(5, 'Zamítnuto');

-- --------------------------------------------------------

--
-- Struktura tabulky `user`
--

CREATE TABLE `user` (
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `blocked` tinyint(1) NOT NULL DEFAULT 0,
  `roles_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `fk_post_status` (`status_id`);

--
-- Indexy pro tabulku `post_reviewer`
--
ALTER TABLE `post_reviewer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_assignment` (`post_id`,`reviewer_id`),
  ADD KEY `fk_postreviewer_user` (`reviewer_id`);

--
-- Indexy pro tabulku `post_status`
--
ALTER TABLE `post_status`
  ADD PRIMARY KEY (`id_status`);

--
-- Indexy pro tabulku `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_review_status` (`status_id`);

--
-- Indexy pro tabulku `review_status`
--
ALTER TABLE `review_status`
  ADD PRIMARY KEY (`id_status`);

--
-- Indexy pro tabulku `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_role`);

--
-- Indexy pro tabulku `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id_status`);

--
-- Indexy pro tabulku `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `roles_id` (`roles_id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `post`
--
ALTER TABLE `post`
  MODIFY `id_post` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pro tabulku `post_reviewer`
--
ALTER TABLE `post_reviewer`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT pro tabulku `post_status`
--
ALTER TABLE `post_status`
  MODIFY `id_status` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pro tabulku `review`
--
ALTER TABLE `review`
  MODIFY `id_review` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pro tabulku `review_status`
--
ALTER TABLE `review_status`
  MODIFY `id_status` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pro tabulku `roles`
--
ALTER TABLE `roles`
  MODIFY `id_role` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pro tabulku `status`
--
ALTER TABLE `status`
  MODIFY `id_status` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pro tabulku `user`
--
ALTER TABLE `user`
  MODIFY `id_user` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_post_status` FOREIGN KEY (`status_id`) REFERENCES `post_status` (`id_status`),
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `post_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `status` (`id_status`);

--
-- Omezení pro tabulku `post_reviewer`
--
ALTER TABLE `post_reviewer`
  ADD CONSTRAINT `fk_postreviewer_post` FOREIGN KEY (`post_id`) REFERENCES `post` (`id_post`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_postreviewer_user` FOREIGN KEY (`reviewer_id`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `fk_review_status` FOREIGN KEY (`status_id`) REFERENCES `review_status` (`id_status`),
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id_post`),
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`);

--
-- Omezení pro tabulku `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
