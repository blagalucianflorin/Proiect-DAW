CREATE TABLE `users` (
	`user_id` int NOT NULL AUTO_INCREMENT,
	`email` varchar(255) NOT NULL UNIQUE,
	`password` varchar(255) NOT NULL,
	`full_name` varchar(512) NOT NULL,
	`created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`activated` BOOLEAN NOT NULL DEFAULT 0,
	`permissions` TEXT NOT NULL,
	PRIMARY KEY (`user_id`)
);

CREATE TABLE `trains` (
	`train_id` int NOT NULL AUTO_INCREMENT UNIQUE,
	`route_id` int NOT NULL,
	`km_cost` FLOAT NOT NULL,
	`stations` TEXT NOT NULL,
	`seats` TEXT NOT NULL,
	`start_date` TIMESTAMP NOT NULL,
	`end_date` TIMESTAMP NOT NULL,
	PRIMARY KEY (`train_id`)
);

CREATE TABLE `tickets` (
	`ticket_id` int NOT NULL AUTO_INCREMENT,
	`user_id` int NOT NULL,
	`train_id` int NOT NULL,
	`price` FLOAT NOT NULL,
	`wagon` int NOT NULL,
	`seat` int NOT NULL,
	`purchase_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`ticket_type` TEXT NOT NULL,
	`start_station` varchar(255) NOT NULL,
	`end_station` varchar(255) NOT NULL,
	`start_date` TIMESTAMP NOT NULL,
	`end_date` TIMESTAMP NOT NULL,
	`class` int NOT NULL,
	PRIMARY KEY (`ticket_id`)
);

CREATE TABLE `users_activity` (
	`user_id` int NOT NULL,
	`action` TEXT NOT NULL,
	`date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`ip` varchar(255) NOT NULL
);

CREATE TABLE `login_tokens` (
	`id` int NOT NULL AUTO_INCREMENT,
	`user_id` int NOT NULL,
	`token` varchar(32) NOT NULL,
	`expiry_date` TIMESTAMP NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `register_tokens` (
	`token` varchar(64) NOT NULL,
	`user_id` int NOT NULL,
	`expiry_date` TIMESTAMP NOT NULL,
	PRIMARY KEY (`token`)
);

CREATE TABLE `routes` (
	`route_id` int NOT NULL AUTO_INCREMENT,
	`stations` TEXT NOT NULL,
	PRIMARY KEY (`route_id`)
);

ALTER TABLE `trains` ADD CONSTRAINT `trains_fk0` FOREIGN KEY (`route_id`) REFERENCES `routes`(`route_id`);

ALTER TABLE `tickets` ADD CONSTRAINT `tickets_fk0` FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`);

ALTER TABLE `tickets` ADD CONSTRAINT `tickets_fk1` FOREIGN KEY (`train_id`) REFERENCES `trains`(`train_id`);

ALTER TABLE `users_activity` ADD CONSTRAINT `users_activity_fk0` FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`);

ALTER TABLE `login_tokens` ADD CONSTRAINT `login_tokens_fk0` FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`);

ALTER TABLE `register_tokens` ADD CONSTRAINT `register_tokens_fk0` FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`);

