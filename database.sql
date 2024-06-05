-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2024 at 06:06 PM
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
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `statut` varchar(255) DEFAULT 'en attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departements`
--

CREATE TABLE `departements` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `nom_directeur` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departements`
--

INSERT INTO `departements` (`id`, `nom`, `nom_directeur`) VALUES
(1, 'finance', 'mohcine');

-- --------------------------------------------------------

--
-- Table structure for table `employes`
--

CREATE TABLE `employes` (
  `id` int(11) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `id_pole` int(11) NOT NULL,
  `id_departement` int(11) DEFAULT NULL,
  `id_service` int(11) DEFAULT NULL,
  `role` enum('rh','directeur','chef de service','employe') NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `dob` varchar(50) NOT NULL,
  `occupation` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `education` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employes`
--

INSERT INTO `employes` (`id`, `fullName`, `email`, `id_pole`, `id_departement`, `id_service`, `role`, `photo`, `password`, `username`, `dob`, `occupation`, `age`, `education`, `createdAt`) VALUES
(24, 'zk eijfzo', 'akkad@gmail.com', 0, 1, 1, 'chef de service', '../uploads/entreprise', '$2y$10$c1mJh2.CdQzvUFlXGKYf..TZ0dPCNw/soioOqbf1p48XVwoPSGKYC', 'wak07', '', '', 0, '', '2024-06-04 13:15:27'),
(26, 'oijuygvuhioj', 'alokaokl@gmail.com', 0, 1, 1, 'rh', NULL, '$2y$10$9sr2DdmaAClEpvMU0ouHge4/zBInfKwGb1xbr2EG6urJT9gdCzmtS', 'kpojihjn', '', '', 0, '', '2024-06-04 13:15:27'),
(28, 'oijuygvuhioj', 'aloaokl@gmail.com', 0, 1, 1, 'rh', NULL, '$2y$10$4seTATOFpmOTvKUq02yEd.IpmevjOJ5AxV5IlR2DUG6Ns782kU1eC', 'ki', '', '', 0, '', '2024-06-04 13:15:27'),
(29, 'oijuygvuhioj', 'alookl@gmail.com', 0, 1, 1, 'rh', NULL, '$2y$10$g8.jeMsvXPzFqu/qVVRiZu3XpMC1c9jLW/J639AsA.QudHrSLAaU6', 'ki7', '', '', 0, '', '2024-06-04 13:15:27'),
(30, 'oijuygvuhioj', 'alokl@gmail.com', 0, 1, 1, 'rh', NULL, '$2y$10$Rkpry.eFnkc8Mt7tB3w8Sezq4NeIgcmOGbkBdnuxeVIOBv.Sxynfq', 'ki8', '', '', 0, '', '2024-06-04 13:15:27'),
(31, 'oijuygvuhioj', 'alkl@gmail.com', 0, 1, 1, 'rh', NULL, '$2y$10$igT5coyWUAupG3f7H58a.egIn8ixh37ceMyZBF1WDRm2na1M9XWYe', 'ki9', '', '', 0, '', '2024-06-04 13:15:27'),
(32, 'saadaa', 'a@gmail.com', 0, 1, 1, 'rh', '../uploads/entreprise', '$2y$10$MTMnmZZuWiHp.xFXX0JdgeKYNupjYlHsJHG/lHX/K28FMDOzuPMNG', 'saad00', '', '', 0, '', '2024-06-04 13:15:27'),
(33, 'ctfgyvh', 'aa@gmail.com', 0, 1, 1, 'rh', '../uploads/entreprise', '$2y$10$JTNYMi7Le7nuLEFQBchU8.8ur0wdwJFmPjmC71dXqBWGAAtsWv5WO', 'q14', '', '', 0, '', '2024-06-04 13:15:27'),
(34, 'hhya', 'hanae@gmail.com', 0, 1, 1, 'directeur', '../uploads/entreprise.jpg', '$2y$10$By.A0a499AFOrIPlUHiEmOiVe4SFTq5YjAnaExCptjXYeAZpltF9S', 'hanae887', '', '', 0, '', '2024-06-04 13:15:27'),
(35, 'chaibia', 'chaibia@gmail.com', 0, 1, 1, 'chef de service', '../uploads/entreprise.jpg', '$2y$10$wqYi5bonSAQmuOSimvPqgu.4EqScDAw7rlv2bKgHBopCmbES7c4lK', 'mama', '1993-01-29', 'chef de nuit', 50, 'bac+7', '2024-06-04 13:16:49'),
(36, 'akkad mohcine', 'akkadmohcine07@gmail.com', 0, 1, 1, 'directeur', '../uploads/WhatsApp_Image_2024-06-04_at_23_07_38.jpeg', '$2y$10$kAjWpxvkcumtjhwLwXoZiOekedw2JyQQdfn2RNkaNDgEPGo.XChuy', 'Mohcine07', '2004-03-25', 'directeur finance', 20, 'bac+2', '2024-06-04 22:09:45'),
(37, 'azdc', 'ad@gmail.com', 0, 1, 1, 'employe', '../uploads/king.jpg', '$2y$10$a9JwsUr9tGBZuDDRTx6Ox.HAuxFUWDnkrXDb7siCiQJX5NilQmdnG', 'pop1', '2015-10-28', 'chef de nuit', 50, 'bac+8', '2024-06-05 15:39:16');

-- --------------------------------------------------------

--
-- Table structure for table `poles`
--

CREATE TABLE `poles` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `nom_directeur` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poles`
--

INSERT INTO `poles` (`id`, `nom`, `nom_directeur`) VALUES
(1, 'huh', 'mohcine');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `nom_chef` varchar(255) DEFAULT NULL,
  `id_departement` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `nom`, `nom_chef`, `id_departement`) VALUES
(1, 'flos', 'akkad', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `conges`
--
ALTER TABLE `conges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_employe` (`id_employe`);

--
-- Indexes for table `departements`
--
ALTER TABLE `departements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nom_directeur` (`nom_directeur`);

--
-- Indexes for table `employes`
--
ALTER TABLE `employes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_departement` (`id_departement`),
  ADD KEY `id_service` (`id_service`) USING BTREE,
  ADD KEY `id_pole` (`id_pole`);

--
-- Indexes for table `poles`
--
ALTER TABLE `poles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nom_directeur` (`nom_directeur`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_departement` (`id_departement`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `conges`
--
ALTER TABLE `conges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departements`
--
ALTER TABLE `departements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employes`
--
ALTER TABLE `employes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `poles`
--
ALTER TABLE `poles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `conges`
--
ALTER TABLE `conges`
  ADD CONSTRAINT `conges_ibfk_1` FOREIGN KEY (`id_employe`) REFERENCES `employes` (`id`);

--
-- Constraints for table `employes`
--
ALTER TABLE `employes`
  ADD CONSTRAINT `employes_ibfk_1` FOREIGN KEY (`id_departement`) REFERENCES `departements` (`id`),
  ADD CONSTRAINT `employes_ibfk_2` FOREIGN KEY (`id_service`) REFERENCES `services` (`id`);

--
-- Constraints for table `poles`
--
ALTER TABLE `poles`
  ADD CONSTRAINT `poles_ibfk_1` FOREIGN KEY (`nom_directeur`) REFERENCES `departements` (`nom_directeur`);

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`id_departement`) REFERENCES `departements` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
