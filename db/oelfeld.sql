-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Erstellungszeit: 03. Jan 2021 um 14:57
-- Server-Version: 10.4.14-MariaDB
-- PHP-Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `barrel`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `oelfeld`
--

CREATE TABLE `oelfeld` (
  `OID` int(11) NOT NULL,
  `Standort` int(11) NOT NULL,
  `Foerderwege` int(11) NOT NULL,
  `Bohrkosten` int(11) NOT NULL,
  `SpielCode` char(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `oelfeld`
--
ALTER TABLE `oelfeld`
  ADD PRIMARY KEY (`OID`),
  ADD KEY `Standort` (`Standort`),
  ADD KEY `SpielCode` (`SpielCode`);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `oelfeld`
--
ALTER TABLE `oelfeld`
  ADD CONSTRAINT `oelfeld_ibfk_1` FOREIGN KEY (`Standort`) REFERENCES `standort` (`Standort`),
  ADD CONSTRAINT `oelfeld_ibfk_2` FOREIGN KEY (`SpielCode`) REFERENCES `spiel` (`SpielCode`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
