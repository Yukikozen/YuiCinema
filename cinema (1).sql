-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2024 at 04:07 PM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cinema`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `cust_id` int(11) DEFAULT NULL,
  `show_id` int(11) DEFAULT NULL,
  `no_ticket` int(11) DEFAULT NULL,
  `booking_date` date DEFAULT NULL,
  `total_amount` decimal(10,0) DEFAULT NULL,
  `seat_dt_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `cust_id`, `show_id`, `no_ticket`, `booking_date`, `total_amount`, `seat_dt_id`) VALUES
(49, 22, 2, 3, '2023-09-05', '45', 69),
(50, 22, 2, 3, '2023-09-03', '45', 70),
(51, 22, 2, 3, '2023-08-30', '45', 71),
(52, 22, 2, 3, '2023-08-30', '45', 72),
(53, 22, 2, 3, '2023-08-30', '45', 73),
(54, 22, 2, 3, '2023-08-30', '45', 75),
(55, 22, 1, 3, '2023-09-01', '45', 76),
(56, 22, 1, 3, '2023-09-01', '45', 77),
(57, 22, 1, 3, '2023-09-01', '45', 78),
(58, 22, 2, 2, '2023-08-29', '30', 79),
(59, 22, 2, 3, '2023-08-30', '45', 80),
(60, 22, 2, 3, '2023-08-30', '45', 81),
(61, 22, 2, 3, '2023-08-30', '45', 82),
(62, 22, 2, 3, '2023-08-29', '45', 83),
(63, 22, 2, 3, '2023-09-03', '45', 84);

-- --------------------------------------------------------

--
-- Table structure for table `cinema`
--

CREATE TABLE `cinema` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `location` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cinema`
--

INSERT INTO `cinema` (`id`, `name`, `location`, `city`) VALUES
(1, 'Cineplex Atrium Mall', 'Askari ', 'Karachi'),
(2, 'Nueplex Cinema', 'Saddar', 'Karachi'),
(3, 'Nueplex', 'Askari IV', 'Karachi'),
(4, 'Nueplex Cinema', 'North Nazimabad', 'Karachi'),
(5, 'Cineplex Lucky One', 'Gulshan', 'Karachi');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `hpnum` varchar(50) DEFAULT NULL,
  `msg` varchar(100) DEFAULT NULL,
  `msg_date` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `name`, `email`, `hpnum`, `msg`, `msg_date`) VALUES
(1, 'Yukiko', 'ushiococo@gmail.com', '98413161', 'helloo', '2023-07-26'),
(2, 'Yukiko', 'ushiococo@gmail.com', '98413161', 'helloo', '2023-07-26'),
(3, 'Yukiko', 'ushiococo@gmail.com', '98413161', 'hi there', '2023-08-15'),
(4, 'Yukiko', 'ushiococo@gmail.com', '98413161', 'hello2', '2023-09-28');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `hpnum` varchar(50) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `hash` varchar(255) DEFAULT NULL,
  `profilepic` varchar(255) DEFAULT NULL,
  `expirytime` varchar(255) DEFAULT NULL,
  `verify` int(11) DEFAULT NULL,
  `forgetExpire_time` varchar(255) DEFAULT NULL,
  `secret` varchar(1000) DEFAULT NULL,
  `disabled` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `username`, `email`, `hpnum`, `gender`, `password`, `hash`, `profilepic`, `expirytime`, `verify`, `forgetExpire_time`, `secret`, `disabled`) VALUES
(21, 'Yukiko2', 'ushiococo2@gmail.com', '98413161', 'female', '$2y$10$Tiuumza1oGcPloCPu.g2Y.0a6lX2hN/9MzT88Hpy0SC219uFJEUDm', '81448138f5f163ccdba4acc69819f280', '', '1692106204', NULL, NULL, NULL, NULL),
(22, 'Yukiko', 'ushiococo@gmail.com', '98413161', 'Female', '$2y$10$ATnGP2ghAYkvAh3.xtCfsuP9KKOeZvIWoYW/TzmUlW7P8kamhowha', '839ab46820b524afda05122893c2fe8e', 'images/profile_pictures/Ruby_Jewelpet.png', '1692108360', 1, '1712072116', 'BUVBVD4Z3QRA3TAN', 1),
(85, 'Zen', 'yukikoran08@gmail.com', '98413161', 'Female', '$2y$10$FjKhrBFUdundb0mmCd60b.tCUUerVt3JkAtpVjWq5uQ5RGLOZZDJy', '4b04a686b0ad13dce35fa99fa4161c65', 'images/Default_pfp.png', '1698948024', 1, '1703155342', 'LQBGWAUPAMTIBB5P', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `cust_id` int(11) DEFAULT NULL,
  `movie_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `cust_id`, `movie_id`) VALUES
(41, 22, 57),
(47, 22, 45),
(71, 22, 49),
(79, 22, 61),
(81, 22, 43),
(100, 22, 46),
(101, 22, 44),
(102, 22, 59),
(103, 22, 52),
(104, 22, 54),
(105, 22, 60),
(108, 22, 58);

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE `genre` (
  `id` int(11) NOT NULL,
  `genre_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` (`id`, `genre_name`) VALUES
(1, 'Action'),
(2, 'Comedy'),
(3, 'Horror'),
(4, 'Historical'),
(5, 'Romance'),
(6, 'Mystery'),
(7, 'Mythical'),
(10, 'Novel'),
(11, 'Sci-Fi'),
(12, 'Fantasy'),
(15, 'Non Fiction'),
(16, 'Cartoon'),
(17, 'Adventure'),
(18, 'Short');

-- --------------------------------------------------------

--
-- Table structure for table `industry`
--

CREATE TABLE `industry` (
  `id` int(11) NOT NULL,
  `industry_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `industry`
--

INSERT INTO `industry` (`id`, `industry_name`) VALUES
(1, 'CATHAY CINEPLEX DOWNTOWN EAST'),
(5, 'GV Tampines'),
(17, 'CATHAY CINEPLEX AMK HUB');

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `id` int(11) NOT NULL,
  `lang_name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `lang_name`) VALUES
(1, 'English'),
(2, 'Mandarin'),
(3, 'Melayu'),
(4, 'Japanese æ—¥æ–‡'),
(5, 'Korean éŸ©æ–‡');

-- --------------------------------------------------------

--
-- Table structure for table `movie`
--

CREATE TABLE `movie` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `movie_banner` varchar(255) DEFAULT NULL,
  `description` varchar(5000) DEFAULT NULL,
  `rdate` date DEFAULT current_timestamp(),
  `lang_id` int(11) DEFAULT NULL,
  `runtime` int(11) DEFAULT NULL,
  `viewers` varchar(45) DEFAULT NULL,
  `trailer` varchar(500) DEFAULT NULL,
  `videopath` varchar(500) DEFAULT NULL,
  `slider_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `movie`
--

INSERT INTO `movie` (`id`, `name`, `movie_banner`, `description`, `rdate`, `lang_id`, `runtime`, `viewers`, `trailer`, `videopath`, `slider_id`) VALUES
(43, 'Twilight - New Moon', 'images/movie/twilight_01.jpg', 'Bella Swan is on the cusp of her 18th birthday and blissfully happy with her undead beau Edward Cullen. While celebrating her birthday with Edward\'s family of `vegetarian\' vampires, a frightening incident convinces Edward that he\'s simply too dangerous to be around his sweetheart. He decides to leave the town of Forks in order to ensure her safety - leaving her behind, angry and depressed.', '2009-11-20', 1, 130, '1', 'https://www.youtube.com/embed/q58iQSHhZGg', 'videos/The Twilight Saga- New Moon (2009).mp4', 0),
(44, 'Twilight - Eclipse', 'images/movie/p3623663_p_v8_bc.jpg', 'Danger once again surrounds Bella (Kristen Stewart), as a string of mysterious killings terrorizes Seattle and a malicious vampire continues her infernal quest for revenge. Amid the tumult, Bella must choose between her love for Edward (Robert Pattinson) and her friendship with Jacob (Taylor Lautner), knowing that her decision may ignite the long-simmering feud between vampire and werewolf.', '2010-06-30', 1, 123, '1', 'https://www.youtube.com/embed/wFQ_d1bCKMs', 'videos/The Twilight Saga- Eclipse (2010).mp4', 0),
(45, 'Twilight - Breaking Dawn', 'images/movie/83f86c_99d5a8c2886142c7a7230635383cb4a0~mv2.jpg', 'At last, Bella (Kristen Stewart) and Edward (Robert Pattinson) are getting married. When Jacob (Taylor Lautner) finds out that Bella wants to spend her honeymoon as a human, he is horrified -- for Edward\'s passion could accidentally kill her. Bella does indeed survive her honeymoon, but a new complication arises when she discovers that she\'s pregnant -- and the child is growing at an alarming rate. The pregnancy sets the wolves against Bella and Edward, but Jacob vows to protect his friend.', '2011-11-24', 1, 121, '1', 'https://www.youtube.com/embed/uKcFqL9-bHo', 'videos/The Twilight Saga- Breaking Dawn â€“ Part 1 (2011).mp4', 0),
(46, 'Twilight - Breaking Dawn 2', 'images/movie/MV5BMTcyMzUyMzY1OF5BMl5BanBnXkFtZTcwNDQ4ODk1OA@@._V1_.jpg', 'Bella (Kristen Stewart) awakes -- as a vampire -- from her life-threatening labor, and her newborn daughter, Renesmee, proves to be very special indeed. While Bella adjusts to her new state of being, Renesmee experiences accelerated growth. When the Volturi learn of the baby\'s existence, they declare her to be an abomination and sentence the Cullens to death. Bella, Edward (Robert Pattinson) and the rest of the clan seek help from allies around the world to protect their family.', '2012-11-22', 1, 115, '1', 'https://www.youtube.com/embed/RV5nTZ8EdiE', 'videos/The Twilight Saga- Breaking Dawn â€“ Part 2 (2012).mp4', 0),
(49, 'Sing to the Dawn', 'images/movie/$value.jpg', 'Sing to the Dawn is the coming-of-age story of a girl fighting for her right to basic education and achieving her dreams. Dawan lives in a small village but dreams of getting an education rather than marrying the village head\'s son.', '2008-10-30', 1, 93, '1', 'https://www.youtube.com/embed/J_JySYGZ3OU', NULL, 0),
(52, 'Interstellar', 'images/movie/32299846_max.jpg', 'In Earth\'s future, a global crop blight and second Dust Bowl are slowly rendering the planet uninhabitable. Professor Brand (Michael Caine), a brilliant NASA physicist, is working on plans to save mankind by transporting Earth\'s population to a new home via a wormhole. But first, Brand must send former NASA pilot Cooper (Matthew McConaughey) and a team of researchers through the wormhole and across the galaxy to find out which of three planets could be mankind\'s new home.', '2014-11-06', 1, 169, '1', 'https://www.youtube.com/embed/2LqzF5WauAw', NULL, 0),
(54, 'Twilight', 'images/movie/41BIlCaeieL.jpg', 'High-school student Bella Swan (Kristen Stewart), always a bit of a misfit, doesn\'t expect life to change much when she moves from sunny Arizona to rainy Washington state. Then she meets Edward Cullen (Robert Pattinson), a handsome but mysterious teen whose eyes seem to peer directly into her soul. Edward is a vampire whose family does not drink blood, and Bella, far from being frightened, enters into a dangerous romance with her immortal soulmate.', '2008-12-18', 1, 121, '1', 'https://www.youtube.com/embed/a39k8FhGl8M', 'videos/Twilight (2008).mp4', 0),
(55, 'èŠ±åƒéª¨', 'images/movie/366822040_1018872502882989_8729598940151679842_n.jpg', 'TBA', '2024-10-29', 1, 121, '2', 'https://www.youtube.com/embed/zqbmZTaMbhc', NULL, 0),
(56, 'æ­¥æ­¥æƒŠå¿ƒ', 'images/movie/f3b5983055aafcaad29c530f1006f81b.jpg', 'A single modern-day city girl name Zhang Xiaowen accidentally travels back in time to the Qing Dynasty and becomes Ruoxi, but gets caught up in a web of love and politics in the royal court of Emperor Kangxi.', '2024-10-09', 1, 114, '2', 'https://www.youtube.com/embed/wbM1Ulw5bBw', NULL, 0),
(57, 'Mulan (2020)', 'images/movie/il_570xN.2233267728_b3f0.jpg', 'To keep her ailing father from serving in the Imperial Army, a fearless young woman disguises herself as a man and battles northern invaders in China.', '2020-07-24', 1, 115, '2', 'https://www.youtube.com/embed/KK8FHdFluOQ', NULL, 0),
(58, 'Be With You 2018', 'images/movie/Be_with_You1.jpg', 'A woman dies, leaving her husband and young son, but during the rain season, she comes back to life and has no memories. The three get together and are happy again, but she has to go back when the rain season ends.', '2018-04-19', 1, 131, '1', 'https://www.youtube.com/embed/_6tJmGxGaK4', 'videos/çŽ°åœ¨åŽ»è§ä½ .Be.with.You.2018.mp4', 0),
(59, 'The Incredibles', 'images/movie/mHP0104_1024x1024.jpg', 'In this lauded Pixar animated film, married superheroes Mr. Incredible (Craig T. Nelson) and Elastigirl (Holly Hunter) are forced to assume mundane lives as Bob and Helen Parr after all super-powered activities have been banned by the government. While Mr. Incredible loves his wife and kids, he longs to return to a life of adventure, and he gets a chance when summoned to an island to battle an out-of-control robot. Soon, Mr. Incredible is in trouble, and it\'s up to his family to save him.', '2004-11-05', 1, 115, '1', 'https://www.youtube.com/embed/ixKqYNMqcnE', 'videos/The Incredibles (2004).mp4', 0),
(60, 'The Incredibles 2', 'images/movie/7j1xlR9UCrggidzVoJrYgjgeWsk1.jpg', 'Telecommunications guru Winston Deavor enlists Elastigirl to fight crime and make the public fall in love with superheroes once again. That leaves Mr. Incredible with one of his greatest challenges ever -- staying home and taking care of three rambunctious children. As Violet, Dash and Jack-Jack offer him a new set of headaches, a cybercriminal named Screenslaver launches his dastardly plan -- hypnotizing the world through computer screens.', '2018-06-15', 1, 118, '1', 'https://www.youtube.com/embed/i5qOzqD9Rms', 'videos/Incredibles 2 (2018).mp4', 0),
(61, 'æ¶ˆå¤±çš„å¥¹', 'images/movie/img39641.jpg', 'LI MUZI, wife of HE FEI, mysteriously disappeared on their anniversary trip to Balandia, a Southeast Asian island. The clueless husband turned for help to CHEN MAI, a renowned international lawyer. As the investigation progressed, more hidden secrets were revealedâ€¦\r\n\r\nä½•éžçš„å¦»å­æŽæœ¨å­åœ¨å‰å¾€ä¸œå—äºšå°å²›åº†ç¥ç»“å©šå‘¨å¹´çºªå¿µæ—¥çš„æ—…è¡Œä¸­ç¦»å¥‡æ¶ˆå¤±ã€‚æ¯«æ— çº¿ç´¢çš„ä¸ˆå¤«è‹¦å¯»æ— æžœï¼Œåªå¥½å‘é‡‘ç‰Œå›½é™…å¾‹å¸ˆé™ˆéº¦æ±‚åŠ©ã€‚éšç€è°ƒæŸ¥çš„æ·±å…¥ï¼Œæ›´å¤šéšè—çš„ç§˜å¯†æµ®å‡ºæ°´é¢â€¦', '2023-07-27', 1, 122, '1', 'https://www.youtube.com/embed/3LX5wQ2YtUU', 'videos/æ¶ˆå¤±çš„å¥¹.mp4', 0);

-- --------------------------------------------------------

--
-- Table structure for table `movie_genre`
--

CREATE TABLE `movie_genre` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `genre_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `movie_genre`
--

INSERT INTO `movie_genre` (`id`, `movie_id`, `genre_id`) VALUES
(334, 56, 5),
(335, 56, 7),
(551, 45, 3),
(552, 45, 5),
(553, 45, 12),
(697, 54, 3),
(698, 54, 5),
(699, 54, 12),
(706, 57, 1),
(707, 57, 4),
(708, 57, 12),
(726, 61, 6),
(739, 59, 1),
(740, 59, 2),
(741, 59, 17),
(761, 52, 6),
(762, 52, 11),
(763, 52, 17),
(776, 49, 2),
(777, 49, 16),
(781, 43, 3),
(782, 43, 5),
(783, 43, 12),
(786, 46, 3),
(787, 46, 5),
(788, 46, 12),
(803, 58, 5),
(804, 58, 12),
(807, 55, 7),
(808, 55, 10),
(809, 44, 3),
(810, 44, 5),
(811, 44, 12),
(812, 60, 1),
(813, 60, 2),
(814, 60, 11),
(815, 60, 17);

-- --------------------------------------------------------

--
-- Table structure for table `movie_viewers`
--

CREATE TABLE `movie_viewers` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `movie_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `movie_viewers`
--

INSERT INTO `movie_viewers` (`id`, `username`, `movie_id`) VALUES
(1, 'Yukiko', 57),
(2, 'Yukiko', 54),
(3, 'Yukiko', 43),
(4, 'Yukiko', 45),
(5, 'Yukiko', 44),
(6, 'Yukiko', 46),
(7, 'Yukiko', 58),
(8, 'Yukiko', 59),
(9, 'Yukiko', 52),
(10, 'Yukiko', 60),
(11, 'Yukiko', 56),
(12, 'Yukiko', 55),
(13, 'Yukiko', 49),
(14, 'Yukiko', 61),
(15, 'Zen', 55),
(16, 'Zen', 56);

-- --------------------------------------------------------

--
-- Table structure for table `seat_detail`
--

CREATE TABLE `seat_detail` (
  `id` int(11) NOT NULL,
  `cust_id` int(11) DEFAULT NULL,
  `show_id` int(11) DEFAULT NULL,
  `seat_no` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `seat_detail`
--

INSERT INTO `seat_detail` (`id`, `cust_id`, `show_id`, `seat_no`) VALUES
(69, 22, 2, '3'),
(70, 22, 2, '3'),
(71, 22, 2, '3'),
(72, 22, 2, '3'),
(73, 22, 2, '3'),
(74, 22, 2, '3'),
(75, 22, 2, '3'),
(76, 22, 1, '3'),
(77, 22, 1, '3'),
(78, 22, 1, '3'),
(79, 22, 2, '2'),
(80, 22, 2, '3'),
(81, 22, 2, '3'),
(82, 22, 2, '3'),
(83, 22, 2, '3'),
(84, 22, 2, '3');

-- --------------------------------------------------------

--
-- Table structure for table `seat_reserved`
--

CREATE TABLE `seat_reserved` (
  `id` int(11) NOT NULL,
  `show_id` int(11) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `seat_number` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `reserved` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `seat_reserved`
--

INSERT INTO `seat_reserved` (`id`, `show_id`, `cust_id`, `seat_number`, `reserved`) VALUES
(1, 2, 22, 'R3S3', 0),
(2, 2, 22, 'R3S4', 0),
(3, 2, 22, 'R3S8', 0),
(4, 2, 22, 'R3S2', 0),
(5, 2, 22, 'R3S3', 0),
(6, 2, 22, 'R3S7', 0),
(7, 2, 22, 'R2S4', 0),
(8, 2, 22, 'R2S5', 0),
(9, 2, 22, 'R2S8', 0);

-- --------------------------------------------------------

--
-- Table structure for table `show`
--

CREATE TABLE `show` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `show_date` date DEFAULT NULL,
  `show_time_id` int(11) DEFAULT NULL,
  `no_seat` int(11) DEFAULT NULL,
  `cinema_id` int(11) DEFAULT NULL,
  `ticket_price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `show`
--

INSERT INTO `show` (`id`, `movie_id`, `show_date`, `show_time_id`, `no_seat`, `cinema_id`, `ticket_price`) VALUES
(1, 1, '2023-09-24', 1, 40, 1, 15),
(2, 2, '2023-08-24', 2, 40, 1, 15);

-- --------------------------------------------------------

--
-- Table structure for table `show_time`
--

CREATE TABLE `show_time` (
  `id` int(11) NOT NULL,
  `time` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `show_time`
--

INSERT INTO `show_time` (`id`, `time`) VALUES
(1, '1:00PM - 3:00PM'),
(2, '3:30PM -5:30PM'),
(3, '6:00PM -8:00PM'),
(4, '8:30PM-10:30PM'),
(5, '11:00PM -1:00AM');

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE `slider` (
  `id` int(11) NOT NULL,
  `imgpath` varchar(500) DEFAULT NULL,
  `alt` varchar(50) DEFAULT NULL,
  `movie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `slider`
--

INSERT INTO `slider` (`id`, `imgpath`, `alt`, `movie_id`) VALUES
(1, 'images/HD-wallpaper-mulan-2020-liu-yifei-afis-sword-poster-red-movie-mulan-warrior-actress-girl-asian-princess.jpg', 'Mulan (2020)', 0),
(2, 'images/interstellar-movie-movies-wallpaper-preview.jpg', 'Interstellar', 0),
(6, 'images/t013f2fcc9c9f74eb22.jpg', 'èŠ±åƒéª¨', 0),
(7, 'images/maxresdefault.jpg', 'ä¸€å»å®šæƒ…', 0),
(8, 'images/AAAABZIKB0OBTYbSeCrLMhqheK5NvT-fPaUXMem_pF9Cy7dcZ80FJ6PgcmQ6Bio8ph7htXDhqS_YkO41xCLG2gkV6yB6CF8kWTCj46xf.jpg', 'Be With You', 0),
(9, 'images/AAAABSlVRq_2vf0E5QrAxKWid-60J7vKLjTMZW6kZlPbWhFspSIa2Cf-16gWP4r1ozwIiU5VQHahpm9kyGs8_TUNOjb0lCoID07fyn5e.jpg', 'ä¸‰ç”Ÿä¸‰ä¸–åé‡Œæ¡ƒèŠ±', 0),
(11, 'images/xRAdBbmBNM.png', 'æ¶ˆå¤±çš„å¥¹ Lost in Stars', 0),
(13, 'images/twilight_banner_or_desktop_by_leia_loves_twilight_d1jinzm-fullview.jpg', 'Twilight', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `show_id` (`show_id`),
  ADD KEY `cust_id` (`cust_id`),
  ADD KEY `seat_dt_id` (`seat_dt_id`);

--
-- Indexes for table `cinema`
--
ALTER TABLE `cinema`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`,`hpnum`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cust_id` (`cust_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indexes for table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `industry`
--
ALTER TABLE `industry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movie`
--
ALTER TABLE `movie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang_id` (`lang_id`);

--
-- Indexes for table `movie_genre`
--
ALTER TABLE `movie_genre`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movie_id` (`movie_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indexes for table `movie_viewers`
--
ALTER TABLE `movie_viewers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indexes for table `seat_detail`
--
ALTER TABLE `seat_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cust_id` (`cust_id`),
  ADD KEY `show_id` (`show_id`);

--
-- Indexes for table `seat_reserved`
--
ALTER TABLE `seat_reserved`
  ADD PRIMARY KEY (`id`),
  ADD KEY `show_id` (`show_id`),
  ADD KEY `cust_id` (`cust_id`);

--
-- Indexes for table `show`
--
ALTER TABLE `show`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movie_id` (`movie_id`),
  ADD KEY `show_ibfk_3` (`cinema_id`),
  ADD KEY `show_ibfk_4` (`show_time_id`);

--
-- Indexes for table `show_time`
--
ALTER TABLE `show_time`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `cinema`
--
ALTER TABLE `cinema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `genre`
--
ALTER TABLE `genre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `industry`
--
ALTER TABLE `industry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `movie`
--
ALTER TABLE `movie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `movie_genre`
--
ALTER TABLE `movie_genre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=816;

--
-- AUTO_INCREMENT for table `movie_viewers`
--
ALTER TABLE `movie_viewers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `seat_detail`
--
ALTER TABLE `seat_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `seat_reserved`
--
ALTER TABLE `seat_reserved`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `show`
--
ALTER TABLE `show`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `show_time`
--
ALTER TABLE `show_time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`show_id`) REFERENCES `show` (`id`),
  ADD CONSTRAINT `booking_ibfk_4` FOREIGN KEY (`cust_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `booking_ibfk_5` FOREIGN KEY (`seat_dt_id`) REFERENCES `seat_detail` (`id`);

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`cust_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`);

--
-- Constraints for table `movie_genre`
--
ALTER TABLE `movie_genre`
  ADD CONSTRAINT `movie_genre_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`),
  ADD CONSTRAINT `movie_genre_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`);

--
-- Constraints for table `movie_viewers`
--
ALTER TABLE `movie_viewers`
  ADD CONSTRAINT `movie_viewers_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`);

--
-- Constraints for table `seat_detail`
--
ALTER TABLE `seat_detail`
  ADD CONSTRAINT `seat_detail_ibfk_1` FOREIGN KEY (`cust_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `seat_detail_ibfk_2` FOREIGN KEY (`show_id`) REFERENCES `show` (`id`);

--
-- Constraints for table `seat_reserved`
--
ALTER TABLE `seat_reserved`
  ADD CONSTRAINT `seat_reserved_ibfk_1` FOREIGN KEY (`show_id`) REFERENCES `show` (`id`),
  ADD CONSTRAINT `seat_reserved_ibfk_2` FOREIGN KEY (`cust_id`) REFERENCES `customer` (`id`);

--
-- Constraints for table `show`
--
ALTER TABLE `show`
  ADD CONSTRAINT `show_ibfk_3` FOREIGN KEY (`cinema_id`) REFERENCES `cinema` (`id`),
  ADD CONSTRAINT `show_ibfk_4` FOREIGN KEY (`show_time_id`) REFERENCES `show_time` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
