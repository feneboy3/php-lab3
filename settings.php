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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bgColor = Auth::checkColor($_POST['bg_color'] ?? '', '#ffffff');
    $textColor = Auth::checkColor($_POST['text_color'] ?? '', '#000000');
    $auth->saveSettings($user['id'], $bgColor, $textColor);
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Настройки</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color: <?= $user['bg_color'] ?>; color: <?= $user['text_color'] ?>;">
<div class="box">
    <h1>Настройки</h1>
    <form method="post">
        <label>Цвет фона <input type="color" name="bg_color" value="<?= $user['bg_color'] ?>"></label>
        <label>Цвет текста <input type="color" name="text_color" value="<?= $user['text_color'] ?>"></label>
        <button type="submit">Сохранить</button>
    </form>
    <p><a href="index.php">На главную</a></p>
</div>
</body>
</html>
