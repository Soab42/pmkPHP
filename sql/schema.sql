-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: pmk
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `consumer_bill_paid`
--

DROP TABLE IF EXISTS `consumer_bill_paid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `consumer_bill_paid` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `Branch_name` varchar(12) DEFAULT NULL,
  `Paid_Date` varchar(10) DEFAULT NULL,
  `Bill_Paid_ID` varchar(15) DEFAULT NULL,
  `Order_Code` varchar(20) DEFAULT NULL,
  `Bank_Name` varchar(32) DEFAULT NULL,
  `Checque_No` varchar(20) DEFAULT NULL,
  `Paid_Amount` int(6) DEFAULT NULL,
  `Product_Quantity` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=177 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `consumer_bill_paid_report`
--

DROP TABLE IF EXISTS `consumer_bill_paid_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `consumer_bill_paid_report` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `Branch name and code` varchar(20) DEFAULT NULL,
  `Purchase Price` int(6) DEFAULT NULL,
  `Purchase Price (Trans Rec Prod)` int(5) DEFAULT NULL,
  `Payable Amount to HO` int(6) DEFAULT NULL,
  `Paid Amount (MIS)` int(6) DEFAULT NULL,
  `Balance` int(6) DEFAULT NULL,
  `Paid Amount (Accounts)` int(7) DEFAULT NULL,
  `Paid Amount (HO)` int(7) DEFAULT NULL,
  `Varience MIS & AIS` int(7) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `consumer_bill_paid_report2`
--

DROP TABLE IF EXISTS `consumer_bill_paid_report2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `consumer_bill_paid_report2` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `Branch Name` varchar(20) DEFAULT NULL,
  `Brand Name` varchar(6) DEFAULT NULL,
  `Product Category` varchar(15) DEFAULT NULL,
  `Shiped Quantity` int(2) DEFAULT NULL,
  `Recieved Quantity` int(2) DEFAULT NULL,
  `Non-Rec Quantity` int(1) DEFAULT NULL,
  `Purchase Price` int(6) DEFAULT NULL,
  `Paid Amount` int(6) DEFAULT NULL,
  `Due Amount` int(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `consumer_branch_report`
--

DROP TABLE IF EXISTS `consumer_branch_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `consumer_branch_report` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `Branch name and code` varchar(20) DEFAULT NULL,
  `Purchase Price` int(6) DEFAULT NULL,
  `Purchase Price (Trans Rec Prod)` int(5) DEFAULT NULL,
  `Payble Amount to HO` int(6) DEFAULT NULL,
  `Paid Amount (MIS)` int(6) DEFAULT NULL,
  `Balance` int(6) DEFAULT NULL,
  `Paid Amount (Accounts)` int(7) DEFAULT NULL,
  `Paid Amount (HO)` int(7) DEFAULT NULL,
  `Varience MIS & AIS` int(7) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `consumer_order`
--

DROP TABLE IF EXISTS `consumer_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `consumer_order` (
  `id` int(3) DEFAULT NULL,
  `Branch name` varchar(12) DEFAULT NULL,
  `Order ID` int(8) DEFAULT NULL,
  `Order Date` varchar(10) DEFAULT NULL,
  `Distributor Name` varchar(6) DEFAULT NULL,
  `Product Name` varchar(15) DEFAULT NULL,
  `Model No` varchar(24) DEFAULT NULL,
  `Order Status` varchar(14) DEFAULT NULL,
  `Product Price` int(5) DEFAULT NULL,
  `Order Quantity` int(1) DEFAULT NULL,
  `Shiped Quantity` int(1) DEFAULT NULL,
  `Received Quantity` int(1) DEFAULT NULL,
  `Purchase Price` varchar(6) DEFAULT NULL,
  `Status Change Date` varchar(10) DEFAULT NULL,
  `Bill Pay` varchar(6) DEFAULT NULL,
  `Remarks` varchar(18) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `consumer_product`
--

DROP TABLE IF EXISTS `consumer_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `consumer_product` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `idcode` int(4) DEFAULT NULL,
  `Branch name` varchar(11) DEFAULT NULL,
  `Product Recieved Date` varchar(10) DEFAULT NULL,
  `Challan/Bill No` varchar(20) DEFAULT NULL,
  `Order ID` varchar(8) DEFAULT NULL,
  `Recieved ID` int(7) DEFAULT NULL,
  `Distributor Name` varchar(6) DEFAULT NULL,
  `Product Name` varchar(14) DEFAULT NULL,
  `Model No` varchar(24) DEFAULT NULL,
  `Product Status` varchar(10) DEFAULT NULL,
  `Product Purchase Price` int(5) DEFAULT NULL,
  `Product Salable Price` int(5) DEFAULT NULL,
  `Staff Comission` int(3) DEFAULT NULL,
  `Purchase/Transfer` varchar(10) DEFAULT NULL,
  `Sale` varchar(4) DEFAULT NULL,
  `Return` varchar(6) DEFAULT NULL,
  `Transfer` varchar(8) DEFAULT NULL,
  `Service Req` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `consumer_trans_from`
--

DROP TABLE IF EXISTS `consumer_trans_from`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `consumer_trans_from` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `Transfer Date` varchar(10) DEFAULT NULL,
  `Transfer Code` int(7) DEFAULT NULL,
  `Product Code` int(7) DEFAULT NULL,
  `Order ID` int(8) DEFAULT NULL,
  `Model No` varchar(24) DEFAULT NULL,
  `Transfer to Branch` varchar(12) DEFAULT NULL,
  `Transfer From Branch` varchar(11) DEFAULT NULL,
  `Bank Name and Branch` varchar(27) DEFAULT NULL,
  `Check No` varchar(8) DEFAULT NULL,
  `Check Amount` int(5) DEFAULT NULL,
  `Transfer Status` varchar(8) DEFAULT NULL,
  `Product quantity` int(1) DEFAULT NULL,
  `Product Purchase Price` int(5) DEFAULT NULL,
  `Distributor Name` varchar(6) DEFAULT NULL,
  `Product Category` varchar(12) DEFAULT NULL,
  `transfer details` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `consumer_trans_this`
--

DROP TABLE IF EXISTS `consumer_trans_this`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `consumer_trans_this` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `Transfer Date` varchar(10) DEFAULT NULL,
  `Transfer Code` int(7) DEFAULT NULL,
  `Product Code` int(7) DEFAULT NULL,
  `Order ID` int(8) DEFAULT NULL,
  `Transfer to Branch` varchar(17) DEFAULT NULL,
  `Transfer From Branch` varchar(11) DEFAULT NULL,
  `Bank Name and Branch` varchar(27) DEFAULT NULL,
  `Check No` varchar(8) DEFAULT NULL,
  `Check Amount` int(5) DEFAULT NULL,
  `Transfer Status` varchar(8) DEFAULT NULL,
  `Product Quantity` int(1) DEFAULT NULL,
  `Product Purchase Price` int(5) DEFAULT NULL,
  `Distributor Name` varchar(6) DEFAULT NULL,
  `Product Category` varchar(14) DEFAULT NULL,
  `transfer details` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `consumer_transaction_from_extra`
--

DROP TABLE IF EXISTS `consumer_transaction_from_extra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `consumer_transaction_from_extra` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `Branch name` varchar(11) DEFAULT NULL,
  `Order ID` int(8) DEFAULT NULL,
  `Order Date` varchar(10) DEFAULT NULL,
  `Distributor Name` varchar(6) DEFAULT NULL,
  `Product Name` varchar(12) DEFAULT NULL,
  `Model No` varchar(20) DEFAULT NULL,
  `Order Status` varchar(7) DEFAULT NULL,
  `Product Price` int(5) DEFAULT NULL,
  `Order Quantity` int(1) DEFAULT NULL,
  `Shiped Quantity` int(1) DEFAULT NULL,
  `Received Quantity` int(1) DEFAULT NULL,
  `Purchase Price` varchar(10) DEFAULT NULL,
  `Status Change Date` varchar(10) DEFAULT NULL,
  `Bill Pay` varchar(6) DEFAULT NULL,
  `Remarks` varchar(18) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-08-28  9:14:47
