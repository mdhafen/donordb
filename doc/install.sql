CREATE TABLE `user` (
	`userid` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(16) NOT NULL DEFAULT '',
	`fullname` VARCHAR(128) NOT NULL DEFAULT '',
	`email` VARCHAR(128) NOT NULL DEFAULT '',
	`password` BLOB NOT NULL,
	`salt` BLOB NOT NULL,
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
	`amount` FLOAT(10,2) NOT NULL DEFAULT '0.00',
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
