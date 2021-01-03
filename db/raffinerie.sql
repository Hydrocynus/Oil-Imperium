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
-- Tabellenstruktur für Tabelle `raffinerie`
--

CREATE TABLE `raffinerie` (
  `Name` varchar(100) NOT NULL,
  `Wert` int(11) NOT NULL,
  `PID` int(11) NOT NULL,
  `SpielCode` char(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `raffinerie`
--
ALTER TABLE `raffinerie`
  ADD PRIMARY KEY (`Name`),
  ADD KEY `PID` (`PID`),
  ADD KEY `SpielCode` (`SpielCode`);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `raffinerie`
--
ALTER TABLE `raffinerie`
  ADD CONSTRAINT `raffinerie_ibfk_1` FOREIGN KEY (`PID`) REFERENCES `position` (`PID`),
  ADD CONSTRAINT `raffinerie_ibfk_2` FOREIGN KEY (`SpielCode`) REFERENCES `spiel` (`SpielCode`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
