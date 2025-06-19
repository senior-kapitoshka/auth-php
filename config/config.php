<?php

session_start();
$host = 'sql301.infinityfree.com';
$port = '3306';
$dbname = 'if0_39274415_only_auth';
$user = 'if0_39274415';
$password = 'IKezona8Y4l';


$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";


try {
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}
