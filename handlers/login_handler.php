<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/login.php');
    exit;
}
$login = trim($_POST['login'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($login) || empty($password)) {
    $_SESSION['error'] = 'Нужно заполнить все поля';
    header('Location: ../pages/login.php');
    exit;
}

//----- капча -----

$token = $_POST['smart-token'] ?? '';

if (!$token) {
    $_SESSION['error'] = 'Подтвердите, что вы не робот.';
    header('Location: ../pages/login.php');
    exit;
}

$secretKey = 'здесь должен быть секретный ключ яндекс капчи ysc2...';
$verifyUrl = 'https://smartcaptcha.yandexcloud.net/validate';

$data = [
    'secret' => $secretKey,
    'token' => $token,
];

$options = [
    'http' => [
        'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ]
];

$context  = stream_context_create($options);
$file_contents = file_get_contents($verifyUrl, false, $context);


if ($file_contents === false) {
    echo "Ошибка запроса";
    exit;
}

$result = json_decode($file_contents, true);

// ----- -----


if ($result['status'] === 'ok') {
    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :login OR phone = :login');
        $stmt->execute(['login' => $login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id'    => $user['id'],
                'name'  => $user['name'],
                'email' => $user['email'],
                'phone' => $user['phone']
            ];
            header('Location: ../pages/home.php');
            exit;
        } else {
            $_SESSION['error'] = 'Неверный логин или пароль.';
            header('Location: ../pages/login.php');
            exit;
        }

    } catch (PDOException $e) {
        $_SESSION['error'] = 'Ошибка сервера: ' . $e->getMessage();
        header('Location: ../pages/login.php');
        exit;
    }
}else{
    $_SESSION['error'] = 'Не пройдена проверка капчи.';
    header('Location: ../pages/login.php');
    exit;
}
