-- CREATE TABLE employe (
--     id INT NOT NULL AUTO_INCREMENT,
--     matricule INT NOT NULL,
--     fullName VARCHAR(255) NOT NULL,
--     username VARCHAR(255) NOT NULL,
--     tel VARCHAR(20) NOT NULL,
--     email VARCHAR(255) NOT NULL,
--     grade VARCHAR(255) NOT NULL,
--     fonction VARCHAR(255) NOT NULL,
--     nb_post INT NOT NULL,
--     nb_bureau INT NOT NULL,
--     pole_id INT,
--     departement_id INT,
--     service_id INT,
--     PRIMARY KEY (id),
--     UNIQUE (matricule),
--     FOREIGN KEY (pole_id) REFERENCES poles(id) ON DELETE CASCADE,
--     FOREIGN KEY (departement_id) REFERENCES departements(id) ON DELETE CASCADE,
--     FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2024 at 08:15 PM
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
  `statut` varchar(255) DEFAULT 'en attente',
  `motif` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `conges`
--

INSERT INTO `conges` (`id`, `id_employe`, `date_debut`, `date_fin`, `statut`, `motif`) VALUES
(15, 61, '2024-10-10', '2024-11-11', 'approve', 'rest');

-- --------------------------------------------------------

--
-- Table structure for table `departements`
--

CREATE TABLE `departements` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `nom_directeur` varchar(255) NOT NULL,
  `nom_pole` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departements`
--

INSERT INTO `departements` (`id`, `nom`, `nom_directeur`, `nom_pole`) VALUES
(14, 'departement1', 'fullName', 'pole3'),
(15, 'dep2', 'fullName', 'pole3'),
(16, 'dep3', 'fullName', 'pole4');

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
  `role` varchar(255) NOT NULL,
  `corps` varchar(255) NOT NULL,
  `fonction` varchar(255) NOT NULL,
  `grade` varchar(55) NOT NULL,
  `nb_post` int(11) NOT NULL,
  `nb_bureau` int(11) NOT NULL,
  `pole_id` int(11) DEFAULT NULL,
  `departement_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employe`
--



-- --------------------------------------------------------

--
-- Table structure for table `employes`
--

--
-- Table structure for table `poles`
--

CREATE TABLE `poles` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `nom_directeur` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poles`
--

INSERT INTO `poles` (`id`, `nom`, `nom_directeur`) VALUES
(15, 'pole1', 'fullName'),
(16, 'pole2', 'fullName'),
(17, 'pole4', 'fullName'),
(18, 'pole3', 'fullName'),
(20, 'pole5', 'fullName');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `nom_chef` varchar(255) DEFAULT NULL,
  `id_departement` int(11) DEFAULT NULL,
  `nom_departement` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `nom`, `nom_chef`, `id_departement`, `nom_departement`) VALUES
(5, 'service1', 'chef', 16, 0);

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
-- Indexes for table `departements`
--
ALTER TABLE `departements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nom_pole` (`nom_pole`);

--
-- Indexes for table `employe`
--
ALTER TABLE `employe`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matricule` (`matricule`),
  ADD KEY `pole_id` (`pole_id`),
  ADD KEY `departement_id` (`departement_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `employes`
--

-- Indexes for table `poles`
--
ALTER TABLE `poles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_departement` (`id_departement`),
  ADD KEY `nom_departement` (`nom_departement`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `conges`
--
ALTER TABLE `conges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `departements`
--
ALTER TABLE `departements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `employe`
--
ALTER TABLE `employe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employes`
--

--
-- AUTO_INCREMENT for table `poles`
--
ALTER TABLE `poles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `conges`
--
ALTER TABLE `conges`
  ADD CONSTRAINT `conges_ibfk_1` FOREIGN KEY (`id_employe`) REFERENCES `employes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employe`
--
ALTER TABLE `employe`
  ADD CONSTRAINT `employe_ibfk_1` FOREIGN KEY (`pole_id`) REFERENCES `poles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employe_ibfk_2` FOREIGN KEY (`departement_id`) REFERENCES `departements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employe_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employes`
--
    
--
-- Constraints for table `poles`
--
ALTER TABLE `poles`
  ADD CONSTRAINT `poles_ibfk_1` FOREIGN KEY (`nom_directeur`) REFERENCES `departements` (`nom_directeur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`id_departement`) REFERENCES `departements` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
