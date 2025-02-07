-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 07, 2025 at 08:50 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bandochoi`
--

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `BillID` int NOT NULL,
  `CustomerID` int NOT NULL,
  `CreateTime` date DEFAULT NULL,
  `UpdateTime` date DEFAULT NULL,
  `Subtotal` double NOT NULL,
  `Total` double NOT NULL,
  `Address` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `payment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill`
--

INSERT INTO `bill` (`BillID`, `CustomerID`, `CreateTime`, `UpdateTime`, `Subtotal`, `Total`, `Address`, `payment`, `status`) VALUES
(9, 3, '2025-02-06', '2025-02-06', 600000, 600000, 'sadad', 'Paypal', 'Đã Nhận Hàng'),
(10, 3, '2025-02-06', '2025-02-06', 800000, 800000, 'sdad', 'VisaCard', 'Đã Nhận Hàng');

-- --------------------------------------------------------

--
-- Table structure for table `billdetail`
--

CREATE TABLE `billdetail` (
  `BillID` int NOT NULL,
  `ProductID` int NOT NULL,
  `Quantity` int NOT NULL,
  `Unitprice` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `billdetail`
--

INSERT INTO `billdetail` (`BillID`, `ProductID`, `Quantity`, `Unitprice`) VALUES
(9, 2, 1, 600000),
(10, 2, 1, 600000),
(10, 3, 1, 200000);

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `BrandID` int NOT NULL,
  `BrandName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`BrandID`, `BrandName`, `Status`) VALUES
(1, 'Among Us', 0),
(2, 'Lego', 0),
(3, 'One Piece', 0),
(4, 'Pokemon', 0),
(5, 'MINIONS', 0),
(6, 'HOT WHEELS', 1);

-- --------------------------------------------------------

--
-- Table structure for table `carousel`
--

CREATE TABLE `carousel` (
  `sr_no` int NOT NULL,
  `image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carousel`
--

INSERT INTO `carousel` (`sr_no`, `image`) VALUES
(1, '1.webp'),
(2, '2.webp'),
(3, '3.webp'),
(4, '4.webp');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `TypeID` int NOT NULL,
  `TypeName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`TypeID`, `TypeName`, `Status`) VALUES
(1, 'Đồ chơi theo phim', 0),
(2, 'Đồ chơi lắp ghép', 1),
(3, 'Đồ chơi phương tiện', 0);

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `create_at` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `name`, `email`, `phone`, `subject`, `message`, `create_at`, `status`) VALUES
(1, 'tuấn', 'huynhngoctuan48@gmail.com', '0938124402', 'none', 'sad', '2025-01-01', 1),
(2, 'Nam', 'huynhngoctuan48@gmail.com', '0938124402', 'none', '?????', '2025-01-19', 0);

-- --------------------------------------------------------

--
-- Table structure for table `import`
--

CREATE TABLE `import` (
  `ImportID` int NOT NULL,
  `SupplierID` int NOT NULL,
  `StaffID` int DEFAULT NULL,
  `CreateTime` date DEFAULT NULL,
  `UpdateTime` date DEFAULT NULL,
  `Total` int DEFAULT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:Chờ duyệt, 1:Đã Duyệt, 2:Hủy'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `import`
--

INSERT INTO `import` (`ImportID`, `SupplierID`, `StaffID`, `CreateTime`, `UpdateTime`, `Total`, `Status`) VALUES
(11, 1, NULL, '2025-02-06', '2025-02-06', 150000000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `importdetail`
--

CREATE TABLE `importdetail` (
  `ImportID` int NOT NULL,
  `ProductID` int NOT NULL,
  `Quantity` int NOT NULL,
  `Unitprice` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `importdetail`
--

INSERT INTO `importdetail` (`ImportID`, `ProductID`, `Quantity`, `Unitprice`) VALUES
(11, 1, 100, 400000),
(11, 2, 100, 500000),
(11, 3, 100, 600000);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `ProductID` int NOT NULL,
  `ProductName` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Quantity` int NOT NULL DEFAULT '0',
  `ProductPrice` int NOT NULL,
  `TypeID` int NOT NULL,
  `BrandID` int NOT NULL,
  `Description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Age` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Origin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Gender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `IMG` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `create_at` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`ProductID`, `ProductName`, `Quantity`, `ProductPrice`, `TypeID`, `BrandID`, `Description`, `Age`, `Origin`, `Gender`, `IMG`, `create_at`, `status`, `deleted`) VALUES
(1, 'Đồ chơi lắp ráp Chiến giáp của Cole LEGO NINJAGO 71806', 100, 500000, 2, 2, 'Nothing', '8 tuổi trở lên', 'Việt Nam', 'Boy', 'images/products/PROD_1738849194.webp', '2025-01-14', 0, 0),
(2, 'Đồ Chơi Lắp Ráp Siêu xe McLaren F1 LEGO SPEED CHAMPIONS 76919', 95, 600000, 2, 2, 'Nothing', '9 tuổi trở lên', 'Trung Quốc', 'Boy', 'images/products/PROD_1738849226.webp', '2025-01-14', 0, 0),
(3, 'Pokemon Thư Giãn - Pikachu POKEMON TOYS SF82206-2', 99, 200000, 1, 4, 'Nothing', '3 tuổi trở lên', 'Nhật Bản', 'Boy', 'images/products/PROD_1738849164.webp', '2025-01-14', 0, 0),
(4, 'Xe Trái Chuối Stuart Có Chạy Trớn MINIONS FW033A', 0, 250000, 3, 5, 'Nothing', '3 tuổi trở lên', 'Mỹ', 'Unisex', 'images/products/PROD_1738849031.webp', '2025-01-14', 0, 0),
(5, 'Minions Trượt VĐèn, Âm Thanh Và Chạy Trớn MINIONS FW038', 0, 350000, 3, 5, '????', '3', 'Mỹ', 'Unisex', 'images/products/PROD_1738849067.webp', '2025-01-17', 0, 0),
(6, 'Đồ Chơi Mô Hình Siêu Xe Đổi Màu HOT WHEELS GYP13', 0, 1000000, 3, 6, '???', '3', 'Mỹ', 'Unisex', 'images/products/PROD_1738849265.webp', '2025-02-01', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `RoleID` int NOT NULL,
  `RoleName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`RoleID`, `RoleName`) VALUES
(1, 'Khách hàng'),
(2, 'Nhân viên bán hàng'),
(3, 'Admin'),
(4, 'Nhân viên kho'),
(5, 'Quản lý');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `SupplierID` int NOT NULL,
  `SupplierName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Phone` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `FaxNumber` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`SupplierID`, `SupplierName`, `Address`, `Phone`, `FaxNumber`, `status`, `deleted`) VALUES
(1, 'ABC', 'sgu,273', '0938124402', '0938124402', 0, 0),
(2, 'ABCD', 'sgu', '0938124401', '0938124401', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `supplierdetail`
--

CREATE TABLE `supplierdetail` (
  `SupplierID` int NOT NULL,
  `ProductID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplierdetail`
--

INSERT INTO `supplierdetail` (`SupplierID`, `ProductID`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(2, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int NOT NULL,
  `UserName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `RoleID` int NOT NULL,
  `FullName` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Phone` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Gender` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `create_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` datetime DEFAULT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `UserName`, `Password`, `RoleID`, `FullName`, `Phone`, `Email`, `Gender`, `Address`, `create_at`, `update_at`, `Status`, `deleted`) VALUES
(2, 'admin123', '$2y$10$y4JeyVjKDP4xnK3GbC0huOZGFjcu2zzAWwCvfILzR7QSQ03x0uu8y', 3, 'Admin', '0909090909', 'admin123@gmail.com', 'm', 'sgu', '2025-01-14 16:42:39', NULL, 0, 0),
(3, 'manage12345', '$2y$10$y4JeyVjKDP4xnK3GbC0huOZGFjcu2zzAWwCvfILzR7QSQ03x0uu8y', 5, 'Nguyễn Văn A', '0938124409', 'nva@gmail.com', '', 'sgu,273', '2025-01-14 00:00:00', NULL, 0, 0),
(4, 'nvb123', '$2y$10$y4JeyVjKDP4xnK3GbC0huOZGFjcu2zzAWwCvfILzR7QSQ03x0uu8y', 2, 'Nguyễn Văn B', '0938124403', 'nvb@gmail.com', 'm', 'sgu', '2025-01-16 00:00:00', NULL, 0, 1),
(5, 'nvc123', '$2y$10$y4JeyVjKDP4xnK3GbC0huOZGFjcu2zzAWwCvfILzR7QSQ03x0uu8y', 4, 'Nguyễn Văn C', '0938124407', 'nvc@gmail.com', '', 'sgu', '2025-01-16 00:00:00', NULL, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `voucher`
--

CREATE TABLE `voucher` (
  `VoucherID` int NOT NULL,
  `VoucherCode` varchar(255) NOT NULL,
  `VoucherType` int NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Cart_value` int DEFAULT NULL,
  `DiscountValue` double NOT NULL,
  `UsageLimit` int NOT NULL,
  `UsageCount` int DEFAULT '0',
  `Status` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `voucher`
--

INSERT INTO `voucher` (`VoucherID`, `VoucherCode`, `VoucherType`, `Description`, `Cart_value`, `DiscountValue`, `UsageLimit`, `UsageCount`, `Status`, `deleted`) VALUES
(1, 'ABCXYZ123', 2, 'MỪNG 2024', 3000000, 10, 10, 0, 0, 0),
(2, 'ABCXYZ124', 1, 'MỪNG SINH NHẬT', 500000, 20000, 10, 0, 0, 0),
(3, 'NAMMOI2025', 2, 'NOTHING', 5000000, 50, 500, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `voucherdetail`
--

CREATE TABLE `voucherdetail` (
  `VoucherID` int NOT NULL,
  `UserID` int NOT NULL,
  `BillID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`BillID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `BillID` (`BillID`,`CustomerID`);

--
-- Indexes for table `billdetail`
--
ALTER TABLE `billdetail`
  ADD PRIMARY KEY (`BillID`,`ProductID`) USING BTREE,
  ADD KEY `ProductID` (`ProductID`),
  ADD KEY `BillID` (`BillID`),
  ADD KEY `BillID_2` (`BillID`);

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`BrandID`);

--
-- Indexes for table `carousel`
--
ALTER TABLE `carousel`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`TypeID`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `import`
--
ALTER TABLE `import`
  ADD PRIMARY KEY (`ImportID`),
  ADD KEY `SupplierID` (`SupplierID`),
  ADD KEY `StaffID` (`StaffID`);

--
-- Indexes for table `importdetail`
--
ALTER TABLE `importdetail`
  ADD PRIMARY KEY (`ImportID`,`ProductID`) USING BTREE,
  ADD KEY `ImportID` (`ProductID`),
  ADD KEY `ImportID_2` (`ImportID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`ProductID`),
  ADD KEY `TypeID` (`TypeID`),
  ADD KEY `BrandID` (`BrandID`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`RoleID`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`SupplierID`);

--
-- Indexes for table `supplierdetail`
--
ALTER TABLE `supplierdetail`
  ADD PRIMARY KEY (`SupplierID`,`ProductID`),
  ADD KEY `SupplierID` (`SupplierID`),
  ADD KEY `SupplierID_2` (`ProductID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD KEY `RoleID` (`RoleID`);

--
-- Indexes for table `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`VoucherID`);

--
-- Indexes for table `voucherdetail`
--
ALTER TABLE `voucherdetail`
  ADD PRIMARY KEY (`VoucherID`,`UserID`,`BillID`) USING BTREE,
  ADD KEY `fk_voucherdetail_voucher` (`VoucherID`),
  ADD KEY `fk_voucherdetail_user` (`UserID`),
  ADD KEY `fk_voucherdetail_bill` (`BillID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bill`
--
ALTER TABLE `bill`
  MODIFY `BillID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `BrandID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `carousel`
--
ALTER TABLE `carousel`
  MODIFY `sr_no` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `TypeID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `import`
--
ALTER TABLE `import`
  MODIFY `ImportID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `ProductID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `RoleID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `SupplierID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `voucher`
--
ALTER TABLE `voucher`
  MODIFY `VoucherID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bill`
--
ALTER TABLE `bill`
  ADD CONSTRAINT `bill_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `billdetail`
--
ALTER TABLE `billdetail`
  ADD CONSTRAINT `billdetail_ibfk_1` FOREIGN KEY (`BillID`) REFERENCES `bill` (`BillID`),
  ADD CONSTRAINT `billdetail_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`);

--
-- Constraints for table `import`
--
ALTER TABLE `import`
  ADD CONSTRAINT `import_ibfk_1` FOREIGN KEY (`SupplierID`) REFERENCES `supplier` (`SupplierID`),
  ADD CONSTRAINT `import_ibfk_2` FOREIGN KEY (`StaffID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `importdetail`
--
ALTER TABLE `importdetail`
  ADD CONSTRAINT `importdetail_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`),
  ADD CONSTRAINT `importdetail_ibfk_2` FOREIGN KEY (`ImportID`) REFERENCES `import` (`ImportID`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`TypeID`) REFERENCES `category` (`TypeID`),
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`BrandID`) REFERENCES `brand` (`BrandID`);

--
-- Constraints for table `supplierdetail`
--
ALTER TABLE `supplierdetail`
  ADD CONSTRAINT `supplierdetail_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`),
  ADD CONSTRAINT `supplierdetail_ibfk_2` FOREIGN KEY (`SupplierID`) REFERENCES `supplier` (`SupplierID`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`RoleID`) REFERENCES `role` (`RoleID`);

--
-- Constraints for table `voucherdetail`
--
ALTER TABLE `voucherdetail`
  ADD CONSTRAINT `fk_voucherdetail_bill` FOREIGN KEY (`BillID`) REFERENCES `bill` (`BillID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_voucherdetail_user` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_voucherdetail_voucher` FOREIGN KEY (`VoucherID`) REFERENCES `voucher` (`VoucherID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
