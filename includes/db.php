<?php

$serverName = "localhost";
$dbUsername = "dbuser";
$dbPassword = "1x33ED[99mU6uiI8";
$dbName = "pictashare";

try {
    $conn = new PDO("mysql:host=$serverName;dbname=$dbName", $dbUsername, $dbPassword);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
