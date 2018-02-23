
#GRANT ALL PRIVILEGES ON server1.* TO 'serve'@'%' IDENTIFIED BY 'serve';
#GRANT ALL PRIVILEGES ON server1.* TO 'serve'@'localhost' IDENTIFIED BY 'serve';

CREATE DATABASE IF NOT EXISTS server1;
USE server1;

CREATE TABLE IF NOT EXISTS tablename (
  id smallint unsigned not null auto_increment,
  name varchar(20) not null,
  constraint pk_example primary key (id)
);

CREATE TABLE categories(
  id int not null auto_increment primary key,
  cat_name varchar(255) not null,
  cat_description text
) ENGINE=InnoDB;

CREATE TABLE products(
  prd_id int not null auto_increment primary key,
  prd_name varchar(355) not null,
  prd_price decimal,
  cat_id int not null,
  FOREIGN KEY fk_cat(cat_id)
  REFERENCES categories(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
)ENGINE=InnoDB;


INSERT INTO tablename ( id, name ) VALUES ( null, 'Sample data' );

CREATE OR REPLACE VIEW getTablename AS SELECT * FROM tablename;

SELECT * FROM getTablename;