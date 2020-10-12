<?php

$servername = "localhost";
$database = "wordpress";
$username = "root";
$password = "developer";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password, [PDO::ATTR_PERSISTENT => true]);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection Failed: ".$e->getMessage();
}