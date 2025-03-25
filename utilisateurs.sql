-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 25 mars 2025 à 23:05
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `utilisateurs`
--

-- --------------------------------------------------------

--
-- Structure de la table `groupes`
--

CREATE TABLE `groupes` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `createur` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `groupes`
--

INSERT INTO `groupes` (`group_id`, `group_name`, `created_at`, `createur`) VALUES
(40, 'tp1', '2025-03-21 14:03:27', 'tahaadnane1'),
(41, 'tp4', '2025-03-21 14:03:57', 'tahaadnane1'),
(42, 'tp5', '2025-03-21 14:34:57', 'tahaadnane1'),
(43, 'tp6', '2025-03-21 14:56:48', 'tahaadnane'),
(44, 'tp1', '2025-03-21 15:29:02', 'tahaadnane3'),
(45, 'tp4', '2025-03-21 15:41:15', 'tahaadnane3'),
(47, 'tp12', '2025-03-22 13:24:02', 'tahaadnane1'),
(48, 'grp4', '2025-03-24 19:48:25', 'tahaadnane'),
(49, 'tp6', '2025-03-24 19:48:51', 'tahaadnane'),
(50, 'grp1', '2025-03-24 19:50:40', 'tahaadnane');

-- --------------------------------------------------------

--
-- Structure de la table `membres_groupes`
--

CREATE TABLE `membres_groupes` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `membres_groupes`
--

INSERT INTO `membres_groupes` (`user_id`, `group_id`, `role`, `joined_at`) VALUES
(1, 44, 'membre', '2025-03-21 15:29:14'),
(1, 45, 'membre', '2025-03-21 15:41:21'),
(1, 47, 'membre', '2025-03-22 13:24:15'),
(2, 50, 'membre', '2025-03-24 19:51:20'),
(4, 44, 'membre', '2025-03-21 15:41:38');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`message_id`, `user_id`, `group_id`, `message`, `created_at`) VALUES
(59, 1, 43, 'cv', '2025-03-22 13:23:02'),
(60, 1, 40, 'cv', '2025-03-22 13:23:04'),
(66, 2, 40, 'slm', '2025-03-22 15:48:03'),
(67, 1, 40, 'lhamdolilah', '2025-03-22 15:48:48'),
(68, 1, 47, 'slm', '2025-03-22 22:36:10'),
(69, 1, 47, 'cv', '2025-03-22 22:36:13'),
(70, 1, 47, 'lhamdolilah', '2025-03-22 22:36:53'),
(71, 1, 44, 'slm', '2025-03-23 02:14:32'),
(72, 1, 44, 'cv', '2025-03-23 02:14:34'),
(73, 1, 44, 'slm', '2025-03-24 19:53:11');

-- --------------------------------------------------------

--
-- Structure de la table `projects`
--

CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `tach_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `projects`
--

INSERT INTO `projects` (`project_id`, `project_name`, `user_id`, `group_id`, `created_at`, `tach_id`) VALUES
(38, 'taha', 1, 43, '2025-03-23 16:56:19', 0),
(39, 'abdo', 1, 43, '2025-03-23 17:47:17', 0),
(40, 'nazis', 1, 50, '2025-03-24 19:54:21', 0);

-- --------------------------------------------------------

--
-- Structure de la table `ressources`
--

CREATE TABLE `ressources` (
  `ress_id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ressources`
--

INSERT INTO `ressources` (`ress_id`, `group_id`, `user_id`, `project_id`, `file_name`, `file_path`, `upload_date`) VALUES
(1, 43, 1, 38, 'taha', '8581-1-3.PNG', '2025-03-24 00:57:48');

-- --------------------------------------------------------

--
-- Structure de la table `taches`
--

CREATE TABLE `taches` (
  `tach_id` int(11) NOT NULL,
  `tach_nom` varchar(250) NOT NULL,
  `tach_etat` enum('À faire','En cours','Terminé') DEFAULT 'À faire',
  `group_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `datelim` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `taches`
--

INSERT INTO `taches` (`tach_id`, `tach_nom`, `tach_etat`, `group_id`, `project_id`, `user_id`, `datelim`) VALUES
(12, 'abdomanoc', 'En cours', 50, 40, 1, '2025-02-26');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `created_at`) VALUES
(1, 'tahaadnane', 'tahaadnane@gmail.com', '$2y$10$CdUCbry3eNpAzp8RAaPSsOMgowmxlETnNQBuL8dxd.z7w1NNn5aj2', '2025-03-20 01:51:07'),
(2, 'tahaadnane1', 'tahaadnane39@gmail.com', '$2y$10$pvfm3oWtkvBhMChx9.7z9OMXHR0STyAjJigTkJiZIVKFm0gSpCa52', '2025-03-20 04:02:43'),
(3, 'tahaadnane2', 'tahaadnane329@gmail.com', '$2y$10$mFXJd4kR4luuKrfN0jyaduNM7v0OTsCvqN.sdTShyR5yvCwPbm9tq', '2025-03-20 15:16:50'),
(4, 'tahaadnane3', 'tahaadnane60@gmail.com', '$2y$10$c78TnY0qC/XQyS8gAfX6Xu..PJkFS6x..fCEnF5PBvF2J3CM3GKT6', '2025-03-20 16:00:55');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `groupes`
--
ALTER TABLE `groupes`
  ADD PRIMARY KEY (`group_id`);

--
-- Index pour la table `membres_groupes`
--
ALTER TABLE `membres_groupes`
  ADD PRIMARY KEY (`user_id`,`group_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Index pour la table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Index pour la table `ressources`
--
ALTER TABLE `ressources`
  ADD PRIMARY KEY (`ress_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Index pour la table `taches`
--
ALTER TABLE `taches`
  ADD PRIMARY KEY (`tach_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `groupes`
--
ALTER TABLE `groupes`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT pour la table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `ressources`
--
ALTER TABLE `ressources`
  MODIFY `ress_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `taches`
--
ALTER TABLE `taches`
  MODIFY `tach_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `membres_groupes`
--
ALTER TABLE `membres_groupes`
  ADD CONSTRAINT `membres_groupes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `membres_groupes_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groupes` (`group_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groupes` (`group_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projects_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groupes` (`group_id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `ressources`
--
ALTER TABLE `ressources`
  ADD CONSTRAINT `ressources_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groupes` (`group_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ressources_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ressources_ibfk_3` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `taches`
--
ALTER TABLE `taches`
  ADD CONSTRAINT `taches_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groupes` (`group_id`),
  ADD CONSTRAINT `taches_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`),
  ADD CONSTRAINT `taches_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
