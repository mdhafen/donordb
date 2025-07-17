--
-- Table structure for table `message_templates`
--

DROP TABLE IF EXISTS `message_templates`;
CREATE TABLE `message_templates` (
  `template_id` INT NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `transport` ENUM('EMail','SMS','Print') NOT NULL DEFAULT 'EMail',
  `lang` varchar(5) NOT NULL,
  `subject` varchar(200),
  `body` mediumtext,
  PRIMARY KEY (`template_id`),
  UNIQUE KEY (`code`,`transport`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `message_queue`
--

DROP TABLE IF EXISTS `message_queue`;
CREATE TABLE `message_queue` (
  `queue_id` INT NOT NULL AUTO_INCREMENT,
  `to_uid` varchar(40) NOT NULL,
  `from_uid` varchar(40) NOT NULL,
  `template_id` INT,
  `status` ENUM('pending','failed','sent','cancelled') NOT NULL DEFAULT 'pending',
  `status_metadata` mediumtext,
  `subject` varchar(200),
  `body` mediumtext,
  PRIMARY KEY (`queue_id`),
  CONSTRAINT `template_id_fk` FOREIGN KEY (`template_id`) REFERENCES `message_templates` (`template_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `user_message_settings`
--

DROP TABLE IF EXISTS `user_message_settings`;
CREATE TABLE `user_message_settings` (
  `userid` varchar(40),
  `template_id` INT NOT NULL,
  UNIQUE KEY (`userid`,`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user` (
	`userid` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(16) NOT NULL DEFAULT '',
	`fullname` VARCHAR(128) NOT NULL DEFAULT '',
	`email` VARCHAR(128) NOT NULL DEFAULT '',
	`password` BLOB NOT NULL,
	`salt` BLOB NOT NULL,
	`password_mode` VARCHAR(32) NOT NULL DEFAULT '',
	`role` INT(4) NOT NULL DEFAULT 0,
	PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `location` (
	`locationid` INT(10) UNSIGNED NOT NULL,
	`name` VARCHAR(128) NOT NULL DEFAULT '',
	PRIMARY KEY (`locationid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user_location_links` (
	`userid` INT(10) UNSIGNED NOT NULL DEFAULT 0,
	`locationid` INT(10) UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY (`userid`,`locationid`),
	KEY `ull_k_userid` (`userid`),
	CONSTRAINT `ull_fk_locationid` FOREIGN KEY (`locationid`)
		REFERENCES `location` (`locationid`)
		ON DELETE RESTRICT ON UPDATE CASCADE,
	CONSTRAINT `ull_fk_userid` FOREIGN KEY (`userid`)
		REFERENCES `user` (`userid`)
		ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sessions` (
       `sessionid` VARCHAR(16) NOT NULL DEFAULT '',
       `sessiondata` TEXT,
       `sessionstamp` INT UNSIGNED NOT NULL,
       PRIMARY KEY (`sessionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `message_templates` (
       `message_type` CHAR(4) NOT NULL DEFAULT 'EKTB',
       `subject` TEXT,
       `body` TEXT,
       PRIMARY KEY ( `message_type` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `message_templates` (`message_type`,`subject`,`body`) VALUES
 ('EDR',
  'WCSD Foundation: Donation received.  Thank you!',
  'Dear [[to.fullname]],\r\n  Thank you for your donation to the Washington County School District Foundation.');

CREATE TABLE `message_queue` (
       `messageid` INT UNSIGNED NOT NULL AUTO_INCREMENT,
       `from` INT UNSIGNED NOT NULL,
       `to` INT UNSIGNED NOT NULL,
       `transport` ENUM ( 'email' ) DEFAULT 'email',
       `status` ENUM ( 'pending', 'delayed', 'sent', 'failed' ) DEFAULT 'delayed',
       `date_queued` INT UNSIGNED,
       `subject` TEXT,
       `body`	 TEXT,
       PRIMARY KEY ( `messageid` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `accounts` (
	`accountid` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(128) NOT NULL DEFAULT 'Unnamed',
	`locationid` INT(10) UNSIGNED DEFAULT NULL ,
	`note` TEXT NOT NULL,
	`retired` TINYINT UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY (`accountId`),
	KEY `schoolId` (`locationid`),
	CONSTRAINT `acc_fk_locationid` FOREIGN KEY (`locationid`)
		REFERENCES `location` (`locationid`)
		ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `contacts` (
	`contactid` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(128) DEFAULT NULL,
	`company` VARCHAR(128) DEFAULT NULL,
	`street` VARCHAR(255) DEFAULT NULL,
	`city` VARCHAR(128) DEFAULT NULL,
	`state` VARCHAR(128) DEFAULT NULL,
	`zip` VARCHAR(56) DEFAULT NULL,
	`phone` VARCHAR(56) DEFAULT NULL,
	PRIMARY KEY (`contactId`),
	KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `actions` (
	`actionid` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`date` DATE DEFAULT NULL,
	`amount` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
	`contactid` BIGINT(20) UNSIGNED DEFAULT NULL,
	`accountid` BIGINT(20) UNSIGNED DEFAULT NULL,
	`locationid` INT(10) UNSIGNED DEFAULT NULL,
	`receipt` VARCHAR(8) DEFAULT NULL,
	`po` VARCHAR(25) DEFAULT NULL,
	`note` TEXT,
	`timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`udate` DATETIME DEFAULT NULL,
	`is_transfer` TINYINT(1) NOT NULL DEFAULT 0,
	`in_kind` TINYINT(1) NOT NULL DEFAULT 0,
	PRIMARY KEY (`actionid`),
	KEY `contactid` (`contactid`),
	KEY `accountid` (`accountid`),
	KEY `locationid` (`locationid`),
	CONSTRAINT `act_fk_contactid` FOREIGN KEY (`contactid`)
		REFERENCES `contacts` (`contactid`)
		ON DELETE RESTRICT ON UPDATE CASCADE,
	CONSTRAINT `act_fk_accountid` FOREIGN KEY (`accountid`)
		REFERENCES `accounts` (`accountid`)
		ON DELETE RESTRICT ON UPDATE CASCADE,
	CONSTRAINT `act_fk_locationid` FOREIGN KEY (`locationid`)
		REFERENCES `location` (`locationid`)
		ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `modlog_actions` (
	`actionid` BIGINT(20) UNSIGNED NOT NULL,
	`field` VARCHAR(16) NOT NULL,
	`old_value` TEXT NULL DEFAULT NULL,
	`new_value` TEXT NULL DEFAULT NULL,
	`userid` INT(10) UNSIGNED NULL DEFAULT NULL,
	ipAddress VARBINARY(16) NOT NULL DEFAULT 2130706433,
	timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	KEY (actionid),
	KEY (field)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `modlog_accounts` (
	`accountid` BIGINT(20) UNSIGNED NOT NULL,
	`field` VARCHAR(16) NOT NULL,
	`old_value` TEXT NULL DEFAULT NULL,
	`new_value` TEXT NULL DEFAULT NULL,
	`userid` INT(10) UNSIGNED NULL DEFAULT NULL,
	ipAddress VARBINARY(16) NOT NULL DEFAULT 2130706433,
	timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	KEY (accountid),
	KEY (field)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
