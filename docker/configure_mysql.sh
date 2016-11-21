service mysql restart
mysql -uroot -pNEWMYSQLROOTPASSWORD -e "CREATE USER 'username'@'localhost' IDENTIFIED BY 'password'"
mysql -uroot -pNEWMYSQLROOTPASSWORD -e "CREATE DATABASE shortscience"
mysql -uroot -pNEWMYSQLROOTPASSWORD -e "GRANT ALL PRIVILEGES ON shortscience.* TO 'username'@'localhost'"
mysql -uroot -pNEWMYSQLROOTPASSWORD shortscience < /var/www/html/working/db.sql
