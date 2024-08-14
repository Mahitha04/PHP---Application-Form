<?php

$host = 'localhost'; 
$db = 'school_db'; 
$user = 'root'; 
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connection Sucessful";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
