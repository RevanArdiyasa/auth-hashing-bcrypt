<?php
require 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $now = date("Y-m-d H:i:s");

        if ($user['locked_until'] !== null && $user['locked_until'] > $now) {
            $message = "Akun terkunci sementara. Coba lagi beberapa menit.";
        } else {
            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['last_activity'] = time();

                $resetQuery = "UPDATE users SET failed_attempts = 0, locked_until = NULL WHERE id = ?";
                $resetStmt = mysqli_prepare($conn, $resetQuery);
                mysqli_stmt_bind_param($resetStmt, "i", $user['id']);
                mysqli_stmt_execute($resetStmt);

                header("Location: dashboard.php");
                exit;
            } else {
                $failedAttempts = $user['failed_attempts'] + 1;

                if ($failedAttempts >= 5) {
                    $lockedUntil = date("Y-m-d H:i:s", strtotime("+5 minutes"));

                    $updateQuery = "UPDATE users SET failed_attempts = ?, locked_until = ? WHERE id = ?";
                    $updateStmt = mysqli_prepare($conn, $updateQuery);
                    mysqli_stmt_bind_param($updateStmt, "isi", $failedAttempts, $lockedUntil, $user['id']);
                    mysqli_stmt_execute($updateStmt);

                    $message = "Password salah 5 kali. Akun dikunci selama 5 menit.";
                } else {
                    $updateQuery = "UPDATE users SET failed_attempts = ? WHERE id = ?";
                    $updateStmt = mysqli_prepare($conn, $updateQuery);
                    mysqli_stmt_bind_param($updateStmt, "ii", $failedAttempts, $user['id']);
                    mysqli_stmt_execute($updateStmt);

                    $sisa = 5 - $failedAttempts;
                    $message = "Password salah. Sisa percobaan: $sisa";
                }
            }
        }
    } else {
        $message = "Email atau password salah.";
    }
}
?>

<h2>Form Login</h2>

<p><?= $message; ?></p>

<form method="POST">
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Login</button>
</form>

<br>
<a href="register.php">Belum punya akun? Register</a>