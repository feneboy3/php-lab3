<?php
require 'vendor/autoload.php';

use App\Auth;
use App\Database;

$auth = new Auth(Database::connect());
if ($auth->getUser() !== null) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($auth->login($login, $password)) {
        header('Location: index.php');
        exit;
    }
    $error = 'Неверный логин или пароль';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="box">
    <h1>Вход</h1>
    <?php if ($error !== ''): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Логин <input type="text" name="login"></label>
        <label>Пароль <input type="password" name="password"></label>
        <button type="submit">Войти</button>
    </form>
    <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
</div>
</body>
</html>
