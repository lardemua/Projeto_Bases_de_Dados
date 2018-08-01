
Isto só foi testado depois do teste estar terminado.
Para um sistema central linux comunicar com um sistema local windows os utilizadores têm de ser criados com mysql native password:
CREATE USER 'user'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password';

Também importante é definir os acessos quando se cria o utilizador:
CREATE USER 'user'@'localhost' <- localhost
CREATE USER 'user'@'192.0.3.54'<- IP único
CREATE USER 'user'@'%'         <- todos os IPs
CREATE USER 'user'@'192.0.3.%' <- familia de IPs

Quando se define permissões para um utilizador o endereço tem de ser igual:
GRANT ALL PRIVILEGES ON base_de_dados.tabela TO 'user'@'192.0.3.54' <- Este IP tem de ser igual ao que se usou quando se criou o utilizador. Por exemplo se se criar o utilizador para uma familia de IPs '192.0.3.%' este comando não funciona (apesar de '192.0.3.54' pertence à familia de IPs)

Terminado com a escolha das tabelas:
GRANT ALL PRIVILEGES ON base_de_dados.tabela TO 'user'@'192.0.3.54' <- Tabela específica da base de dados
GRANT ALL PRIVILEGES ON base_de_dados.*      TO 'user'@'192.0.3.54' <- Todas as tabels de uma base de dados
GRANT ALL PRIVILEGES ON             *.*      TO 'user'@'192.0.3.54' <- Todas as bases de dados e respetivas tabelas
