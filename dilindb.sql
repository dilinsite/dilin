CREATE TABLE `shopping_lists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `url` varchar(200) NOT NULL,
  `brand` varchar(45) NOT NULL,
  `unit_price` varchar(45) NOT NULL,
  `quantity` int(4) NOT NULL DEFAULT '1',
  `buyer` varchar(45) NOT NULL,
  `status` enum('pending','completed') NOT NULL DEFAULT 'pending',
  `has_img` int(1) NOT NULL,
  `create_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8