<?php
session_start();

$file = 'app/storage/users.json';

/**
 * Fungsi untuk mendapatkan daftar pengguna dari JSON
 */
function getUsers($file) {
    if (!file_exists($file)) {
        return [];
    }

    $json = file_get_contents($file);
    $users = json_decode($json, true);

    return is_array($users) ? $users : [];
}

/**
 * Fungsi untuk mengecek apakah sesi yang sedang aktif valid
 */
function isSessionValid($file) {
    if (!isset($_SESSION['user'])) {
        return false; // Tidak ada sesi
    }

    $users = getUsers($file);
    foreach ($users as $user) {
        if ($user['email'] === $_SESSION['user']['email']) {
            return true; // Sesi valid
        }
    }

    // Jika user di sesi tidak ditemukan di JSON, sesi dihapus
    error_log("WARNING: Sesi tidak valid, menghapus sesi.");
    session_destroy();
    return false;
}

// Cek apakah sesi user valid
if (isSessionValid($file)) {
    include 'pages/main.php';
} else {
    include 'pages/auth/login.php';
}
?>
