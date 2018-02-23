DELIMITER $$
DROP PROCEDURE IF EXISTS `save_user`$$
CREATE PROCEDURE `save_user`(
  IN p_name  VARCHAR(20),
  IN p_pass  VARCHAR(255),
  IN p_level VARCHAR(100)
)
  MODIFIES SQL DATA
  BEGIN
    DECLARE user_id INT;
    INSERT INTO users (name, password, level) VALUES (p_name, p_pass, p_level);
  END $$
DELIMITER ;
 CALL save_user('Dre','$2y$10$vZNOsJ44R9OJ7bcVzy9o/e/q3U07pKTy9LJZaF7twjvWrBvEK9MXK', 'basic');
#ALTER TABLE users MODIFY id INT(11) NOT NULL AUTO_INCREMENT;