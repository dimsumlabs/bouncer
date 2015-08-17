CREATE DATABASE `members`;
USE `members`;

CREATE TABLE `Payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` char(64) NOT NULL,
  `submitted` datetime NOT NULL,
  `amount` int(11) NOT NULL,
  `verified` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_Payments_EMAIL` (`email`)
);

CREATE TABLE `Users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` char(64) NOT NULL,
  `password` char(32) NOT NULL,
  `salt` char(32) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `paid_verified` date DEFAULT NULL,
  `paid` date DEFAULT NULL,
  `since` date NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  `mac` char(40) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDX_Users_EMAIL` (`email`),
  UNIQUE KEY `IDX_Users_PASSWORD` (`password`),
  UNIQUE KEY `IDX_Users_MAC` (`mac`)
);

