-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2022 at 11:57 PM
-- Server version: 5.7.17
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `g4udatabase`
--

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `orderID` int(11) NOT NULL,
  `staffID` varchar(10) NOT NULL,
  `orderDate` datetime NOT NULL,
  `receiveDate` datetime DEFAULT NULL,
  `authoriserStaffID1` varchar(10) DEFAULT NULL,
  `authoriserStaffID2` varchar(10) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `orderType` enum('Singular','Monthly','Weekly') NOT NULL,
  `authoriser1AuthTime` datetime DEFAULT NULL,
  `authoriser2AuthTime` datetime DEFAULT NULL,
  `authoriser1Status` enum('Approved','Denied','Awaiting Approval','') NOT NULL DEFAULT 'Awaiting Approval',
  `authoriser2Status` enum('Approved','Denied','Awaiting Approval','') NOT NULL DEFAULT 'Awaiting Approval'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`orderID`, `staffID`, `orderDate`, `receiveDate`, `authoriserStaffID1`, `authoriserStaffID2`, `state`, `orderType`, `authoriser1AuthTime`, `authoriser2AuthTime`, `authoriser1Status`, `authoriser2Status`) VALUES
(1, 'BRE510', '2022-03-16 11:52:39', NULL, 'GRE056', 'SYSTEM', 'Declined', 'Singular', '2022-03-16 16:52:20', '2022-04-04 19:28:32', 'Denied', 'Approved'),
(2, 'GRE056', '2022-03-16 14:41:55', NULL, 'GRE056', 'SYSTEM', 'Pending', 'Singular', '2022-03-16 17:43:42', '2022-04-04 19:27:58', 'Approved', 'Approved'),
(3, 'GRE056', '2022-02-16 14:44:40', NULL, 'GRE056', 'SYSTEM', 'Declined', 'Singular', '2022-03-16 17:46:26', '2022-04-04 20:26:35', 'Denied', 'Approved'),
(6, 'SYSTEM', '2022-03-23 00:00:00', NULL, NULL, NULL, 'Pending', 'Singular', NULL, NULL, 'Awaiting Approval', 'Awaiting Approval'),
(7, 'SYSTEM', '2022-03-23 00:00:00', NULL, NULL, NULL, 'Pending', 'Singular', NULL, NULL, 'Awaiting Approval', 'Awaiting Approval'),
(8, 'SYSTEM', '2022-03-23 00:00:00', NULL, NULL, NULL, 'Pending', 'Singular', NULL, NULL, 'Awaiting Approval', 'Awaiting Approval'),
(9, 'SYSTEM', '2022-03-23 00:00:00', NULL, NULL, NULL, 'Pending', 'Singular', NULL, NULL, 'Awaiting Approval', 'Awaiting Approval'),
(10, 'GRE056', '2022-04-04 13:03:59', NULL, 'GRE056', 'SYSTEM', 'Pending', 'Singular', '2022-04-04 13:04:12', '2022-04-04 18:50:04', 'Approved', 'Approved'),
(11, 'MAH042', '2022-04-05 16:51:45', NULL, NULL, NULL, 'Pending', 'Singular', NULL, NULL, 'Awaiting Approval', 'Awaiting Approval');

--
-- Triggers `order`
--
DELIMITER $$
CREATE TRIGGER `changed authorisation status` BEFORE UPDATE ON `order` FOR EACH ROW BEGIN

IF (NEW.`authoriser2Status` = 'Denied' OR NEW.`authoriser1Status` = 'Denied') THEN
SET NEW.state := "Declined";
ELSEIF (NEW.`authoriser2Status` = 'Approved' AND NEW.`authoriser1Status` = 			'Approved') THEN
SET NEW.state := "Approved";
END IF;



 END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `productID` varchar(7) NOT NULL,
  `productType` varchar(45) NOT NULL,
  `productName` varchar(45) NOT NULL,
  `productDescription` text NOT NULL,
  `stock` int(11) NOT NULL,
  `minimumOrderAmount` int(11) NOT NULL,
  `stockToReorderAt` int(11) NOT NULL,
  `imageDirectory` varchar(100) NOT NULL,
  `orderPlacedBySystem` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productID`, `productType`, `productName`, `productDescription`, `stock`, `minimumOrderAmount`, `stockToReorderAt`, `imageDirectory`, `orderPlacedBySystem`) VALUES
('FP59', 'Toys', 'Funko Pop! Disney: Frozen 2 - Olaf', 'An Olaf funko pop from the Disney film Frozen 2.', 50000, 100, 200, 'olaf_funko_pop.jpg', 0),
('KST01', 'Toys', 'KLIKBOT Studio Thud', 'A klikbot studio thud toy designed to be suitable for all age groups.', 400, 200, 100, 'kilkbot_studio_thud.jpg', 0),
('LEX95', 'Toys', 'LEGO Classic Bricks and Ideas - 11001', 'Lego brick set - LEGO Classic Bricks and Ideas 11001', 400, 200, 200, 'lego_11001.jpg', 0),
('NRF10', 'Toys', 'Nerf N-Strike Elite Disruptor', 'The Nerf N-Strike Elite disruptor from the new series', 400, 200, 100, 'nerf_disruptor.jpg', 0),
('PIN00', 'Toys', 'Plan Toys Pinball', 'The plan toys pinball is a pinball machine you can play with on the go.', 200, 100, 50, 'pinball.jpg', 0),
('POL03', 'Gadgets', 'Polaroid Play 3D Pen', 'A polaroid 3D pen', 500, 500, 200, 'polaroid pen.jpg', 0),
('PPF03', 'Gadgets', 'Portable Personal Fan', 'A personal, portable fan useful for warm summer days.', 500, 500, 200, 'portable fan.jpg', 0),
('PWR41', 'Gadgets', 'USB Power Bank 10000mAh', 'A USB power bank -10000mAh', 3000, 500, 200, 'USB Power Bank 10000mAh.jpg', 0),
('PWR43', 'Gadgets', 'USB Power Bank 20000mAh', 'A USB Power bank - 20000mAh', 500, 500, 200, 'USB Power Bank 10000mAh.jpg', 0),
('PWR44', 'Gadgets', 'USB Power Bank 25800mAh', 'A USB Power bank - 25800mAh', 500, 500, 200, 'USB Power Bank 10000mAh.jpg', 0),
('SC01', 'Gadgets', 'Spider Catcher', 'The perfect tool to keep the spiders away from you without harming them.', 500, 500, 200, 'spider catcher.jpg', 0),
('SW08', 'Gadgets', 'Star Wars USB Cup Warmer BB-8', 'A star wars cup warmer styled in the shape of BB-8', 500, 500, 200, 'bb8 star wars.jpg', 0);

--
-- Triggers `product`
--
DELIMITER $$
CREATE TRIGGER `Allow System to make order after stock exceeds min stock` BEFORE UPDATE ON `product` FOR EACH ROW BEGIN

IF(NEW.stock >= NEW.stockToReorderAt) THEN
    SET NEW.orderPlacedBySystem=0;
END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `Auto create order when stock < minimumStock` AFTER UPDATE ON `product` FOR EACH ROW BEGIN

SET @numberOfLowStockProducts = (Select count(productID) from `product` WHERE product.stock <= product.stockToReorderAt AND product.orderPlacedBySystem=0);

IF(@numberOfLowStockProducts >0) THEN

SET @productID = (Select productID from `product` WHERE product.stock <= product.stockToReorderAt AND product.orderPlacedBySystem=0 LIMIT 1);

SET @todayDate = CURDATE();

SET @minOrderAmt = (Select minimumOrderAmount from `product` WHERE product.productID = @productID);

SET @supplierProductID = (SELECT supplierProductID from `supplierproduct` WHERE supplierproduct.productID = @productID ORDER BY `supplierproduct`.deliveryTimeInWorkingDays ASC LIMIT 1);

INSERT INTO `order`(staffID,orderDate,state)
VALUES('SYSTEM',@todayDate,'Pending');

SET @LastInsertedPrimKey = (SELECT LAST_INSERT_ID());

SET @individualPrice = (SELECT price from supplierproduct WHERE supplierproduct.supplierProductID = @supplierProductID);

SET @priceSubTotal = CAST((@individualPrice * @minOrderAmt) AS DECIMAL(16,2));

INSERT INTO `productorder` (`orderID`,`productID`,`quantity`,`priceOnPurchase`,`supplierProductID`) VALUES(@LastInsertedPrimKey, @productID, @minOrderAmt,@priceSubTotal,@supplierProductID);
END IF;


END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `productorder`
--

CREATE TABLE `productorder` (
  `productOrderID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `productID` varchar(7) NOT NULL,
  `quantity` int(11) NOT NULL,
  `priceOnPurchase` decimal(16,2) NOT NULL,
  `supplierProductID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `productorder`
--

INSERT INTO `productorder` (`productOrderID`, `orderID`, `productID`, `quantity`, `priceOnPurchase`, `supplierProductID`) VALUES
(1, 1, 'LEX95', 500, '3840.00', 24),
(2, 2, 'LEX95', 200, '1600.00', 25),
(3, 3, 'LEX95', 200, '1536.00', 24),
(4, 3, 'FP59', 123, '922.50', 22),
(5, 5, 'FP59', 100, '7.50', 22),
(6, 6, 'FP59', 100, '7.50', 22),
(7, 7, 'FP59', 100, '7.50', 22),
(8, 8, 'PWR41', 500, '9.95', 1),
(9, 9, 'PWR41', 500, '4975.00', 1),
(10, 10, 'FP59', 100, '750.00', 22),
(11, 10, 'FP59', 100, '750.00', 22),
(12, 11, 'FP59', 100, '750.00', 22);

-- --------------------------------------------------------

--
-- Table structure for table `producttype`
--

CREATE TABLE `producttype` (
  `productType` varchar(45) NOT NULL,
  `staffID` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `producttype`
--

INSERT INTO `producttype` (`productType`, `staffID`) VALUES
('Toys', 'MAH042'),
('Gadgets', 'PAT201');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staffID` varchar(10) NOT NULL,
  `title` enum('Mr','Miss','Sir','Ms') NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `jobTitle` varchar(75) NOT NULL,
  `viewOrderPrivilege` tinyint(1) NOT NULL DEFAULT '0',
  `password` varchar(100) NOT NULL,
  `managingUserPermsAuthorisation` tinyint(1) NOT NULL DEFAULT '0',
  `orderAuthPermission` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staffID`, `title`, `firstName`, `lastName`, `jobTitle`, `viewOrderPrivilege`, `password`, `managingUserPermsAuthorisation`, `orderAuthPermission`) VALUES
('BRE510', 'Mr', 'Jason', 'Brentwood', 'Senior SalesPerson GT', 0, '123', 0, 0),
('DUN021', 'Ms', 'Sarah', 'Dunkley', 'CEO PG4U', 1, '123', 0, 0),
('GRE056', 'Ms', 'Ann', 'Greengold', 'Assistant QA Controller ACC Dept', 1, '123', 1, 1),
('GRE123', 'Miss', 'Jennifer', 'Green', 'Sales Assistant GT', 1, '123', 0, 1),
('HID001', 'Sir', 'Adrian', 'Hidcote-Armstrong', 'MID & Chairman of G4U Board', 1, '123', 1, 1),
('MAH042', 'Mr', 'Mustafa', 'Mahmood', 'Sales Assistant GT', 0, '123', 0, 0),
('PAT201', 'Ms', 'Amanda', 'Patel', 'Sales Assistant GT', 1, '123', 0, 0),
('PIA412', 'Mr', 'Enrico', 'Piam', 'Sales Assistant GT', 0, '123', 0, 0),
('PIT101', 'Mr', 'Derek', 'Pitts', 'Sales Assistant GT', 0, '123', 0, 0),
('SYSTEM', 'Mr', 'Gadgets', '4U', 'G4U System AI', 1, '123', 1, 1),
('VER121', 'Mr', 'John', 'Vermont', 'Mgr PG4U GT Dept.', 1, '123', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplierID` char(2) NOT NULL,
  `supplierName` varchar(90) NOT NULL,
  `address1` tinytext NOT NULL,
  `address2` tinytext,
  `city` varchar(85) NOT NULL,
  `postcode` varchar(14) NOT NULL,
  `country` varchar(56) NOT NULL,
  `county` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplierID`, `supplierName`, `address1`, `address2`, `city`, `postcode`, `country`, `county`) VALUES
('BI', 'Bitmore Inc', 'Park House', '15-19 Greenhill Crescent', 'Watford Business Park', 'WD18 8PH', 'England', 'Hertfordshire'),
('BS', 'BrainStorm Ltd', 'Mill Lane', 'BrainStorm Limited Unit 1A', 'Gisburn', 'BB7 4LN', 'UK', 'Lancashire'),
('CT', 'Cottage Toys', 'Spitfire Business Park', 'Unit 11 Hawker Road', 'Croydon', 'CR0 4WD', 'England', 'Surrey'),
('SH', 'Shenzhen Housing Technology Development Co.,Ltd.', 'Weixinda Industrial Par', 'Caowei Xixiang Baoan District', 'Shenzhen', '518128', 'China', 'Guangdong');

-- --------------------------------------------------------

--
-- Table structure for table `supplierproduct`
--

CREATE TABLE `supplierproduct` (
  `supplierProductID` int(11) NOT NULL,
  `productID` varchar(7) NOT NULL,
  `supplierID` char(2) NOT NULL,
  `deliveryTimeInWorkingDays` int(11) NOT NULL,
  `price` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `supplierproduct`
--

INSERT INTO `supplierproduct` (`supplierProductID`, `productID`, `supplierID`, `deliveryTimeInWorkingDays`, `price`) VALUES
(1, 'PWR41', 'BI', 5, '9.95'),
(2, 'PWR41', 'BS', 5, '9.95'),
(3, 'PWR43', 'BI', 5, '18.99'),
(4, 'PWR43', 'BS', 5, '18.64'),
(5, 'PWR44', 'BI', 5, '19.99'),
(6, 'PWR44', 'BS', 5, '19.00'),
(7, 'SC01', 'SH', 20, '1.58'),
(8, 'SC01', 'BS', 3, '1.99'),
(9, 'PPF03', 'BI', 5, '5.65'),
(10, 'PPF03', 'SH', 15, '4.80'),
(11, 'SW08', 'BS', 5, '10.99'),
(12, 'SW08', 'BI', 4, '9.99'),
(13, 'SW08', 'SH', 25, '9.85'),
(14, 'POL03', 'SH', 25, '22.00'),
(15, 'POL03', 'BS', 4, '28.59'),
(16, 'NRF10', 'BI', 12, '12.99'),
(17, 'NRF10', 'SH', 30, '10.50'),
(18, 'KST01', 'BS', 12, '9.95'),
(19, 'KST01', 'SH', 30, '7.50'),
(20, 'PIN00', 'BI', 5, '40.00'),
(21, 'PIN00', 'CT', 6, '38.25'),
(22, 'FP59', 'SH', 5, '7.50'),
(23, 'FP59', 'CT', 6, '7.10'),
(24, 'LEX95', 'SH', 10, '7.68'),
(25, 'LEX95', 'CT', 5, '8.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `order staffid to staff staffid_idx` (`staffID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productID`),
  ADD KEY `product productType to productType_idx` (`productType`);

--
-- Indexes for table `productorder`
--
ALTER TABLE `productorder`
  ADD PRIMARY KEY (`productOrderID`),
  ADD KEY `productorder productid to product productid_idx` (`productID`),
  ADD KEY `supplierProductID` (`supplierProductID`),
  ADD KEY `supplierProductID_2` (`supplierProductID`);

--
-- Indexes for table `producttype`
--
ALTER TABLE `producttype`
  ADD PRIMARY KEY (`productType`),
  ADD KEY `producttype staffid to staff staffid_idx` (`staffID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staffID`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplierID`);

--
-- Indexes for table `supplierproduct`
--
ALTER TABLE `supplierproduct`
  ADD PRIMARY KEY (`supplierProductID`),
  ADD KEY `supplierproduct productid to product productID_idx` (`productID`),
  ADD KEY `supplierproduct supplierid to supplier supplierid_idx` (`supplierID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `productorder`
--
ALTER TABLE `productorder`
  MODIFY `productOrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `supplierproduct`
--
ALTER TABLE `supplierproduct`
  MODIFY `supplierProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order staffid to staff staffid` FOREIGN KEY (`staffID`) REFERENCES `staff` (`staffID`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product productType to productType` FOREIGN KEY (`productType`) REFERENCES `producttype` (`productType`);

--
-- Constraints for table `productorder`
--
ALTER TABLE `productorder`
  ADD CONSTRAINT `productorder productid to product productid` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`),
  ADD CONSTRAINT `productorder supplierproductid to supplierproduct` FOREIGN KEY (`supplierProductID`) REFERENCES `supplierproduct` (`supplierProductID`);

--
-- Constraints for table `producttype`
--
ALTER TABLE `producttype`
  ADD CONSTRAINT `producttype staffid to staff staffid` FOREIGN KEY (`staffID`) REFERENCES `staff` (`staffID`);

--
-- Constraints for table `supplierproduct`
--
ALTER TABLE `supplierproduct`
  ADD CONSTRAINT `supplierproduct productid to product productID` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`),
  ADD CONSTRAINT `supplierproduct supplierid to supplier supplierid` FOREIGN KEY (`supplierID`) REFERENCES `supplier` (`supplierID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
