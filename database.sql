-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2024 at 02:24 PM
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
(8, 61, '2024-10-10', '2024-10-12', 'approve', 'jid');

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
(14, 'departement1', 'saad akkad', 'finance');

-- --------------------------------------------------------

--
-- Table structure for table `employes`
--

CREATE TABLE `employes` (
  `id` int(11) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `id_pole` int(11) DEFAULT NULL,
  `id_departement` int(11) DEFAULT NULL,
  `id_service` int(11) DEFAULT NULL,
  `role` enum('rh','directeur','chef de service','employe') NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `nb_post` int(11) NOT NULL,
  `occupation` varchar(255) NOT NULL,
  `nb_bureau` int(11) NOT NULL,
  `corps` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employes`
--

INSERT INTO `employes` (`id`, `fullName`, `email`, `id_pole`, `id_departement`, `id_service`, `role`, `photo`, `password`, `username`, `nb_post`, `occupation`, `nb_bureau`, `corps`, `createdAt`) VALUES
(61, 'akkad mohcine', 'akkad@gmail.com', 15, 14, 5, 'rh', '../uploads/entreprise.jpg', '$2y$10$kKe9g/PVM/zXovjWv9BuseZ3CONTys0kOse6Mmh.uI.iE8Mv/1tDa', 'mohcine1', 1, '0', 1, 'Corps is a term used for several different kinds of organization. A military innovation by Napoleon I, the formation was first named as such in 1805.', '2024-06-07 12:38:24'),
(62, 'saad akkad', 'aa@gmail.com', 15, 14, 5, 'directeur', NULL, '$2y$10$3xXBSLwqQ5F71DKuE1OrE.Oi/WByw0m3WE8IXZ79rnLMoRUECGGhq', 'saad01', 4, '0', 7, 'Fatal error: Uncaught mysqli_sql_exception: Column &amp;#039;id_pole&amp;#039; cannot be null in D:\\xampp\\htdocs\\ministere\\pages\\signup.php:61 Stack trace: #0 D:\\xampp\\htdocs\\ministere\\pages\\signup.php(61): mysqli_stmt-&amp;gt;execute() #1 {main} thrown i', '2024-06-07 12:40:15'),
(63, 'chef said', 'aaa@gmail.com', 15, 14, 5, 'chef de service', '../uploads/entreprise.jpg', '$2y$10$XPAcB3QnF7m3fDC2CerhJeKwcOB2Pv..tAw/NIMxNudOdXDwYZ0wC', 'chef1', 45, '0', 78, '0', '2024-06-07 12:59:44'),
(64, 'employe', 'lo@gmail.com', 15, 14, 5, 'employe', '../uploads/image_2024-06-07_140203986.png', '$2y$10$wC9Olp/ltI4hzkSXY/1DAOLEEXpvD.rZVvfuLBofd0xKO1nQBPNJS', 'emp1', 1452, '0', 7, 'jiufhyhuirg', '2024-06-07 13:02:37'),
(65, 'rh', 'rh@gmail.com', 15, 14, 5, 'rh', '../uploads/entreprise.jpg', '$2y$10$ei1EKh4G3onjxgGu6/I0Ze/l.RaDIBIpz.7kvd0Hosx/JiQA/wLZ.', 'rh', 47, 'rh', 78, 'rhhh', '2024-06-07 13:04:52'),
(66, 'simple', 'x@gmail.com', 15, 14, 5, 'employe', '../uploads/king.jpg', '$2y$10$xBRbzf49iCiRXJls5Vft6.0nU2u3Fqaq2utIAKchFPyn480YYYgDq', 's1x', 478, 'ihugyt', 6, 'uigyugzgyeygzyzeygzygeuygz', '2024-06-07 13:08:10'),
(67, 'huhu', 'aaaa@gmail.com', 15, 14, 5, 'rh', '../uploads/entreprise.jpg', '$2y$10$qdxADZQQaK2gSRozG8F59eoXq3zCOjFIcSQUP9KktFty.HBlys5kC', 'h1', 4, 'hello', 7, 'ojioyuiui', '2024-06-07 20:40:28'),
(68, 'hanae laassel', 'la@gmail.com', 15, 14, 5, 'employe', '../uploads/Black_Oranye_Archetype_Inspired_Logo__1_.png', '$2y$10$ocRXYgdgzDOvwhWquldYa.OTkXZCMgLwQ4p7hFBmxli9Gxw5oC2fO', 'la1', 6, 'hterv', 8, 'eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee', '2024-06-08 11:18:19'),
(69, 'mama', 'm@gmail.com', 16, 14, NULL, 'directeur', '../uploads/king.jpg', '$2y$10$opPRYXyfPZaPtDJ/15HpIeUqjTi0v6dbns1iPQXiw1KhxjrN5vKuK', 'm1', 58, 'gtutyty', 4, 'rrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr', '2024-06-08 11:19:49');

-- --------------------------------------------------------

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
(15, 'finance', 'saad akkad'),
(16, 'finance', 'saad akkad');

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
(5, 'service1', 'chef said', 14, 0);

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
  ADD KEY `nom_directeur` (`nom_directeur`),
  ADD KEY `nom_pole` (`nom_pole`);

--
-- Indexes for table `employes`
--
ALTER TABLE `employes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_service` (`id_service`) USING BTREE,
  ADD KEY `id_pole` (`id_pole`),
  ADD KEY `employes_ibfk_1` (`id_departement`);

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
  ADD KEY `id_departement` (`id_departement`),
  ADD KEY `nom_departement` (`nom_departement`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `conges`
--
ALTER TABLE `conges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `departements`
--
ALTER TABLE `departements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `employes`
--
ALTER TABLE `employes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `poles`
--
ALTER TABLE `poles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  ADD CONSTRAINT `employes_ibfk_1` FOREIGN KEY (`id_departement`) REFERENCES `departements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employes_ibfk_2` FOREIGN KEY (`id_service`) REFERENCES `services` (`id`);

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
