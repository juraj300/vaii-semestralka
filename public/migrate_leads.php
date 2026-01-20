<?php
use Framework\DB\Connection;

require_once __DIR__ . '/../Framework/ClassLoader.php';

try {
    $conn = Connection::getInstance();
    
    // Check if columns exist before adding
    $stmt = $conn->prepare("SHOW COLUMNS FROM `leads` LIKE 'website'");
    $stmt->execute();
    if ($stmt->fetch() === false) {
        $conn->exec("ALTER TABLE `leads` ADD COLUMN `website` VARCHAR(255) NULL;");
        echo "Column 'website' added.<br>";
    }
    
    $stmt = $conn->prepare("SHOW COLUMNS FROM `leads` LIKE 'background_info'");
    $stmt->execute();
    if ($stmt->fetch() === false) {
        $conn->exec("ALTER TABLE `leads` ADD COLUMN `background_info` TEXT NULL;");
        echo "Column 'background_info' added.<br>";
    }
    
    echo "<h1>Success!</h1><p>Database migration complete.</p>";
} catch (Exception $e) {
    echo "<h1>Error</h1><p>" . $e->getMessage() . "</p>";
}
