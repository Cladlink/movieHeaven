-- MySQL dump 10.13  Distrib 5.5.53, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: movieHeaven
-- ------------------------------------------------------
-- Server version	5.5.53-0+deb8u1

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
-- Table structure for table `Commande`
--

DROP TABLE IF EXISTS `Commande`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Commande` (
  `idCommande` int(11) NOT NULL AUTO_INCREMENT,
  `prixCommande` double NOT NULL,
  `dateCommande` date NOT NULL,
  `utilisateurId` int(11) DEFAULT NULL,
  `etatId` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCommande`),
  KEY `IDX_979CC42B31EE9377` (`utilisateurId`),
  KEY `IDX_979CC42B5EAF78A2` (`etatId`),
  CONSTRAINT `FK_979CC42B31EE9377` FOREIGN KEY (`utilisateurId`) REFERENCES `Utilisateur` (`idUtilisateur`),
  CONSTRAINT `FK_979CC42B5EAF78A2` FOREIGN KEY (`etatId`) REFERENCES `EtatCommande` (`idEtatCommande`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Commande`
--

LOCK TABLES `Commande` WRITE;
/*!40000 ALTER TABLE `Commande` DISABLE KEYS */;
INSERT INTO `Commande` VALUES (1,0,'2016-11-30',7,NULL),(2,49.24,'2016-11-30',5,2);
/*!40000 ALTER TABLE `Commande` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CommentaireFilm`
--

DROP TABLE IF EXISTS `CommentaireFilm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CommentaireFilm` (
  `id_comment_film` int(11) NOT NULL AUTO_INCREMENT,
  `commentaire` longtext COLLATE utf8_unicode_ci NOT NULL,
  `filmId` int(11) DEFAULT NULL,
  `utilisateurId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_comment_film`),
  KEY `IDX_7CB9517EA1D6191D` (`filmId`),
  KEY `IDX_7CB9517E31EE9377` (`utilisateurId`),
  CONSTRAINT `FK_7CB9517E31EE9377` FOREIGN KEY (`utilisateurId`) REFERENCES `Utilisateur` (`idUtilisateur`),
  CONSTRAINT `FK_7CB9517EA1D6191D` FOREIGN KEY (`filmId`) REFERENCES `Film` (`idFilm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CommentaireFilm`
--

LOCK TABLES `CommentaireFilm` WRITE;
/*!40000 ALTER TABLE `CommentaireFilm` DISABLE KEYS */;
/*!40000 ALTER TABLE `CommentaireFilm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Droit`
--

DROP TABLE IF EXISTS `Droit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Droit` (
  `idDroit` int(11) NOT NULL AUTO_INCREMENT,
  `libelleDroit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idDroit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Droit`
--

LOCK TABLES `Droit` WRITE;
/*!40000 ALTER TABLE `Droit` DISABLE KEYS */;
/*!40000 ALTER TABLE `Droit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `EtatCommande`
--

DROP TABLE IF EXISTS `EtatCommande`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EtatCommande` (
  `idEtatCommande` int(11) NOT NULL AUTO_INCREMENT,
  `libelleEtatCommande` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idEtatCommande`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EtatCommande`
--

LOCK TABLES `EtatCommande` WRITE;
/*!40000 ALTER TABLE `EtatCommande` DISABLE KEYS */;
INSERT INTO `EtatCommande` VALUES (1,'Expediee'),(2,'En attente d expedition'),(4,'Pas commandee'),(5,'Livree');
/*!40000 ALTER TABLE `EtatCommande` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Film`
--

DROP TABLE IF EXISTS `Film`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Film` (
  `idFilm` int(11) NOT NULL AUTO_INCREMENT,
  `titreFilm` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dureeFilm` int(11) NOT NULL,
  `dateFilm` date NOT NULL,
  `prixFilm` double NOT NULL,
  `quantiteFilm` int(11) NOT NULL,
  `imageFilm` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `typeFilmId` int(11) DEFAULT NULL,
  `realisateurId` int(11) DEFAULT NULL,
  PRIMARY KEY (`idFilm`),
  KEY `IDX_2276111C224E7740` (`typeFilmId`),
  KEY `IDX_2276111C61E3D5F9` (`realisateurId`),
  CONSTRAINT `FK_2276111C224E7740` FOREIGN KEY (`typeFilmId`) REFERENCES `TypeFilm` (`idTypeFilm`),
  CONSTRAINT `FK_2276111C61E3D5F9` FOREIGN KEY (`realisateurId`) REFERENCES `Realisateur` (`idRealisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Film`
--

LOCK TABLES `Film` WRITE;
/*!40000 ALTER TABLE `Film` DISABLE KEYS */;
INSERT INTO `Film` VALUES (7,'Batman begins',139,'2995-06-15',12.31,12,'Batman begins.jpeg',7,7),(8,'The dark Knight',147,'2008-08-13',18,5,'The dark Knight.jpeg',7,7),(9,'The dark knight rises',164,'2012-11-25',19,20,'The dark knight rises.jpeg',7,7),(10,'Interstellar',169,'2014-11-05',15,4,'Interstellar.jpeg',7,7),(11,'Le hobbit : un voyage inattendu',169,'2012-12-12',25,8,'Le hobbit : un voyage inattendu.jpeg',7,8),(12,'Le hobbit : la désolation de Smaug',161,'2013-12-11',25,35,'Le hobbit : la désolation de Smaug.jpeg',7,8),(13,'Le hobbit : la bataille des cinq armées',144,'2014-12-10',25,2,'Le hobbit : la bataille des cinq armées.jpeg',7,8),(14,'Assassins',135,'1995-11-01',10,1,'Assassins.jpeg',8,9),(15,'L\'arme fatale',110,'1987-08-05',5,30,'L\'arme fatale.jpeg',8,9),(16,'le seigneur des anneaux : la communauté de l\'anneau',161,'2013-12-11',23,11,'le seigneur des anneaux : la communauté de l\'anneau.jpeg',7,8),(17,'le seigneur des anneaux : les deux tours',200,'2002-12-18',22,10,'le seigneur des anneaux : les deux tours.jpeg',7,8),(18,'Le seigneur des anneaux : le retour du roi',200,'2003-12-17',23,10,'Le seigneur des anneaux : le retour du roi.jpeg',7,8),(19,'L\'arme fatale 2',114,'1989-08-02',23,15,'L\'arme fatale 2.jpeg',7,9),(20,'L\'arme fatale 3',118,'1992-05-15',8,2,'L\'arme fatale 3.jpeg',8,9),(21,'L\'arme fatale 4',120,'1998-12-15',8,13,'L\'arme fatale 4.jpeg',8,9),(22,'Superman',145,'1979-01-26',14,16,'Superman.jpeg',7,9),(23,'Superman II',127,'1980-12-09',15,14,'Superman II.jpeg',7,9),(24,'2012',158,'2009-11-11',10,33,'2012.jpeg',7,10),(25,'10000',149,'2008-03-12',149,6,'10000.jpeg',7,10),(26,'Batman',125,'1989-09-13',10,3,'Batman.jpeg',7,6),(27,'Batman le défi',126,'1992-07-15',10,32,'Batman le défi.jpeg',7,6),(28,'Charlie et la chocolaterie',156,'2005-07-13',10,12,'Charlie et la chocolaterie.jpeg',7,6),(29,'Dark shadows',112,'2012-05-09',19,20,'Dark shadows.jpeg',7,6),(30,'Les noces funèbres',155,'2005-10-19',11,11,'Les noces funèbres.jpeg',9,6),(31,'Sweeney Todd',115,'2008-01-13',10,15,'Sweeney Todd.jpeg',9,6);
/*!40000 ALTER TABLE `Film` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Panier`
--

DROP TABLE IF EXISTS `Panier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Panier` (
  `idPanier` int(11) NOT NULL AUTO_INCREMENT,
  `quantitePanier` int(11) NOT NULL,
  `utilisateurId` int(11) DEFAULT NULL,
  `filmId` int(11) DEFAULT NULL,
  `commandeId` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPanier`),
  KEY `IDX_236008C431EE9377` (`utilisateurId`),
  KEY `IDX_236008C4A1D6191D` (`filmId`),
  KEY `IDX_236008C48F992C7E` (`commandeId`),
  CONSTRAINT `FK_236008C431EE9377` FOREIGN KEY (`utilisateurId`) REFERENCES `Utilisateur` (`idUtilisateur`),
  CONSTRAINT `FK_236008C48F992C7E` FOREIGN KEY (`commandeId`) REFERENCES `Commande` (`idCommande`),
  CONSTRAINT `FK_236008C4A1D6191D` FOREIGN KEY (`filmId`) REFERENCES `Film` (`idFilm`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Panier`
--

LOCK TABLES `Panier` WRITE;
/*!40000 ALTER TABLE `Panier` DISABLE KEYS */;
INSERT INTO `Panier` VALUES (1,1,7,7,1),(2,4,5,7,2);
/*!40000 ALTER TABLE `Panier` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Realisateur`
--

DROP TABLE IF EXISTS `Realisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Realisateur` (
  `idRealisateur` int(11) NOT NULL AUTO_INCREMENT,
  `nomRealisateur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prenomRealisateur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idRealisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Realisateur`
--

LOCK TABLES `Realisateur` WRITE;
/*!40000 ALTER TABLE `Realisateur` DISABLE KEYS */;
INSERT INTO `Realisateur` VALUES (6,'Burton','Tim'),(7,'Nolan','Christopher'),(8,'Jackson','Peter'),(9,'Donner','Richard'),(10,'Emmerich','Roland');
/*!40000 ALTER TABLE `Realisateur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TypeFilm`
--

DROP TABLE IF EXISTS `TypeFilm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TypeFilm` (
  `idTypeFilm` int(11) NOT NULL AUTO_INCREMENT,
  `libelleTypeFilm` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`idTypeFilm`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TypeFilm`
--

LOCK TABLES `TypeFilm` WRITE;
/*!40000 ALTER TABLE `TypeFilm` DISABLE KEYS */;
INSERT INTO `TypeFilm` VALUES (7,'Science-fiction'),(8,'Action'),(9,'Fantastique');
/*!40000 ALTER TABLE `TypeFilm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Utilisateur`
--

DROP TABLE IF EXISTS `Utilisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Utilisateur` (
  `idUtilisateur` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `emailUtilisateur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nomUtilisateur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prenomUtilisateur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `adresseUtilisateur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `codePostalUtilisateur` int(11) NOT NULL,
  `VilleUtilisateur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `isActiveUtilisateur` tinyint(1) NOT NULL,
  `uniqueKeyUtilisateur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)',
  PRIMARY KEY (`idUtilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Utilisateur`
--

LOCK TABLES `Utilisateur` WRITE;
/*!40000 ALTER TABLE `Utilisateur` DISABLE KEYS */;
INSERT INTO `Utilisateur` VALUES (1,'$2y$13$ff5q2FCUUzSXJ5k1E4EU9e0VzYKJ3uLByMYoUv8xFffxFBP5TGMxG','kevin@limacher.fr','kevin','limacher','3 rue de la rue',60000,'rue',0,'583c37c258270','[\"ROLE_USER\"]'),(2,'$2y$13$wlKa8CYiV14rJ7kJ3QQIeuhaoGeZQGNnELNsuysMEA/tg0Ku.ouoq','kevin2@limacher.fr','kevin','limacher','3 rue de la rue',60000,'rue',0,'583c37e9b18e4','[\"ROLE_USER\"]'),(3,'$2y$13$61hNLVGxZD2Rxp8VMS5fju2EAn5KNxizr0O6Eo8p9.LqDgqJXU.hi','marlu@caniard.fr','marlu','caniard','3 rue de marlu',60000,'marlu',0,'583c386025577','[\"ROLE_USER\"]'),(4,'$2y$13$sD/Ojkl3ASXZxy.8aHMwHeF/E5lp8O5FDMQWD2Gy3TAIH4wUeTeOy','cladlink@live.fr','BOUTBOUL','Michael','15 rue des carrières',90000,'BELFORT',0,'583c3d7b4bf5a','[\"ROLE_USER\"]'),(5,'$2y$13$VAP2okuJgiNtDdkcGF3OJ.2GvZyE7QWmmLX/1GcfI2Z2xWF8lpUbG','tete@pied.fr','azerty','ty','2 rue de bbb',90000,'belfort',0,'583caa560f6af','[\"ROLE_USER\"]'),(6,'$2y$13$w6vxgwIOD0Yl0raGHtlwq.xstewwWrBYmlfnmMwCxyA7U1yFjR9lC','a@b.fr','A','B','C',10000,'v',0,'583d6896eddb3','[\"ROLE_USER\"]'),(7,'$2y$13$d8O5aHu.xVkT4jqNPiALZe2WWwjrR7k44s9BYi9s1.0RqooEMMq92','admin@admin.fr','admin','admin','admin',12000,'R',0,'583d693004190','[\"ROLE_ADMIN\"]'),(8,'$2y$13$MR5MbyC5gATJgC8fp.pTLuXU4TDpFALY3hzs.3ALElyjfYkbU1mVq','ezee@rzzr.fr','aaa','aaa','aaa',11111,'aa',0,'583ea109111be','[\"ROLE_USER\"]');
/*!40000 ALTER TABLE `Utilisateur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration_versions`
--

DROP TABLE IF EXISTS `migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration_versions` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration_versions`
--

LOCK TABLES `migration_versions` WRITE;
/*!40000 ALTER TABLE `migration_versions` DISABLE KEYS */;
INSERT INTO `migration_versions` VALUES ('20161128142405'),('20161129223153'),('20161129224842'),('20161130081242'),('20161130084935'),('20161130100450'),('20161130131406'),('20161130132559'),('20161130151130');
/*!40000 ALTER TABLE `migration_versions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-11-30 15:15:32
