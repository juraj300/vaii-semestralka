<?php

use Framework\DB\Connection;

require_once __DIR__ . '/../Framework/ClassLoader.php';

try {
    // We recreate the tables to avoid complex ALTER migrations for this semester project tool
    $sql = "DROP TABLE IF EXISTS `attachments`;
    DROP TABLE IF EXISTS `appointments`;
    
    CREATE TABLE `appointments` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) NOT NULL,
      `lead_id` int(11) DEFAULT NULL,
      `title` varchar(255) NOT NULL,
      `start_at` datetime NOT NULL,
      `end_at` datetime NOT NULL,
      `description` text,
      `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `user_id` (`user_id`),
      KEY `lead_id` (`lead_id`),
      CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
      CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    CREATE TABLE `attachments` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `lead_id` int(11) DEFAULT NULL,
      `user_id` int(11) NOT NULL,
      `filename` varchar(255) NOT NULL,
      `path` varchar(255) NOT NULL,
      `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `lead_id` (`lead_id`),
      KEY `user_id` (`user_id`),
      CONSTRAINT `attachments_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
      CONSTRAINT `attachments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    Connection::getInstance()->exec($sql);
    echo "<h1>Success!</h1><p>Tables 'attachments' and 'appointments' updated successfully.</p><a href='/?c=lead'>Go back to Leads</a>";

} catch (PDOException $e) {
    echo "<h1>Error</h1><p>" . $e->getMessage() . "</p>";
}
