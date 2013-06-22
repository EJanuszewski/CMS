CREATE TABLE IF NOT EXISTS `pages` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`content` text NOT NULL,
	`title` varchar(255) NOT NULL,
	`template` int(11) NOT NULL,
	`url` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `templates` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`content` text NOT NULL,
	`title` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `users` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`username` varchar(255) NOT NULL,
	`password` varchar(255) NOT NULL,
	`role` int(4) NOT NULL,
	PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user_roles` (
	`id` int(4) NOT NULL AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	`description` varchar(255) NOT NULL,
	`can_do_everything` tinyint(1) NOT NULL,
	PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;