<?php
include 'app/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new Auth();
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $response = $auth->login($email, $password);
    if ($response) {
        echo "<script>alert('Login berhasil!'); window.history.back();</script>";
    } else {
        echo "<script>alert('Email atau password salah!'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="assets/images/favicon.png" />
    <style>
        body {
            background: #f4f7fc;
        }
        .login-container {
            min-height: 100vh;
        }
        .login-card {
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .form-control {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center login-container">
        <div class="card login-card p-4 col-md-4">
            <div class="text-center">
                <h3 class="mb-4">Admin Login</h3>
            </div>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" class="form-control" name="email" placeholder="Masukkan email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password *</label>
                    <input type="password" class="form-control" name="password" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
