CREATE TABLE `user` (
	`userid` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(16) NOT NULL DEFAULT '',
	`fullname` VARCHAR(128) NOT NULL DEFAULT '',
	`email` VARCHAR(128) NOT NULL DEFAULT '',
	`password` BLOB NOT NULL,
	`salt` BLOB NOT NULL,
	`role` INT(4) NOT NULL DEFAULT 0,
	PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `location` (
	`locationid` INT(10) UNSIGNED NOT NULL DEFAULT 0,
	`name` VARCHAR(128) NOT NULL DEFAULT '',
	PRIMARY KEY (`locationid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user_location_links` (
	`userid` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`locationid` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`userid`,`locationid`),
	KEY `ull_userid` (`userid`),
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
