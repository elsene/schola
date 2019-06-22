-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Dim 10 Mars 2019 à 14:55
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `chat_android`
--

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

CREATE TABLE `contact` (
  `monID` int(11) NOT NULL,
  `idContact2` int(11) NOT NULL,
  `monToken` varchar(50) NOT NULL,
  `tokenContact2` varchar(50) NOT NULL,
  `connexionStatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `contact`
--

INSERT INTO `contact` (`monID`, `idContact2`, `monToken`, `tokenContact2`, `connexionStatus`) VALUES
(1, 2, 'aa', 'bb', 1),
(1, 3, 'ss', 'pp', 1),
(1, 4, 'tt', '', 0),
(2, 1, 'bb', 'aa', 1),
(3, 1, 'pp', 'ss', 1),
(3, 5, 'lNBVo', 't2qWU', 1),
(5, 3, 't2qWU', 'lNBVo', 1),
(5, 6, '6_uxE', 'lNBVo', 1),
(6, 2, 'dd', 'qq', 0),
(6, 5, 'lNBVo', '6_uxE', 1);

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `idMessage` int(11) NOT NULL,
  `content` text NOT NULL,
  `date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `server`
--

CREATE TABLE `server` (
  `idServer` varchar(50) NOT NULL,
  `port` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `server`
--

INSERT INTO `server` (`idServer`, `port`) VALUES
('10', '1010'),
('20', '2020'),
('30', '3030'),
('40', '4040');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `idUser` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `idServer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`idUser`, `nom`, `prenom`, `email`, `password`, `idServer`) VALUES
(1, 'sene', 'elimane', 'magenelec@gmail.com', 'sene1', 10),
(2, 'kasa', 'noblesse', 'noblessekasa2@gmail.com', 'kasa2', 20),
(3, 'diop', 'djibril', 'djidiop89@gmail.com', 'diop3', 30),
(4, 'diarra', 'dieneba', 'dienayna93@gmail.com', 'diarra4', 40),
(5, 'toto', 'tata', 'tototata@gmail.com', 'toto5', 50),
(6, 'zaza', 'zozo', 'zazazozo@gmail.com', 'zaza6', 60);

-- --------------------------------------------------------

--
-- Structure de la table `writes`
--

CREATE TABLE `writes` (
  `idContactSend` int(11) NOT NULL,
  `idContactReceive` int(11) NOT NULL,
  `idMessage` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`monID`,`idContact2`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`idMessage`);

--
-- Index pour la table `server`
--
ALTER TABLE `server`
  ADD PRIMARY KEY (`idServer`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`);

--
-- Index pour la table `writes`
--
ALTER TABLE `writes`
  ADD PRIMARY KEY (`idContactReceive`,`idMessage`,`idContactSend`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `idMessage` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
