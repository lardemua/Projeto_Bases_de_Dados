CREATE USER 'backup_manager'@'localhost' IDENTIFIED BY 'password';
GRANT SELECT, INSERT, DELETE ON backups.* TO 'backup_manager'@'localhost';
FLUSH PRIVILEGES;