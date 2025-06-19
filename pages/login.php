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
    <title>Вход</title>
</head>
  <body>

    <h1>Вход</h1>

        <?php if ($error): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="../handlers/login_handler.php" method="POST">
            <label>
                Телефон или электронная почта:<br>
                <input type="text" name="login" required>
            </label>
            <br><br>
            <label>
                Пароль:<br>
                <input type="password" name="password" required>
            </label>
            <br><br>
            
            <div style="width: 150px"
                class="smart-captcha"
                data-sitekey="ysc1_fPdgwhmRsLCeQKPa4aFhbvceGeseZ2jbmHe1HCbS64eb27e1"
            ></div>
            <button type="submit">Войти</button>
        </form>


      <p>Первый раз? <a href="register.php">Зарегистрироваться</a></p>
    <script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>
  </body>
</html>
