CREATE USER 'transferencia'@'localhost' IDENTIFIED BY 'transferencia1234';
GRANT INSERT ON central.registos TO 'transferencia'@'localhost';
FLUSH PRIVILEGES;
CREATE USER 'user'@'%' IDENTIFIED BY 'password';
GRANT SELECT, INSERT, DELETE, UPDATE ON central.clientes TO 'user'@'%';
GRANT SELECT, INSERT, DELETE ON central.moldes TO 'user'@'%';
GRANT SELECT, INSERT, DELETE ON central.sensores TO 'user'@'%';
GRANT SELECT, DELETE ON central.registos TO 'user'@'%';
FLUSH PRIVILEGES;
