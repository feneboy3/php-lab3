<?php

namespace App;

use PDO;

class Auth
{
    const COOKIE_TIME = 2592000;

    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public static function checkColor($color, $default)
    {
        if (preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
            return $color;
        }
        return $default;
    }

    public function register($login, $password, $bgColor, $textColor)
    {
        if ($login === '' || $password === '') {
            return 'Заполните логин и пароль';
        }
        $stmt = $this->pdo->prepare('SELECT id FROM users WHERE login = ?');
        $stmt->execute([$login]);
        if ($stmt->fetch()) {
            return 'Пользователь с таким логином уже есть';
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare('INSERT INTO users (login, password_hash, bg_color, text_color) VALUES (?, ?, ?, ?)');
        $stmt->execute([$login, $hash, $bgColor, $textColor]);
        return '';
    }

    public function login($login, $password)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE login = ?');
        $stmt->execute([$login]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }
        $token = bin2hex(random_bytes(32));
        $stmt = $this->pdo->prepare('UPDATE users SET token = ? WHERE id = ?');
        $stmt->execute([$token, $user['id']]);
        setcookie('token', $token, time() + self::COOKIE_TIME, '/', '', false, true);
        $this->setColorCookies($user['bg_color'], $user['text_color']);
        return true;
    }

    public function getUser()
    {
        if (empty($_COOKIE['token'])) {
            return null;
        }
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE token = ?');
        $stmt->execute([$_COOKIE['token']]);
        $user = $stmt->fetch();
        if (!$user) {
            return null;
        }
        return $user;
    }

    public function saveSettings($userId, $bgColor, $textColor)
    {
        $stmt = $this->pdo->prepare('UPDATE users SET bg_color = ?, text_color = ? WHERE id = ?');
        $stmt->execute([$bgColor, $textColor, $userId]);
        $this->setColorCookies($bgColor, $textColor);
    }

    public function logout($userId)
    {
        $stmt = $this->pdo->prepare('UPDATE users SET token = NULL WHERE id = ?');
        $stmt->execute([$userId]);
        setcookie('token', '', time() - 3600, '/');
        setcookie('bg_color', '', time() - 3600, '/');
        setcookie('text_color', '', time() - 3600, '/');
    }

    private function setColorCookies($bgColor, $textColor)
    {
        setcookie('bg_color', $bgColor, time() + self::COOKIE_TIME, '/');
        setcookie('text_color', $textColor, time() + self::COOKIE_TIME, '/');
    }
}
