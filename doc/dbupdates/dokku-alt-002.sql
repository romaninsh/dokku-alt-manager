CREATE TABLE `opauth` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `oauth_id` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `secret` varchar(255) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `other` text,
  `raw_info` text,
  PRIMARY KEY (`id`)
);
