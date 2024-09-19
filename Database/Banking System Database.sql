-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2023 at 08:25 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gnbdatabase`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AccountBalance` (IN `accountNo` BIGINT(11))   SELECT
    SUM(CASE WHEN transactionCRDB = 'CR' THEN transactionAmount ELSE 0 END) -
    SUM(CASE WHEN transactionCRDB = 'DB' THEN transactionAmount ELSE 0 END) AS net_difference
FROM transactions
WHERE fromAccount = accountNo AND (transactionCRDB = 'CR' OR transactionCRDB = 'DB')$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `accountBalancebyAccNO` (IN `AccNo` BIGINT)   SELECT
    clientaccount.*,
    COALESCE(balance.net_difference, 0) AS account_balance
FROM
    clientaccount
LEFT JOIN (
    SELECT
        fromAccount,
        SUM(CASE WHEN transactionCRDB = 'CR' THEN transactionAmount ELSE 0 END) -
        SUM(CASE WHEN transactionCRDB = 'DB' THEN transactionAmount ELSE 0 END) AS net_difference
    FROM
        transactions
    WHERE
        transactionCRDB IN ('CR', 'DB')
    GROUP BY
        fromAccount
) balance ON clientaccount.accountNo = balance.fromAccount
WHERE
    clientaccount.accountNo = AccNo$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `accountbalancebyID` (IN `NIC` VARCHAR(15))   SELECT clientaccount.*, COALESCE(balance.net_difference, 0) AS account_balance FROM clientaccount LEFT JOIN ( SELECT fromAccount, SUM(CASE WHEN transactionCRDB = 'CR' THEN transactionAmount ELSE 0 END) - SUM(CASE WHEN transactionCRDB = 'DB' THEN transactionAmount ELSE 0 END) AS net_difference FROM transactions WHERE transactionCRDB IN ('CR', 'DB') GROUP BY fromAccount ) balance ON clientaccount.accountNo = balance.fromAccount WHERE clientaccount.clientNIC = NIC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `addATM` (IN `cardNo` BIGINT(16), IN `AccNo` BIGINT, IN `Pin` INT(4))   INSERT INTO `atm`(`cardNo`, `accountNo`, `PIN`) VALUES (cardNo, AccNo, Pin)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `addCheques` (IN `bookNo` INT, IN `AccNo` BIGINT, IN `chNo` INT)   INSERT INTO `cheques`( `chequeBookNo`, `accountNo`, `chequeNo`) VALUES (bookNo,AccNo,chNo)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `adminlogin` (IN `username` VARCHAR(10), IN `password` INT(10))   SELECT * from adminlogin WHERE adminUserName = username AND adminPassword = password$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `allClientInfo` (IN `NIC` VARCHAR(15))   SELECT * from clientinfo WHERE clientNIC = NIC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `allMessagesfromAccount` (IN `AccNo` BIGINT)   SELECT * 
FROM messageds 
WHERE sender = AccNo OR reciever = AccNo
ORDER BY messageID DESC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ATMverify` (IN `ATMcard` BIGINT(16), IN `Pin` INT)   SELECT `accountNo` FROM `atm` WHERE cardNo = ATMcard AND PIN = Pin and status = 'Active'$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `chequeValidate` (IN `chNo` INT, IN `AccNo` BIGINT)   SELECT
    cheques.accountNo,
    cheques.chequeNo,
    CASE
        WHEN usedcheques.chequeNo IS NULL THEN 'Not Used'
        ELSE 'Used'
    END AS chequeStatus
FROM
    cheques
LEFT JOIN
    usedcheques ON cheques.accountNo = usedcheques.accountNo AND cheques.chequeNo = usedcheques.chequeNo
WHERE
    cheques.chequeNo = chNo
    AND
    cheques.accountNo = AccNo$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `closeAccount` (IN `AccNo` BIGINT)   BEGIN
  UPDATE accounts
  SET accountStatus = 'Inactive', closedDate = NOW()
  WHERE accountNo = AccNo;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateAccount` (IN `accType` VARCHAR(10))   INSERT into accounts (accountType, createdDate) VALUES (accType, now())$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `createAccountExistingClient` (IN `accType` VARCHAR(10), IN `existing` VARCHAR(5))   INSERT into accounts (accountType, createdDate,existingClient) VALUES (accType, now(),existing)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deactivateATM` (IN `ID` INT)   UPDATE `atm` SET `status`='Inactive' WHERE ATMID = ID$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `depositToAccount` (IN `AccNo` BIGINT, IN `Remark` VARCHAR(20), IN `Amount` FLOAT, IN `Type` VARCHAR(15))   INSERT INTO `transactions`(`fromAccount`, `transactionMethod`, `remarks`, `transactionAmount`, `transactionCRDB`) VALUES (AccNo, Type, Remark,Amount,'CR')$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `editClient` (IN `NIC` VARCHAR(20), IN `clentsFName` VARCHAR(255), IN `clientLname` VARCHAR(255), IN `clientContact` INT(15), IN `cleintAddress` VARCHAR(255), IN `clientBday` DATE, IN `clentEmail` VARCHAR(255))   UPDATE `clientinfo` SET `clientFName`=clentsFName,`clentsLName`=clientLname,`clientContact`=clientContact,`cleintAddress`=cleintAddress,`clientBday`=clientBday,`clentEmail`=clentEmail WHERE clientNIC = NIC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `findATMfromaccount` (IN `AccNo` BIGINT)   SELECT * from ATM WHERE accountNo = AccNo
 AND STATUS = 'active'$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getavailableCheques` (IN `AccNo` BIGINT)   SELECT
    cheques.chequeNo
FROM
    cheques
LEFT JOIN
    usedcheques ON cheques.chequeNo = usedcheques.chequeNo
WHERE
    cheques.accountNo = AccNo
    AND usedcheques.chequeNo IS NULL$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getEverythingByAccNo` (IN `AccNo` BIGINT)   SELECT
    `accounts`.`accountType`,
    `accounts`.`accountStatus`, 
    `accounts`.`closedDate`,    
    `clientaccount`.`accountNo`,
    `clientinfo`.*,
    COALESCE(balance.net_difference, 0) AS account_balance
FROM
    `accounts`
LEFT JOIN
    `clientaccount` ON `clientaccount`.`accountNo` = `accounts`.`accountNo`
LEFT JOIN
    `clientinfo` ON `clientaccount`.`clientNIC` = `clientinfo`.`clientNIC`
LEFT JOIN (
    SELECT
        fromAccount,
        SUM(CASE WHEN transactionCRDB = 'CR' THEN transactionAmount ELSE 0 END) -
        SUM(CASE WHEN transactionCRDB = 'DB' THEN transactionAmount ELSE 0 END) AS net_difference
    FROM
        `transactions`
    WHERE
        transactionCRDB IN ('CR', 'DB')
    GROUP BY
        fromAccount
) balance ON `clientaccount`.`accountNo` = `balance`.`fromAccount`
WHERE
    `clientaccount`.`accountNo` = AccNo
    AND
    `accounts`.`accountStatus` != 'Inactive'$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `geteverythingbyATM` (IN `ATM` BIGINT(16), IN `Pin` INT(4))   SELECT
    `accounts`.`accountType`,
    `accounts`.`accountStatus`, 
    `accounts`.`closedDate`,    
    `clientaccount`.`accountNo`,
    `clientinfo`.*,
    COALESCE(balance.net_difference, 0) AS account_balance
FROM
    `accounts`
LEFT JOIN
    `clientaccount` ON `clientaccount`.`accountNo` = `accounts`.`accountNo`
LEFT JOIN
    `clientinfo` ON `clientaccount`.`clientNIC` = `clientinfo`.`clientNIC`
LEFT JOIN (
    SELECT
        fromAccount,
        SUM(CASE WHEN transactionCRDB = 'CR' THEN transactionAmount ELSE 0 END) -
        SUM(CASE WHEN transactionCRDB = 'DB' THEN transactionAmount ELSE 0 END) AS net_difference
    FROM
        `transactions`
    WHERE
        transactionCRDB IN ('CR', 'DB')
    GROUP BY
        fromAccount
) balance ON `clientaccount`.`accountNo` = `balance`.`fromAccount`
LEFT JOIN
    `atm` ON `atm`.`accountNo` = `clientaccount`.`accountNo`
WHERE
    `atm`.`cardNo` = ATM
    AND
    `atm`.`PIN` = Pin
    AND
    `accounts`.`accountStatus` != 'Inactive'
        AND
    `atm`.`status` = 'Active'$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `geteverythingbyLoginID` (IN `UID` INT)   SELECT 
    accounts.accountType,
    clientaccount.accountNo,
    clientinfo.*,
    COALESCE(balance.net_difference, 0) AS account_balance
FROM 
    accounts
LEFT JOIN 
    clientaccount ON clientaccount.accountNo = accounts.accountNo
LEFT JOIN 
    clientinfo ON clientaccount.clientNIC = clientinfo.clientNIC
LEFT JOIN (
    SELECT 
        fromAccount,
        SUM(CASE WHEN transactionCRDB = 'CR' THEN transactionAmount ELSE 0 END) - 
        SUM(CASE WHEN transactionCRDB = 'DB' THEN transactionAmount ELSE 0 END) AS net_difference
    FROM 
        transactions
    WHERE 
        transactionCRDB IN ('CR', 'DB')
    GROUP BY 
        fromAccount
) balance ON clientaccount.accountNo = balance.fromAccount
WHERE 
    clientinfo.clientNIC IN (
        SELECT 
            userandclient.clientNIC
        FROM 
            userandclient
        WHERE 
            userandclient.userID = UID
    )$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getEverythingByNIC` (IN `NIC` VARCHAR(15))   SELECT `accounts`.`accountType`, `clientaccount`.`accountNo`, `clientinfo`.*, COALESCE(balance.net_difference, 0) AS account_balance FROM `accounts` LEFT JOIN `clientaccount` ON `clientaccount`.`accountNo` = `accounts`.`accountNo` LEFT JOIN `clientinfo` ON `clientaccount`.`clientNIC` = `clientinfo`.`clientNIC` LEFT JOIN (SELECT fromAccount, SUM(CASE WHEN transactionCRDB = 'CR' THEN transactionAmount ELSE 0 END) - SUM(CASE WHEN transactionCRDB = 'DB' THEN transactionAmount ELSE 0 END) AS net_difference FROM `transactions` WHERE transactionCRDB IN ('CR', 'DB') GROUP BY fromAccount) balance ON `clientaccount`.`accountNo` = `balance`.`fromAccount` WHERE `clientinfo`.`clientNIC` = NIC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertClient` (IN `clientFName` VARCHAR(255), IN `clentsLName` VARCHAR(255), IN `clientNIC` VARCHAR(15), IN `clientContact` INT(15), IN `cleintAddress` VARCHAR(255), IN `clientBday` DATE, IN `clentEmail` VARCHAR(255))   INSERT INTO `clientinfo`(`clientFName`, `clentsLName`, `clientNIC`, `clientContact`, `cleintAddress`, `clientBday`, `clentEmail`) VALUES (clientFName,clentsLName,clientNIC,clientContact,cleintAddress,clientBday,clentEmail)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `readMessage` (IN `MID` INT)   UPDATE `messageds`
SET `ReadDate` = NOW(), `Status` = 'Read'
WHERE `messageID` = MID$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SelectAll` (`tablename` VARCHAR(20))   BEGIN
    SET @sql = NULL;
    SET @sql = CONCAT('SELECT * FROM ', tablename);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sendMessage` (IN `Sender` VARCHAR(15), IN `reciever` VARCHAR(15), IN `message` VARCHAR(500))   INSERT INTO `messageds`(`sender`, `reciever`, `message`, `sentDate`) VALUES (Sender,reciever,message,now())$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `transactionreportBetween2Dates` (IN `AccNo` BIGINT, IN `start` DATE, IN `end` DATE)   SELECT * FROM `transactions`
WHERE fromAccount = AccNo
  AND `transactionDate` >= start
  AND `transactionDate` < end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `transactionreportMostRecent` (IN `AccNo` BIGINT)   SELECT *
FROM `transactions`
WHERE fromAccount = AccNo
ORDER BY `transactionDate` DESC$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `transfers` (IN `Amount` FLOAT, IN `FromAcc` BIGINT, IN `ToAcc` BIGINT)   INSERT INTO `transfers`(`transferAmount`, `fromAccount`, `toAccount`) VALUES (Amount, FromAcc, ToAcc)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `useCheque` (IN `chNo` INT, IN `AccNo` BIGINT, IN `Amount` FLOAT, IN `RecieverName` VARCHAR(100), IN `NIC` VARCHAR(15))   INSERT INTO `usedcheques`( `chequeNo`, `accountNo`, `amount`, `consigneeName`, `consigneeID`) VALUES (chNo,AccNo,Amount,RecieverName,NIC)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `userlogin` (IN `username` VARCHAR(20), IN `password` VARCHAR(20))   SELECT * FROM `userlogin` WHERE `username`= username AND `userpassword`= password$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `viewAllMessages` (IN `user` VARCHAR(20))   SELECT * FROM `messageds` WHERE reciever = user$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `viewUnreadMessages` (IN `user` VARCHAR(20))   SELECT * FROM `messageds` WHERE reciever = user and `Status` = 'unread'$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `withdrawCash` (IN `AccNo` BIGINT, IN `Amount` FLOAT)   INSERT INTO `transactions`( `fromAccount`, `transactionMethod`, `remarks`, `transactionAmount`, `transactionCRDB`) VALUES (AccNo,'Cash','counter', Amount,'DB')$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `withdrawCashATM` (IN `AccNo` BIGINT, IN `Amount` FLOAT)   BEGIN
    INSERT INTO `transactions` (`fromAccount`, `transactionMethod`, `remarks`, `transactionAmount`, `transactionCRDB`)
    VALUES (AccNo, 'Cash', 'ATM', Amount, 'DB');
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `withdrawCheques` (IN `AccNo` BIGINT, IN `Amount` FLOAT, IN `chequeNo` INT)   INSERT INTO `transactions` (`fromAccount`, `transactionMethod`, `remarks`, `transactionAmount`, `transactionCRDB`)
    VALUES (AccNo, 'Cheque', chequeNo , Amount, 'DB')$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `accountNo` bigint(11) NOT NULL,
  `accountType` varchar(10) NOT NULL,
  `createdDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `existingClient` varchar(4) NOT NULL DEFAULT 'No' COMMENT 'The trigger ''newAccountClient'' will ignore any entries with "yes" in this column. So that existing clinets can be added with more than one acccount\r\n',
  `accountStatus` varchar(10) NOT NULL DEFAULT 'Active',
  `closedDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`accountNo`, `accountType`, `createdDate`, `existingClient`, `accountStatus`, `closedDate`) VALUES
(10000000001, 'saving', '2023-08-27 03:42:30', 'No', 'Active', NULL),
(10000000002, 'current', '2023-09-09 03:23:55', 'No', 'Active', NULL),
(10000000004, 'saving', '2023-08-28 05:04:32', 'No', 'Active', NULL),
(10000000007, 'current', '2023-09-09 03:24:03', 'No', 'Active', NULL),
(10000000008, 'saving', '2023-09-08 06:25:48', 'No', 'Active', NULL),
(10000000009, 'saving', '2023-09-08 06:42:13', 'No', 'Active', NULL),
(10000000010, 'current', '2023-09-09 03:23:40', 'Yes', 'Active', NULL),
(10000000011, 'current', '2023-09-08 07:49:44', 'No', 'Active', NULL),
(10000000012, 'current', '2023-09-08 16:19:58', 'No', 'Active', NULL),
(10000000013, 'saving', '2023-09-09 13:58:56', 'No', 'Active', NULL),
(10000000014, 'saving', '2023-09-16 05:04:49', 'No', 'Active', NULL),
(10000000016, 'saving', '2023-09-17 16:54:46', 'No', 'Active', NULL);

--
-- Triggers `accounts`
--
DELIMITER $$
CREATE TRIGGER `newAccountClint` AFTER INSERT ON `accounts` FOR EACH ROW BEGIN
  DECLARE latest_client_id VARCHAR(255);
  DECLARE latest_account_no BIGINT;

  -- Check the value of existingClient in the newly inserted row
  DECLARE existingClientValue VARCHAR(255);
  SELECT existingClient INTO existingClientValue FROM accounts WHERE accountNo = NEW.accountNo;

  -- If existingClient is 'No', then proceed with the trigger
  IF existingClientValue = 'No' THEN
    -- Get the latest clientNIC from the clientInfo table
    SELECT clientNIC INTO latest_client_id FROM clientInfo ORDER BY clientID DESC LIMIT 1;

    -- Get the latest accountNo from the accounts table
    SELECT accountNo INTO latest_account_no FROM accounts ORDER BY accountNo DESC LIMIT 1;

    -- Insert the values into the clientAccount table
    INSERT INTO clientAccount (clientNIC, accountNo)
    VALUES (latest_client_id, latest_account_no);
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `adminlogin`
--

CREATE TABLE `adminlogin` (
  `adminID` int(11) NOT NULL,
  `adminUserName` varchar(15) NOT NULL,
  `adminPassword` varchar(10) NOT NULL,
  `adminFname` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adminlogin`
--

INSERT INTO `adminlogin` (`adminID`, `adminUserName`, `adminPassword`, `adminFname`) VALUES
(1, 'admin', 'admin', 'Praneeth');

-- --------------------------------------------------------

--
-- Table structure for table `atm`
--

CREATE TABLE `atm` (
  `ATMID` int(11) NOT NULL,
  `cardNo` bigint(16) NOT NULL,
  `accountNo` bigint(20) NOT NULL,
  `PIN` int(4) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'Active',
  `modifiedDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `atm`
--

INSERT INTO `atm` (`ATMID`, `cardNo`, `accountNo`, `PIN`, `status`, `modifiedDate`) VALUES
(1, 1111222233334444, 10000000001, 0, 'Active', '2023-09-17 02:23:45'),
(2, 1111222233335555, 10000000002, 2226, 'Active', '2023-09-17 02:23:45'),
(3, 1234567891234567, 10000000001, 6265, 'Inactive', '2023-09-17 02:23:45'),
(4, 9999888877774444, 10000000004, 5555, 'Inactive', '2023-09-17 02:54:48'),
(5, 9999888877772222, 10000000004, 5555, 'Active', '2023-09-17 02:55:11');

-- --------------------------------------------------------

--
-- Table structure for table `bankledger`
--

CREATE TABLE `bankledger` (
  `ledgerID` int(11) NOT NULL,
  `ledgerAmount` int(11) NOT NULL,
  `trasactionID` int(11) NOT NULL,
  `CRDB` varchar(10) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bankledger`
--

INSERT INTO `bankledger` (`ledgerID`, `ledgerAmount`, `trasactionID`, `CRDB`, `timestamp`) VALUES
(2, 10000, 4, 'CR', '0000-00-00 00:00:00'),
(3, 2000, 5, 'CR', '0000-00-00 00:00:00'),
(4, 2000, 6, 'CR', '0000-00-00 00:00:00'),
(5, 2000, 7, 'CR', '0000-00-00 00:00:00'),
(6, 2000, 8, 'DB', '0000-00-00 00:00:00'),
(7, 15000, 9, 'DB', '2023-09-09 03:57:03'),
(8, 15000, 10, 'DB', '2023-09-09 03:59:39'),
(9, 6000, 11, 'CR', '2023-09-09 04:06:09'),
(10, 6000, 12, 'DB', '2023-09-09 10:34:02'),
(11, 1800, 13, 'DB', '2023-09-09 10:40:27'),
(12, 1500, 14, 'DB', '2023-09-09 10:42:52'),
(13, 13000, 15, 'DB', '2023-09-09 10:43:19'),
(14, 15000, 16, 'DB', '2023-09-09 10:50:46'),
(15, 700, 17, 'DB', '2023-09-09 13:00:19'),
(16, 1500, 18, 'DB', '2023-09-09 13:07:36'),
(17, 24500, 19, 'DB', '2023-09-09 13:14:00'),
(18, 10000, 20, 'DB', '2023-09-09 13:17:20'),
(19, 10000, 21, 'DB', '2023-09-09 13:21:12'),
(20, 13000, 22, 'DB', '2023-09-09 13:42:50'),
(21, 48756, 23, 'DB', '2023-09-09 13:49:57'),
(22, 25000, 24, 'DB', '2023-09-09 13:59:30'),
(23, 1500, 25, 'CR', '2023-09-09 14:46:15'),
(24, 10000, 26, 'CR', '2023-09-09 14:46:55'),
(25, 500, 27, 'CR', '2023-09-09 15:08:16'),
(26, 10000, 28, 'DB', '2023-09-10 02:55:31'),
(27, 10000, 29, 'DB', '2023-09-10 02:59:34'),
(28, 9500, 30, 'CR', '2023-09-10 03:09:41'),
(29, 0, 31, 'DB', '2023-09-10 03:12:50'),
(30, 10000, 32, 'DB', '2023-09-10 03:15:55'),
(31, 0, 33, 'DB', '2023-09-10 03:21:13'),
(32, 10000, 34, 'DB', '2023-09-10 03:45:25'),
(33, 1500, 35, 'DB', '2023-09-10 03:45:41'),
(34, 9000, 36, 'CR', '2023-09-10 03:45:53'),
(35, 0, 37, 'CR', '2023-09-10 15:59:53'),
(36, 0, 38, 'CR', '2023-09-10 16:00:23'),
(37, 500, 39, 'CR', '2023-09-10 17:28:09'),
(38, 500, 40, 'DB', '2023-09-10 17:28:09'),
(39, 10000, 41, 'CR', '2023-09-10 17:29:13'),
(40, 10000, 42, 'DB', '2023-09-10 17:29:13'),
(41, 500, 43, 'CR', '2023-09-10 17:44:22'),
(42, 500, 44, 'DB', '2023-09-10 17:44:22'),
(43, 12500, 45, 'CR', '2023-09-10 17:44:44'),
(44, 12500, 46, 'DB', '2023-09-10 17:44:44'),
(45, 500, 47, 'CR', '2023-09-14 14:14:34'),
(46, 500, 48, 'DB', '2023-09-14 14:14:34'),
(47, 1142, 49, 'CR', '2023-09-15 10:54:58'),
(48, 1142, 50, 'DB', '2023-09-15 10:54:58'),
(49, 1142, 51, 'CR', '2023-09-15 11:00:56'),
(50, 1142, 52, 'DB', '2023-09-15 11:00:56'),
(51, 1142, 53, 'CR', '2023-09-15 11:02:32'),
(52, 1142, 54, 'DB', '2023-09-15 11:02:32'),
(53, 1142, 55, 'CR', '2023-09-15 11:02:58'),
(54, 1142, 56, 'DB', '2023-09-15 11:02:58'),
(55, 2500, 57, 'CR', '2023-09-16 04:59:58'),
(56, 2500, 58, 'DB', '2023-09-16 04:59:58'),
(57, 100000, 59, 'DB', '2023-09-16 05:06:55'),
(58, 1500, 60, 'CR', '2023-09-16 09:34:37'),
(59, 500, 61, 'CR', '2023-09-16 10:11:15'),
(60, 500, 62, 'DB', '2023-09-16 10:11:15'),
(61, 100000, 63, 'DB', '2023-09-16 10:20:10'),
(62, 10000, 64, 'CR', '2023-09-16 10:20:17'),
(63, 10000, 65, 'DB', '2023-09-16 10:20:38'),
(64, 13000, 66, 'CR', '2023-09-16 10:21:01'),
(65, 13000, 67, 'DB', '2023-09-16 10:21:01'),
(66, 5500, 68, 'CR', '2023-09-16 19:27:25'),
(67, 100, 69, 'CR', '2023-09-16 19:51:42'),
(68, 500, 70, 'CR', '2023-09-16 19:55:22'),
(69, 1500, 71, 'CR', '2023-09-16 19:57:20'),
(70, 10000, 72, 'CR', '2023-09-17 05:12:56'),
(71, 10000, 73, 'DB', '2023-09-17 05:12:56'),
(72, 1000, 74, 'CR', '2023-09-17 05:16:18'),
(73, 1000, 75, 'DB', '2023-09-17 05:16:18'),
(74, 10000, 76, 'CR', '2023-09-17 16:36:42'),
(75, 10000, 77, 'DB', '2023-09-17 16:36:51'),
(76, 1500, 78, 'DB', '2023-09-17 16:37:05'),
(77, 8500, 79, 'CR', '2023-09-17 16:37:33'),
(78, 8500, 80, 'DB', '2023-09-17 16:37:33');

-- --------------------------------------------------------

--
-- Table structure for table `cheques`
--

CREATE TABLE `cheques` (
  `chequeBookID` int(11) NOT NULL,
  `chequeBookNo` int(10) NOT NULL,
  `accountNo` bigint(20) NOT NULL,
  `chequeNo` int(11) NOT NULL,
  `checkAddedDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cheques`
--

INSERT INTO `cheques` (`chequeBookID`, `chequeBookNo`, `accountNo`, `chequeNo`, `checkAddedDate`) VALUES
(277, 252627, 10000000012, 100025, '2023-09-16 17:46:59'),
(278, 252627, 10000000012, 100026, '2023-09-16 17:46:59'),
(279, 252627, 10000000012, 100027, '2023-09-16 17:46:59'),
(280, 252627, 10000000012, 100028, '2023-09-16 17:46:59'),
(281, 252627, 10000000012, 100029, '2023-09-16 17:46:59'),
(282, 252627, 10000000012, 100030, '2023-09-16 17:46:59'),
(283, 252627, 10000000012, 100031, '2023-09-16 17:46:59'),
(284, 252627, 10000000012, 100032, '2023-09-16 17:46:59'),
(285, 252627, 10000000012, 100033, '2023-09-16 17:46:59'),
(286, 252627, 10000000012, 100034, '2023-09-16 17:46:59'),
(287, 252627, 10000000012, 100035, '2023-09-16 17:46:59'),
(288, 252627, 10000000012, 100036, '2023-09-16 17:46:59'),
(289, 252627, 10000000012, 100037, '2023-09-16 17:46:59'),
(290, 252627, 10000000012, 100038, '2023-09-16 17:46:59'),
(291, 252627, 10000000012, 100039, '2023-09-16 17:46:59'),
(292, 252627, 10000000012, 100040, '2023-09-16 17:46:59'),
(293, 252627, 10000000012, 100041, '2023-09-16 17:46:59'),
(294, 252627, 10000000012, 100042, '2023-09-16 17:46:59'),
(295, 252627, 10000000012, 100043, '2023-09-16 17:46:59'),
(296, 252627, 10000000012, 100044, '2023-09-16 17:46:59'),
(297, 252627, 10000000012, 100045, '2023-09-16 17:46:59'),
(298, 252627, 10000000012, 100046, '2023-09-16 17:46:59'),
(299, 252627, 10000000012, 100047, '2023-09-16 17:46:59'),
(300, 252627, 10000000012, 100048, '2023-09-16 17:46:59'),
(301, 252627, 10000000012, 100049, '2023-09-16 17:46:59'),
(302, 252627, 10000000012, 100000, '2023-09-16 18:08:58'),
(303, 252627, 10000000012, 100001, '2023-09-16 18:08:58'),
(304, 252627, 10000000012, 100002, '2023-09-16 18:08:58'),
(305, 252627, 10000000012, 100003, '2023-09-16 18:08:58'),
(306, 252627, 10000000012, 100004, '2023-09-16 18:08:58'),
(307, 252627, 10000000012, 100005, '2023-09-16 18:08:58'),
(308, 252627, 10000000012, 100006, '2023-09-16 18:08:58'),
(309, 252627, 10000000012, 100007, '2023-09-16 18:08:58'),
(310, 252627, 10000000012, 100008, '2023-09-16 18:08:58'),
(311, 252627, 10000000012, 100009, '2023-09-16 18:08:58'),
(312, 252627, 10000000012, 100010, '2023-09-16 18:08:58'),
(313, 252627, 10000000012, 100011, '2023-09-16 18:08:58'),
(314, 252627, 10000000012, 100012, '2023-09-16 18:08:58'),
(315, 252627, 10000000012, 100013, '2023-09-16 18:08:58'),
(316, 252627, 10000000012, 100014, '2023-09-16 18:08:58'),
(317, 252627, 10000000012, 100015, '2023-09-16 18:08:58'),
(318, 252627, 10000000012, 100016, '2023-09-16 18:08:58'),
(319, 252627, 10000000012, 100017, '2023-09-16 18:08:58'),
(320, 252627, 10000000012, 100018, '2023-09-16 18:08:58'),
(321, 252627, 10000000012, 100019, '2023-09-16 18:08:58'),
(322, 252627, 10000000012, 100020, '2023-09-16 18:08:58'),
(323, 252627, 10000000012, 100021, '2023-09-16 18:08:58'),
(324, 252627, 10000000012, 100022, '2023-09-16 18:08:58'),
(325, 252627, 10000000012, 100023, '2023-09-16 18:08:58'),
(326, 252627, 10000000012, 100024, '2023-09-16 18:08:58'),
(327, 151617, 10000000007, 326580, '2023-09-16 18:11:54'),
(328, 151617, 10000000007, 326581, '2023-09-16 18:11:54'),
(329, 151617, 10000000007, 326582, '2023-09-16 18:11:54'),
(330, 151617, 10000000007, 326583, '2023-09-16 18:11:54'),
(331, 151617, 10000000007, 326584, '2023-09-16 18:11:54'),
(332, 151617, 10000000007, 326585, '2023-09-16 18:11:54'),
(333, 151617, 10000000007, 326586, '2023-09-16 18:11:54'),
(334, 151617, 10000000007, 326587, '2023-09-16 18:11:54'),
(335, 151617, 10000000007, 326588, '2023-09-16 18:11:54'),
(336, 151617, 10000000007, 326589, '2023-09-16 18:11:54'),
(337, 151617, 10000000007, 326590, '2023-09-16 18:11:54'),
(338, 151617, 10000000007, 326591, '2023-09-16 18:11:54'),
(339, 151617, 10000000007, 326592, '2023-09-16 18:11:54'),
(340, 151617, 10000000007, 326593, '2023-09-16 18:11:54'),
(341, 151617, 10000000007, 326594, '2023-09-16 18:11:54'),
(342, 151617, 10000000007, 326595, '2023-09-16 18:11:54'),
(343, 151617, 10000000007, 326596, '2023-09-16 18:11:54'),
(344, 151617, 10000000007, 326597, '2023-09-16 18:11:54'),
(345, 151617, 10000000007, 326598, '2023-09-16 18:11:54'),
(346, 151617, 10000000007, 326599, '2023-09-16 18:11:54'),
(347, 151617, 10000000007, 326600, '2023-09-16 18:11:54'),
(348, 151617, 10000000007, 326601, '2023-09-16 18:11:54'),
(349, 151617, 10000000007, 326602, '2023-09-16 18:11:54'),
(350, 151617, 10000000007, 326603, '2023-09-16 18:11:54'),
(351, 151617, 10000000007, 326604, '2023-09-16 18:11:54'),
(352, 987456, 10000000011, 854760, '2023-09-16 18:14:52'),
(353, 987456, 10000000011, 854761, '2023-09-16 18:14:52'),
(354, 987456, 10000000011, 854762, '2023-09-16 18:14:52'),
(355, 987456, 10000000011, 854763, '2023-09-16 18:14:52'),
(356, 987456, 10000000011, 854764, '2023-09-16 18:14:52'),
(357, 987456, 10000000011, 854765, '2023-09-16 18:14:52'),
(358, 987456, 10000000011, 854766, '2023-09-16 18:14:52'),
(359, 987456, 10000000011, 854767, '2023-09-16 18:14:52'),
(360, 987456, 10000000011, 854768, '2023-09-16 18:14:52'),
(361, 987456, 10000000011, 854769, '2023-09-16 18:14:52'),
(362, 987456, 10000000011, 854770, '2023-09-16 18:14:52'),
(363, 987456, 10000000011, 854771, '2023-09-16 18:14:52'),
(364, 987456, 10000000011, 854772, '2023-09-16 18:14:52'),
(365, 987456, 10000000011, 854773, '2023-09-16 18:14:52'),
(366, 987456, 10000000011, 854774, '2023-09-16 18:14:52'),
(367, 987456, 10000000011, 854775, '2023-09-16 18:14:52'),
(368, 987456, 10000000011, 854776, '2023-09-16 18:14:52'),
(369, 987456, 10000000011, 854777, '2023-09-16 18:14:52'),
(370, 987456, 10000000011, 854778, '2023-09-16 18:14:52'),
(371, 987456, 10000000011, 854779, '2023-09-16 18:14:52'),
(372, 987456, 10000000011, 854780, '2023-09-16 18:14:52'),
(373, 987456, 10000000011, 854781, '2023-09-16 18:14:52'),
(374, 987456, 10000000011, 854782, '2023-09-16 18:14:52'),
(375, 987456, 10000000011, 854783, '2023-09-16 18:14:52'),
(376, 987456, 10000000011, 854784, '2023-09-16 18:14:52');

-- --------------------------------------------------------

--
-- Table structure for table `clientaccount`
--

CREATE TABLE `clientaccount` (
  `cliAccID` int(11) NOT NULL,
  `clientNIC` varchar(15) NOT NULL,
  `accountNo` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Accounts linked to each client';

--
-- Dumping data for table `clientaccount`
--

INSERT INTO `clientaccount` (`cliAccID`, `clientNIC`, `accountNo`) VALUES
(1, '900758456v', 10000000002),
(2, '900731795v', 10000000001),
(3, '975651908v', 10000000004),
(5, '900456854v', 10000000007),
(6, '900132584v', 10000000008),
(7, '916548985v', 10000000009),
(8, '915647586V', 10000000011),
(9, '200314786125', 10000000012),
(10, '197870203509', 10000000013),
(11, '907080625V', 10000000014),
(13, '201814786125', 10000000016);

-- --------------------------------------------------------

--
-- Table structure for table `clientinfo`
--

CREATE TABLE `clientinfo` (
  `clientID` int(11) NOT NULL,
  `clientFName` varchar(255) NOT NULL,
  `clentsLName` varchar(255) NOT NULL,
  `clientNIC` varchar(15) NOT NULL,
  `clientContact` int(15) NOT NULL,
  `cleintAddress` varchar(255) NOT NULL,
  `clientBday` date NOT NULL,
  `clentEmail` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clientinfo`
--

INSERT INTO `clientinfo` (`clientID`, `clientFName`, `clentsLName`, `clientNIC`, `clientContact`, `cleintAddress`, `clientBday`, `clentEmail`) VALUES
(2, 'Praneeth', 'Vitharana', '900731795v', 713540549, '  219/7/1, Rathmaldeniya Road, Pannipitiya', '1990-03-13', 'praneethtb@gmail.com'),
(3, 'Kavinda', 'Wickramasinghe', '900758456v', 779718570, ' 1361c, Bogahawatta', '1990-06-22', 'johnty@gmail.com'),
(4, 'Nisansala', 'Perera', '975651908v', 714049463, '291/7/1, Rathmaldeniya Road, Pannipitiya', '1997-03-05', 'nisansala@gmail.com'),
(5, 'Nipuna Ranga', 'Francisku', '900456854v', 716855489, ' 23/a, Ranawakawatta Road, Hokandara', '1990-04-01', 'frana@gmail.com'),
(7, 'Poornima', 'Wijesekara', '900132584v', 755874236, '1234/3, Prajamandala Mawatha, Hokandara', '1990-04-17', 'haka@gmail.com'),
(15, 'Supun', 'Dharshana', '916548985v', 745658954, '34/2, Samana Mw, Homagama', '1991-01-15', 'supundar@gmail.com'),
(16, 'Chamli', 'PERERA', '915647586V', 785425698, '98/C/6, Padukka', '1991-01-16', 'chamli@gmail.com'),
(17, 'Deshitha', 'Saumya', '200314786125', 716566872, '39/1/1, Borella Road, Pannipitiya', '2003-01-08', 'deshitha@ymail.com'),
(18, 'Nilmini ', 'Perera', '197870203509', 721340258, '39/1/1, Borella Road, Pannipitiya', '1978-07-20', 'nilmini@gmail.com'),
(19, 'Ashani', 'Wickramasinghe', '907080625V', 774458248, '1361/c,\r\nBogahawatta road,', '1990-07-26', 'ashani.tdesilva@gmail.com'),
(20, '', '', '', 0, ' ', '0000-00-00', ''),
(21, 'Dilum', 'Paranavithana', '201814786125', 773542680, '34/56, Magammana, Homagama', '2018-10-15', 'dilum@gmailcom');

-- --------------------------------------------------------

--
-- Table structure for table `messageds`
--

CREATE TABLE `messageds` (
  `messageID` int(11) NOT NULL,
  `sender` varchar(15) NOT NULL,
  `reciever` varchar(15) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `sentDate` datetime NOT NULL,
  `ReadDate` datetime NOT NULL,
  `Status` varchar(10) NOT NULL DEFAULT 'Unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messageds`
--

INSERT INTO `messageds` (`messageID`, `sender`, `reciever`, `message`, `sentDate`, `ReadDate`, `Status`) VALUES
(2, '10000000001', 'Manager', 'Test message', '2023-09-17 12:21:19', '2023-09-17 12:22:16', 'Read'),
(3, '10000000002', 'Manager', 'Test Message 2', '2023-09-17 12:21:34', '2023-09-17 14:04:04', 'Read'),
(4, 'Manager', '10000000001', 'Reply for the test', '2023-09-17 12:25:28', '2023-09-17 21:24:13', 'Read'),
(5, 'Manager', '10000000002', 'Second Test Reply', '2023-09-17 12:28:34', '2023-09-17 12:28:43', 'Read'),
(6, 'Manager', '10000000002 ', 'We will see to that soon', '2023-09-17 13:51:29', '2023-09-17 21:30:08', 'Read'),
(7, 'Manager', '10000000001', 'Test message', '2023-09-17 14:18:35', '2023-09-17 21:05:16', 'Read'),
(8, '10000000001', 'Manager', 'Test message', '2023-09-17 14:20:39', '2023-09-17 18:36:03', 'Read'),
(9, 'Manager', '10000000001 ', 'We are here for your banking needs', '2023-09-17 18:50:29', '2023-09-17 21:03:37', 'Read'),
(10, 'Manager', '10000000002 ', 'Test Message for deletion', '2023-09-17 21:26:31', '2023-09-17 21:27:02', 'Read'),
(11, '10000000002 ', 'Manager', 'We need more messages', '2023-09-17 21:45:56', '0000-00-00 00:00:00', 'Unread'),
(12, 'Manager', '10000000002  ', 'this is a test message. do not reply\r\n', '2023-09-17 21:53:16', '2023-09-17 21:55:53', 'Read'),
(13, '10000000002 ', 'Manager', 'Gor it', '2023-09-17 21:55:45', '0000-00-00 00:00:00', 'Unread');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transactionID` int(11) NOT NULL,
  `fromAccount` bigint(11) NOT NULL,
  `transactionMethod` varchar(20) NOT NULL COMMENT 'Cash Deposit,Checque Deposit, Counter Withdrawal, ATM withdrawal',
  `remarks` varchar(20) NOT NULL COMMENT 'ATM card number, Checque number or other remarks',
  `transactionDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `transactionAmount` float NOT NULL,
  `transactionCRDB` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transactionID`, `fromAccount`, `transactionMethod`, `remarks`, `transactionDate`, `transactionAmount`, `transactionCRDB`) VALUES
(4, 10000000001, 'ATM', '', '2023-08-27 05:29:56', 10000, 'DB'),
(5, 10000000001, 'ATM', '', '2023-08-27 05:37:06', 2000, 'DB'),
(7, 10000000001, 'Trnsfr', '', '2023-08-28 04:57:01', 2000, 'DB'),
(8, 10000000002, 'Trnsfr', '', '2023-08-28 04:57:01', 2000, 'CR'),
(9, 10000000001, 'Cash', 'counter1', '2023-09-09 03:57:03', 15000, 'CR'),
(10, 10000000001, 'Cash', 'terminal1', '2023-09-09 03:59:39', 15000, 'CR'),
(11, 10000000001, 'Cash', 'Terminal_1', '2023-09-09 04:06:09', 6000, 'DB'),
(12, 10000000001, 'Cash', 'counter1', '2023-09-09 10:34:02', 6000, 'CR'),
(13, 10000000001, 'Cash', 'fees', '2023-09-09 10:40:27', 1800, 'CR'),
(14, 10000000001, 'Cheque', 'HNB25487', '2023-09-09 10:42:52', 1500, 'CR'),
(15, 10000000007, 'Cheque', 'HNB25489', '2023-09-09 10:43:19', 13000, 'CR'),
(16, 10000000012, 'Cheque', 'BOC25487', '2023-09-09 10:50:46', 15000, 'CR'),
(17, 10000000001, 'Cash', 'interest', '2023-09-09 13:00:19', 700, 'CR'),
(18, 10000000001, 'Cash', 'savings', '2023-09-09 13:07:36', 1500, 'CR'),
(19, 10000000004, 'Cash', 'salary', '2023-09-09 13:14:00', 24500, 'CR'),
(20, 10000000001, 'Cheque', 'CDB58974', '2023-09-09 13:17:20', 10000, 'CR'),
(21, 10000000012, 'Cheque', 'RDB986587', '2023-09-09 13:21:12', 10000, 'CR'),
(22, 10000000004, 'Cash', 'remittence', '2023-09-09 13:42:50', 13000, 'CR'),
(23, 10000000009, 'Cash', '', '2023-09-09 13:49:57', 48756, 'CR'),
(24, 10000000013, 'Cash', 'income', '2023-09-09 13:59:30', 25000, 'CR'),
(25, 10000000001, 'Cash', 'User', '2023-09-09 14:46:15', 1500, 'DB'),
(26, 10000000001, 'Cash', 'User', '2023-09-09 14:46:55', 10000, 'DB'),
(27, 10000000001, 'Cash', 'User', '2023-09-09 15:08:16', 500, 'DB'),
(28, 10000000001, 'Cash', 'savings', '2023-09-10 02:55:31', 10000, 'CR'),
(29, 10000000007, 'Cheque', 'COM158965', '2023-09-10 02:59:34', 10000, 'CR'),
(30, 10000000001, 'Cash', 'counter', '2023-09-10 03:09:41', 9500, 'DB'),
(31, 10000000001, 'Cash', '', '2023-09-10 03:12:50', 0, 'CR'),
(32, 10000000001, 'Cash', 'savings', '2023-09-10 03:15:55', 10000, 'CR'),
(33, 10000000001, 'Cash', '', '2023-09-10 03:21:13', 0, 'CR'),
(34, 10000000004, 'Cash', 'fees', '2023-09-10 03:45:25', 10000, 'CR'),
(35, 10000000004, 'Cheque', 'NTB587963', '2023-09-10 03:45:41', 1500, 'CR'),
(36, 10000000004, 'Cash', 'counter', '2023-09-10 03:45:53', 9000, 'DB'),
(37, 10000000001, 'Cash', 'counter', '2023-09-10 15:59:53', 0, 'DB'),
(38, 10000000001, 'Cash', 'counter', '2023-09-10 16:00:23', 0, 'DB'),
(39, 10000000001, 'Trnsfr', '', '2023-09-10 17:28:09', 500, 'DB'),
(40, 10000000012, 'Trnsfr', '', '2023-09-10 17:28:09', 500, 'CR'),
(41, 10000000004, 'Trnsfr', '', '2023-09-10 17:29:13', 10000, 'DB'),
(42, 10000000002, 'Trnsfr', '', '2023-09-10 17:29:13', 10000, 'CR'),
(43, 10000000004, 'Trnsfr', '', '2023-09-10 17:44:22', 500, 'DB'),
(44, 10000000001, 'Trnsfr', '', '2023-09-10 17:44:22', 500, 'CR'),
(45, 10000000001, 'Trnsfr', '', '2023-09-10 17:44:44', 12500, 'DB'),
(46, 10000000004, 'Trnsfr', '', '2023-09-10 17:44:44', 12500, 'CR'),
(47, 10000000001, 'Trnsfr', '', '2023-09-14 14:14:34', 500, 'DB'),
(48, 10000000002, 'Trnsfr', '', '2023-09-14 14:14:34', 500, 'CR'),
(49, 10000000001, 'Trnsfr', '', '2023-09-15 10:54:58', 1142, 'DB'),
(50, 10000000002, 'Trnsfr', '', '2023-09-15 10:54:58', 1142, 'CR'),
(51, 10000000002, 'Trnsfr', '', '2023-09-15 11:00:56', 1142, 'DB'),
(52, 10000000001, 'Trnsfr', '', '2023-09-15 11:00:56', 1142, 'CR'),
(53, 10000000001, 'Trnsfr', '', '2023-09-15 11:02:32', 1142, 'DB'),
(54, 10000000002, 'Trnsfr', '', '2023-09-15 11:02:32', 1142, 'CR'),
(55, 10000000002, 'Trnsfr', '', '2023-09-15 11:02:58', 1142, 'DB'),
(56, 10000000001, 'Trnsfr', '', '2023-09-15 11:02:58', 1142, 'CR'),
(57, 10000000002, 'Trnsfr', '', '2023-09-16 04:59:58', 2500, 'DB'),
(58, 10000000001, 'Trnsfr', '', '2023-09-16 04:59:58', 2500, 'CR'),
(59, 10000000014, 'Cash', 'husband', '2023-09-16 05:06:55', 100000, 'CR'),
(60, 10000000002, 'Cash', 'ATM', '2023-09-16 09:34:37', 1500, 'DB'),
(61, 10000000002, 'Trnsfr', '', '2023-09-16 10:11:15', 500, 'DB'),
(62, 10000000001, 'Trnsfr', '', '2023-09-16 10:11:15', 500, 'CR'),
(63, 10000000001, 'Cash', 'Salary', '2023-09-16 10:20:10', 100000, 'CR'),
(64, 10000000001, 'Cash', 'counter', '2023-09-16 10:20:17', 10000, 'DB'),
(65, 10000000001, 'Cheque', 'BOC25486', '2023-09-16 10:20:38', 10000, 'CR'),
(66, 10000000001, 'Trnsfr', '', '2023-09-16 10:21:01', 13000, 'DB'),
(67, 10000000002, 'Trnsfr', '', '2023-09-16 10:21:01', 13000, 'CR'),
(68, 10000000012, 'Cash', 'counter', '2023-09-16 19:27:25', 5500, 'DB'),
(69, 10000000012, 'Cheque', '7357', '2023-09-16 19:51:42', 100, 'DB'),
(70, 10000000012, 'Cheque', '100040', '2023-09-16 19:55:22', 500, 'DB'),
(71, 10000000012, 'Cheque', '100015', '2023-09-16 19:57:20', 1500, 'DB'),
(72, 10000000002, 'Trnsfr', '', '2023-09-17 05:12:56', 10000, 'DB'),
(73, 10000000014, 'Trnsfr', '', '2023-09-17 05:12:56', 10000, 'CR'),
(74, 10000000002, 'Trnsfr', '', '2023-09-17 05:16:18', 1000, 'DB'),
(75, 10000000004, 'Trnsfr', '', '2023-09-17 05:16:18', 1000, 'CR'),
(76, 10000000001, 'Cash', 'counter', '2023-09-17 16:36:42', 10000, 'DB'),
(77, 10000000001, 'Cash', 'interest', '2023-09-17 16:36:51', 10000, 'CR'),
(78, 10000000001, 'Cheque', 'BOC25482', '2023-09-17 16:37:05', 1500, 'CR'),
(79, 10000000001, 'Trnsfr', '', '2023-09-17 16:37:33', 8500, 'DB'),
(80, 10000000004, 'Trnsfr', '', '2023-09-17 16:37:33', 8500, 'CR');

--
-- Triggers `transactions`
--
DELIMITER $$
CREATE TRIGGER `transaction_insert_trigger` AFTER INSERT ON `transactions` FOR EACH ROW BEGIN
    INSERT INTO `bankledger`(`ledgerAmount`, `trasactionID`, `timestamp`, `CRDB`)
    VALUES (NEW.transactionAmount, NEW.transactionID, NOW(),
        CASE 
            WHEN NEW.transactionCRDB = 'CR' THEN 'DB'
            WHEN NEW.transactionCRDB = 'DB' THEN 'CR'
        END);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `transferID` int(11) NOT NULL,
  `transferAmount` float NOT NULL,
  `fromAccount` bigint(20) NOT NULL,
  `toAccount` bigint(20) NOT NULL,
  `transferDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transfers`
--

INSERT INTO `transfers` (`transferID`, `transferAmount`, `fromAccount`, `toAccount`, `transferDate`) VALUES
(4, 2000, 10000000001, 10000000002, '2023-08-28 04:57:01'),
(5, 500, 10000000001, 10000000012, '2023-09-10 17:28:09'),
(6, 10000, 10000000004, 10000000002, '2023-09-10 17:29:13'),
(7, 500, 10000000004, 10000000001, '2023-09-10 17:44:22'),
(8, 12500, 10000000001, 10000000004, '2023-09-10 17:44:44'),
(9, 500, 10000000001, 10000000002, '2023-09-14 14:14:34'),
(10, 1142, 10000000001, 10000000002, '2023-09-15 10:54:58'),
(11, 1142, 10000000002, 10000000001, '2023-09-15 11:00:56'),
(12, 1142, 10000000001, 10000000002, '2023-09-15 11:02:32'),
(13, 1142, 10000000002, 10000000001, '2023-09-15 11:02:58'),
(14, 2500, 10000000002, 10000000001, '2023-09-16 04:59:58'),
(15, 500, 10000000002, 10000000001, '2023-09-16 10:11:15'),
(16, 13000, 10000000001, 10000000002, '2023-09-16 10:21:01'),
(17, 10000, 10000000002, 10000000014, '2023-09-17 05:12:56'),
(18, 1000, 10000000002, 10000000004, '2023-09-17 05:16:18'),
(19, 8500, 10000000001, 10000000004, '2023-09-17 16:37:33');

--
-- Triggers `transfers`
--
DELIMITER $$
CREATE TRIGGER `updateTransactionstbl` AFTER INSERT ON `transfers` FOR EACH ROW BEGIN
    -- Debit from Account
    INSERT INTO `transactions` (`fromAccount`, `transactionMethod`, `transactionDate`, `transactionAmount`, `transactionCRDB`)
    VALUES (new.fromAccount, 'Trnsfr', now(), new.transferAmount, 'DB');

    -- Credit to account
    INSERT INTO `transactions` (`fromAccount`, `transactionMethod`, `transactionDate`, `transactionAmount`, `transactionCRDB`)
    VALUES (new.toAccount, 'Trnsfr', now(), new.transferAmount, 'CR');
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `usedcheques`
--

CREATE TABLE `usedcheques` (
  `usedcqID` int(11) NOT NULL,
  `chequeNo` int(11) NOT NULL,
  `accountNo` bigint(20) NOT NULL,
  `usedDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `amount` float NOT NULL,
  `consigneeName` varchar(255) NOT NULL,
  `consigneeID` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usedcheques`
--

INSERT INTO `usedcheques` (`usedcqID`, `chequeNo`, `accountNo`, `usedDate`, `amount`, `consigneeName`, `consigneeID`) VALUES
(2, 100014, 10000000012, '2023-09-16 17:51:02', 2000, 'Praneeth Vitharana\r\n\r\n', '900731795v'),
(3, 100040, 10000000012, '2023-09-16 19:55:22', 500, 'Nisansala Perera', '975651908v'),
(4, 100015, 10000000012, '2023-09-16 19:57:20', 1500, 'Supun Dharshana', '915758624v');

-- --------------------------------------------------------

--
-- Table structure for table `userandclient`
--

CREATE TABLE `userandclient` (
  `userclientID` int(11) NOT NULL,
  `clientNIC` varchar(255) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userandclient`
--

INSERT INTO `userandclient` (`userclientID`, `clientNIC`, `userID`) VALUES
(1, '900731795v', 1),
(2, '900758456v', 2),
(3, '975651908v', 3);

-- --------------------------------------------------------

--
-- Table structure for table `userlogin`
--

CREATE TABLE `userlogin` (
  `userloginID` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `userpassword` varchar(20) NOT NULL,
  `usertitle` varchar(20) NOT NULL,
  `usernickname` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userlogin`
--

INSERT INTO `userlogin` (`userloginID`, `username`, `userpassword`, `usertitle`, `usernickname`) VALUES
(1, 'praneeth', '123', 'Mr.', 'Praneeth'),
(2, 'johnty', '2226', 'Mr.', 'Kavinda'),
(3, 'artist', 'gallery', 'Ms.', 'Sandeepani');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`accountNo`);

--
-- Indexes for table `adminlogin`
--
ALTER TABLE `adminlogin`
  ADD PRIMARY KEY (`adminID`);

--
-- Indexes for table `atm`
--
ALTER TABLE `atm`
  ADD PRIMARY KEY (`ATMID`);

--
-- Indexes for table `bankledger`
--
ALTER TABLE `bankledger`
  ADD PRIMARY KEY (`ledgerID`);

--
-- Indexes for table `cheques`
--
ALTER TABLE `cheques`
  ADD PRIMARY KEY (`chequeBookID`);

--
-- Indexes for table `clientaccount`
--
ALTER TABLE `clientaccount`
  ADD PRIMARY KEY (`cliAccID`),
  ADD KEY `accountNo` (`accountNo`),
  ADD KEY `clientNIC` (`clientNIC`);

--
-- Indexes for table `clientinfo`
--
ALTER TABLE `clientinfo`
  ADD PRIMARY KEY (`clientID`),
  ADD UNIQUE KEY `clientNIC` (`clientNIC`);

--
-- Indexes for table `messageds`
--
ALTER TABLE `messageds`
  ADD PRIMARY KEY (`messageID`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transactionID`),
  ADD KEY `account` (`fromAccount`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`transferID`),
  ADD KEY `fromAccount` (`fromAccount`),
  ADD KEY `toAccount` (`toAccount`);

--
-- Indexes for table `usedcheques`
--
ALTER TABLE `usedcheques`
  ADD PRIMARY KEY (`usedcqID`);

--
-- Indexes for table `userandclient`
--
ALTER TABLE `userandclient`
  ADD PRIMARY KEY (`userclientID`),
  ADD KEY `clientNIC` (`clientNIC`);

--
-- Indexes for table `userlogin`
--
ALTER TABLE `userlogin`
  ADD PRIMARY KEY (`userloginID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `accountNo` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10000000017;

--
-- AUTO_INCREMENT for table `adminlogin`
--
ALTER TABLE `adminlogin`
  MODIFY `adminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `atm`
--
ALTER TABLE `atm`
  MODIFY `ATMID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bankledger`
--
ALTER TABLE `bankledger`
  MODIFY `ledgerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `cheques`
--
ALTER TABLE `cheques`
  MODIFY `chequeBookID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=377;

--
-- AUTO_INCREMENT for table `clientaccount`
--
ALTER TABLE `clientaccount`
  MODIFY `cliAccID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `clientinfo`
--
ALTER TABLE `clientinfo`
  MODIFY `clientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `messageds`
--
ALTER TABLE `messageds`
  MODIFY `messageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `transferID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `usedcheques`
--
ALTER TABLE `usedcheques`
  MODIFY `usedcqID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `userandclient`
--
ALTER TABLE `userandclient`
  MODIFY `userclientID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `userlogin`
--
ALTER TABLE `userlogin`
  MODIFY `userloginID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clientaccount`
--
ALTER TABLE `clientaccount`
  ADD CONSTRAINT `clientaccount_ibfk_1` FOREIGN KEY (`accountNo`) REFERENCES `accounts` (`accountNo`),
  ADD CONSTRAINT `clientaccount_ibfk_2` FOREIGN KEY (`clientNIC`) REFERENCES `clientinfo` (`clientNIC`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `account` FOREIGN KEY (`fromAccount`) REFERENCES `accounts` (`accountNo`);

--
-- Constraints for table `transfers`
--
ALTER TABLE `transfers`
  ADD CONSTRAINT `transfers_ibfk_1` FOREIGN KEY (`fromAccount`) REFERENCES `accounts` (`accountNo`),
  ADD CONSTRAINT `transfers_ibfk_2` FOREIGN KEY (`toAccount`) REFERENCES `accounts` (`accountNo`);

--
-- Constraints for table `userandclient`
--
ALTER TABLE `userandclient`
  ADD CONSTRAINT `userandclient_ibfk_1` FOREIGN KEY (`clientNIC`) REFERENCES `clientinfo` (`clientNIC`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
