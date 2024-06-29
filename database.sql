-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 27, 2024 at 04:44 PM
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
-- Database: `ministere`
--

-- --------------------------------------------------------

--
-- Table structure for table `conges`
--

CREATE TABLE `conges` (
  `id` int(11) NOT NULL,
  `id_employe` int(11) NOT NULL,
  `categorie` varchar(255) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `statut` varchar(255) DEFAULT 'en attente',
  `motif` text NOT NULL,
  `id_responsable` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `conges`
--

INSERT INTO `conges` (`id`, `id_employe`, `categorie`, `date_debut`, `date_fin`, `statut`, `motif`, `id_responsable`) VALUES
(24, 3, 'annuel', '2024-10-10', '2024-10-11', 'approve', 'rests', 2),
(25, 3, 'maternite', '2024-11-11', '2025-01-01', 'approve', 'ty', 2);

-- --------------------------------------------------------

--
-- Table structure for table `departement`
--

CREATE TABLE `departement` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `nom_directeur` varchar(255) DEFAULT NULL,
  `nom_pole` varchar(255) DEFAULT NULL,
  `pole_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departement`
--

INSERT INTO `departement` (`id`, `nom`, `nom_directeur`, `nom_pole`, `pole_id`) VALUES
(9, 'Division des Finances Publiques', '', 'PÔLE MACROÉCONOMIE', NULL),
(11, 'Division de l`Analyse Monétaire et de la Veille Stratégique', '', 'PÔLE MACROÉCONOMIE', NULL),
(12, 'Division de la Balance des Paiements', '', 'PÔLE MACROÉCONOMIE', NULL),
(13, 'Division des Relations avec L`Europe', '', 'PÔLE RELATIONS AVEC L`AFRIQUE ET L`EURPOE', NULL),
(14, 'Division des Relations avec l`Afrique', '', 'PÔLE RELATIONS AVEC L`AFRIQUE ET L`EURPOE', NULL),
(15, 'Division de Marché des Capitaux', '', 'PÔLE SECTEUR FINANCIER', NULL),
(16, 'Division de L`Activité Bancaire et de la Stabilité Financière', '', 'PÔLE SECTEUR FINANCIER', NULL),
(17, 'Division du Financement Sectoriel et de l`Inclusion Financière', '', 'PÔLE SECTEUR FINANCIER', NULL),
(18, 'Division des Assurances et de la Prévoyance Sociale', '', 'PÔLE SECTEUR FINANCIER', NULL),
(19, 'Division d`Accompagnement de la Relance Economique (RE)', NULL, 'PÔLE SECTEUR FINANCIER', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employe`
--

CREATE TABLE `employe` (
  `id` int(11) NOT NULL,
  `matricule` varchar(50) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `corps` varchar(255) NOT NULL,
  `fonction` varchar(255) NOT NULL,
  `grade` varchar(55) NOT NULL,
  `nb_post` int(11) NOT NULL,
  `nb_bureau` int(11) NOT NULL,
  `pole_id` int(11) DEFAULT NULL,
  `departement_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `photo` varchar(255) NOT NULL,
  `gender` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employe`
--

INSERT INTO `employe` (`id`, `matricule`, `fullName`, `username`, `tel`, `email`, `role`, `corps`, `fonction`, `grade`, `nb_post`, `nb_bureau`, `pole_id`, `departement_id`, `service_id`, `photo`, `gender`) VALUES
(3, 'BB78458', 'saad akkad', 'saad1', '0625252525', 'saad@gmail.com', NULL, 'rh', '', '', 2, 2, NULL, NULL, NULL, '../uploads/game.png (2).png', 'male'),
(15, 'E07Y8Li', 'test', 'test', '000000', 'test@gmail.com', NULL, 'rh', '', 'Fonction2B', 1, 1, NULL, NULL, NULL, '../uploads/1719412273.tiff', 'male'),
(16, '1111', 'mohcine', 'mohcine', '0619521089', 'm@gmail.com', NULL, 'rh', '', 'Fonction2B', 11111, 11111, NULL, NULL, NULL, '../uploads/1719418539 (4).tiff', 'male'),
(17, 'M10145', 'saket meryem', 'meryem', '0647851256', 'm@gmail.com', NULL, 'Ingénieurs et Architectes', '', 'INGENIEUR D&#039;ETAT 1ER GRADE', 7, 45, 33, 12, 38, '../uploads/1719419527 (4).tiff', 'female');

-- --------------------------------------------------------

--
-- Table structure for table `employes`
--


-- --------------------------------------------------------

--
-- Table structure for table `poles`
--

CREATE TABLE `poles` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `nom_directeur` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poles`
--

INSERT INTO `poles` (`id`, `nom`, `nom_directeur`) VALUES
(33, 'PÔLE MACROÉCONOMIE', ''),
(34, 'PÔLE RELATIONS AVEC L`AFRIQUE ET L`EURPOE', NULL),
(35, 'PÔLE SECTEUR FINANCIER', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `nom_chef` varchar(255) DEFAULT NULL,
  `id_departement` int(11) DEFAULT NULL,
  `nom_departement` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `nom`, `nom_chef`, `id_departement`, `nom_departement`) VALUES
(31, 'Service des Prévisions de la Trésorerie Publique', '', 9, ''),
(32, 'Service des Statistiques des Finances Publiques', '', 9, ''),
(33, 'Service de l`Analyse Monétaire', '', 11, ''),
(34, 'Service des Etudes de la Balance des Paiements', NULL, 12, ''),
(35, 'Service de la Réglementation des Opérations Commerciales et Fiancières', NULL, 12, ''),
(36, 'Service de la Conjoncture et de la Veille Stratégique', '', 11, ''),
(37, 'Service du Suivi et de l`Analyse des évaluations économiques internationales', '', 11, ''),
(38, 'Service de l`Analyse et des Prévisions de la Balance des Paiements', NULL, 12, ''),
(39, 'Service des Relations avec les pays de l`Europe', '', 14, ''),
(40, 'Service des Relations avec l`Union Européenne', '', 13, ''),
(41, 'Service de la Convergence Règlementaire', '', 13, ''),
(42, 'Service de la Gestion des Instruments de la Coopération Technique avec l`UE', '', 13, ''),
(43, 'Service des Affaires Bilatèrales et Règionales Africaines ', '', 14, ''),
(44, 'Service des Affaires Multilatérales Africaines', '', 14, ''),
(45, 'Service des Institutions du Marché', '', 15, ''),
(46, 'Service des Instruments Financièrs', '', 15, ''),
(47, 'Service de l`Epargne Institutionnelle', '', 15, ''),
(48, 'Service des Banques', '', 16, ''),
(49, 'Service des Institutions Financières et des Etudes', '', 16, ''),
(50, 'Service des Instruments de Financement de la MPME', '', 17, ''),
(51, 'Service des Institutions Financières Publiques', '', 17, ''),
(52, 'Services des Instruments de Financement Sectoriel', '', 17, ''),
(53, 'Service de l`Inclusion Financière', '', 17, ''),
(54, 'Service des Assurances', '', 18, ''),
(55, 'Service de la Prévoyance Sociale', '', 18, ''),
(56, 'Service de Régimes de Retraite', '', 18, ''),
(57, 'Service de la Coordination du Financement de la RE', '', 19, ''),
(58, 'Service du Suivi de l`Exécution de la Stratégie de RE', '', 19, ''),
(59, 'Service de Développement des Instruments de RE', NULL, 19, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `conges`
--
ALTER TABLE `conges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conges_ibfk_1` (`id_employe`);

--
-- Indexes for table `departement`
--
ALTER TABLE `departement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_departements_poles` (`nom_pole`),
  ADD KEY `fk_departement_pole` (`pole_id`);

--
-- Indexes for table `employe`
--
ALTER TABLE `employe`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matricule` (`matricule`),
  ADD KEY `employe_ibfk_1` (`pole_id`),
  ADD KEY `employe_ibfk_3` (`service_id`),
  ADD KEY `id_dep` (`departement_id`);

--
-- Indexes for table `employes`
--

-- Indexes for table `poles`
--
ALTER TABLE `poles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index_nom` (`nom`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `conges`
--
ALTER TABLE `conges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `departement`
--
ALTER TABLE `departement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `employe`
--
ALTER TABLE `employe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `employes`
--


--
-- AUTO_INCREMENT for table `poles`
--
ALTER TABLE `poles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `conges`
--
ALTER TABLE `conges`
  ADD CONSTRAINT `conges_ibfk_1` FOREIGN KEY (`id_employe`) REFERENCES `employe` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `departement`
--
ALTER TABLE `departement`
  ADD CONSTRAINT `fk_departement_pole` FOREIGN KEY (`pole_id`) REFERENCES `poles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_departements_poles` FOREIGN KEY (`nom_pole`) REFERENCES `poles` (`nom`) ON DELETE SET NULL;

--
-- Constraints for table `employe`
--
ALTER TABLE `employe`
  ADD CONSTRAINT `employe_ibfk_1` FOREIGN KEY (`pole_id`) REFERENCES `poles` (`id`),
  ADD CONSTRAINT `employe_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  ADD CONSTRAINT `id_dep` FOREIGN KEY (`departement_id`) REFERENCES `departement` (`id`);

--
-- Constraints for table `employes`
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
