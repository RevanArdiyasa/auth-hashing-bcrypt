<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$timeout = 900;

if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > $timeout) {
    session_unset();
    session_destroy();

    header("Location: login.php?timeout=1");
    exit;
}

$_SESSION['last_activity'] = time();
?>

<h2>Dashboard</h2>

<p>Selamat datang, <?= htmlspecialchars($_SESSION['name']); ?></p>

<a href="logout.php">Logout</a>