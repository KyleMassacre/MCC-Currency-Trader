CREATE TABLE IF NOT EXISTS `currency_trader` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `stat_name` varchar(20) NOT NULL DEFAULT '',
  `stat_name_cost` int(11) DEFAULT NULL,
  `description` text,
  `call_back` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `currency_trader` VALUES (
NULL,"Test","money",100,"What you get:<ul><li>1000 of item id 1</li></ul>For:<ul><li>$1</li></ul>","updatestat(\"money\", $ir['money']-1); item_add($userid, 1, 1000);"
);
