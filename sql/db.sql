CREATE TABLE `levelup_items` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `name` varchar(250) DEFAULT NULL,
                            `entryitem` int(11) DEFAULT NULL,
                            `count` int(11) DEFAULT NULL,
                            `realm` int(20) DEFAULT NULL,
                            `icon` varchar(1350) DEFAULT NULL,
                            `tooltip` int(1) DEFAULT NULL,
                            `quality` int(10) DEFAULT NULL,
                            `active` int(1) DEFAULT NULL,
                            KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
