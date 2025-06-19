<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/register.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if (empty($name) || empty($phone) || empty($email) || empty($password) || empty($confirm)) {
    $_SESSION['error'] = 'Пожалуйста, заполните все поля.';
    header('Location: ../pages/register.php');
    exit;
}

if ($password !== $confirm) {
    $_SESSION['error'] = 'Пароли не совпадают.';
    header('Location: ../pages/register.php');
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = :email OR phone = :phone');
    $stmt->execute(['email' => $email, 'phone' => $phone]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $_SESSION['error'] = 'Пользователь с таким email или телефоном уже существует.';
        header('Location: ../pages/register.php');
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare('INSERT INTO users (name, phone, email, password) VALUES (:name, :phone, :email, :password)');
    $stmt->execute([
        'name' => $name,
        'phone' => $phone,
        'email' => $email,
        'password' => $hashedPassword
    ]);

    $_SESSION['user'] = [
        'id'    => $pdo->lastInsertId(),
        'name'  => $name,
        'email' => $email,
        'phone' => $phone
    ];

    header('Location: ../pages/home.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['error'] = 'Ошибка сервера: ' . $e->getMessage();
    header('Location: ../pages/register.php');
    exit;
}
