
<?php

require_once __DIR__ . "/../constants/constant.php";

$conn = null;
try {
    $conn = new PDO(sprintf("mysql:host=%s;dbname=%s;", DB_HOST, DB_NAME),
        DB_USERNAME, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}