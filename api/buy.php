<?php

class CrudUser {
    private $file = '../app/storage/users.json';

    // Mengambil semua pengguna dari file JSON
    public function getUsers() {
        return file_exists($this->file) ? json_decode(file_get_contents($this->file), true) : [];
    }

    // Mendapatkan user berdasarkan ID
    public function getUserById($id) {
        $users = $this->getUsers();
        foreach ($users as $user) {
            if ($user['id'] == $id) {
                return $user;
            }
        }
        return null; 
    }

    // Memperbarui user berdasarkan ID
    public function updateUser($id, $updatedData) {
        $users = $this->getUsers();
        foreach ($users as &$user) {
            if ($user['id'] == $id) {
                // Update data tanpa menghapus ID
                $user = array_merge($user, $updatedData);
                $user['id'] = $id; // Pastikan ID tetap sama

                file_put_contents($this->file, json_encode($users, JSON_PRETTY_PRINT));
                return true;
            }
        }
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    include 'function.php';

    $api = new APIClient();
    $dataUsers = new CrudUser();

    // Periksa apakah pengguna telah login
    if (!isset($_POST['user_id'])) {
        http_response_code(401);
        echo json_encode([
            'status' => 'error',
            'message' => 'Pengguna belum login atau sesi tidak valid.'
        ]);
        exit;
    }

    $user_id = $_POST['user_id'];

    // Periksa apakah ID produk ada
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'ID produk tidak ditemukan.'
        ]);
        exit;
    }

    $id_produk = $_POST['id'];

    // Ambil data produk
    $response = json_decode($api->getProdukBy($id_produk), true);

    if (!$response || $response['status'] !== true || !isset($response['data'])) {
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'Produk tidak ditemukan.'
        ]);
        exit;
    }

    $harga_produk = $response['data']['harga'];

    // Periksa nomor pelanggan
    if (!isset($_POST['customer-no']) || empty($_POST['customer-no'])) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Nomor pelanggan tidak valid.'
        ]);
        exit;
    }

    $customer_no = $_POST['customer-no'];

    // Ambil data pengguna berdasarkan ID
    $user = $dataUsers->getUserById($user_id);

    if (!$user) {
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'Pengguna tidak ditemukan.'
        ]);
        exit;
    }

    // Periksa apakah saldo cukup
    if ($user['saldo'] < $harga_produk) {
        http_response_code(200);
        echo json_encode([
            'status' => 'error',
            'message' => 'Saldo tidak cukup untuk melakukan pembelian.'
        ]);
        exit;
    }

    // Lakukan pembelian
    $updateResponse = json_decode($api->purchase($id_produk, $customer_no), true);

    if (isset($updateResponse['status']) && $updateResponse['status'] === 'success') {
        // Kurangi saldo pengguna
        $user['saldo'] -= $harga_produk;

        // Perbarui data pengguna di file JSON
        $updateSuccess = $dataUsers->updateUser($user_id, ['saldo' => $user['saldo']]);

        if ($updateSuccess) {
            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Produk berhasil dibeli!',
                'saldo_terbaru' => $user['saldo']
            ]);
        } else {
            http_response_code(200);
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal memperbarui saldo pengguna.'
            ]);
        }
    } else {
        http_response_code(200);
        $errorMessage = isset($updateResponse['message']) ? $updateResponse['message'] : 'Terjadi kesalahan.';
        echo json_encode($errorMessage);
    }

}else{
    http_response_code(403);
    echo json_encode([
        'status' => 'error',
        'message' => 'Halaman terlarang dapat menyebabkan gangguan jiwa jika memaksa masuk!'
    ]);
}
