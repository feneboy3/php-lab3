<?php
require 'vendor/autoload.php';

use App\Auth;
use App\Database;

$auth = new Auth(Database::connect());
$user = $auth->getUser();
if ($user === null) {
    header('Location: login.php');
    exit;
}

$bgColor = Auth::checkColor($_COOKIE['bg_color'] ?? $user['bg_color'], '#ffffff');
$textColor = Auth::checkColor($_COOKIE['text_color'] ?? $user['text_color'], '#000000');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color: <?= $bgColor ?>; color: <?= $textColor ?>;">
<div class="box">
    <h1>Здравствуйте, <?= htmlspecialchars($user['login']) ?>!</h1>
    <p>Вы вошли на сайт. Цвет фона и цвет текста этой страницы взяты из cookie.</p>
    <p>Цвет фона: <?= $bgColor ?><br>Цвет текста: <?= $textColor ?></p>
    <p><a href="settings.php">Настройки</a> | <a href="logout.php">Выйти</a></p>
</div>
</body>
</html>
