<?php
require_once '../includes/auth-check.php';


$userId = $_SESSION['user']['id'];
$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($name) || empty($phone) || empty($email)) {
    $_SESSION['error'] = 'Все поля, кроме пароля, обязательны.';
    header('Location: ../pages/home.php.php');
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE (email = :email OR phone = :phone) AND id != :id');
    $stmt->execute(['email' => $email, 'phone' => $phone, 'id' => $userId]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $_SESSION['error'] = 'Email или телефон уже используется другим пользователем.';
        header('Location: ../pages/home.php');
        exit;
    }

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE users SET name = :name, phone = :phone, email = :email, password = :password WHERE id = :id');
        $stmt->execute([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'password' => $hashed,
            'id' => $userId
        ]);
    } else {
        $stmt = $pdo->prepare('UPDATE users SET name = :name, phone = :phone, email = :email WHERE id = :id');
        $stmt->execute([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'id' => $userId
        ]);
    }

    $_SESSION['user']['name'] = $name;
    $_SESSION['user']['phone'] = $phone;
    $_SESSION['user']['email'] = $email;

    $_SESSION['success'] = 'Данные успешно обновлены.';
    header('Location: ../pages/home.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['error'] = 'Ошибка сервера: ' . $e->getMessage();
    header('Location: ../pages/home.php');
    exit;
}
