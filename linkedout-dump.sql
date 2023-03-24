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
  `internshipId` int NOT NULL,
  `personId` int NOT NULL,
  `ratingId` int NULL,
  `wishDate` date DEFAULT NULL,
  `applianceDate` date DEFAULT NULL,
  `responseDate` date DEFAULT NULL,
  `validation` tinyint(1) NOT NULL,
  PRIMARY KEY (`internshipId`,`personId`),
  KEY `apply_persons_FK` (`personId`),
  KEY `apply_rating_FK` (`ratingId`),
  CONSTRAINT `apply_internships_FK` FOREIGN KEY (`internshipId`) REFERENCES `internships` (`internshipId`),
  CONSTRAINT `apply_persons_FK` FOREIGN KEY (`personId`) REFERENCES `persons` (`personId`),
  CONSTRAINT `apply_rating_FK` FOREIGN KEY (`ratingId`) REFERENCES `rating` (`ratingId`)
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
  `companyEmail` varchar(127) NOT NULL,
  PRIMARY KEY (`companyId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

INSERT INTO linkedout.companies (companyLogo, companyName, companySector, companyWebsite, maskedCompany, companyEmail)
    VALUES ('data:image/png;base64,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',
            'Crédit Agricole', 'Bancaire', 'https://www.credit-agricole.fr/', 0, 'contact@credit-agricole.fr');
INSERT INTO linkedout.companies (companyLogo, companyName, companySector, companyWebsite, maskedCompany, companyEmail)
    VALUES ('https://storage.googleapis.com/support-kms-prod/ZAl1gIwyUsvfwxoW9ns47iJFioHXODBbIkrK',
            'Google', 'Informatique', 'https://careers.google.com/jobs/results/', 0, 'contact@google.com');
INSERT INTO linkedout.companies (companyLogo, companyName, companySector, companyWebsite, maskedCompany, companyEmail)
    VALUES ('https://www.apple.com/ac/structured-data/images/knowledge_graph_logo.png?202303160802',
            'Apple', 'Informatique', 'https://jobs.apple.com/fr-fr/search?location=france-FRAC', 0, 'contact@apple.com');
INSERT INTO linkedout.companies (companyLogo, companyName, companySector, companyWebsite, maskedCompany, companyEmail)
    VALUES ('https://upload.wikimedia.org/wikipedia/commons/thumb/1/16/Facebook-icon-1.png/640px-Facebook-icon-1.png',
            'Facebook', 'Informatique',
            'https://www.metacareers.com/areas-of-work/Facebook%20App/?p[divisions][0]=Facebook&divisions[0]=Facebook',
            'contact@facebook.com', 0);
INSERT INTO linkedout.companies (companyLogo, companyName, companySector, companyWebsite, maskedCompany, companyEmail)
    VALUES ('https://s3-symbol-logo.tradingview.com/amazon--600.png',
            'Amazon', 'Informatique', 'https://www.amazon.jobs/fr', 0, 'contact@amazon.com');
INSERT INTO linkedout.companies (companyLogo, companyName, companySector, companyWebsite, maskedCompany, companyEmail)
    VALUES ('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOAAAADgCAMAAAAt85rTAAAARVBMVEXzUyWBvAYFpvD/ugj/////+ff//fbzTBf4nIX7/fa113j2/P8Ao/D/uAD/1Xl4yvb8/vryRAB4uAD7w7TS56ys3vn/5q1vYD6GAAABKElEQVR4nO3PSQrCUABEwa9mVpM43v+obiSia2lIqAe9bqqUVG1XV+/64bpLFfMBAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgL+E9imuvwCMytdrNsCnO9DrFLnqpbmPlapNh7g2gNce4Brb/vAY67P69zEKlOsxyKcn2Oscoo1LcBmPMcq+1TfwEMqQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEDANQJfDICe0X+So2cAAAAASUVORK5CYII=',
            'Microsoft', 'Informatique', 'https://careers.microsoft.com/us/en', 0, 'contact@microsoft.com');

--
-- Altering table `companies` to add FULLTEXT index
--

ALTER TABLE companies ADD FULLTEXT ft_companyName (companyName);
ALTER TABLE companies ADD FULLTEXT ft_companySector (companySector);

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
  `internshipTitle` varchar(63) DEFAULT NULL,
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
  PRIMARY KEY (`internshipId`),
  KEY `internships_companies_FK` (`companyId`),
  KEY `internships_cities0_FK` (`cityId`),
  CONSTRAINT `internships_cities0_FK` FOREIGN KEY (`cityId`) REFERENCES `cities` (`cityId`),
  CONSTRAINT `internships_companies_FK` FOREIGN KEY (`companyId`) REFERENCES `companies` (`companyId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Altering table `internships` to add FULLTEXT index
--

ALTER TABLE internships ADD FULLTEXT ft_internshipTitle (internshipTitle);
ALTER TABLE internships ADD FULLTEXT ft_internshipsSkills (internshipSkills);

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
