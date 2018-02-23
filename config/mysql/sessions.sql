-- sessions.sql
-- SERIAL is an alias for BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE
DROP TABLE IF EXISTS `INSPISESSION`;
CREATE TABLE `INSPISESSION` (
  `SESS_ID` VARCHAR(32) NOT NULL DEFAULT '',
  `SESS_SEC_LEVEL` INT(3) unsigned NOT NULL DEFAULT 255,
  `SESS_CREATED` INT(11) NOT NULL DEFAULT 0,
  `SESS_EXPIRY` INT(11) NOT NULL DEFAULT 0,
  `SESS_TIMEOUT` INT(11) NOT NULL DEFAULT 0,
  `SESS_LOCKED` INT(1) NOT NULL DEFAULT 1,
  `SESS_VALUE` BLOB,
  `SESS_ENC_IV` VARCHAR(32) NOT NULL DEFAULT '',
  `SESS_SEC_ID` VARCHAR(32) NOT NULL DEFAULT '',
  `SESS_TRACE` VARCHAR(4000) NOT NULL DEFAULT '',
  PRIMARY KEY  (`SESS_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
  COMMENT 'NDB_TABLE=READ_BACKUP=0,PARTITION_BALANCE=FOR_RP_BY_NODE';


DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` CHAR(128) NOT NULL,
  `set_time` CHAR(10) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT 'PHP Sessions table';


/**
* Procedure Name: SessionReadData
* Description:    Get session data by ID.
* Created:        2-1-2018
* Modified:       2-2-2018
* Author:         dre board
*/
DELIMITER \\
DROP PROCEDURE IF EXISTS SessionReadData;
CREATE PROCEDURE SessionReadData(
  IN p_id CHAR(128)
)
  READS SQL DATA
  COMMENT 'Get session data by ID'
  BEGIN
    SELECT * FROM `sessions` WHERE sessions.id = p_id LIMIT 1;
  END \\

/**
* Procedure Name: SessionWriteData
* Description:    Write session data into database.
* Created:        2-1-2018
* Modified:       2-2-2018
* Author:         dre board
*/
DELIMITER \\
DROP PROCEDURE IF EXISTS SessionWriteData;
CREATE DEFINER=CURRENT_USER PROCEDURE SessionWriteData(
  IN p_id CHAR(32),
  IN p_time CHAR(10),
  IN p_data TEXT
)
  COMMENT 'Write session data into database'
  MODIFIES SQL DATA
  BEGIN
    REPLACE INTO sessions (id, set_time, data) VALUES (p_id, p_time, p_data);
  END \\

/**
* Procedure Name: SessionDeleteExpired
* Description:    Expired session IDs being destroyed.
* Created:        2-1-2018
* Modified:       2-2-2018
* Author:         dre board
*/
DELIMITER \\
DROP PROCEDURE IF EXISTS SessionDeleteExpired;
CREATE DEFINER=CURRENT_USER PROCEDURE SessionDeleteExpired(
  IN p_limit CHAR(10)
)
  MODIFIES SQL DATA
  COMMENT 'Expired session IDs being destroyed'
  BEGIN
    DELETE FROM `sessions` WHERE sessions.set_time < p_limit;
  END \\

/**
* Procedure Name: SessionDestroy
* Description:    The session ID being destroyed.
* Created:        2-1-2018
* Modified:       2-2-2018
* Author:         dre board
*/
DELIMITER \\
DROP PROCEDURE IF EXISTS SessionDestroy;
CREATE DEFINER=CURRENT_USER PROCEDURE SessionDestroy(
  IN p_id CHAR(32)
)
  MODIFIES SQL DATA
  COMMENT 'The session ID being destroyed'
  BEGIN
    DELETE FROM `sessions` WHERE sessions.id = p_id;
  END \\

/**
* Procedure Name: SessionGarbageCollect
* Description:    Session garbage collection.
* Reference:      SaveSession::gc()
* Created:        2-1-2018
* Modified:       2-2-2018
* Author:         dre board
*/
DELIMITER \\
DROP PROCEDURE IF EXISTS SessionGarbageCollect;
CREATE DEFINER=CURRENT_USER PROCEDURE SessionGarbageCollect(
  IN p_old CHAR(10)
)
  COMMENT 'Session garbage collection'
  MODIFIES SQL DATA
  BEGIN
    DELETE FROM sessions WHERE set_time < P_old;
  END \\


















-- end