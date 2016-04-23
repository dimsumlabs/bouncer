--CREATE DATABASE `members`;
--USE `members`;

CREATE TABLE `Payments` (
  `id` integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  `email` char(64) NOT NULL COLLATE NOCASE,
  `submitted` datetime NOT NULL,
  `amount` int(11) NOT NULL,
  `verified` tinyint(1) DEFAULT NULL
);
DROP INDEX IF EXISTS `IDX_Payments_EMAIL`;
CREATE INDEX `IDX_Payments_EMAIL` ON `Payments`(`email`);

--ALTER TABLE `Users` RENAME TO `Users_old`;
CREATE TABLE `Users` (
  `id` integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  `email` char(64) NOT NULL COLLATE NOCASE,
  `password` char(32) DEFAULT NULL,
  `salt` char(32) DEFAULT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_seen` datetime DEFAULT NULL,
  `paid_verified` date DEFAULT NULL,
  `paid` date DEFAULT NULL,
  `since` date NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `count` int(11) NOT NULL DEFAULT 0,
  `mac` char(40) DEFAULT NULL COLLATE NOCASE,
  `rfid` char(64) DEFAULT NULL COLLATE NOCASE,
  `name` char(64) DEFAULT NULL COLLATE NOCASE
);
--INSERT INTO `Users` SELECT * FROM `Users_old`;
--DROP TABLE `Users_old`;
DROP INDEX IF EXISTS `IDX_Users_EMAIL`;
CREATE UNIQUE INDEX `IDX_Users_EMAIL` ON `Users`(`email`);
DROP INDEX IF EXISTS `IDX_Users_PASSWORD`;
CREATE UNIQUE INDEX `IDX_Users_PASSWORD` ON `Users`(`password`);
DROP INDEX IF EXISTS `IDX_Users_MAC`;
CREATE UNIQUE INDEX `IDX_Users_MAC` ON `Users`(`mac`);
DROP INDEX IF EXISTS `IDX_Users_RFID`;
CREATE UNIQUE INDEX `IDX_Users_RFID` ON `Users`(`rfid`);

CREATE TRIGGER IF EXISTS [UpdateLastTime];
CREATE TRIGGER [UpdateLastTime]
  AFTER UPDATE
  ON Users
  FOR EACH ROW
  BEGIN
    UPDATE Users SET last_update = CURRENT_TIMESTAMP WHERE id = old.id;
  END;
