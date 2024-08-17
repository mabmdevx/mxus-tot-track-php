<?php

try {
    global $pdo;

    $dsn = "mysql:host=".$config_app['DB_HOST'].";dbname=".$config_app['DB_NAME'].";charset=utf8mb4";
    $username = $config_app['DB_USERNAME'];
    $password = $config_app['DB_PASSWORD'];

    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //echo "DB connection successfully";

} catch (PDOException $e) {
    echo "DB connection failed: " . $e->getMessage();
}


?>