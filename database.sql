CREATE TABLE `problems` (
  `id` int(11) NOT NULL,
  `project` enum('mythicaldash','pterodactyl-desktop','kosmapanel','kosmapanel-daemon','mythicalpics') NOT NULL,
  `type` enum('warning','error','critical') NOT NULL DEFAULT 'warning',
  `title` text NOT NULL,
  `message` longtext NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `telemetry` (
  `id` int(11) DEFAULT NULL,
  `project` enum('mythicaldash','pterodactyl-desktop','kosmapanel','kosmapanel-daemon','mythicalpics') NOT NULL,
  `action` text NOT NULL,
  `osName` text NOT NULL,
  `kernelName` text NOT NULL,
  `cpuArchitecture` text NOT NULL,
  `osArchitecture` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `token` text NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `problems`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `telemetry`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `problems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `telemetry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;