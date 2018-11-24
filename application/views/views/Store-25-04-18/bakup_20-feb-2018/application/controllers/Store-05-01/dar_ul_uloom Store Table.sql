-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 21, 2017 at 06:31 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dar_ul_uloom`
--

-- --------------------------------------------------------

--
-- Table structure for table `donation_type`
--

CREATE TABLE `donation_type` (
  `Id` int(3) NOT NULL,
  `Donation_Type` varchar(225) COLLATE utf8_unicode_ci NOT NULL,
  `CreatedBy` bigint(20) DEFAULT NULL,
  `CreatedOn` datetime DEFAULT NULL,
  `UpdatedBy` bigint(20) DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_categories`
--

CREATE TABLE `item_categories` (
  `Id` bigint(60) NOT NULL,
  `Code` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `Name` varchar(225) COLLATE utf8_unicode_ci NOT NULL,
  `Parent_Id` int(60) DEFAULT NULL,
  `CreatedBy` int(60) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `UpdatedBy` int(60) DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_demand_approve`
--

CREATE TABLE `item_demand_approve` (
  `Id` int(20) NOT NULL,
  `Demand_form_id` int(20) NOT NULL,
  `Approve_dateG` date NOT NULL,
  `Approve_dateH` date NOT NULL,
  `CreatedBy` int(20) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `UpdatedBy` int(20) DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_demand_approve_details`
--

CREATE TABLE `item_demand_approve_details` (
  `Id` int(20) NOT NULL,
  `Demand_approve_id` int(20) NOT NULL,
  `Item_id` int(20) NOT NULL,
  `Approve_Quantity` double(5,2) NOT NULL,
  `CreatedBy` int(20) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `UpdatedBy` int(20) DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_demand_form`
--

CREATE TABLE `item_demand_form` (
  `Id` bigint(60) NOT NULL,
  `Status` tinyint(1) DEFAULT NULL COMMENT '0=NotApprove,1=PartialApproved,2=Approved,3=Pending,4=Complete,5=Recieve',
  `Form_Number` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `Form_DateG` date NOT NULL,
  `Form_DateH` date NOT NULL,
  `level_id` bigint(60) NOT NULL,
  `Donation_Type_Id` int(11) NOT NULL,
  `Department_Id` int(60) DEFAULT NULL,
  `CreatedBy` bigint(60) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `UpdatedBy` bigint(60) DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_demand_form_details`
--

CREATE TABLE `item_demand_form_details` (
  `Id` bigint(60) NOT NULL,
  `Demand_Form_Id` bigint(60) NOT NULL,
  `Item_Id` bigint(60) NOT NULL,
  `Item_Quantity` decimal(7,2) NOT NULL,
  `Item_remarks` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CreatedBy` bigint(60) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `UpdatedBy` bigint(60) DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_issue`
--

CREATE TABLE `item_issue` (
  `Id` bigint(60) NOT NULL,
  `Form_Number` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Demand_Form_Id` bigint(60) NOT NULL,
  `Approved_form_Id` int(20) NOT NULL,
  `Status` tinyint(1) NOT NULL COMMENT '0 = Pending, 1 = Recived',
  `Issued_DateG` date NOT NULL,
  `Issued_DateH` date NOT NULL,
  `Remarks` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_returned` tinyint(4) NOT NULL DEFAULT '0',
  `CreatedBy` bigint(60) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `UpdatedBy` bigint(60) DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_issue_details`
--

CREATE TABLE `item_issue_details` (
  `Id` bigint(60) NOT NULL,
  `Item_Issue_Id` bigint(60) NOT NULL,
  `Item_Id` bigint(60) NOT NULL,
  `Issue_Quantity` double(7,2) NOT NULL,
  `CreatedBy` bigint(20) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `UpdatedBy` bigint(20) DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_return`
--

CREATE TABLE `item_return` (
  `Id` int(20) NOT NULL,
  `Item_Issue_Form_Id` int(20) NOT NULL,
  `Status` tinyint(1) NOT NULL COMMENT '0=pending,1=recieved',
  `level_id` int(20) NOT NULL,
  `Department_Id` int(20) DEFAULT NULL,
  `return_dateG` date NOT NULL,
  `return_dateH` date NOT NULL,
  `CreatedBy` int(20) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `UpdatedBy` bigint(20) DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_return_details`
--

CREATE TABLE `item_return_details` (
  `Id` int(20) NOT NULL,
  `return_form_id` int(20) NOT NULL,
  `Item_Id` int(20) NOT NULL,
  `return_quantity` double(7,2) NOT NULL,
  `Item_remarks` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CreatedBy` int(20) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `UpdatedBy` int(20) DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_setup`
--

CREATE TABLE `item_setup` (
  `Id` bigint(60) NOT NULL,
  `code` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(225) COLLATE utf8_unicode_ci NOT NULL,
  `category_Id` int(60) NOT NULL,
  `subCategory_id` int(60) DEFAULT NULL,
  `unit_of_measure` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `opening_quantity` double(8,3) NOT NULL,
  `current_quantity` double(8,3) NOT NULL,
  `donation_type` int(60) NOT NULL,
  `CreatedBy` int(60) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `UpdatedBy` int(11) DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_stockrecieve_slip`
--

CREATE TABLE `item_stockrecieve_slip` (
  `Id` int(20) NOT NULL,
  `Slip_number` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `Supplier_Id` int(20) NOT NULL,
  `Purchase_dateG` date NOT NULL,
  `Purchase_dateH` date NOT NULL,
  `Item_recieve_dateG` date NOT NULL,
  `Item_recieve_dateH` date NOT NULL,
  `Buyer_name` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `CreatedBy` int(20) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `UpdatedBy` int(20) DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_stockrecieve_slip_details`
--

CREATE TABLE `item_stockrecieve_slip_details` (
  `Id` int(20) NOT NULL,
  `Recieve_slip_id` int(20) NOT NULL,
  `Item_id` int(20) NOT NULL,
  `Item_quantity` double(5,2) NOT NULL,
  `Item_price` double(7,2) NOT NULL,
  `Item_remarks` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CreatedBy` int(20) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `UpdatedBy` int(20) DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_stockreturn_slip`
--

CREATE TABLE `item_stockreturn_slip` (
  `Id` int(20) NOT NULL,
  `Return_slip_code` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `CreatedBy` int(20) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `UpdatedBy` int(20) NOT NULL,
  `UpdatedOn` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_stockreturn_slip_details`
--

CREATE TABLE `item_stockreturn_slip_details` (
  `Id` int(20) NOT NULL,
  `Stockreturn_slip_id` int(20) NOT NULL,
  `Item_Id` int(20) NOT NULL,
  `Item_quantity` double(5,2) NOT NULL,
  `Item_price` double(7,2) NOT NULL,
  `Item_remarks` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CreatedBy` int(20) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `UpdatedBy` int(20) DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `donation_type`
--
ALTER TABLE `donation_type`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `item_categories`
--
ALTER TABLE `item_categories`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `item_demand_approve`
--
ALTER TABLE `item_demand_approve`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `item_demand_approve_details`
--
ALTER TABLE `item_demand_approve_details`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `item_demand_form`
--
ALTER TABLE `item_demand_form`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `item_demand_form_details`
--
ALTER TABLE `item_demand_form_details`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `item_issue`
--
ALTER TABLE `item_issue`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `item_issue_details`
--
ALTER TABLE `item_issue_details`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `item_return`
--
ALTER TABLE `item_return`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `item_return_details`
--
ALTER TABLE `item_return_details`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `item_setup`
--
ALTER TABLE `item_setup`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `item_stockrecieve_slip`
--
ALTER TABLE `item_stockrecieve_slip`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `item_stockrecieve_slip_details`
--
ALTER TABLE `item_stockrecieve_slip_details`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `item_stockreturn_slip_details`
--
ALTER TABLE `item_stockreturn_slip_details`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `donation_type`
--
ALTER TABLE `donation_type`
  MODIFY `Id` int(3) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_categories`
--
ALTER TABLE `item_categories`
  MODIFY `Id` bigint(60) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_demand_approve`
--
ALTER TABLE `item_demand_approve`
  MODIFY `Id` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_demand_approve_details`
--
ALTER TABLE `item_demand_approve_details`
  MODIFY `Id` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_demand_form`
--
ALTER TABLE `item_demand_form`
  MODIFY `Id` bigint(60) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_demand_form_details`
--
ALTER TABLE `item_demand_form_details`
  MODIFY `Id` bigint(60) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_issue`
--
ALTER TABLE `item_issue`
  MODIFY `Id` bigint(60) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_issue_details`
--
ALTER TABLE `item_issue_details`
  MODIFY `Id` bigint(60) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_return`
--
ALTER TABLE `item_return`
  MODIFY `Id` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_return_details`
--
ALTER TABLE `item_return_details`
  MODIFY `Id` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_setup`
--
ALTER TABLE `item_setup`
  MODIFY `Id` bigint(60) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_stockrecieve_slip`
--
ALTER TABLE `item_stockrecieve_slip`
  MODIFY `Id` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_stockrecieve_slip_details`
--
ALTER TABLE `item_stockrecieve_slip_details`
  MODIFY `Id` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_stockreturn_slip_details`
--
ALTER TABLE `item_stockreturn_slip_details`
  MODIFY `Id` int(20) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
