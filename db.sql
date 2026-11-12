CREATE DATABASE php_lab3 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE php_lab3;

CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    bg_color VARCHAR(7) NOT NULL,
    text_color VARCHAR(7) NOT NULL,
    token VARCHAR(64) NULL
);
