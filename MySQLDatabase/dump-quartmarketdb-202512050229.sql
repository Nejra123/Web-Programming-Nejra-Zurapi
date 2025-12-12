-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: quartmarketdb
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `number_of_products` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'drinks',1),(2,'fruits',4),(4,'vegetables',2),(5,'snacks',1);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `surname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `role` enum('USER','ADMIN') NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (9,'John Doe',NULL,'john@example.com','$2y$10$VPq1dyaPmjFN.ALlkIlHeOyVSJNriGWEjF2i35ntbChAtIdExq9oy',NULL,'USER'),(10,'Nejra','Zurapi','nejrazurapi@gmail.com','$2y$10$vwU6MVCkIXnnPIn2vjOG9.VrL5TT.Dzs1XI8xrigPvRSmNM1nnjTm',NULL,'ADMIN'),(12,'Hana','Hanic','Hanahanic@gmail.com','$2y$10$Jx0BsWB9bmcPs4xOQQmN9.ghn3pYvaYGa4zEPkp9Q0Hj2MJojc5I.','hanibani','USER'),(14,'User','UserSurname','user@gmail.com','$2y$10$bWCyipN3Mi7SDkdG2Jdec.RBLjOuCJIN2nkn9q51bIpc5QXOEseCO','user123','USER'),(19,'hzthzt','htzhzt','nejrazurapi2@gmail.com','$2y$10$DOeeYqer/Bbi4n46Cbp9LuP6Dj1eAVpKAI8bIIlSCmPxJhGgN4B3m','tzthhzt','USER');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,'mjaumjau','Amazing service!',9),(2,'john_doe','Hello, I have a question...',NULL),(3,NULL,'heyyy',NULL),(4,'nejrazurapi@gmail.com','i love this',10),(5,'nejrazurapi@gmail.com','lovee',10),(6,'nejrazurapi@gmail.com','mewowew',10),(7,'user123','i love this!!',14);
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Date` date DEFAULT NULL,
  `Time` time DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `amount` decimal(10,0) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `items` text DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'2025-10-18','12:59:55','address123',12,9,NULL),(25,'2025-11-22','16:29:01','adressa12324',11,10,'[{\"product_id\":\"11\",\"name\":\"raspberries\",\"quantity\":1,\"price\":7},{\"product_id\":\"12\",\"name\":\"banana\",\"quantity\":1,\"price\":4}]'),(30,'2025-11-22','16:33:06','adresica',24,10,'[{\"product_id\":\"10\",\"name\":\"grapes\",\"quantity\":2,\"price\":12}]'),(31,'2025-11-30','13:12:10','gg',11,10,'[{\"product_id\":\"12\",\"name\":\"banana\",\"quantity\":1,\"price\":4},{\"product_id\":\"11\",\"name\":\"raspberries\",\"quantity\":1,\"price\":7}]'),(32,'2025-11-30','13:17:58','mojaAdr',25,10,'[{\"product_id\":\"11\",\"name\":\"raspberries\",\"quantity\":3,\"price\":7},{\"product_id\":\"12\",\"name\":\"banana\",\"quantity\":1,\"price\":4}]'),(33,'2025-11-30','14:28:37','nova',24,10,'[{\"product_id\":\"10\",\"name\":\"grapes\",\"quantity\":2,\"price\":12}]'),(34,'2025-11-30','14:38:30','rasp',7,10,'[{\"product_id\":\"11\",\"name\":\"raspberries\",\"quantity\":1,\"price\":7}]'),(35,'2025-11-30','15:13:40','potatoFanta',7,10,'[{\"product_id\":\"14\",\"name\":\"potato\",\"quantity\":2,\"price\":3},{\"product_id\":\"16\",\"name\":\"fantaOrange\",\"quantity\":1,\"price\":1.35}]'),(39,'2025-12-02','20:50:51','Sarajevo212',24,10,'[{\"product_id\":\"10\",\"name\":\"grapes\",\"quantity\":2,\"price\":12}]'),(40,'2025-12-02','20:51:23','Ilovetomato222',12,14,'[{\"product_id\":\"15\",\"name\":\"tomato\",\"quantity\":2,\"price\":6}]'),(41,'2025-12-02','21:02:08','Safeta Hadzica',9,10,'[{\"product_id\":\"12\",\"name\":\"banana\",\"quantity\":2,\"price\":4},{\"product_id\":\"16\",\"name\":\"fantaOrange\",\"quantity\":1,\"price\":1.35}]'),(50,'2025-12-03','17:19:18','Alipasina123',10,10,'[{\"product_id\":\"14\",\"name\":\"potato\",\"quantity\":2,\"price\":3},{\"product_id\":\"13\",\"name\":\"orange\",\"quantity\":1,\"price\":4}]'),(51,'2025-12-03','17:29:44','newnew',17,10,'[{\"product_id\":\"11\",\"name\":\"raspberries\",\"quantity\":2,\"price\":7},{\"product_id\":\"16\",\"name\":\"fantaOrange\",\"quantity\":1,\"price\":1.35},{\"product_id\":\"18\",\"name\":\"kinderBueno\",\"quantity\":1,\"price\":1.5}]');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_orders`
--

DROP TABLE IF EXISTS `product_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_orders` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `price` decimal(10,0) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_po_product` (`product_id`),
  KEY `fk_po_order` (`order_id`),
  CONSTRAINT `fk_po_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`ID`),
  CONSTRAINT `fk_po_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_orders`
--

LOCK TABLES `product_orders` WRITE;
/*!40000 ALTER TABLE `product_orders` DISABLE KEYS */;
INSERT INTO `product_orders` VALUES (1,1,2,NULL,1),(5,13,2,10,1),(6,13,2,11,1),(7,13,2,11,1),(8,13,2,11,1),(9,13,2,11,1);
/*!40000 ALTER TABLE `product_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_product_category` (`category_id`),
  CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (10,'grapes',421,12.00,'frontend/img/fruite-item-5.jpg',2),(11,'raspberries',321,7.00,'frontend/img/fruite-item-2.jpg',2),(12,'banana',133,4.00,'frontend/img/fruite-item-3.jpg',2),(13,'orange',343,4.00,'frontend/img/fruite-item-1.jpg',2),(14,'potato',523,3.00,'frontend/img/vegetable-item-5.jpg',4),(15,'tomato',321,6.00,'frontend/img/vegetable-item-1.jpg',4),(16,'fantaOrange',120,1.35,'frontend/img/fanta.png',1),(18,'kinderBueno',101,1.50,'frontend/img/kinderbueno.jpg',5);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'quartmarketdb'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-05  2:29:35
