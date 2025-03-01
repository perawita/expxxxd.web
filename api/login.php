<?php
class Auth {
    private $file = '../app/storage/users.json';
    private $dataUsers;

    public function login($email, $password) {
        $users = file_exists($this->file) ? json_decode(file_get_contents($this->file), true) : [];
        foreach ($users as $user) {
            if (trim(strtolower($user['email'])) === trim(strtolower($email))) {
                if (password_verify($password, $user['password'])) {
                    return $user;
                } else {
                    return "Password Salah!";
                }
            }
        }
        return "Email tidak ditemukan!";
    }    
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'false',
            'message' => 'Email dan password wajib diisi.'
        ]);
        exit;
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $auth = new Auth();

    $user = $auth->login($email, $password);

    if (is_array($user)) {
        http_response_code(200);
        echo json_encode([
            'status' => 'true',
            'message' => 'Berhasil login!',
            'data' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'saldo' => $user['saldo'],
                'role' => $user['role']
            ]
        ]);
    } else {
        http_response_code(200);
        echo json_encode([
            'status' => 'false',
            'message' => 'Gagal login! ' . $user,
            'data' => 'pass anda '. $password .' email anda ' . $email
        ]);
    }
    
} else {
    http_response_code(403);
    echo json_encode([
        'status' => 'false',
        'message' => 'Akses ditolak.'
    ]);
}
?>
