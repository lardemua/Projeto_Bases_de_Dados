CREATE USER 'user'@'localhost'
	IDENTIFIED BY 'password';
GRANT SELECT, INSERT, DELETE, UPDATE
	ON central.tipo TO 'user'@'localhost';
GRANT SELECT, INSERT, DELETE, UPDATE
	ON central.fase TO 'user'@'localhost';
GRANT SELECT, INSERT, DELETE, UPDATE
	ON central.clientes TO 'user'@'localhost';
GRANT SELECT, INSERT, DELETE, UPDATE
	ON central.moldes TO 'user'@'localhost';
GRANT SELECT, INSERT, DELETE, UPDATE
	ON central.sensores TO 'user'@'localhost';
GRANT SELECT, DELETE
	ON central.registos TO 'user'@'localhost';
GRANT SELECT, UPDATE
	ON reg_proc.atualizar TO 'user'@'localhost';
GRANT SELECT, INSERT, DELETE, UPDATE
	ON reg_proc.backups TO 'user'@'localhost';
CREATE USER 'transferencia'@'localhost'
	IDENTIFIED BY 'transferencia1234';
GRANT INSERT
	ON central.registos TO 'transferencia'@'localhost';
GRANT SELECT, UPDATE
	ON reg_proc.atualizar TO 'transferencia'@'localhost';
CREATE USER 'backupmanager'@'localhost'
	IDENTIFIED BY 'backup1234';
GRANT SELECT
	ON central.clientes TO 'backupmanager'@'localhost';
GRANT SELECT
	ON central.moldes TO 'backupmanager'@'localhost';
GRANT SELECT
	ON central.sensores TO 'backupmanager'@'localhost';
GRANT SELECT, DELETE
	ON central.registos TO 'backupmanager'@'localhost';
GRANT SELECT, INSERT, DELETE
	ON backups.* TO 'backupmanager'@'localhost';
GRANT DROP, CREATE, ALTER, REFERENCES, LOCK TABLES, SELECT, INSERT, DELETE
	ON backups_temp.* TO 'backupmanager'@'localhost';
GRANT SELECT, UPDATE
	ON reg_proc.atualizar TO 'backupmanager'@'localhost';
GRANT SELECT, DELETE
	ON reg_proc.backups TO 'backupmanager'@'localhost';
FLUSH PRIVILEGES;
