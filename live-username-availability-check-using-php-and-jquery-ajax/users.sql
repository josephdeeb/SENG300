CREATE TABLE IF NOT EXISTS `users` (
  `userId` int(8) NOT NULL AUTO_INCREMENT,
  `userName` varchar(55) NOT NULL,
  `password` varchar(55) NOT NULL,
  `firstName` varchar(55) NOT NULL,
  `lastName` varchar(55) NOT NULL,
  PRIMARY KEY (`userId`)
)

INSERT INTO `users` (`userId`, `userName`, `password`, `firstName`, `lastName`) VALUES
(24, 'joel', '', 'Joel', 'Thomas'),
(26, 'merlinsofia', '', 'merlin', 'sofia'),
(27, 'matt', '', 'Matthew', 'Matthew'),
(28, 'mike', '', 'Mike', 'Anto');