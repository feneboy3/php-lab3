<?php
require 'vendor/autoload.php';

use App\Auth;
use App\Database;

$auth = new Auth(Database::connect());
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $bgColor = Auth::checkColor($_POST['bg_color'] ?? '', '#ffffff');
    $textColor = Auth::checkColor($_POST['text_color'] ?? '', '#000000');
    $error = $auth->register($login, $password, $bgColor, $textColor);
    if ($error === '') {
        $auth->login($login, $password);
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="box">
    <h1>Регистрация</h1>
    <?php if ($error !== ''): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Логин <input type="text" name="login"></label>
        <label>Пароль <input type="password" name="password"></label>
        <label>Цвет фона <input type="color" name="bg_color" value="#ffffff"></label>
        <label>Цвет текста <input type="color" name="text_color" value="#000000"></label>
        <button type="submit">Зарегистрироваться</button>
    </form>
    <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
</div>
</body>
</html>
