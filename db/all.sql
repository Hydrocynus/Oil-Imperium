-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Erstellungszeit: 03. Jan 2021 um 14:56
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
CREATE DATABASE IF NOT EXISTS `barrel` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `barrel`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bohrstatus`
--

CREATE TABLE `bohrstatus` (
  `SID` int(11) NOT NULL,
  `Beschreibung` varchar(200) NOT NULL,
  `Aktion` varchar(200) NOT NULL,
  `SpielCode` char(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bohrung`
--

CREATE TABLE `bohrung` (
  `BID` int(11) NOT NULL,
  `SpielerID` int(11) NOT NULL,
  `OID` int(11) NOT NULL,
  `SID` int(11) NOT NULL,
  `SpielCode` char(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ereigniskarte`
--

CREATE TABLE `ereigniskarte` (
  `EID` int(11) NOT NULL,
  `Kategorie` varchar(100) NOT NULL,
  `Aktion` varchar(100) NOT NULL,
  `Text` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `informationskarte`
--

CREATE TABLE `informationskarte` (
  `IID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `oefeld2spieler`
--

CREATE TABLE `oefeld2spieler` (
  `OID` int(11) NOT NULL,
  `SpielerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `position`
--

CREATE TABLE `position` (
  `PID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `position2position`
--

CREATE TABLE `position2position` (
  `PID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `raffinerie2informationskarte`
--

CREATE TABLE `raffinerie2informationskarte` (
  `IID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `Aenderung` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `spieler`
--

CREATE TABLE `spieler` (
  `SpielerID` int(11) NOT NULL,
  `farbe` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `geld` int(11) NOT NULL,
  `kredite_gemacht` int(11) DEFAULT NULL,
  `SpielCode` char(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `spielfeld`
--

CREATE TABLE `spielfeld` (
  `Bezeichnung` varchar(100) NOT NULL,
  `Wahrscheinlichkeit` int(11) NOT NULL,
  `Beschreibung` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `standort`
--

CREATE TABLE `standort` (
  `Standort` int(11) NOT NULL,
  `Bezeichnung` int(11) NOT NULL,
  `PID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tanker`
--

CREATE TABLE `tanker` (
  `TID` int(11) NOT NULL,
  `Groesse` int(11) NOT NULL,
  `PID` int(11) NOT NULL,
  `SpielerID` int(11) NOT NULL,
  `SpielCode` char(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `bohrstatus`
--
ALTER TABLE `bohrstatus`
  ADD PRIMARY KEY (`SID`),
  ADD KEY `SpielCode` (`SpielCode`);

--
-- Indizes für die Tabelle `bohrung`
--
ALTER TABLE `bohrung`
  ADD PRIMARY KEY (`BID`),
  ADD KEY `SpielCode` (`SpielCode`),
  ADD KEY `SpielerID` (`SpielerID`),
  ADD KEY `OID` (`OID`),
  ADD KEY `SID` (`SID`);

--
-- Indizes für die Tabelle `ereigniskarte`
--
ALTER TABLE `ereigniskarte`
  ADD PRIMARY KEY (`EID`);

--
-- Indizes für die Tabelle `informationskarte`
--
ALTER TABLE `informationskarte`
  ADD PRIMARY KEY (`IID`);

--
-- Indizes für die Tabelle `oefeld2spieler`
--
ALTER TABLE `oefeld2spieler`
  ADD PRIMARY KEY (`OID`,`SpielerID`),
  ADD KEY `SpielerID` (`SpielerID`);

--
-- Indizes für die Tabelle `oelfeld`
--
ALTER TABLE `oelfeld`
  ADD PRIMARY KEY (`OID`),
  ADD KEY `Standort` (`Standort`),
  ADD KEY `SpielCode` (`SpielCode`);

--
-- Indizes für die Tabelle `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`PID`);

--
-- Indizes für die Tabelle `position2position`
--
ALTER TABLE `position2position`
  ADD PRIMARY KEY (`PID`);

--
-- Indizes für die Tabelle `raffinerie`
--
ALTER TABLE `raffinerie`
  ADD PRIMARY KEY (`Name`),
  ADD KEY `PID` (`PID`),
  ADD KEY `SpielCode` (`SpielCode`);

--
-- Indizes für die Tabelle `raffinerie2informationskarte`
--
ALTER TABLE `raffinerie2informationskarte`
  ADD PRIMARY KEY (`IID`,`name`),
  ADD KEY `name` (`name`);

--
-- Indizes für die Tabelle `spiel`
--
ALTER TABLE `spiel`
  ADD PRIMARY KEY (`SpielCode`);

--
-- Indizes für die Tabelle `spieler`
--
ALTER TABLE `spieler`
  ADD PRIMARY KEY (`SpielerID`),
  ADD KEY `SpielCode` (`SpielCode`);

--
-- Indizes für die Tabelle `spielfeld`
--
ALTER TABLE `spielfeld`
  ADD PRIMARY KEY (`Bezeichnung`);

--
-- Indizes für die Tabelle `standort`
--
ALTER TABLE `standort`
  ADD PRIMARY KEY (`Standort`),
  ADD KEY `PID` (`PID`);

--
-- Indizes für die Tabelle `tanker`
--
ALTER TABLE `tanker`
  ADD PRIMARY KEY (`TID`),
  ADD KEY `SpielerID` (`SpielerID`),
  ADD KEY `SpielCode` (`SpielCode`),
  ADD KEY `PID` (`PID`);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `bohrstatus`
--
ALTER TABLE `bohrstatus`
  ADD CONSTRAINT `bohrstatus_ibfk_1` FOREIGN KEY (`SpielCode`) REFERENCES `spiel` (`SpielCode`);

--
-- Constraints der Tabelle `bohrung`
--
ALTER TABLE `bohrung`
  ADD CONSTRAINT `bohrung_ibfk_1` FOREIGN KEY (`SpielCode`) REFERENCES `spiel` (`SpielCode`),
  ADD CONSTRAINT `bohrung_ibfk_2` FOREIGN KEY (`SpielerID`) REFERENCES `spieler` (`SpielerID`),
  ADD CONSTRAINT `bohrung_ibfk_3` FOREIGN KEY (`OID`) REFERENCES `oelfeld` (`OID`),
  ADD CONSTRAINT `bohrung_ibfk_4` FOREIGN KEY (`SID`) REFERENCES `bohrstatus` (`SID`);

--
-- Constraints der Tabelle `oefeld2spieler`
--
ALTER TABLE `oefeld2spieler`
  ADD CONSTRAINT `oefeld2spieler_ibfk_1` FOREIGN KEY (`OID`) REFERENCES `oelfeld` (`OID`),
  ADD CONSTRAINT `oefeld2spieler_ibfk_2` FOREIGN KEY (`SpielerID`) REFERENCES `spieler` (`SpielerID`);

--
-- Constraints der Tabelle `oelfeld`
--
ALTER TABLE `oelfeld`
  ADD CONSTRAINT `oelfeld_ibfk_1` FOREIGN KEY (`Standort`) REFERENCES `standort` (`Standort`),
  ADD CONSTRAINT `oelfeld_ibfk_2` FOREIGN KEY (`SpielCode`) REFERENCES `spiel` (`SpielCode`);

--
-- Constraints der Tabelle `position2position`
--
ALTER TABLE `position2position`
  ADD CONSTRAINT `position2position_ibfk_1` FOREIGN KEY (`PID`) REFERENCES `position` (`PID`);

--
-- Constraints der Tabelle `raffinerie`
--
ALTER TABLE `raffinerie`
  ADD CONSTRAINT `raffinerie_ibfk_1` FOREIGN KEY (`PID`) REFERENCES `position` (`PID`),
  ADD CONSTRAINT `raffinerie_ibfk_2` FOREIGN KEY (`SpielCode`) REFERENCES `spiel` (`SpielCode`);

--
-- Constraints der Tabelle `raffinerie2informationskarte`
--
ALTER TABLE `raffinerie2informationskarte`
  ADD CONSTRAINT `raffinerie2informationskarte_ibfk_1` FOREIGN KEY (`IID`) REFERENCES `informationskarte` (`IID`),
  ADD CONSTRAINT `raffinerie2informationskarte_ibfk_2` FOREIGN KEY (`name`) REFERENCES `raffinerie` (`Name`);

--
-- Constraints der Tabelle `spieler`
--
ALTER TABLE `spieler`
  ADD CONSTRAINT `spieler_ibfk_1` FOREIGN KEY (`SpielCode`) REFERENCES `spiel` (`SpielCode`);

--
-- Constraints der Tabelle `standort`
--
ALTER TABLE `standort`
  ADD CONSTRAINT `standort_ibfk_1` FOREIGN KEY (`PID`) REFERENCES `position` (`PID`);

--
-- Constraints der Tabelle `tanker`
--
ALTER TABLE `tanker`
  ADD CONSTRAINT `tanker_ibfk_1` FOREIGN KEY (`SpielerID`) REFERENCES `spieler` (`SpielerID`),
  ADD CONSTRAINT `tanker_ibfk_2` FOREIGN KEY (`SpielCode`) REFERENCES `spiel` (`SpielCode`),
  ADD CONSTRAINT `tanker_ibfk_3` FOREIGN KEY (`PID`) REFERENCES `position` (`PID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
