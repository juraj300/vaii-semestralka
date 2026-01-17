<?php

use Framework\DB\Connection;

require_once __DIR__ . '/../Framework/ClassLoader.php';

$loader = new \Framework\ClassLoader();
$loader->register();

try {
    $sql = "CREATE TABLE IF NOT EXISTS `attachments` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `lead_id` int(11) NOT NULL,
      `filename` varchar(255) NOT NULL,
      `path` varchar(255) NOT NULL,
      `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `lead_id` (`lead_id`),
      CONSTRAINT `attachments_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    Connection::getInstance()->exec($sql);
    echo "<h1>Success!</h1><p>Table 'attachments' created successfully.</p><a href='/?c=lead'>Go back to Leads</a>";

} catch (PDOException $e) {
    echo "<h1>Error</h1><p>" . $e->getMessage() . "</p>";
}
