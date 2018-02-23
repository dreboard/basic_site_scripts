/*


docker exec -i mysql-container mysql -uuser -ppassword name_db < data.sql
docker exec database.dev sh -c 'exec mysqldump server1 -uroot -p"$MYSQL_ROOT_PASSWORD"' > /Users/andreboard/Desktop/SERVER/server1.sql


mysqldump --all-databases --single-transaction --user=root --password > /Users/andreboard/Desktop/db_test/backup.sql

--all-databases - this dumps all of the tables in all of the databases
--user - The MySQL user name you want to use for the backup
--password - The password for this user.  You can leave this blank or include the password value (which is less secure)
--single-transaction - for InnoDB tables



* Security





create a function in your .bash_profile:

dumpdb () {
   docker exec "${PWD##*/}_my-db_1" \
       mysqldump -uroot --password=password "$1" >backup.sql
}

dumpdb "my_database"





Not within the mysql client environment

mysqldump -h 127.0.0.1 -u root -p --all-databases > dump.sql

mysqldump -h 127.0.0.1 -u serve -p --all-databases > server1_backup.sql


*/
USE server1;
CREATE TABLE IF NOT EXISTS tablename ( id smallint unsigned not null auto_increment, name varchar(20) not null, constraint pk_example primary key (id) );

INSERT INTO tablename ( id, name ) VALUES ( null, 'Sample data' );