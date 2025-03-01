<?php
require_once 'app/crudUser.php';

class Topup {
    private $file = 'app/storage/users.json';
    private $dataUsers;

    public function __construct() {
        $this->dataUsers = new CrudUser();
    }

    public function topupUser($id, $updatedData) {
        $users = $this->dataUsers->getUsers();
        foreach ($users as &$user) {
            if ($user['id'] == $id) {
                $saldo['saldo'] = $user['saldo'] += $updatedData['saldo'];
                // Update data tanpa menghapus ID
                $user = array_merge($user, $saldo);
                $user['id'] = $id; // Pastikan ID tetap sama

                file_put_contents($this->file, json_encode($users, JSON_PRETTY_PRINT));
                return true;
            }
        }
        return false;
    }
}