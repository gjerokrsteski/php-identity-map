CREATE TABLE IF NOT EXISTS `tbl_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(10) CHARACTER SET latin1 NOT NULL,
  `password` varchar(50) CHARACTER SET latin1 NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;