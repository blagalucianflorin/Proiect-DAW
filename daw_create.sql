
CREATE TABLE `login_tokens` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `token` varchar(32) NOT NULL,
  `expiry_date` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `register_tokens` (
  `token` varchar(64) NOT NULL,
  `user_id` int NOT NULL,
  `expiry_date` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `routes` (
  `route_id` int NOT NULL,
  `stations` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `tickets` (
  `ticket_id` int NOT NULL,
  `user_id` int NOT NULL,
  `train_id` int NOT NULL,
  `price` float NOT NULL,
  `wagon` int NOT NULL,
  `seat` int NOT NULL,
  `purchase_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `start_station` varchar(255) NOT NULL,
  `end_station` varchar(255) NOT NULL,
  `start_date` timestamp NOT NULL,
  `end_date` timestamp NOT NULL,
  `class` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `trains` (
  `train_id` int NOT NULL,
  `route_id` int NOT NULL,
  `km_cost` float NOT NULL,
  `stations` text NOT NULL,
  `seats` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(512) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `permissions` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `users_activity` (
  `user_id` int NOT NULL,
  `action` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


ALTER TABLE `login_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_tokens_fk0` (`user_id`);

ALTER TABLE `register_tokens`
  ADD PRIMARY KEY (`token`),
  ADD KEY `register_tokens_fk0` (`user_id`);

ALTER TABLE `routes`
  ADD PRIMARY KEY (`route_id`);

ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `tickets_fk0` (`user_id`),
  ADD KEY `tickets_fk1` (`train_id`);

ALTER TABLE `trains`
  ADD PRIMARY KEY (`train_id`),
  ADD UNIQUE KEY `train_id` (`train_id`),
  ADD KEY `trains_fk0` (`route_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `users_activity`
  ADD KEY `users_activity_fk0` (`user_id`);


ALTER TABLE `login_tokens`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `routes`
  MODIFY `route_id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `tickets`
  MODIFY `ticket_id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `trains`
  MODIFY `train_id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT;


ALTER TABLE `login_tokens`
  ADD CONSTRAINT `login_tokens_fk0` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

ALTER TABLE `register_tokens`
  ADD CONSTRAINT `register_tokens_fk0` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_fk0` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `tickets_fk1` FOREIGN KEY (`train_id`) REFERENCES `trains` (`train_id`);

ALTER TABLE `trains`
  ADD CONSTRAINT `trains_fk0` FOREIGN KEY (`route_id`) REFERENCES `routes` (`route_id`);

ALTER TABLE `users_activity`
  ADD CONSTRAINT `users_activity_fk0` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
