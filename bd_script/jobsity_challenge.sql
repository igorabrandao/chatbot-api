-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 01, 2021 at 09:07 PM
-- Server version: 5.7.18-0ubuntu0.16.04.1
-- PHP Version: 7.2.31-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jobsity_challenge`
--

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `hash` text NOT NULL,
  `holder_fullname` varchar(255) NOT NULL,
  `holder_birthday` varchar(255) NOT NULL,
  `holder_cpf_number` varchar(11) NOT NULL,
  `phone_area_code` int(2) NOT NULL,
  `phone_country_code` int(2) NOT NULL DEFAULT '55',
  `phone_number` varchar(255) NOT NULL,
  `address_type` varchar(255) NOT NULL DEFAULT 'SHIPPING',
  `address_street` text NOT NULL,
  `address_number` int(11) NOT NULL,
  `address_district` varchar(255) NOT NULL,
  `address_city` varchar(255) NOT NULL,
  `address_state` varchar(255) NOT NULL,
  `address_zip` varchar(10) NOT NULL,
  `address_complement` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `cpf` varchar(14) NOT NULL,
  `encrypted_password` varchar(255) DEFAULT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `expiration_date_reset_token` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `access_level` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `address_proof` int(11) DEFAULT NULL,
  `criminal_record` varchar(255) DEFAULT NULL,
  `crlv` int(11) DEFAULT NULL,
  `cnh` int(11) DEFAULT NULL,
  `wirecard_customer_id` varchar(255) DEFAULT NULL,
  `last_used_card_id` varchar(255) DEFAULT NULL,
  `phone_country_code` int(11) DEFAULT '55',
  `phone_area_code` int(11) DEFAULT NULL,
  `phone_number` int(11) DEFAULT NULL,
  `birth_date` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `wirecard_token` varchar(255) DEFAULT NULL,
  `wirecard_id` varchar(255) DEFAULT NULL,
  `is_online` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `name`, `cpf`, `encrypted_password`, `access_token`, `password_reset_token`, `expiration_date_reset_token`, `is_active`, `birth_date`, `created_at`) VALUES
(1, 'igorabrandao@gmail.com', 'Igor Brandao', '222.333.444-55', '$2y$13$dCsvvoX5h3IsA9YNYZtjZef7KCzE3Qg0rPTF9q7AmamYhsk8XWCt2', 'fv5VlIKjGby7pDYJg7zMGxbt1ZWWY73h', 'uV_oaO_VIL8ZnQdilgZV54XLfqMDl-Bj', '', 1, '26-04-1992', '2021-02-01 19:24:43');

INSERT INTO `user` (`id`, `email`, `name`, `cpf`, `encrypted_password`, `access_token`, `password_reset_token`, `expiration_date_reset_token`, `is_active`, `access_level`, `company_id`, `address_proof`, `criminal_record`, `crlv`, `cnh`, `wirecard_customer_id`, `last_used_card_id`, `phone_country_code`, `phone_area_code`, `phone_number`, `birth_date`, `created_at`, `updated_at`, `wirecard_token`, `wirecard_id`, `is_online`) VALUES
(13, 'andreyeloy@gmail.com', 'Andrey ', '022.449.544-55', '$2y$13$nwJdOiNfH.vEvmuDPwLSeeSfsK3yKbezH.vNpxY3TiieYzsQZ20ou', 'xDrmVJMJVC9uhY8QksS6Pr3FdL5EPMrX', NULL, NULL, 1, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 55, 83, 988804814, '29-12-1976', '2020-05-28 12:46:45', '2020-05-28 12:46:45', NULL, NULL, NULL),
(12, 'igorabrandao@outlook.com', 'Gestor teste Saude Farma', '222.222.222-22', '$2y$13$oKRDKGYgLN0IPC29F7q8EeuiKgmji4PSYImkVrxbDvNvD8FBQxL0S', 'LYvJVOe1GMgvmqmt3RWJu551IRDKVMGp', NULL, NULL, 0, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, 55, NULL, NULL, NULL, '2020-05-20 09:13:03', '2020-05-20 09:13:03', NULL, NULL, NULL),
(4, 'apple_test@webfarma.net.br', 'Apple Teste', '716.342.560-00', '$2y$13$CChRNL1R94Ef53CU54rkpOdfPsaxeb3LMtBL7D85KO5j6A26negQm', 'lLbsB3c6hzJuNdX0FtIv_0BC2SWcHT37', NULL, NULL, 1, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 55, 84, 987654321, '25-11-2000', '2019-11-18 14:34:00', '2019-11-19 14:35:04', NULL, NULL, NULL),
(5, 'apple_test_entregador@webfarma.net.br', 'Apple Entregador', '940.818.394-09', '$2y$13$tTKw6WeRN/3fBwYcf3K/0upvlGkSHaQBOWFUFjSmOOm8izaGRmFra', 'nTLK1b8ZwptwT_1Sx4Ey2gjLrKW0tOBj', NULL, NULL, 1, 3, NULL, 2, NULL, 2, 2, NULL, NULL, 55, 84, 987654321, NULL, NULL, '2020-06-05 07:25:57', '61b5d67404d14a51a722867283e6e40b_v2', 'MPA-9D6952F91B61', 1),
(6, 'gracegarcia76@icloud.com', 'Grace Garcia', '695.450.220-00', '$2y$13$iYs060dtShVRu/0dxhzd4eBB.UTdOr5ELh80VMSJXmcGFbG6cXWUq', 'AmyZbXOIJ-tGKcdy8_KOf3mgQvmqExud', NULL, NULL, 1, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 55, 11, 896876587, '02-01-2019', '2019-11-19 12:29:09', '2019-11-19 12:29:09', NULL, NULL, NULL),
(7, 'manuel@farmacia.com.br', 'Manuel Admin Farmácia', '118.878.214-22', '$2y$13$80o.6tMQqHhyX9C39MkPC.Cm6MwidtH6qzrOBnayAAG9nZvHlxzja', 'M14_mwNX9CBxf0ztEqIepZEM9_RggpmJ', NULL, NULL, 0, 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, 55, NULL, NULL, NULL, '2019-11-19 16:18:25', '2019-11-19 16:18:25', NULL, NULL, NULL),
(8, 'thiagolimaoliveira@hotmail.com', 'Administrador Webfarma', '111.111.111-11', '$2y$13$CO5dES8od4X6v8yJNXB6G.RvJSpf5fjGUCFELDnGTrMBlOBZ2Uiuy', 'kGv3vBYx1aPr73GAh4HWzsh6v3BgUyFg', NULL, NULL, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 55, NULL, NULL, NULL, '2019-11-27 09:48:51', '2019-11-27 09:48:51', NULL, NULL, NULL),
(9, 'igorabrandao@gmail.com', 'Igor Brandao', '222.333.444-55', '$2y$13$dCsvvoX5h3IsA9YNYZtjZef7KCzE3Qg0rPTF9q7AmamYhsk8XWCt2', 'fv5VlIKjGby7pDYJg7zMGxbt1ZWWY73h', 'uV_oaO_VIL8ZnQdilgZV54XLfqMDl-Bj', NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 55, 84, 999431545, '26-04-1992', '2019-12-13 13:37:43', '2020-05-05 11:35:07', NULL, NULL, NULL),
(10, 'thiagolimaoliveira17@gmail.com', 'Thiago ', '089.481.404-42', '$2y$13$Pl5PERFDsHHGcIhVMYKV.O0QB4A/xPRe.TveWWqaUi8Pe15ILJDUi', 'Q9uK70kLyV9JmmPJN8sQR_Yrc9jmiZFp', NULL, NULL, 1, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 55, 84, 999427500, '17-08-1988', '2020-05-07 15:53:41', '2020-05-07 15:53:41', NULL, NULL, NULL),
(11, 'maadeo0@gmail.com', 'Marcos Alberto Araujo de Oliveira', '308.068.954-20', '$2y$13$SWXTm9RZsokK2QqIwsSnkOa38GF3dAxpWT9K.CWbI2UjJuEgRWmXu', 'iHIjLWplU5htIcmvG2x4eRINS5Sti04w', NULL, NULL, 1, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 55, 84, 999743541, '18-03-1964', '2020-05-07 16:11:54', '2020-05-07 16:11:54', NULL, NULL, NULL),
(14, 'webfarmadev@gmail.com', 'Gestor Thiago Saude Farma', '089.481.404-42', '$2y$13$0.DEj/4kWzZrMJzXE9Eyj.HQ9sPeLCRcbGzgssdmWyKvBpoI/VACG', 'h9eh4zQ-4oN7hUdyilQz8BpeSNHWWIkO', NULL, NULL, 0, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, 55, NULL, NULL, NULL, '2020-06-03 14:22:53', '2020-06-03 14:22:53', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `device`
--
ALTER TABLE `device`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-device-user_id` (`user_id`);

--
-- Indexes for table `distribution`
--
ALTER TABLE `distribution`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-distribution-user_id` (`user_id`);

--
-- Indexes for table `evaluation`
--
ALTER TABLE `evaluation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-evaluation-purchase_id` (`purchase_id`);

--
-- Indexes for table `fiscal_governance`
--
ALTER TABLE `fiscal_governance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ingress_storage`
--
ALTER TABLE `ingress_storage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-ingress_storage-user_id` (`user_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-item-merchandise_id` (`merchandise_id`),
  ADD KEY `idx-item-prescription_id` (`prescription_id`),
  ADD KEY `idx-item-purchase_id` (`purchase_id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-location-company_id` (`company_id`),
  ADD KEY `idx-location-user_id` (`user_id`);

--
-- Indexes for table `merchandise`
--
ALTER TABLE `merchandise`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-merchandise-company_id` (`company_id`),
  ADD KEY `idx-merchandise-product_id` (`product_id`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-payment-purchase_id` (`purchase_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-purchase-customer_id` (`customer_id`),
  ADD KEY `idx-purchase-distribution_id` (`distribution_id`),
  ADD KEY `idx-purchase-location_id` (`location_id`);

--
-- Indexes for table `upload`
--
ALTER TABLE `upload`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-user-company_id` (`company_id`),
  ADD KEY `idx-user-address-proof-upload_id` (`address_proof`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `device`
--
ALTER TABLE `device`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `distribution`
--
ALTER TABLE `distribution`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `evaluation`
--
ALTER TABLE `evaluation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `fiscal_governance`
--
ALTER TABLE `fiscal_governance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ingress_storage`
--
ALTER TABLE `ingress_storage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `merchandise`
--
ALTER TABLE `merchandise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4933;
--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4933;
--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `upload`
--
ALTER TABLE `upload`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
