-- Adminer 4.6.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `lxl_keyword`;
CREATE TABLE `lxl_keyword` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(50) NOT NULL,
  `jumlah` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `lxl_keyword` (`id`, `keyword`, `jumlah`) VALUES
(1,	'Lutfi Anam',	'0'),
(2,	'Linailil Muna',	'0');

DROP TABLE IF EXISTS `lxl_link_download`;
CREATE TABLE `lxl_link_download` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_url` varchar(6) NOT NULL,
  `ip_user` varchar(15) NOT NULL,
  `url_download` varchar(150) NOT NULL,
  `url_demo` varchar(150) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(1) DEFAULT NULL,
  `deskripsi` longtext NOT NULL,
  `tag` varchar(300) NOT NULL,
  `kategori` enum('0','1','2') NOT NULL,
  `download` varchar(20) NOT NULL DEFAULT '0',
  `pengunjung` varchar(20) NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;


-- 2020-09-05 04:58:55
