CREATE USER 'user'@'localhost'
	IDENTIFIED BY 'password';
GRANT SELECT, INSERT, DELETE, UPDATE
	ON local.tipo TO 'user'@'localhost';
GRANT SELECT, INSERT, DELETE, UPDATE
	ON local.fase TO 'user'@'localhost';
GRANT SELECT, INSERT, DELETE, UPDATE
	ON local.clientes TO 'user'@'localhost';
GRANT SELECT, INSERT, DELETE, UPDATE
	ON local.moldes TO 'user'@'localhost';
GRANT SELECT, INSERT, DELETE, UPDATE
	ON local.sensores TO 'user'@'localhost';
GRANT SELECT, INSERT, DELETE, UPDATE
	ON local.tipo TO 'user'@'centralhost';
GRANT SELECT, INSERT, DELETE, UPDATE
	ON local.fase TO 'user'@'centralhost';
GRANT SELECT, INSERT, DELETE, UPDATE
	ON local.clientes TO 'user'@'centralhost';
GRANT SELECT, INSERT, DELETE, UPDATE
	ON local.moldes TO 'user'@'centralhost';
GRANT SELECT, INSERT, DELETE, UPDATE
	ON local.sensores TO 'user'@'centralhost';
GRANT SELECT, INSERT, DELETE, UPDATE
	ON temp_local.* TO 'user'@'centralhost';
CREATE USER 'transferencia'@'centralhost'
	IDENTIFIED BY 'transferencia1234';
GRANT SELECT, DELETE
	ON local.registos TO 'transferencia'@'centralhost';
CREATE USER 'sensores'@'localhost'
	IDENTIFIED BY 'sensores1234';
GRANT INSERT
	ON local.registos TO 'sensores'@'localhost';
FLUSH PRIVILEGES;
