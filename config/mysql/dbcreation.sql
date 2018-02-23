

# mysqldump -h 127.0.0.1 -u root -p server1 > server1_backup.sql;

/***************************************************************************************
                                       views
**************************************************************************************/

DROP VIEW IF EXISTS organization;
CREATE VIEW organization AS
SELECT
  CONCAT(e.lastName, e.firstName) AS employee,
  e.email AS emplyeeEmail,
  CONCAT(m.lastName, m.firstName) AS manager
FROM
  employees as e
    INNER JOIN
  employees AS m ON m.employeeNumber = e.reportsTo
ORDER BY manager;


SHOW CREATE VIEW organization;
SELECT * FROM organization;

CREATE OR REPLACE VIEW contacts AS
  SELECT
    firstName, lastName, extension, email, jobTitle
  FROM
    employees;

CREATE OR REPLACE VIEW nineteeth AS
SELECT * FROM employees WHERE  LEFT(employeeNumber, 2) = 16;


CREATE OR REPLACE VIEW GetSubCatFromType AS SELECT * FROM products;

DELIMITER //
DROP PROCEDURE IF EXISTS CountCustomers;
CREATE PROCEDURE CountCustomers(OUT cust_count INT)

  BEGIN
    DECLARE cust_count INT;
    SELECT COUNT(*) INTO cust_count FROM customers;
  END //
DELIMITER ;

CALL CountCustomers(@cust_count);

SELECT @cust_count;

/***************************************************************************************
                                       procedures
**************************************************************************************/
SET @customerNo = 112;

DELIMITER //
DROP PROCEDURE IF EXISTS GetCustomerShipping;
CREATE PROCEDURE GetCustomerShipping(
  in  p_customerNumber int(11),
  out p_shiping        varchar(50)
)
  BEGIN
    DECLARE customerCountry varchar(50);

    SELECT country INTO customerCountry
    FROM customers
    WHERE customerNumber = p_customerNumber;

    CASE customerCountry
      WHEN  'USA' THEN
        SET p_shiping = '2-day Shipping';
      WHEN 'Canada' THEN
        SET p_shiping = '3-day Shipping';
    ELSE
        SET p_shiping = '5-day Shipping';
    END CASE;
END //

DELIMITER ;

SELECT country into @country
FROM customers
WHERE customernumber = @customerNo;

CALL GetCustomerShipping(@customerNo,@shipping);
SELECT @customerNo AS Customer,
       @country    AS Country,
       @shipping   AS Shipping;
/***************************************************************************************
                                       functions
**************************************************************************************/




/***************************************************************************************
                                       triggers
**************************************************************************************/




/***************************************************************************************
                                       inline
**************************************************************************************/

/**
SHOW STATUS LIKE 'Last_Query_Cost';
 */

/**

Proposed structure
- unit
- types
- categories

*/
SET foreign_key_checks=0;
DROP TABLE IF EXISTS `saveUnit`;
CREATE TABLE `saveUnit` (
  `id` int(10) NOT NULL,
  `unitID` int(15) NOT NULL COMMENT 'Units ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT 'getAllUnits view connects all keys';


DROP TABLE IF EXISTS `unit`;
CREATE TABLE `unit` (
  `id` int(10) NOT NULL,
  `label` varchar(50) NOT NULL,
  `typeID` int(15) NOT NULL COMMENT 'Units type',
  `categoryID` int(15) NOT NULL COMMENT 'Units category',
  `version` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT 'getAllUnits view connects all keys';

DROP TABLE IF EXISTS `unitTypes`;
CREATE TABLE `unitTypes` (
  `id` int(11) NOT NULL,
  `categoryID` int(15) NOT NULL COMMENT 'label of category.',
  `label` VARCHAR(20) NOT NULL COMMENT 'label of this type.',
  `fill` int(10) NOT NULL COMMENT 'Amount to make full container.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT 'How to show comments';

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(15) NOT NULL,
  `label` varchar(20) NOT NULL,
  `value` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT 'How to show comments';

#---------------------------------------------
ALTER TABLE saveUnit ADD KEY IX_unitID(unitID);
ALTER TABLE unit ADD FOREIGN KEY (typeID) REFERENCES unitTypes(id);
ALTER TABLE saveUnit ADD FOREIGN KEY (unitID) REFERENCES unit(id);
ALTER TABLE unit ADD FOREIGN KEY (categoryID) REFERENCES categories(id);
ALTER TABLE unitTypes ADD FOREIGN KEY (categoryID) REFERENCES categories(id);

EXPLAIN SELECT unitID FROM saveUnit;

#---------------------------------------------
INSERT INTO server1.saveUnit
(id, unitID)
VALUES
  (1, '1'),
  (2, 2),
  (3, 1)
;
INSERT INTO server1.unit
(id, label, typeID, categoryID, version)
VALUES
  (1, 'Product', '1', '1', 'Circulated');

INSERT INTO server1.unitTypes (id, categoryID, label, fill) VALUES (1, '1', 'Block', 10);
INSERT INTO server1.categories (id, label, value) VALUES (1, 'Structure', 1.00);

SELECT getUnitLabel('Supply');


#---------------------------------------------

DELIMITER \\
DROP PROCEDURE IF EXISTS GetSavedUnit;
CREATE PROCEDURE GetSavedUnit(
  IN unitNum INT
)

  BEGIN
    DECLARE CONTINUE HANDLER FOR SQLWARNING
    SELECT
      saveUnit.id,
      unit.label,
      unit.version,
      unitTypes.fill,
      categories.label              AS category,
      categories.value,
      unitTypes.label               AS typeName,
      getSavedType(saveUnit.unitID) AS saved
    FROM saveUnit

      INNER JOIN unit ON saveUnit.id = unit.id
      INNER JOIN unitTypes ON unit.typeID = unitTypes.id
      INNER JOIN categories ON unit.categoryID = categories.id
    WHERE saveUnit.id = unitNum;
  END \\
#---------------------------------------------

CREATE OR REPLACE VIEW getAllUnits AS
  SELECT unit.label, unitTypes.fill, categories.label as category, categories.value,
    #getUnitCategory(unit.categoryID) AS category,
    unitTypes.label AS typeName
    #getUnitType(unit.typeID) AS type
  FROM unit
INNER JOIN unitTypes ON unit.typeID = unitTypes.id
INNER JOIN categories ON unit.categoryID = categories.id;


#---------------------------------------------

DELIMITER \\
DROP FUNCTION IF EXISTS getUnitCategory;
CREATE FUNCTION getUnitCategory(catID INT(15))
  RETURNS VARCHAR(100)
  BEGIN
    DECLARE outUnit VARCHAR(100);
    SELECT label INTO outUnit
    FROM categories WHERE categories.id = catID;
    RETURN(outUnit);
  END \\
DELIMITER ;

DELIMITER \\
DROP FUNCTION IF EXISTS getUnitType;
CREATE FUNCTION getUnitType(catID INT(15))
  RETURNS VARCHAR(100)
  BEGIN
    DECLARE outUnit VARCHAR(100);
    SELECT label INTO outUnit
    FROM unitTypes WHERE unitTypes.id = catID;
    RETURN(outUnit);
  END \\
DELIMITER ;

DELIMITER \\
DROP FUNCTION IF EXISTS getSavedType;
CREATE FUNCTION getSavedType(unitID INT(15))
  RETURNS VARCHAR(100)
  BEGIN
    DECLARE outUnit VARCHAR(100);
    SELECT count(saveUnit.unitID) INTO outUnit

    FROM saveUnit WHERE saveUnit.unitID = unitID;
    RETURN(outUnit);
  END \\
DELIMITER ;


DELIMITER \\
DROP FUNCTION IF EXISTS getSavedType;
CREATE FUNCTION getSavedType(unitID INT(15))
  RETURNS VARCHAR(100)
  BEGIN
    DECLARE outUnit VARCHAR(100);
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET outUnit = 0;
    SELECT count(saveUnit.unitID) INTO outUnit
    FROM saveUnit WHERE saveUnit.unitID = unitID;

    IF outUnit = 0 THEN
      SET outUnit = 22;
    END IF;

    CASE unitID
      WHEN  0 THEN
      SET outUnit = 'None Saved';
      WHEN 1 THEN
      SET outUnit = 'Saved 1';
    ELSE
      SET outUnit = 'Need to save';
    END CASE;


    RETURN(outUnit);
  END \\
DELIMITER ;
#---------------------------------------------


CALL getAllSavedUnits(:num);
CALL GetSavedUnit(:num);

SELECT getSavedType(:num) AS unitsSaved;
SHOW STATUS LIKE 'Last_Query_Cost';

SET @x = getSavedType(1);
SELECT @x;

SELECT * FROM getAllUnits;
SHOW STATUS LIKE 'Last_Query_Cost';


#----------------------------------------------------------------------------------
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL COMMENT 'users name',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT 'users table';

DROP TABLE IF EXISTS `sections`;
CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL COMMENT 'Section name',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT 'Section table';

DROP TABLE IF EXISTS `assignments`;
CREATE TABLE `assignments` (
  `userid` int(11) NOT NULL,
  `sectionid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT 'assign users table';

ALTER TABLE assignments ADD FOREIGN KEY (userid) REFERENCES users(id);
ALTER TABLE assignments ADD FOREIGN KEY (sectionid) REFERENCES sections(id);

INSERT INTO `users` (`id`, `name`) VALUES
  (1, 'William'),
  (2, 'Marc'),
  (3, 'John');
INSERT INTO `sections` (`id`, `name`) VALUES
  (1, 'IT'),
  (2, 'Security'),
  (3, 'HR');
INSERT INTO `assignments` (`userid`, `sectionid`) VALUES
  (1, 1),
  (2, 1),
  (3, 3);

DELIMITER \\
DROP FUNCTION IF EXISTS getUserSection;
CREATE FUNCTION getUserSection(id INT(11))
  RETURNS VARCHAR(100)
  BEGIN
    DECLARE secName VARCHAR(100);
    SELECT sections.name INTO secName
    FROM sections WHERE sections.id = id;
    RETURN(secName);
  END \\
DELIMITER ;


CREATE OR REPLACE VIEW getAllUsersView AS
  SELECT users.*, getUserSection(assignments.sectionid) AS section FROM users INNER JOIN assignments ON assignments.userid = users.id;

SELECT * FROM getAllUsersView;

SELECT users.*,
  (SELECT sections.name
   FROM sections WHERE sections.id = assignments.sectionid) AS section
  #getUserSection(assignments.sectionid) AS section
FROM users
  INNER JOIN assignments ON assignments.userid = users.id;
#end
SELECT * FROM getAllUsersView;