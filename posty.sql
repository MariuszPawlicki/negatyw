-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 07 Cze 2018, 21:35
-- Wersja serwera: 10.1.31-MariaDB
-- Wersja PHP: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `negatyw`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `posty`
--

CREATE TABLE `posty` (
  `id` int(11) NOT NULL,
  `title` text COLLATE ucs2_polish_ci NOT NULL,
  `img` text COLLATE ucs2_polish_ci NOT NULL,
  `description` text COLLATE ucs2_polish_ci NOT NULL,
  `content` text COLLATE ucs2_polish_ci NOT NULL,
  `views` int(11) NOT NULL DEFAULT '1',
  `entries` int(11) NOT NULL DEFAULT '1',
  `ups` int(11) NOT NULL,
  `downs` int(11) NOT NULL,
  `rating` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ucs2 COLLATE=ucs2_polish_ci;

--
-- Zrzut danych tabeli `posty`
--

INSERT INTO `posty` (`id`, `title`, `img`, `description`, `content`, `views`, `entries`, `ups`, `downs`, `rating`) VALUES
(1, 'tytul', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ut diam neque. Sed aliquet nibh dui, vel tincidunt libero eleifend sed. Phasellus justo diam, dapibus a laoreet nec, imperdiet quis dolor. Aliquam consectetur, lacus ut scelerisque porta, nibh nulla posuere magna, eu efficitur est lorem ut turpis. Donec id ipsum ut tortor venenatis molestie sit amet sit amet risus.', '', 24, 0, 0, 0, 0),
(2, 'tytul 2', '', 'lalala', '', 26, 2, 0, 0, 400),
(3, '3', '', 'lalala', '', 29, 0, 0, 0, 0),
(4, '4', '', 'lalala', '', 29, 0, 0, 0, 0),
(5, '5', '', 'lalala', '', 29, 0, 0, 0, 0),
(6, '6', '', 'lalala', '', 29, 0, 0, 0, 0),
(7, '7', '', 'lalala', '', 9, 0, 0, 0, 0);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `posty`
--
ALTER TABLE `posty`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `posty`
--
ALTER TABLE `posty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
