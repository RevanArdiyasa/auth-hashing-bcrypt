# Prototype Sistem Autentikasi Web dengan Hashing Password

Project ini merupakan prototype sistem autentikasi berbasis web menggunakan PHP Native dan MySQL. Sistem ini menerapkan teknik hashing password menggunakan Bcrypt untuk melindungi data sensitif pengguna di database.

## Fitur

- Registrasi user
- Login user
- Hash password menggunakan Bcrypt
- Verifikasi password menggunakan password_verify()
- Session security
- Session timeout
- Anti brute force login
- Penyimpanan password dalam bentuk hash

## Teknologi

- PHP Native
- MySQL
- XAMPP
- Bcrypt

## Cara Menjalankan
-Clone repository ini.

-Simpan folder project ke dalam htdocs.

-Buat database auth_hashing.

-Jalankan query pembuatan tabel users.

-Copy file config.example.php menjadi config.php.

-Sesuaikan konfigurasi database di config.php.

-Jalankan XAMPP Apache dan MySQL.
Akses melalui browser:
http://localhost/auth-hashing/register.php

## Struktur Database

```sql
CREATE DATABASE auth_hashing;
USE auth_hashing;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    failed_attempts INT DEFAULT 0,
    locked_until DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);