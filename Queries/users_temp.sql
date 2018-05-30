CREATE USER 'user'@'localhost' IDENTIFIED BY 'password';
GRANT SELECT, INSERT, DELETE ON temp_local.* TO 'user'@'localhost';
FLUSH PRIVILEGES;
