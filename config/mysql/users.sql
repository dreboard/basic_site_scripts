
/**
* Table Creation: users
* Description:    Create users table.
* Created:        2-1-2018
* Modified:       2-2-2018
* Author:         dre board
*/
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
`id` INT(128) AUTO_INCREMENT,
`name` VARCHAR(30) NOT NULL COMMENT 'users name',
`password` VARCHAR(255) NOT NULL,
`level` CHAR(1) NOT NULL,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT 'users table';

/**
* View Creation: users
* Description:    Select all from users table.
* Created:        2-1-2018
* Modified:       2-2-2018
* Author:         dre board
*/
CREATE OR REPLACE VIEW getAllUsers AS SELECT * FROM `users`;


/**
* Procedure Name: SessionReadData
* Description:    Get session data by ID.
* Created:        2-1-2018
* Modified:       2-2-2018
* Author:         dre board
*/
DELIMITER \\
DROP PROCEDURE IF EXISTS SaveUserToDB;
CREATE DEFINER=CURRENT_USER PROCEDURE SaveUserToDB(
  IN p_name VARCHAR(30),
  IN p_pass VARCHAR(255),
  IN p_data TEXT
)
  COMMENT 'Write session data into database'
  MODIFIES SQL DATA
  BEGIN
    INSERT INTO users (name, password, level) VALUES (p_name, p_pass, p_level);
  END \\

/**
* Procedure Name: FindUserByName
* Description:    Get session data by ID.
* Created:        2-1-2018
* Modified:       2-2-2018
* Author:         dre board
*/
DELIMITER \\
DROP PROCEDURE IF EXISTS FindUserByName;
CREATE DEFINER=CURRENT_USER PROCEDURE FindUserByName(
  IN p_name VARCHAR(30)
)
  COMMENT 'Prevent duplicate usernames'
  READS SQL DATA
  BEGIN
    SELECT * FROM `users` WHERE users.name = p_name;
  END \\

/**
* Procedure Name: FindUser
* Description:    Get user data.
* Created:        2-1-2018
* Modified:       2-2-2018
* Author:         dre board
*/
DELIMITER \\
DROP PROCEDURE IF EXISTS FindUser;
CREATE DEFINER=CURRENT_USER PROCEDURE FindUser(
  IN p_name VARCHAR(30)
)
COMMENT 'Prevent duplicate usernames'
READS SQL DATA
BEGIN
  SELECT users.id, users.name, users.password, users.level FROM `users` WHERE users.name = p_name;
END \\

/**
* Procedure Name: FindUserByID
* Description:    Get User By ID.
* Created:        2-1-2018
* Modified:       2-2-2018
* Author:         dre board
*/
DELIMITER \\
DROP PROCEDURE IF EXISTS FindUserByID;
CREATE DEFINER=CURRENT_USER PROCEDURE FindUserByID(
  IN p_id INT(128)
)
COMMENT 'Get User By ID'
READS SQL DATA
BEGIN
  SELECT * FROM `users` WHERE users.id = p_id;
END \\