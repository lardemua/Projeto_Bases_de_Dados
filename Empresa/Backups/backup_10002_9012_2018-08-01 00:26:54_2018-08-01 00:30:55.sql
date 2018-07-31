-- MySQL dump 10.13  Distrib 5.7.23, for Linux (x86_64)
--
-- Host: localhost    Database: backups_temp
-- ------------------------------------------------------
-- Server version	5.7.23-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientes` (
  `cl_ID` int(11) NOT NULL,
  `cl_nome` varchar(50) NOT NULL,
  `cl_morada` varchar(100) DEFAULT NULL,
  `cl_IP` varchar(50) NOT NULL,
  `cl_port` int(11) NOT NULL,
  PRIMARY KEY (`cl_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (10002,'Máscaras de Carnaval LDA','Rua de Elm street, nº 13','127.0.0.1',3306);
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fase`
--

DROP TABLE IF EXISTS `fase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fase` (
  `fase_ID` int(11) NOT NULL,
  `fase_nome` varchar(50) NOT NULL,
  PRIMARY KEY (`fase_ID`),
  UNIQUE KEY `REPETIDO_NOME_FASE` (`fase_nome`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fase`
--

LOCK TABLES `fase` WRITE;
/*!40000 ALTER TABLE `fase` DISABLE KEYS */;
INSERT INTO `fase` VALUES (4,'Abertura'),(3,'Compactação'),(2,'Enchimento'),(5,'Extração'),(1,'Fecho');
/*!40000 ALTER TABLE `fase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `moldes`
--

DROP TABLE IF EXISTS `moldes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `moldes` (
  `m_IDCliente` int(11) NOT NULL,
  `m_ID` int(11) NOT NULL,
  `m_nome` varchar(30) DEFAULT NULL,
  `m_descricao` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`m_ID`),
  KEY `MOLDE_MAU_ID_CLIENTE` (`m_IDCliente`),
  CONSTRAINT `MOLDE_MAU_ID_CLIENTE` FOREIGN KEY (`m_IDCliente`) REFERENCES `clientes` (`cl_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `moldes`
--

LOCK TABLES `moldes` WRITE;
/*!40000 ALTER TABLE `moldes` DISABLE KEYS */;
INSERT INTO `moldes` VALUES (10002,9012,NULL,NULL);
/*!40000 ALTER TABLE `moldes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registos`
--

DROP TABLE IF EXISTS `registos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registos` (
  `r_IDMolde` int(11) NOT NULL,
  `r_numSensor` int(11) NOT NULL,
  `r_fase` int(11) NOT NULL,
  `r_data_hora` datetime NOT NULL,
  `r_milissegundos` tinyint(4) NOT NULL,
  `r_valor` float DEFAULT NULL,
  PRIMARY KEY (`r_IDMolde`,`r_numSensor`,`r_data_hora`,`r_milissegundos`),
  KEY `REGISTOS_MAU_ID_FASE` (`r_fase`),
  CONSTRAINT `REGISTOS_MAU_ID_FASE` FOREIGN KEY (`r_fase`) REFERENCES `fase` (`fase_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `REGISTOS_MAU_ID_MOLDE_SENSOR` FOREIGN KEY (`r_IDMolde`, `r_numSensor`) REFERENCES `sensores` (`s_IDMolde`, `s_num`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registos`
--

LOCK TABLES `registos` WRITE;
/*!40000 ALTER TABLE `registos` DISABLE KEYS */;
INSERT INTO `registos` VALUES (9012,1,1,'2018-08-01 00:26:54',88,498.66),(9012,1,1,'2018-08-01 00:27:55',16,509.92),(9012,1,1,'2018-08-01 00:28:55',26,501.24),(9012,1,1,'2018-08-01 00:29:55',46,490.07),(9012,1,1,'2018-08-01 00:30:55',70,498.88),(9012,2,1,'2018-08-01 00:26:54',88,2266.25),(9012,2,1,'2018-08-01 00:27:55',16,2228.12),(9012,2,1,'2018-08-01 00:28:55',26,2214.34),(9012,2,1,'2018-08-01 00:29:55',46,2186.56),(9012,2,1,'2018-08-01 00:30:55',70,2152.83),(9012,3,1,'2018-08-01 00:26:54',88,2266.25),(9012,3,1,'2018-08-01 00:27:55',16,2228.12),(9012,3,1,'2018-08-01 00:28:55',26,2214.34),(9012,3,1,'2018-08-01 00:29:55',46,2186.56),(9012,3,1,'2018-08-01 00:30:55',70,2152.83),(9012,4,1,'2018-08-01 00:26:54',88,2266.25),(9012,4,1,'2018-08-01 00:27:55',16,2228.12),(9012,4,1,'2018-08-01 00:28:55',26,2214.34),(9012,4,1,'2018-08-01 00:29:55',46,2186.56),(9012,4,1,'2018-08-01 00:30:55',70,2152.83),(9012,5,1,'2018-08-01 00:26:54',88,2266.25),(9012,5,1,'2018-08-01 00:27:55',16,2228.12),(9012,5,1,'2018-08-01 00:28:55',26,2214.34),(9012,5,1,'2018-08-01 00:29:55',46,2186.56),(9012,5,1,'2018-08-01 00:30:55',70,2152.83);
/*!40000 ALTER TABLE `registos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sensores`
--

DROP TABLE IF EXISTS `sensores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sensores` (
  `s_IDMolde` int(11) NOT NULL,
  `s_num` int(11) NOT NULL,
  `s_tipo` int(11) NOT NULL,
  `s_nome` varchar(30) DEFAULT NULL,
  `s_descricao` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`s_IDMolde`,`s_num`),
  KEY `SENSOR_MAU_ID_TIPO` (`s_tipo`),
  CONSTRAINT `SENSOR_MAU_ID_MOLDE` FOREIGN KEY (`s_IDMolde`) REFERENCES `moldes` (`m_ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `SENSOR_MAU_ID_TIPO` FOREIGN KEY (`s_tipo`) REFERENCES `tipo` (`tipo_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sensores`
--

LOCK TABLES `sensores` WRITE;
/*!40000 ALTER TABLE `sensores` DISABLE KEYS */;
INSERT INTO `sensores` VALUES (9012,1,1,NULL,NULL),(9012,2,2,NULL,NULL),(9012,3,3,NULL,NULL),(9012,4,4,NULL,NULL),(9012,5,5,NULL,NULL);
/*!40000 ALTER TABLE `sensores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo`
--

DROP TABLE IF EXISTS `tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo` (
  `tipo_ID` int(11) NOT NULL,
  `tipo_nome` varchar(50) NOT NULL,
  PRIMARY KEY (`tipo_ID`),
  UNIQUE KEY `REPETIDO_NOME_TIPO` (`tipo_nome`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo`
--

LOCK TABLES `tipo` WRITE;
/*!40000 ALTER TABLE `tipo` DISABLE KEYS */;
INSERT INTO `tipo` VALUES (6,'Acelerómetro X'),(7,'Acelerómetro Y'),(8,'Acelerómetro Z'),(2,'Dinamómetro'),(3,'Extensómetro'),(5,'Pressão'),(1,'Termómetro'),(4,'Vibrómetro');
/*!40000 ALTER TABLE `tipo` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-08-01  0:31:38
