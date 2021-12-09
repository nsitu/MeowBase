/* 

This is the Schema Definition for MeowBase
It contains the SQL commands needed to build the database from scratch.
It includes two tabes: User and Meow
A relationship is defined between the two tables. 

An example user and Meow is also included. 

Setup:
1. Point your environment variables to a new/blank database.
2. Point your browser to the setup URL, i.e. add /setup to the public URL for your Replit

In general your PHP code must agree with the database schema it refers to. Where there are disagreements, PHP will give you hints (warnings) about what's happening.

*/

/* erase any existing structure and data */
DROP TABLE IF EXISTS `Paw`;
DROP TABLE IF EXISTS `Comment`;
DROP TABLE IF EXISTS `Meow`;
DROP TABLE IF EXISTS `User`;

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `ID` int NOT NULL,
  `FullName` varchar(250) NOT NULL,
  `PolicyApproved` tinyint(1) DEFAULT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `Phone` varchar(50) DEFAULT NULL,
  `Email` varchar(250) NOT NULL,
  `Breed` varchar(250) DEFAULT NULL,
  `Eats` varchar(250) DEFAULT NULL,
  `Username` varchar(50) DEFAULT NULL,
  `Password` varchar(255) NOT NULL,
  `ConfirmationCode` int DEFAULT NULL,
  `ProfilePicture` varchar(250) DEFAULT NULL,
  `Bio` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Table structure for table `Meow`
--

CREATE TABLE `Meow` (
  `ID` int NOT NULL,
  `User_ID` int NOT NULL,
  `PublishDateTime` datetime NOT NULL,
  `Body` varchar(250) NOT NULL,
  `Picture` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



--
-- Table structure for table `Comment`
--

CREATE TABLE `Comment` (
  `ID` int NOT NULL,
  `User_ID` int NOT NULL,
  `Meow_ID` int NOT NULL,
  `PublishDateTime` datetime NOT NULL,
  `Body` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Table structure for table `Paw`
--

CREATE TABLE `Paw` (
  `ID` int NOT NULL,
  `User_ID` int NOT NULL,
  `Meow_ID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Indexes for table `Comment`
--
ALTER TABLE `Comment`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UserCreatesComment` (`User_ID`),
  ADD KEY `CommentRespondsToMeow` (`Meow_ID`);


--
-- Indexes for table `Meow`
--
ALTER TABLE `Meow`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UserCreatesMeow` (`User_ID`) USING BTREE;

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Email` (`Email`);


--
-- Indexes for table `Paw`
--
ALTER TABLE `Paw`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UniquePaw` (`User_ID`,`Meow_ID`),
  ADD KEY `PawApprovesOfMeow` (`Meow_ID`);
 

--
-- AUTO_INCREMENT for table `Comment`
--
ALTER TABLE `Comment`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Meow`
--
ALTER TABLE `Meow`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;


--
-- AUTO_INCREMENT for table `Paw`
--
ALTER TABLE `Paw`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;


--
-- Constraints for table `Comment`
--
ALTER TABLE `Comment`
  ADD CONSTRAINT `CommentRespondsToMeow` FOREIGN KEY (`Meow_ID`) REFERENCES `Meow` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `UserCreatesComment` FOREIGN KEY (`User_ID`) REFERENCES `User` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;


--
-- Constraints for table `Meow`
--
ALTER TABLE `Meow`
  ADD CONSTRAINT `UserCreatesMeow` FOREIGN KEY (`User_ID`) REFERENCES `User` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;


--
-- Constraints for table `Paw`
--
ALTER TABLE `Paw`
  ADD CONSTRAINT `PawApprovesOfMeow` FOREIGN KEY (`Meow_ID`) REFERENCES `Meow` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `PawGivenByUser` FOREIGN KEY (`User_ID`) REFERENCES `User` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;



--
-- Dumping data for all tables
--

INSERT INTO `User` (`ID`, `FullName`, `PolicyApproved`, `DateOfBirth`, `Phone`, `Email`, `Breed`, `Eats`, `Username`, `Password`, `ConfirmationCode`, `ProfilePicture`, `Bio`) VALUES
(1, 'Shadow', 1, '2019-02-05', '123-456-7890', 'shadow@shadow.ca', 'Shade!', 'Friskies', NULL, '$2y$10$454nW2dLxe.AU7Vteot84eMaPAiuTINABaqxPadXYCs11t0K2t53O', NULL, '/images/uploads/1637844472.jpg', 'Shadow Naps');
 
INSERT INTO `Meow` (`ID`, `User_ID`, `PublishDateTime`, `Body`, `Picture`) VALUES
(1, 1, '2021-11-25 12:50:30', 'A relatable pace.', '/images/uploads/1637844584.jpg');

INSERT INTO `Comment` (`ID`, `User_ID`, `Meow_ID`, `PublishDateTime`, `Body`) VALUES
(2, 1, 1, '2021-12-02 12:37:17', 'Slow is good.');

INSERT INTO `Paw` (`ID`, `User_ID`, `Meow_ID`) VALUES (NULL, '1', '1');