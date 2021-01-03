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
-- Tabellenstruktur für Tabelle `oefeld2spieler`
--

CREATE TABLE `oefeld2spieler` (
  `OID` int(11) NOT NULL,
  `SpielerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `oefeld2spieler`
--
ALTER TABLE `oefeld2spieler`
  ADD PRIMARY KEY (`OID`,`SpielerID`),
  ADD KEY `SpielerID` (`SpielerID`);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `oefeld2spieler`
--
ALTER TABLE `oefeld2spieler`
  ADD CONSTRAINT `oefeld2spieler_ibfk_1` FOREIGN KEY (`OID`) REFERENCES `oelfeld` (`OID`),
  ADD CONSTRAINT `oefeld2spieler_ibfk_2` FOREIGN KEY (`SpielerID`) REFERENCES `spieler` (`SpielerID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
