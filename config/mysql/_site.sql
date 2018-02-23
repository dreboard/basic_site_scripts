/*



*/

DROP TABLE IF EXISTS site_log;
CREATE TABLE IF NOT EXISTS site_log (
  `id` int(10) unsigned NOT NULL auto_increment,
  `msg` TEXT DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) COMMENT 'Site log'
);
INSERT INTO site_log(`msg`) VALUES('Test app');



DELIMITER //
DROP PROCEDURE IF EXISTS find_user; //
CREATE PROCEDURE find_user(IN user VARCHAR(100))
  BEGIN
    SELECT id, name, password FROM users WHERE name = user;
  END; //



DELIMITER //
DROP PROCEDURE IF EXISTS write_session_data; //
CREATE PROCEDURE write_session_data(
  IN p_id CHAR(128),
  IN p_time CHAR(10),
  IN p_data TEXT,
  IN p_key CHAR(128)
)
MODIFIES SQL DATA
  COMMENT 'when we assign a value to a session'
  BEGIN
    REPLACE INTO sessions (id, set_time, data, session_key) VALUES (p_id, p_time, p_data, p_key);
  END; //
DELIMITER ;


DELIMITER //
DROP PROCEDURE IF EXISTS read_session_data; //
CREATE PROCEDURE read_session_data(IN p_id VARCHAR(128))
READS SQL DATA
  BEGIN
    SELECT `data` FROM sessions WHERE `id` = p_id LIMIT 1;
  END; //
DELIMITER ;

CALL find_user('Marc');