<?php
require 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash password menggunakan Bcrypt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashedPassword);

    if (mysqli_stmt_execute($stmt)) {
        $message = "Registrasi berhasil. Silakan login.";
    } else {
        $message = "Registrasi gagal: " . mysqli_error($conn);
    }
}
?>

<h2>Form Registrasi</h2>

<p><?= $message; ?></p>

<form method="POST">
    <input type="text" name="name" placeholder="Nama" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Register</button>
</form>

<br>
<a href="login.php">Sudah punya akun? Login</a>