-- phpMyAdmin SQL Dump
-- version 5.2.4-dev+20251120.d136b4450b
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Sob 22. lis 2025, 08:24
-- Verze serveru: 10.4.24-MariaDB
-- Verze PHP: 8.1.5

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
  `file_path` varchar(255) NOT NULL,
  `author_id` bigint(20) UNSIGNED NOT NULL,
  `status_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `date_uploaded` datetime NOT NULL DEFAULT current_timestamp(),
  `date_changed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabulky `post_reviewer`
--

CREATE TABLE `post_reviewer` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `reviewer_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `post_reviewer`
--

INSERT INTO `post_reviewer` (`id`, `post_id`, `reviewer_id`, `assigned_at`) VALUES
(46, 3, 6, '2025-11-21 21:20:10'),
(47, 3, 5, '2025-11-21 21:20:21');

-- --------------------------------------------------------

--
-- Struktura tabulky `post_status`
--

CREATE TABLE `post_status` (
  `id_status` bigint(20) UNSIGNED NOT NULL,
  `status_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `review`
--

INSERT INTO `review` (`id_review`, `rev_quality`, `rev_language`, `rev_originality`, `comment`, `date_created`, `published`, `post_id`, `user_id`, `status_id`) VALUES
(8, 5, 4, 2, '', '2025-11-21 21:20:44', 3, 3, 5, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `review_status`
--

CREATE TABLE `review_status` (
  `id_status` bigint(20) UNSIGNED NOT NULL,
  `status_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  MODIFY `id_post` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pro tabulku `post_reviewer`
--
ALTER TABLE `post_reviewer`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT pro tabulku `post_status`
--
ALTER TABLE `post_status`
  MODIFY `id_status` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pro tabulku `review`
--
ALTER TABLE `review`
  MODIFY `id_review` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id_user` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
