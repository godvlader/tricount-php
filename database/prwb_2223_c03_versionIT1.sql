-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 05 fév. 2023 à 19:05
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `prwb_2223_c03`
--

-- --------------------------------------------------------

--
-- Structure de la table `operations`
--

CREATE TABLE `operations` (
  `id` int(11) NOT NULL,
  `title` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `tricount` int(11) NOT NULL,
  `amount` double NOT NULL,
  `operation_date` date NOT NULL,
  `initiator` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `operations`
--

INSERT INTO `operations` (`id`, `title`, `tricount`, `amount`, `operation_date`, `initiator`, `created_at`) VALUES
(1, 'Colruyt', 4, 100, '2022-10-13', 2, '2022-10-13 19:09:18'),
(2, 'Plein essence', 4, 75, '2022-10-13', 1, '2022-10-13 20:10:41'),
(3, 'Grosses courses LIDL', 4, 212.47, '2022-10-13', 3, '2022-10-13 21:23:49'),
(5, 'Boucherie', 4, 25.5, '2022-10-26', 2, '2022-10-26 09:59:56'),
(12, 'iteration 1', 18, 150, '2023-02-05', 7, '2023-02-05 05:21:38'),
(26, 'qqsdqsd', 19, 150, '2562-10-02', 7, '2023-02-05 06:30:35'),
(27, 'test', 19, 50, '2023-02-05', 6, '2023-02-05 06:31:56');

-- --------------------------------------------------------

--
-- Structure de la table `repartitions`
--

CREATE TABLE `repartitions` (
  `operation` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `weight` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `repartitions`
--

INSERT INTO `repartitions` (`operation`, `user`, `weight`) VALUES
(1, 1, 1),
(1, 2, 1),
(2, 1, 1),
(2, 2, 1),
(3, 1, 2),
(3, 2, 1),
(3, 3, 1),
(5, 1, 2),
(5, 2, 1),
(5, 3, 1),
(12, 6, 1),
(12, 7, 1),
(12, 8, 1),
(26, 6, 1),
(26, 7, 2),
(27, 6, 1),
(27, 7, 1);

-- --------------------------------------------------------

--
-- Structure de la table `repartition_templates`
--

CREATE TABLE `repartition_templates` (
  `id` int(11) NOT NULL,
  `title` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `tricount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `repartition_templates`
--

INSERT INTO `repartition_templates` (`id`, `title`, `tricount`) VALUES
(2, 'Benoit ne paye rien', 4),
(1, 'Boris paye double', 4),
(27, 'KENJI PAYE QUADRUPLE', 19),
(18, 'Kenji paye triple', 18),
(16, 'oui', 18),
(35, 'repartition correcte', 19),
(17, 'test', 18),
(29, 'trampoline template', 19),
(38, 'vlad paye', 19),
(20, 'vlad paye double', 19),
(49, 'vlad paye le double :)', 19),
(25, 'vlad paye le double par gentillesse', 19);

-- --------------------------------------------------------

--
-- Structure de la table `repartition_template_items`
--

CREATE TABLE `repartition_template_items` (
  `user` int(11) NOT NULL,
  `repartition_template` int(11) NOT NULL,
  `weight` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `repartition_template_items`
--

INSERT INTO `repartition_template_items` (`user`, `repartition_template`, `weight`) VALUES
(1, 1, 2),
(1, 2, 1),
(1, 17, 1),
(2, 1, 1),
(3, 1, 1),
(3, 2, 1),
(6, 16, 1),
(6, 17, 1),
(6, 18, 3),
(6, 20, 1),
(6, 25, 1),
(6, 27, 4),
(6, 29, 3),
(6, 35, 1),
(6, 49, 1),
(7, 16, 1),
(7, 17, 1),
(7, 18, 1),
(7, 20, 2),
(7, 25, 2),
(7, 27, 1),
(7, 29, 1),
(7, 38, 1),
(7, 49, 2),
(8, 16, 1),
(8, 17, 1),
(8, 18, 1);

-- --------------------------------------------------------

--
-- Structure de la table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `tricount` int(11) NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `subscriptions`
--

INSERT INTO `subscriptions` (`tricount`, `user`) VALUES
(1, 1),
(4, 1),
(4, 2),
(4, 3),
(17, 1),
(17, 2),
(18, 1),
(18, 6),
(18, 7),
(18, 8),
(19, 6),
(19, 7);

-- --------------------------------------------------------

--
-- Structure de la table `tricounts`
--

CREATE TABLE `tricounts` (
  `id` int(11) NOT NULL,
  `title` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `creator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `tricounts`
--

INSERT INTO `tricounts` (`id`, `title`, `description`, `created_at`, `creator`) VALUES
(1, 'Gers 2022', NULL, '2022-10-10 18:42:24', 1),
(4, 'Vacances', 'A la mer du nord', '2022-10-10 19:31:09', 1),
(17, 'QSqs', 'qsdqsd', '2023-02-05 17:02:24', 1),
(18, 'tricount de kenji', 'ttttt', '2023-02-05 17:15:46', 6),
(19, 'test', 'test', '2023-02-05 17:24:00', 7);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `mail` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `hashed_password` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `full_name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `role` enum('user','admin') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `iban` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `mail`, `hashed_password`, `full_name`, `role`, `iban`) VALUES
(1, 'boverhaegen@epfc.eu', '56ce92d1de4f05017cf03d6cd514d6d1', 'Boris', 'user', NULL),
(2, 'bepenelle@epfc.eu', '56ce92d1de4f05017cf03d6cd514d6d1', 'Benoît', 'user', NULL),
(3, 'xapigeolet@epfc.eu', '56ce92d1de4f05017cf03d6cd514d6d1', 'Xavier', 'user', NULL),
(4, 'mamichel@epfc.eu', '56ce92d1de4f05017cf03d6cd514d6d1', 'Marc', 'user', '1234'),
(5, 'machin@machin.com', '56ce92d1de4f05017cf03d6cd514d6d1', 'Machin', 'user', 'BE11 1111 1111 1111'),
(6, 'kenji@kenji.com', '56ce92d1de4f05017cf03d6cd514d6d1', 'Kenji', 'user', 'BE11 1111 1111 1112'),
(7, 'vlad@vlad.com', '56ce92d1de4f05017cf03d6cd514d6d1', 'Vlad', 'user', 'BE11 1111 1111 1113'),
(8, 'alberich@alberich.com', '56ce92d1de4f05017cf03d6cd514d6d1', 'Albérich', 'user', 'BE11 1111 1111 1114');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `operations`
--
ALTER TABLE `operations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Initiator` (`initiator`),
  ADD KEY `Tricount` (`tricount`);

--
-- Index pour la table `repartitions`
--
ALTER TABLE `repartitions`
  ADD PRIMARY KEY (`operation`,`user`),
  ADD KEY `User` (`user`);

--
-- Index pour la table `repartition_templates`
--
ALTER TABLE `repartition_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Title` (`title`,`tricount`),
  ADD KEY `Tricount` (`tricount`);

--
-- Index pour la table `repartition_template_items`
--
ALTER TABLE `repartition_template_items`
  ADD PRIMARY KEY (`user`,`repartition_template`),
  ADD KEY `Distribution` (`repartition_template`);

--
-- Index pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`tricount`,`user`),
  ADD KEY `User` (`user`);

--
-- Index pour la table `tricounts`
--
ALTER TABLE `tricounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Title` (`title`,`creator`),
  ADD KEY `Creator` (`creator`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Mail` (`mail`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `operations`
--
ALTER TABLE `operations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `repartition_templates`
--
ALTER TABLE `repartition_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT pour la table `tricounts`
--
ALTER TABLE `tricounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `operations`
--
ALTER TABLE `operations`
  ADD CONSTRAINT `operations_ibfk_1` FOREIGN KEY (`initiator`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `operations_ibfk_2` FOREIGN KEY (`tricount`) REFERENCES `tricounts` (`id`);

--
-- Contraintes pour la table `repartitions`
--
ALTER TABLE `repartitions`
  ADD CONSTRAINT `repartitions_ibfk_1` FOREIGN KEY (`operation`) REFERENCES `operations` (`id`),
  ADD CONSTRAINT `repartitions_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `repartition_templates`
--
ALTER TABLE `repartition_templates`
  ADD CONSTRAINT `repartition_templates_ibfk_1` FOREIGN KEY (`tricount`) REFERENCES `tricounts` (`id`);

--
-- Contraintes pour la table `repartition_template_items`
--
ALTER TABLE `repartition_template_items`
  ADD CONSTRAINT `repartition_template_items_ibfk_1` FOREIGN KEY (`repartition_template`) REFERENCES `repartition_templates` (`id`),
  ADD CONSTRAINT `repartition_template_items_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`tricount`) REFERENCES `tricounts` (`id`),
  ADD CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `tricounts`
--
ALTER TABLE `tricounts`
  ADD CONSTRAINT `tricounts_ibfk_1` FOREIGN KEY (`creator`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
