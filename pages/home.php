<?php
require_once '../includes/auth-check.php';


$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);


$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Профиль</title>
</head>
<body>

<h1>Привет, <?= htmlspecialchars($_SESSION['user']['name']) ?>!</h1>
<h3>Здесь ты можешь обновить свои данные:</h3>

<?php if ($success): ?>
    <p style="color: green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<?php if ($error): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form action="../handlers/update.php" method="POST">
    <label>
        Имя:<br>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
    </label>
    <br><br>
    <label>
        Телефон:<br>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
    </label>
    <br><br>
    <label>
        Email:<br>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    </label>
    <br><br>
    <label>
        Новый пароль:<br>
        <input type="password" name="password">
    </label>
    <br><br>

    <button type="submit">Сохранить изменения</button>
</form>

<br>
<form action="logout.php" method="POST">
    <button type="submit">Выйти</button>
</form>

</body>
</html>
