-- MySQL dump 10.13  Distrib 8.0.32, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: linkedout
-- ------------------------------------------------------
-- Server version	8.0.32

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
-- Table structure for table `appliance` which contains the wish list and internship applications
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appliance` (
  `intershipId` int NOT NULL,
  `personId` int NOT NULL,
  `ratingId` int NOT NULL,
  `wishDate` date DEFAULT NULL,
  `applianceDate` date DEFAULT NULL,
  `responseDate` date DEFAULT NULL,
  `validation` tinyint(1) NOT NULL,
  PRIMARY KEY (`intershipId`,`personId`,`ratingId`),
  KEY `apply_persons0_FK` (`personId`),
  KEY `apply_rating1_FK` (`ratingId`),
  CONSTRAINT `apply_internships_FK` FOREIGN KEY (`intershipId`) REFERENCES `internships` (`internshipId`),
  CONSTRAINT `apply_persons0_FK` FOREIGN KEY (`personId`) REFERENCES `persons` (`personId`),
  CONSTRAINT `apply_rating1_FK` FOREIGN KEY (`ratingId`) REFERENCES `rating` (`ratingId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `campus` which contains the differents campus of CESI
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `campus` (
  `campusId` int NOT NULL AUTO_INCREMENT,
  `campusName` varchar(50) NOT NULL,
  PRIMARY KEY (`campusId`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campus`
--

INSERT INTO `campus` VALUES (1,'Aix-en-Provence');
INSERT INTO `campus` VALUES (2,'Angoulême');
INSERT INTO `campus` VALUES (3,'Arras');
INSERT INTO `campus` VALUES (4,'Bordeaux');
INSERT INTO `campus` VALUES (5,'Brest');
INSERT INTO `campus` VALUES (6,'Caen');
INSERT INTO `campus` VALUES (7,'Châteauroux');
INSERT INTO `campus` VALUES (8,'Dijon');
INSERT INTO `campus` VALUES (9,'Grenoble');
INSERT INTO `campus` VALUES (10,'La Rochelle');
INSERT INTO `campus` VALUES (11,'Le Mans');
INSERT INTO `campus` VALUES (12,'Lille');
INSERT INTO `campus` VALUES (13,'Lyon');
INSERT INTO `campus` VALUES (14,'Montpellier');
INSERT INTO `campus` VALUES (15,'Nancy');
INSERT INTO `campus` VALUES (16,'Nantes');
INSERT INTO `campus` VALUES (17,'Nice');
INSERT INTO `campus` VALUES (18,'Orléans');
INSERT INTO `campus` VALUES (19,'Paris (La Défense - Nanterre)');
INSERT INTO `campus` VALUES (20,'Pau');
INSERT INTO `campus` VALUES (21,'Reims');
INSERT INTO `campus` VALUES (22,'Rouen');
INSERT INTO `campus` VALUES (23,'Saint-Nazaire');
INSERT INTO `campus` VALUES (24,'Strasbourg');
INSERT INTO `campus` VALUES (25,'Toulouse');

--
-- Table structure for table `cities` which contains a list of all cities in France
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cities` (
  `cityId` int NOT NULL AUTO_INCREMENT,
  `cityName` varchar(50) NOT NULL,
  `zipcode` char(5) NOT NULL,
  PRIMARY KEY (`cityId`)
) ENGINE=InnoDB AUTO_INCREMENT=35854 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `companies` which contains the companies that offer internships
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `companies` (
  `companyId` int NOT NULL AUTO_INCREMENT,
  `companyLogo` text NOT NULL,
  `companyName` varchar(50) NOT NULL,
  `companySector` varchar(50) NOT NULL,
  `companyWebsite` varchar(50) NOT NULL,
  `maskedCompany` tinyint(1) NOT NULL,
  PRIMARY KEY (`companyId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `internship_studentYear` which links internships and required student years
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `internship_studentYear` (
  `studentYearId` int NOT NULL,
  `internshipId` int NOT NULL,
  PRIMARY KEY (`studentYearId`,`internshipId`),
  KEY `want_internships0_FK` (`internshipId`),
  CONSTRAINT `want_internships0_FK` FOREIGN KEY (`internshipId`) REFERENCES `internships` (`internshipId`),
  CONSTRAINT `want_studentsYears_FK` FOREIGN KEY (`studentYearId`) REFERENCES `studentsYears` (`studentYearId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `internships` which contains the differents internships
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `internships` (
  `internshipId` int NOT NULL AUTO_INCREMENT,
  `internshipDescription` text NOT NULL,
  `internshipSkills` text NOT NULL,
  `internshipSalary` int NOT NULL,
  `internshipOfferDate` date NOT NULL,
  `internshipBeginDate` date NOT NULL,
  `internshipEndDate` date NOT NULL,
  `numberPlaces` int NOT NULL,
  `maskedInternship` tinyint(1) NOT NULL,
  `companyId` int NOT NULL,
  `cityId` int NOT NULL,
  `internshipTitle` varchar(63) DEFAULT NULL,
  PRIMARY KEY (`internshipId`),
  KEY `internships_companies_FK` (`companyId`),
  KEY `internships_cities0_FK` (`cityId`),
  CONSTRAINT `internships_cities0_FK` FOREIGN KEY (`cityId`) REFERENCES `cities` (`cityId`),
  CONSTRAINT `internships_companies_FK` FOREIGN KEY (`companyId`) REFERENCES `companies` (`companyId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `person_promotion` which links persons and promotions
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `person_promotion` (
  `personId` int NOT NULL,
  `promotionId` int NOT NULL,
  PRIMARY KEY (`personId`,`promotionId`),
  KEY `integrate_promotions0_FK` (`promotionId`),
  CONSTRAINT `integrate_persons_FK` FOREIGN KEY (`personId`) REFERENCES `persons` (`personId`),
  CONSTRAINT `integrate_promotions0_FK` FOREIGN KEY (`promotionId`) REFERENCES `promotions` (`promotionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `persons` which contains a list of all persons. Student, tutor and administrator
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `persons` (
  `personId` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `password` varchar(127) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `roleId` int NOT NULL,
  PRIMARY KEY (`personId`),
  KEY `persons_roles_FK` (`roleId`),
  CONSTRAINT `persons_roles_FK` FOREIGN KEY (`roleId`) REFERENCES `roles` (`roleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `promotions` which contains a list of CESI promotions
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `promotions` (
  `promotionId` int NOT NULL AUTO_INCREMENT,
  `promotionName` varchar(50) NOT NULL,
  `campusId` int NOT NULL,
  PRIMARY KEY (`promotionId`),
  KEY `promotions_campus_FK` (`campusId`),
  CONSTRAINT `promotions_campus_FK` FOREIGN KEY (`campusId`) REFERENCES `campus` (`campusId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` VALUES (1,'NY2AP201',15);

--
-- Table structure for table `rating` which contains a list of company rating
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rating` (
  `ratingId` int NOT NULL AUTO_INCREMENT,
  `rate` int NOT NULL,
  PRIMARY KEY (`ratingId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roles` which contains a list of roles for persons
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `roleId` int NOT NULL AUTO_INCREMENT,
  `roleName` varchar(50) NOT NULL,
  PRIMARY KEY (`roleId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` VALUES (1,'administrator');
INSERT INTO `roles` VALUES (2,'tutor');
INSERT INTO `roles` VALUES (3,'student');

--
-- Table structure for table `studentsYears` which contains the differents student years
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `studentsYears` (
  `studentYearId` int NOT NULL AUTO_INCREMENT,
  `studentYear` char(2) NOT NULL,
  PRIMARY KEY (`studentYearId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `studentsYears`
--

INSERT INTO `studentsYears` VALUES (1,'A1');
INSERT INTO `studentsYears` VALUES (2,'A2');
INSERT INTO `studentsYears` VALUES (3,'A3');
INSERT INTO `studentsYears` VALUES (4,'A4');
INSERT INTO `studentsYears` VALUES (5,'A5');
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-03-20  8:57:25
