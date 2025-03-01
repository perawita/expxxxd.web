<?php

if (isset($_SESSION['user'])) {
    unset($_SESSION['user']);
    session_destroy();
    error_log("DEBUG: Logout berhasil.");
    echo "<script>alert('Logout berhasil.'); window.location.href='index.php';</script>";

}

error_log("ERROR: Tidak ada sesi yang ditemukan.");
echo "<script>alert('Tidak ada sesi yang ditemukan.'); window.history.back();</script>";

?>
