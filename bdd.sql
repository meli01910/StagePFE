-- MySQL dump 10.13  Distrib 8.0.42, for Linux (x86_64)
--
-- Host: localhost    Database: FootballBD
-- ------------------------------------------------------
-- Server version	8.0.42-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `detections`
--

DROP TABLE IF EXISTS `detections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `heure` time DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `partner_club` varchar(255) DEFAULT NULL,
  `logo_club` varchar(255) DEFAULT NULL,
  `age_category` varchar(50) NOT NULL,
  `description` text,
  `max_participants` int NOT NULL,
  `status` enum('planned','ongoing','completed') DEFAULT 'planned',
  `date_fin_inscription` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detections`
--

LOCK TABLES `detections` WRITE;
/*!40000 ALTER TABLE `detections` DISABLE KEYS */;
INSERT INTO `detections` VALUES (10,'Détection U15 Real M','2025-06-01','14:00:00','53 avenue Paris 75014','Paris Saint Germain','/FootballProject/uploads/logos/1747699042_','U15','Justificatif Obligatoire',15,'planned','2025-05-25','2025-05-18 13:37:01','2025-05-19 23:57:22'),(12,'Detection U13','2025-06-01','14:00:00','15 rue drancy','OM','/FootballProject/uploads/logos/1747698902_','U13','Justificatif obligatoire',30,'planned','2025-05-25','2025-05-18 13:41:44','2025-05-19 23:55:02'),(14,'Detection PSG U19','2025-06-01','14:00:00','Stage paris saint germain','PSG','/FootballProject/uploads/logos/1747698834_','U11','Justificatif obligatoire',10,'planned','2025-05-25','2025-05-18 13:51:11','2025-05-19 23:53:54'),(15,'Detection FCB U15','2025-06-03','13:00:00','Camp nou','FC Barcelone','/FootballProject/uploads/logos/1747698816_','U11','',30,'planned','2025-05-27','2025-05-19 23:30:24','2025-05-19 23:53:36');
/*!40000 ALTER TABLE `detections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipes`
--

DROP TABLE IF EXISTS `equipes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipes`
--

LOCK TABLES `equipes` WRITE;
/*!40000 ALTER TABLE `equipes` DISABLE KEYS */;
INSERT INTO `equipes` VALUES (12,'PSG','/FootballProject/uploads/logos/1747707430_logo_PSG.png','psg@gmail.com'),(18,'Real madrid','/FootballProject/uploads/logos/1747707695_realmadrid.png','realmadrid@gmail.com'),(39,'FC Barcelone','/FootballProject/uploads/logos/1747705097_fcb.png','fcb@gmail.com'),(40,'pfe','/FootballProject/uploads/logos/680bd59b37aa2-logoPFE.png','pfe@gmail.com'),(41,'RBF','/FootballProject/uploads/logos/1747140151_Gemini_Generated_Image_bcodo3bcodo3bcod_1_-removebg-preview.png','rbf@gmail.com'),(42,'Olympique de Marseille','/FootballProject/uploads/logos/1747707728_om.png','om@gmail.com'),(43,'melissa','','melissa@gmail.com'),(44,'melissa2','','melissa2@gmail.com'),(45,'det1','','meli@gmail.com'),(46,'mel','','melissa2@gmail.com'),(47,'RBF','','mmm@gmail.com'),(48,'pp','','pfe@gmail.com'),(49,'psg','FootballProject/uploads/logos/1747424632_neymar.jpeg','psg@gmail.com'),(50,'Real madrid','/FootballProject/uploads/logos/1747706287_realmadrid.png','rlm@gmail.com'),(51,'gggf','/FootballProject/uploads/logos/1747430089_logo_academie.png','gffgfg@gmail.com'),(52,'OM','/FootballProject/uploads/logos/1747705113_om.png','om@gmail.com');
/*!40000 ALTER TABLE `equipes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groupe_equipe`
--

DROP TABLE IF EXISTS `groupe_equipe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groupe_equipe` (
  `groupe_id` int NOT NULL,
  `equipe_id` int NOT NULL,
  `points` int NOT NULL DEFAULT '0',
  `joues` int NOT NULL DEFAULT '0',
  `gagnes` int NOT NULL DEFAULT '0',
  `nuls` int NOT NULL DEFAULT '0',
  `perdus` int NOT NULL DEFAULT '0',
  `buts_pour` int NOT NULL DEFAULT '0',
  `buts_contre` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`groupe_id`,`equipe_id`),
  KEY `equipe_id` (`equipe_id`),
  CONSTRAINT `groupe_equipe_ibfk_1` FOREIGN KEY (`groupe_id`) REFERENCES `groupes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `groupe_equipe_ibfk_2` FOREIGN KEY (`equipe_id`) REFERENCES `equipes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groupe_equipe`
--

LOCK TABLES `groupe_equipe` WRITE;
/*!40000 ALTER TABLE `groupe_equipe` DISABLE KEYS */;
INSERT INTO `groupe_equipe` VALUES (4,12,2,2,0,2,0,0,0),(4,18,2,2,0,2,0,0,0),(4,39,2,2,0,2,0,0,0),(5,40,2,2,0,2,0,0,0),(5,41,2,2,0,2,0,0,0),(5,42,2,2,0,2,0,0,0),(6,12,2,2,0,2,0,0,0),(6,39,2,2,0,2,0,0,0),(6,42,2,2,0,2,0,0,0),(7,18,0,0,0,0,0,0,0),(7,40,0,0,0,0,0,0,0),(7,41,0,0,0,0,0,0,0),(8,18,0,0,0,0,0,0,0),(8,43,0,0,0,0,0,0,0),(8,45,0,0,0,0,0,0,0),(8,48,0,0,0,0,0,0,0),(9,12,3,3,0,3,0,0,0),(9,39,3,3,0,3,0,0,0),(9,41,3,3,0,3,0,0,0),(9,42,3,3,0,3,0,0,0),(10,40,0,0,0,0,0,0,0),(10,44,0,0,0,0,0,0,0),(10,46,0,0,0,0,0,0,0),(10,47,0,0,0,0,0,0,0);
/*!40000 ALTER TABLE `groupe_equipe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groupes`
--

DROP TABLE IF EXISTS `groupes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groupes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tournoi_id` int NOT NULL,
  `nom` varchar(50) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `tournoi_id` (`tournoi_id`),
  CONSTRAINT `groupes_ibfk_1` FOREIGN KEY (`tournoi_id`) REFERENCES `tournois` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groupes`
--

LOCK TABLES `groupes` WRITE;
/*!40000 ALTER TABLE `groupes` DISABLE KEYS */;
INSERT INTO `groupes` VALUES (4,19,'Groupe A','first group '),(5,19,'B',''),(6,20,'C',''),(7,20,'D',''),(8,21,'A',''),(9,21,'B',''),(10,21,'c','');
/*!40000 ALTER TABLE `groupes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inscription_detections`
--

DROP TABLE IF EXISTS `inscription_detections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inscription_detections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `joueur_id` int NOT NULL,
  `detection_id` int NOT NULL,
  `date_inscription` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('registered','confirmed','canceled','declined','attended','absent') DEFAULT 'registered',
  PRIMARY KEY (`id`),
  UNIQUE KEY `joueur_id` (`joueur_id`,`detection_id`),
  KEY `detection_id` (`detection_id`),
  CONSTRAINT `inscription_detections_ibfk_1` FOREIGN KEY (`joueur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inscription_detections_ibfk_2` FOREIGN KEY (`detection_id`) REFERENCES `detections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inscription_detections`
--

LOCK TABLES `inscription_detections` WRITE;
/*!40000 ALTER TABLE `inscription_detections` DISABLE KEYS */;
INSERT INTO `inscription_detections` VALUES (19,1,14,'2025-05-18 16:19:18','confirmed'),(24,16,10,'2025-05-20 10:05:12','registered');
/*!40000 ALTER TABLE `inscription_detections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matchs`
--

DROP TABLE IF EXISTS `matchs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `matchs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tournoi_id` int DEFAULT NULL,
  `est_amical` tinyint(1) DEFAULT '0',
  `equipe1_id` int DEFAULT NULL,
  `equipe2_id` int NOT NULL,
  `score1` int DEFAULT NULL,
  `score2` int DEFAULT NULL,
  `date_match` datetime NOT NULL,
  `lieu_match` varchar(255) DEFAULT 'À déterminer',
  `phase` varchar(100) DEFAULT 'Phase de groupes',
  `statut` enum('à_venir','en_cours','terminé') DEFAULT 'à_venir',
  `groupe_id` int DEFAULT NULL,
  `numero_match` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tournoi_id` (`tournoi_id`),
  KEY `equipe1_id` (`equipe1_id`),
  KEY `equipe2_id` (`equipe2_id`),
  KEY `groupe_id` (`groupe_id`),
  CONSTRAINT `matchs_ibfk_1` FOREIGN KEY (`tournoi_id`) REFERENCES `tournois` (`id`) ON DELETE CASCADE,
  CONSTRAINT `matchs_ibfk_2` FOREIGN KEY (`equipe1_id`) REFERENCES `equipes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `matchs_ibfk_3` FOREIGN KEY (`equipe2_id`) REFERENCES `equipes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `matchs_ibfk_4` FOREIGN KEY (`groupe_id`) REFERENCES `groupes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matchs`
--

LOCK TABLES `matchs` WRITE;
/*!40000 ALTER TABLE `matchs` DISABLE KEYS */;
INSERT INTO `matchs` VALUES (1,12,0,12,39,NULL,NULL,'2025-07-13 15:00:00','paris','Phase de groupes','à_venir',NULL,NULL,'2025-05-15 19:47:59'),(2,12,0,40,41,3,0,'2025-07-18 12:00:00','Paris','Phase de groupes','terminé',NULL,NULL,'2025-05-15 19:47:59'),(10,9,0,12,39,NULL,NULL,'2025-04-21 00:00:00','Paris','Phase de groupes','à_venir',NULL,NULL,'2025-05-15 19:47:59'),(11,9,0,12,40,NULL,NULL,'2025-04-23 00:00:00','Paris','Phase de groupes','à_venir',NULL,NULL,'2025-05-15 19:47:59'),(12,9,0,18,39,NULL,NULL,'2025-04-25 00:00:00','Paris','Phase de groupes','à_venir',NULL,NULL,'2025-05-15 19:47:59'),(13,9,0,18,40,NULL,NULL,'2025-04-23 00:00:00','Paris','Phase de groupes','à_venir',NULL,NULL,'2025-05-15 19:47:59'),(14,9,0,39,40,NULL,NULL,'2025-04-24 00:00:00','Paris','Phase de groupes','à_venir',NULL,NULL,'2025-05-15 19:47:59'),(19,15,0,12,39,NULL,NULL,'2025-06-12 00:00:00','Paris','Demi-finales','à_venir',NULL,NULL,'2025-05-15 19:47:59'),(20,15,0,39,40,NULL,NULL,'2025-06-14 00:00:00','Paris','Demi-finales','à_venir',NULL,NULL,'2025-05-15 19:47:59'),(22,NULL,1,18,12,NULL,NULL,'2025-08-12 13:00:00','m',NULL,'à_venir',NULL,NULL,'2025-05-15 19:47:59'),(23,NULL,1,12,39,NULL,NULL,'2025-12-13 21:00:00','Marseille',NULL,'à_venir',NULL,NULL,'2025-05-15 19:47:59'),(49,14,0,12,18,NULL,NULL,'2025-08-20 00:00:00','le havre','Championnat','à_venir',NULL,NULL,'2025-05-15 19:47:59'),(50,14,0,12,39,NULL,NULL,'2025-08-30 00:00:00','le havre','Championnat','à_venir',NULL,NULL,'2025-05-15 19:47:59'),(51,14,0,12,40,NULL,NULL,'2025-09-09 00:00:00','le havre','Championnat','à_venir',NULL,NULL,'2025-05-15 19:47:59'),(52,14,0,18,39,NULL,NULL,'2025-09-19 00:00:00','le havre','Championnat','à_venir',NULL,NULL,'2025-05-15 19:47:59'),(53,14,0,18,40,NULL,NULL,'2025-08-24 00:00:00','le havre','Championnat','à_venir',NULL,NULL,'2025-05-15 19:47:59'),(54,14,0,39,40,NULL,NULL,'2025-08-25 00:00:00','le havre','Championnat','à_venir',NULL,NULL,'2025-05-15 19:47:59'),(59,18,0,12,41,NULL,NULL,'2025-07-12 00:00:00','paris','Groupe A','à_venir',NULL,NULL,'2025-05-15 19:47:59'),(60,18,0,12,40,NULL,NULL,'2025-07-18 00:00:00','paris','Groupe A','à_venir',NULL,NULL,'2025-05-15 19:47:59'),(61,18,0,41,40,NULL,NULL,'2025-07-24 00:00:00','paris','Groupe A','à_venir',NULL,NULL,'2025-05-15 19:47:59'),(62,18,0,18,39,NULL,NULL,'2025-07-30 00:00:00','paris','Groupe B','à_venir',NULL,NULL,'2025-05-15 19:47:59'),(65,19,0,12,18,2,1,'2025-09-12 00:00:00','Alger','Phase de groupes - Groupe Groupe A','terminé',4,NULL,'2025-05-15 19:47:59'),(66,19,0,12,39,0,1,'2025-09-27 00:00:00','Alger','Phase de groupes - Groupe Groupe A','terminé',4,NULL,'2025-05-15 19:47:59'),(67,19,0,18,39,0,5,'2025-10-12 00:00:00','Alger','Phase de groupes - Groupe Groupe A','terminé',4,NULL,'2025-05-15 19:47:59'),(68,19,0,40,41,5,0,'2025-09-12 00:00:00','Alger','Phase de groupes - Groupe Groupe B','terminé',5,NULL,'2025-05-15 19:47:59'),(69,19,0,40,42,8,1,'2025-09-27 00:00:00','Alger','Phase de groupes - Groupe Groupe B','terminé',5,NULL,'2025-05-15 19:47:59'),(70,19,0,41,42,0,8,'2025-10-12 00:00:00','Alger','Phase de groupes - Groupe Groupe B','terminé',5,NULL,'2025-05-15 19:47:59'),(77,20,0,12,39,5,12,'2025-12-13 00:00:00','Paris','Phase de groupes - Groupe C','terminé',6,NULL,'2025-05-16 12:03:46'),(78,20,0,12,42,3,0,'2025-12-22 00:00:00','Paris','Phase de groupes - Groupe C','terminé',6,NULL,'2025-05-16 12:03:46'),(79,20,0,39,42,1,0,'2025-12-31 00:00:00','Paris','Phase de groupes - Groupe C','terminé',6,NULL,'2025-05-16 12:03:46'),(80,20,0,18,40,1,0,'2025-12-13 00:00:00','Paris','Phase de groupes - Groupe D','terminé',7,NULL,'2025-05-16 12:03:58'),(81,20,0,18,41,1,0,'2025-12-22 00:00:00','Paris','Phase de groupes - Groupe D','terminé',7,NULL,'2025-05-16 12:03:58'),(82,20,0,40,41,1,0,'2025-12-31 00:00:00','Paris','Phase de groupes - Groupe D','terminé',7,NULL,'2025-05-16 12:03:58'),(91,20,0,39,18,NULL,NULL,'2025-12-13 21:00:00','mmm','Quarts de finale','à_venir',NULL,NULL,'2025-05-16 12:22:01'),(93,19,0,41,40,2,1,'2025-05-17 20:00:00','À déterminer','Demi-finales','terminé',NULL,NULL,'2025-05-16 12:51:47'),(95,20,0,39,12,5,0,'2025-05-17 20:00:00','À déterminer','Demi-finales','terminé',NULL,NULL,'2025-05-16 12:54:46'),(96,20,0,12,39,0,5,'2025-05-16 20:00:00','À déterminer','Demi-finales','terminé',NULL,NULL,'2025-05-16 13:16:17'),(97,20,0,40,18,5,0,'2025-05-17 20:00:00','À déterminer','Demi-finales','terminé',NULL,NULL,'2025-05-16 13:16:17'),(98,19,0,12,18,9,0,'2025-05-16 20:00:00','À déterminer','Demi-finales','terminé',NULL,NULL,'2025-05-16 13:17:29'),(99,19,0,41,40,3,3,'2025-05-17 20:00:00','À déterminer','Demi-finales','terminé',NULL,NULL,'2025-05-16 13:17:29'),(100,19,0,40,12,2,0,'2025-05-16 20:00:00','À déterminer','Demi-finales','terminé',NULL,NULL,'2025-05-16 13:17:34'),(101,19,0,18,41,2,0,'2025-05-17 20:00:00','À déterminer','Demi-finales','terminé',NULL,NULL,'2025-05-16 13:17:34'),(102,20,0,39,40,NULL,NULL,'2025-05-17 17:00:00','À déterminer','Match pour la 3e place','à_venir',NULL,NULL,'2025-05-16 13:28:22'),(103,20,0,18,39,NULL,NULL,'2025-05-17 20:00:00','À déterminer','Finale','à_venir',NULL,NULL,'2025-05-16 13:28:22'),(104,21,0,18,43,1,0,'2025-09-15 00:00:00','paris','Phase de groupes - Groupe A','terminé',8,NULL,'2025-05-16 13:33:38'),(105,21,0,18,45,3,3,'2025-09-25 00:00:00','paris','Phase de groupes - Groupe A','terminé',8,NULL,'2025-05-16 13:33:38'),(106,21,0,18,48,6,5,'2025-10-05 00:00:00','paris','Phase de groupes - Groupe A','terminé',8,NULL,'2025-05-16 13:33:38'),(107,21,0,43,45,2,0,'2025-10-15 00:00:00','paris','Phase de groupes - Groupe A','terminé',8,NULL,'2025-05-16 13:33:38'),(108,21,0,43,48,6,0,'2025-09-19 00:00:00','paris','Phase de groupes - Groupe A','terminé',8,NULL,'2025-05-16 13:33:38'),(109,21,0,45,48,0,0,'2025-09-20 00:00:00','paris','Phase de groupes - Groupe A','terminé',8,NULL,'2025-05-16 13:33:38'),(110,21,0,12,39,2,0,'2025-09-15 00:00:00','paris','Phase de groupes - Groupe B','terminé',9,NULL,'2025-05-16 13:33:46'),(111,21,0,12,41,5,8,'2025-09-25 00:00:00','paris','Phase de groupes - Groupe B','terminé',9,NULL,'2025-05-16 13:33:46'),(112,21,0,12,42,6,5,'2025-10-05 00:00:00','paris','Phase de groupes - Groupe B','terminé',9,NULL,'2025-05-16 13:33:46'),(113,21,0,39,41,6,0,'2025-10-15 00:00:00','paris','Phase de groupes - Groupe B','terminé',9,NULL,'2025-05-16 13:33:46'),(114,21,0,39,42,3,0,'2025-09-19 00:00:00','paris','Phase de groupes - Groupe B','terminé',9,NULL,'2025-05-16 13:33:46'),(115,21,0,41,42,6,3,'2025-09-20 00:00:00','paris','Phase de groupes - Groupe B','terminé',9,NULL,'2025-05-16 13:33:46'),(116,21,0,40,44,0,2,'2025-09-15 00:00:00','paris','Phase de groupes - Groupe c','terminé',10,NULL,'2025-05-16 13:33:49'),(117,21,0,40,46,6,1,'2025-09-25 00:00:00','paris','Phase de groupes - Groupe c','terminé',10,NULL,'2025-05-16 13:33:49'),(118,21,0,40,47,6,2,'2025-10-05 00:00:00','paris','Phase de groupes - Groupe c','terminé',10,NULL,'2025-05-16 13:33:49'),(119,21,0,44,46,5,0,'2025-10-15 00:00:00','paris','Phase de groupes - Groupe c','terminé',10,NULL,'2025-05-16 13:33:49'),(120,21,0,44,47,5,0,'2025-09-19 00:00:00','paris','Phase de groupes - Groupe c','terminé',10,NULL,'2025-05-16 13:33:49'),(121,21,0,46,47,5,0,'2025-09-20 00:00:00','paris','Phase de groupes - Groupe c','terminé',10,NULL,'2025-05-16 13:33:49'),(122,19,0,18,12,NULL,NULL,'2025-05-17 20:00:00','À déterminer','Finale','à_venir',NULL,NULL,'2025-05-16 13:45:00'),(123,19,0,40,41,NULL,NULL,'2025-05-18 20:00:00','À déterminer','Finale','à_venir',NULL,NULL,'2025-05-16 13:45:00'),(124,19,0,41,18,NULL,NULL,'2025-05-19 20:00:00','À déterminer','Finale','à_venir',NULL,NULL,'2025-05-16 13:45:00');
/*!40000 ALTER TABLE `matchs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tournoi_equipe`
--

DROP TABLE IF EXISTS `tournoi_equipe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tournoi_equipe` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tournoi_id` int NOT NULL,
  `equipe_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tournoi_id` (`tournoi_id`,`equipe_id`),
  KEY `equipe_id` (`equipe_id`),
  CONSTRAINT `tournoi_equipe_ibfk_1` FOREIGN KEY (`tournoi_id`) REFERENCES `tournois` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tournoi_equipe_ibfk_2` FOREIGN KEY (`equipe_id`) REFERENCES `equipes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tournoi_equipe`
--

LOCK TABLES `tournoi_equipe` WRITE;
/*!40000 ALTER TABLE `tournoi_equipe` DISABLE KEYS */;
INSERT INTO `tournoi_equipe` VALUES (12,9,12),(15,9,18),(14,9,39),(13,9,40),(8,12,39),(7,12,40),(9,12,41),(10,13,39),(11,13,40),(23,14,12),(22,14,18),(20,14,39),(21,14,40),(16,15,12),(17,15,18),(18,15,39),(19,15,40),(29,18,12),(30,18,18),(31,18,39),(32,18,40),(33,18,41),(34,19,12),(35,19,18),(36,19,39),(37,19,40),(38,19,41),(39,19,42),(40,20,12),(41,20,18),(42,20,39),(43,20,40),(44,20,41),(45,20,42),(46,21,12),(47,21,18),(48,21,39),(49,21,40),(50,21,41),(51,21,42),(52,21,43),(53,21,44),(54,21,45),(55,21,46),(56,21,47),(57,21,48);
/*!40000 ALTER TABLE `tournoi_equipe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tournois`
--

DROP TABLE IF EXISTS `tournois`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tournois` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `categorie` varchar(50) NOT NULL,
  `nb_equipes_max` int NOT NULL,
  `reglement` text,
  `statut` enum('planifie','en_cours','termine') DEFAULT 'planifie',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `poules_generees` tinyint(1) DEFAULT '0',
  `matchs_poule_generes` tinyint(1) DEFAULT '0',
  `format` enum('championnat','coupe','poules') DEFAULT 'championnat',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tournois`
--

LOCK TABLES `tournois` WRITE;
/*!40000 ALTER TABLE `tournois` DISABLE KEYS */;
INSERT INTO `tournois` VALUES (9,'Tournoi 2 ','Paris','2025-04-19','2025-04-25','U13',18,NULL,'planifie','2025-04-24 19:34:26',0,0,'championnat'),(12,'Tournoi 4','Paris','2025-07-12','2025-08-12','U13',10,NULL,'planifie','2025-05-13 06:47:31',0,0,'championnat'),(13,'Tournoi 6','paris','2025-05-11','2025-05-22','Junior',0,NULL,'planifie','2025-05-13 10:57:17',0,0,'championnat'),(14,'Tournoi 7','le havre','2025-08-20','2025-09-20','Senior',0,NULL,'planifie','2025-05-14 10:22:51',0,0,'championnat'),(15,'Tournoi 8','Paris','2025-06-13','2025-07-13','Junior',0,NULL,'planifie','2025-05-14 10:32:15',0,0,'coupe'),(17,'tournoi 11','Paris','2025-08-12','2025-09-12','Junior',0,NULL,'planifie','2025-05-14 12:58:41',0,0,'coupe'),(18,'Tournoi 12','paris','2025-07-12','2025-08-12','Junior',0,NULL,'planifie','2025-05-15 16:10:02',0,0,'coupe'),(19,'Tournoi 15','Alger','2025-09-12','2025-10-12','Junior',0,NULL,'planifie','2025-05-15 16:21:15',0,0,'coupe'),(20,'T16','Paris','2025-12-13','2026-01-01','Junior',0,NULL,'planifie','2025-05-16 12:00:11',0,0,'coupe'),(21,'t17','paris','2025-09-15','2025-10-15','Cadet',0,NULL,'planifie','2025-05-16 13:29:26',0,0,'coupe');
/*!40000 ALTER TABLE `tournois` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `poste` varchar(50) DEFAULT NULL,
  `niveau_jeu` enum('debutant','amateur','confirme','expert') DEFAULT NULL,
  `taille` int NOT NULL,
  `poids` int NOT NULL,
  `nationalite` varchar(50) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `justificatif` varchar(255) DEFAULT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('joueur','admin') NOT NULL DEFAULT 'joueur',
  `statut` enum('en_attente','approuve','refuse') NOT NULL DEFAULT 'en_attente',
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `equipe_id` int DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_utilisateurs_equipe` (`equipe_id`),
  CONSTRAINT `fk_utilisateurs_equipe` FOREIGN KEY (`equipe_id`) REFERENCES `equipes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateurs`
--

LOCK TABLES `utilisateurs` WRITE;
/*!40000 ALTER TABLE `utilisateurs` DISABLE KEYS */;
INSERT INTO `utilisateurs` VALUES (1,'Admin','System','1990-01-01','Gardien','amateur',170,70,'Française','0123456789','admin@exemple.com',NULL,'$2y$10$OgKeMFnhfBJxQf9OOmlPIOe2QZTeFgqsSsZFwZleWr9B7iST7Qfje','admin','approuve','2025-04-17 16:14:36',NULL,NULL),(9,'player1','p1','2006-12-13','gardien','amateur',180,70,'Espagnole','0123456789','player1@gmail.com','justificatif_681076b147a1a.png','$2y$10$LTQ1IM6qDMmOT7N4Bxf3BuxjhFfTQuH8ECfwH/aKLgARFEp3jpjRy','joueur','approuve','2025-04-29 06:50:25',12,NULL),(11,'meli','p3','2005-12-15','gardien','confirme',181,50,'Espagnole','1234569','p3@gmail.com','justificatif_68107ae278768.jpeg','$2y$10$DUSJiQ0EMDeknN8dc1Ms9eIvhp18jvqKEJ.F.QC8bZ7d1wbLxGNDu','joueur','approuve','2025-04-29 07:08:18',12,NULL),(12,'melissa','pfe','2002-12-15','','expert',150,60,'Anglaise','123456','m@gmazil.com','justificatif_681082c24dbcb.pdf','$2y$10$a4.gB1lAq.kGvcfF6TsndOtSPKuAdM739uoZlkpvCRyVqnZaKOU1y','joueur','refuse','2025-04-29 07:41:54',41,NULL),(15,'melissa','mer','2003-06-12','milieu','debutant',180,60,'Anglaise','0123456789','mermourimelissa02@gmail.com','justificatif_6817dfd7ed231.png','$2y$10$uvFbIAAK9uz9GHKmqMf/C.sHt1OU1zVYBLZ3qzHrKodUA2syFGDqi','joueur','approuve','2025-05-04 21:44:56',41,NULL),(16,'Mermouri','Melissa','2002-04-12','gardien','amateur',180,62,'Française','444444','melissamermouri609@gmail.com','justificatif_6817e697c2f02.png','$2y$10$GVGiq.Tuxg5B/RliGIiUu.x5ICFMz1Grg.laPh2dWAd3Hvz3lEhSO','joueur','approuve','2025-05-04 22:13:43',41,NULL),(17,'h','meli','2006-11-13','Milieu',NULL,187,80,'Algerienne','0631254897','mmeli8977@gmail.com','justificatif_68278006b51b5.png','$2y$10$kGvcAGyQ/NU1o.ZaXCtee.Ffy4EYiKuD04cw4s8AtvklAMXjZNC1y','joueur','approuve','2025-05-16 18:12:22',NULL,NULL),(18,'JR','neymar','2010-05-12','Attaquant',NULL,180,80,'Algerienne','123456789','njr@gmail.com','justificatif_68278667f2db8.png','$2y$10$/FeRAHZE7fwdemMn69uv.OVgDf/uIZoayxqrcRLuBAK9Orcj50jQC','joueur','en_attente','2025-05-16 18:39:36',NULL,NULL),(19,'mbappe','kylian','2007-02-10','Attaquant',NULL,180,80,'Francaise','123456789','km@gmail.com','justificatif_6827879c1874a.png','$2y$10$ZksTTmhtf5OgmYacpScZJOuHlkv1bghdTLB3ih/MVeULtCTSoTqtO','joueur','approuve','2025-05-16 18:44:44',NULL,'photo_6827879c18821.jpeg'),(20,'lionel','messi','2002-12-13','Attaquant',NULL,170,68,'Argentine','0112365478','lm10@gmail.com','justificatif_68278e6f647a3.png','$2y$10$JCUmyWl5K3KyudVXeKeiJ.ghvwZW8qNPKClZPKtiCxQCul3QA.67a','joueur','approuve','2025-05-16 19:13:51',NULL,''),(21,'Yamal','lamine','2007-12-13','Attaquant',NULL,180,70,'Espagnole','012547896','ryan@gmail.com','justificatif_6827921adaac1.jpeg','$2y$10$eMA5jFStPuqvQBA/nLlsjuUODiswe2S5JjBcmLI55PtSmv1Dsv.j.','joueur','en_attente','2025-05-16 19:29:30',NULL,'photo_6827921adadd9.jpeg'),(23,'gonzalez','pedri','2002-02-12','Milieu',NULL,180,81,'Espagnole','0123654789','mm@gmail.com','justificatif_6828684cb9779.png','$2y$10$5NtDEnxZislCmH40rfCJJOslJ44k8Kyu1P8DvlSLHTag8IvxuaEVe','joueur','approuve','2025-05-17 10:43:24',12,'/FootballProject/uploads/photos/1747478604_neymar.jpeg'),(24,'Ronaldo','Cristiano','2001-02-13','Attaquant',NULL,187,80,'Algerienne','0631256987','cr7@gmail.com','justificatif_682881677b527.jpeg','$2y$10$OPtgNoe97Um8d1yCv5/bh.jPHtzGuHCkHP5fjRqGIzXGGe1uK8PCe','joueur','approuve','2025-05-17 12:30:31',12,'/FootballProject/uploads/photos/1747485031_cr7.jpeg'),(25,'fgfg','kfhdigr','2002-12-13','Milieu',NULL,190,80,'Espagnole','625489785','ml@gmail.com','justificatif_682c53cb63f80.png','$2y$10$LTahbk/kJPmF5Ww3k0cb2.mRyaXOTVS92/Rj.ccwsmVYmnJJzgXr6','joueur','en_attente','2025-05-20 10:04:59',NULL,'');
/*!40000 ALTER TABLE `utilisateurs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-20 12:08:03
