CREATE USER 'user'@'localhost' IDENTIFIED BY 'password';
GRANT SELECT, INSERT, DELETE ON temp.* TO 'user'@'localhost';
FLUSH PRIVILEGES;