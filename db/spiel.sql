-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Erstellungszeit: 03. Jan 2021 um 14:58
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
-- Tabellenstruktur für Tabelle `spiel`
--

CREATE TABLE `spiel` (
  `SpielCode` char(4) NOT NULL,
  `IP` varchar(15) NOT NULL,
  `Port` int(11) NOT NULL,
  `Letzte_Aenderung` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `spiel`
--

INSERT INTO `spiel` (`SpielCode`, `IP`, `Port`, `Letzte_Aenderung`) VALUES
('GKCB', '192.168.2.114', 49609, NULL),
('JUMP', '192.168.2.114', 63051, NULL),
('ZJCD', '192.168.2.114', 52204, NULL),
('ZOYL', '192.168.2.114', 63826, NULL);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `spiel`
--
ALTER TABLE `spiel`
  ADD PRIMARY KEY (`SpielCode`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
