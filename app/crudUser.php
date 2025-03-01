<?php
class CrudUser {
    private $file = 'app/storage/users.json';

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
        return null; // Jika tidak ditemukan
    }

    // Menambahkan pengguna baru
    public function addUser($newUser) {
        $users = $this->getUsers();
        
        // Cek apakah username/email sudah digunakan
        foreach ($users as $user) {
            if ($newUser['name'] == $user['name'] || $newUser['email'] == $user['email']) {
                return false;
            }
        }

        // Generate ID unik
        $newUser['id'] = rand(1000, 9999);
        $users[] = $newUser;

        return file_put_contents($this->file, json_encode($users, JSON_PRETTY_PRINT));
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

    // Menghapus user berdasarkan ID
    public function deleteUser($id) {
        $users = $this->getUsers();
        $filteredUsers = array_filter($users, function($user) use ($id) {
            return $user['id'] != $id;
        });

        return file_put_contents($this->file, json_encode(array_values($filteredUsers), JSON_PRETTY_PRINT));
    }
}
?>
