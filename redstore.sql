-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Φιλοξενητής: 127.0.0.1
-- Χρόνος δημιουργίας: 17 Απρ 2016 στις 15:51:05
-- Έκδοση διακομιστή: 10.1.10-MariaDB
-- Έκδοση PHP: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `redstore`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `monuments`
--

CREATE TABLE `monuments` (
  `id` int(11) NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'all',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` double NOT NULL DEFAULT '0',
  `longitude` double NOT NULL DEFAULT '0',
  `source` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Άδειασμα δεδομένων του πίνακα `monuments`
--

INSERT INTO `monuments` (`id`, `type`, `name`, `latitude`, `longitude`, `source`) VALUES
(1, 'statues', 'Λέων Αμφίπολης', 40.803073, 23.842533, 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/31/Amphipolis_Lion.jpg/240px-Amphipolis_Lion.jpg'),
(2, 'museums', 'Αχμέτ Πάσα', 41.091472, 23.559535, 'http://www.serres.gr/files/001/images/axiotheata/tzami_axmet_pasa/482.jpg'),
(3, 'museums', 'Μουσείο Φυσικής Ιστορίας', 41.099921, 23.569491, 'http://www.serrelib.gr/images/mousio_fisikis/02.jpg'),
(4, 'museums', 'Μουσείο Παλαιάς Μητρόπολης Σερρών, Ίωνος Δραγούμη', 41.093731, 23.552608, 'http://www.content.4ty.gr/merchants/photos/2015/10/2551-MOYSEIA-MOYSEIO-PALAIAS-MITROPOLIS-SERRON-nov3.jpg'),
(5, 'museums', 'Εκκλησιαστικό Μουσείο Ιεράς Μητρόπολης', 41.092958, 23.549323, NULL),
(6, 'museums', 'Αρχαιολογικό Μουσείο Αμφίπολης', 40.825181, 23.848735, 'http://www.prosotsani.gr/travel/media/k2/items/cache/42121f00ffc451d0c288e11c1f28cbd4_XL.jpg'),
(7, 'heroons', 'Μνημείο Ηρώων', 41.075134, 23.538306, 'http://3.bp.blogspot.com/-cF6Fl9RDxt4/T5cFmm4ZkfI/AAAAAAAAAL4/IEHdp6uQh1U/s1600/%CE%95%CE%B9%CE%BA%CF%8C%CE%BD%CE%B1+3+018.jpg'),
(8, 'museums', 'Οχυρό Ρούπελ', 41.345866, 23.370981, 'http://www.enikos.gr/data/photos/0e67236e8468e0b5f132ebaf7eb380fb.jpg'),
(9, 'statues', 'Στέλιος Συμεωνίδης', 41.087552, 23.56433, 'http://3.bp.blogspot.com/-Cf76nnlLdHc/VdWade6ZKNI/AAAAAAABW8E/Gw6XtGjhImQ/s400/249.JPG');

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `monuments`
--
ALTER TABLE `monuments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `monuments`
--
ALTER TABLE `monuments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
