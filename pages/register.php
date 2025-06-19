<?php
require_once '../config/config.php';

if (isset($_SESSION['user'])) {
    header('Location: home.php');
    exit;
}

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
</head>
<body>

<h1>Регистрация</h1>

<?php if ($error): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form action="../handlers/register_handler.php" method="POST">
    <label>
        Имя:<br>
        <input type="text" name="name" required>
    </label>
    <br><br>
    <label>
        Телефон:<br>
        <input type="text" name="phone" required>
    </label>
    <br><br>
    <label>
        Электронная почта:<br>
        <input type="email" name="email" required>
    </label>
    <br><br>
    <label>
        Пароль:<br>
        <input type="password" name="password" required>
    </label>
    <br><br>
    <label>
        Повторите пароль:<br>
        <input type="password" name="confirm_password" required>
    </label>
    <br><br>

    <button type="submit">Зарегистрироваться</button>
</form>

<p>Уже есть аккаунт? <a href="login.php">Войти</a></p>

</body>
</html>
